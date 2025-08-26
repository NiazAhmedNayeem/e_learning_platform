<?php

namespace App\Http\Controllers\backend\course;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\User;
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
            $assign_teacher = Course::findOrFail($request->course_id);
            $assign_teacher->teacher_id = $request->teacher_id;
            //dd($assign_teacher);

            $assign_teacher->save();
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
            $assign_teacher = Course::findOrFail($id); 
            $assign_teacher->teacher_id = $request->teacher_id;
            $assign_teacher->save();

            DB::commit();
            return redirect()->back()->with('success', 'Assign Teacher updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function delete($id)
    {
        $assign_teacher = Course::findOrFail($id);
        $assign_teacher->teacher_id = null; 
        $assign_teacher->save();

        return redirect()->back()->with('success', 'Teacher assignment removed successfully.');
    }


}
