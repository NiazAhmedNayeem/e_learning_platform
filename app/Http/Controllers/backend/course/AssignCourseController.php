<?php

namespace App\Http\Controllers\backend\course;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\User;
use App\Notifications\TeacherCourseAssignNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AssignCourseController extends Controller
{
    public function index(){
        $assign_courses = Course::whereNotNull('teacher_id')->where('status', 1)->get();
        $courses = Course::whereNull('teacher_id')->where('status', 1)->get();
        return view('backend.course_assign.index', compact('courses', 'assign_courses'));
    }

    public function getTeachersByCategory(Request $request)
    {
        $teachers = User::where('role', 'teacher')
                        ->where('expertise_category_id', $request->category_id)
                        ->get(['id', 'name']);

        return response()->json($teachers);
    }

    public function store(Request $request)
    {
        $request->validate([
            'teacher_id' => 'required|integer|exists:users,id',
        ]);

        //dd($request);
        DB::beginTransaction();

        try{
            $course = Course::findOrFail($request->course_id);
            $course->teacher_id = $request->teacher_id;
            //dd($assign_teacher);

            $course->save();

            // Notification send
            $teacher = User::find($request->teacher_id);
            $teacher->notify(new TeacherCourseAssignNotification($course->title, $course->id, 'assigned'));

            DB::commit();
            return redirect()->back()->with('success', 'Teacher assigned successfully.');

        }catch(\Exception $e){
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }

    }   

    public function update(Request $request, $id)
    {
        $request->validate([
            'teacher_id' => 'required|integer|exists:users,id',
        ]);

        DB::beginTransaction();

        try {
            $course = Course::findOrFail($id); 
            $oldTeacher = $course->teacher;
            $course->teacher_id = $request->teacher_id;
            $course->save();

            //for old teacher remove notification
            if ($oldTeacher && $oldTeacher->id != $request->teacher_id) {
            $oldTeacher->notify(new \App\Notifications\TeacherCourseAssignNotification($course->title, $course->id, 'removed'));
            }

            //for new teacher add notification
            $newTeacher = User::find($request->teacher_id);
            if ($newTeacher) {
                $newTeacher->notify(new \App\Notifications\TeacherCourseAssignNotification($course->title, $course->id, 'assigned'));
            }

            DB::commit();
            return redirect()->back()->with('success', 'Assign Teacher updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function delete($id)
    {
        $course = Course::findOrFail($id);
        
        $teacher = $course->teacher;

        $course->teacher_id = null; 
        $course->save();

        if ($teacher) {
            $teacher->notify(new \App\Notifications\TeacherCourseAssignNotification($course->title, $course->id, 'removed'));
        }

        return redirect()->back()->with('success', 'Teacher assignment removed successfully.');
    }



}
