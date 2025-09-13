<?php

namespace App\Http\Controllers\backend\course;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseVideo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CourseVideoController extends Controller
{

    ////course video management here

    public function videoPlayer($id){
        $course = Course::find($id);
        $videos = CourseVideo::where('course_id', $id)->orderBy('position', 'asc')->get();
        //dd($videos);
        return view('backend.course.player.index', compact('course', 'videos'));
    }

    public function index($id){
        $course = Course::find($id);
        return view('backend.course.course_video.index', compact('course'));
    }

    public function data(Request $request, $id){
        $query = CourseVideo::where('course_id', $id);
        if ($request->has('search') && $request->search != '') {
            $query->where('title', 'like', '%' . $request->search . '%');
        }
        $videos = $query->orderBy('position', 'asc')->paginate(5);
        return response()->json($videos);
    }

    public function store(Request $request)
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

    public function edit($id){
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

    public function update(Request $request, $id)
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


    public function delete($id){
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
