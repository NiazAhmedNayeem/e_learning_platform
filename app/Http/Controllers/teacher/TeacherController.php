<?php

namespace App\Http\Controllers\teacher;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Course;
use App\Models\CourseVideo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TeacherController extends Controller
{
    public function index(){
        return view('teacher.dashboard.index');
    }

    public function profile(){
        $teacher = auth()->user();
        $categories = Category::where('status', 1)->get();
        //dd($teacher);
        return view('teacher.profile.index', compact('teacher', 'categories'));
    }


    public function update(Request $request){

        $teacher = auth()->user();

        $validator = Validator::make($request->all(), [
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email,'.$teacher->id,
            'phone'       => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:11|max:15',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'expertise_category_id' => 'required|exists:categories,id',
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }


        $teacher->name = $request->name;
        $teacher->email = $request->email;
        $teacher->phone = $request->phone;
        $teacher->gender = $request->gender;
        $teacher->expertise_category_id = $request->expertise_category_id;
        $teacher->profession = $request->profession;
        $teacher->address = $request->address;
        $teacher->bio = $request->bio;

        if($request->hasFile('image')){ 
            @unlink(public_path("upload/teacher/".$teacher->image));
            $fileName = rand().time().'.'.request()->image->getClientOriginalExtension(); 
            request()->image->move(public_path('upload/teacher/'),$fileName); 
            $teacher->image = $fileName; 
        }
        //dd($teacher);
        $teacher->save();

        $html = view('teacher.profile.profile_view', compact('teacher'))->render();

        return response()->json([
            'status' => 'success',
            'message' => 'Profile update successfully.',
            'html' => $html,
        ]);

        // return redirect()->route('teacher.profile')->with('success', 'Your profile is updated.');

    }

    public function assignedCourses(Request $request){
        $search = $request->input('search');
        $auth = auth()->user()->id;
        $assign_courses = Course::where('teacher_id', $auth)->where('status', 1)
                ->where(function($query) use ($search){
                $query->where('title', 'like', "%{$search}%");
                })->paginate(5);
        return view('teacher.course.index', compact('assign_courses', 'search'));
    }

    public function assignedCoursesDetails($slug){
        $course = Course::where('slug', $slug)->first();
        //dd($course);
        return view('teacher.course.details', compact('course'));
    }

    public function totalCourseStudent(Request $request)
    {
        $search = $request->input('search');
        $teacher = auth()->user()->id;

        $courses = Course::where('teacher_id', $teacher)
            ->where('status', 1)
            ->where(function($query) use ($search){
                $query->where('title', 'like', "%{$search}%");
            })->paginate(5);

        return view('teacher.student.index', compact('courses', 'search'));
    }








    ////course video management section

    public function videoPlayer($id){
        $course = Course::find($id);
        // check course belongs to logged in teacher
        if ($course->teacher_id !== auth()->id()) {
            abort(403, 'Unauthorized access');
        }
        $videos = CourseVideo::where('course_id', $id)->orderBy('position', 'asc')->get();
        //dd($videos);
        return view('backend.course.player.index', compact('course', 'videos'));
    }

    // public function videoIndex($id){
    //     $course = Course::find($id);
    //     return view('teacher.course_video.index', compact('course'));
    // }

    // public function videoData(Request $request, $id){
    //     $query = CourseVideo::where('course_id', $id);
    //     if ($request->has('search') && $request->search != '') {
    //         $query->where('title', 'like', '%' . $request->search . '%');
    //     }
    //     $videos = $query->orderBy('position', 'asc')->paginate(5);
    //     return response()->json($videos);
    // }


    public function videoIndex($id)
    {
        $course = Course::findOrFail($id);

        // check course belongs to logged in teacher
        if ($course->teacher_id !== auth()->id()) {
            abort(403, 'Unauthorized access');
        }

        return view('teacher.course_video.index', compact('course'));
    }

    public function videoData(Request $request, $id)
    {
        $course = Course::findOrFail($id);

        // again check ownership
        if ($course->teacher_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized access'], 403);
        }

        $query = CourseVideo::where('course_id', $id);

        if ($request->has('search') && $request->search != '') {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $videos = $query->orderBy('position', 'asc')->paginate(5);

        return response()->json($videos);
    }


    public function videoStore(Request $request)
    {
        //dd($request->all());
        $validator = Validator::make($request->all(), [
            'course_id'   => 'required|exists:courses,id',
            'title'       => 'required|string|max:255',
            'video_link'  => 'required|string',
            'is_demo'     => 'required|in:0,1',
            'status'      => 'required|in:active,inactive',
            'description' => 'nullable|string',
            'position'    => 'nullable|integer|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $video = CourseVideo::create([
            'course_id'   => $request->course_id,
            'title'       => $request->title,
            'video_link'  => $request->video_link,
            'is_demo'     => $request->is_demo,
            'status'      => $request->status,
            'description' => $request->description,
            'position'    => $request->position ?? 1,
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Video added successfully!',
            'video'    => $video
        ]);
    }

    public function videoEdit($id){
        $video = CourseVideo::find($id);
        if(!$video){
            return response()->json([
                'status' => 'error',
                'message' => 'Video not found.'
            ], 404);
        }
        return response()->json([
            'status' => 'success',
            'data' => [
                'id'          => $video->id,
                'title'       => $video->title,
                'video_link'  => $video->video_link,
                'position'    => $video->position,
                'description' => $video->description,
                'is_demo'     => $video->is_demo,
                'status'      => $video->status,
            ],
        ]);
    }

    public function videoUpdate(Request $request, $id)
    {
        $video = CourseVideo::find($id);
        //dd($request->all());
        $validator = Validator::make($request->all(), [
            'title'       => 'required|string|max:255',
            'video_link'  => 'required|string',
            'is_demo'     => 'required|in:0,1',
            'status'      => 'required|in:active,inactive',
            'description' => 'nullable|string',
            'position'    => 'nullable|integer|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $video->update([
            'title'       => $request->title,
            'video_link'  => $request->video_link,
            'is_demo'     => $request->is_demo,
            'status'      => $request->status,
            'description' => $request->description,
            'position'    => $request->position ?? 0,
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Video update successfully!',
            'video'    => $video
        ]);
    }


    public function videoDelete($id){
        $video = CourseVideo::find($id);
        if(!$video){
            return response()->json([
                'status' => 'error',
                'message' => 'Video not found.'
            ], 404);
        }

        $video->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Video delete successfully.',
        ]);
    }


}
