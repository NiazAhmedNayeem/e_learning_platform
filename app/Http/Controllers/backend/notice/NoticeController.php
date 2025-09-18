<?php

namespace App\Http\Controllers\backend\notice;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Notice;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class NoticeController extends Controller
{
    public function index(){
        $courses = Course::where('status', 1)->get();
        return view('backend.notice.index',  compact('courses'));
    }

    public function noticeData(Request $request){
        $query = Notice::with('user');
        if($request->has('search') && $request->search != ''){
            $query->where('title', 'like', '%'. $request->search. '%');
        }
        $notices = $query->orderBy('id', 'desc')->paginate(5);
        return response()->json($notices);
    }

    public function store(Request $request)
    {
        //dd($request->all());
        $validator = Validator::make($request->all(), [
            'title'            => 'required|string|max:255',
            'description'      => 'nullable|string',
            'target_role'      => 'required|in:all,admin,teacher,student',
            'target_course_id' => 'nullable|exists:courses,id',
            'start_at'         => 'required|date',
            'end_at'           => 'nullable|date|after_or_equal:start_at',
            'attachments.*'    => 'nullable|file|max:5120',
            'status'           => 'required|in:active,inactive',
            'image'            => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        // slug generate
        $slug = Str::slug($request->title);
        $originalSlug = $slug;
        $count = 1;
        while (Notice::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        // handle attachments
        $attachments = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $fileName = $file->getClientOriginalName();
                $file->move(public_path('upload/notice/attachments/'), $fileName);
                $attachments[] = $fileName;
            }
        }

        // create notice
        $notice = Notice::create([
            'title'            => $request->title,
            'slug'             => $slug,
            'creator_id'       => auth()->id(),
            'description'      => $request->description,
            'target_role'      => $request->target_role,
            'target_course_id' => $request->target_course_id,
            'start_at'         => $request->start_at,
            'end_at'           => $request->end_at,
            'status'           => $request->status,
            'attachments'      => !empty($attachments) ? json_encode($attachments) : null,
        ]);

        //dd($notice);

        // handle image upload
        if ($request->hasFile('image')) {
            $fileName = uniqid() . '.' . $request->image->getClientOriginalExtension();
            $request->image->move(public_path('upload/notice/'), $fileName);
            $notice->image = $fileName;
            $notice->save();
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'Notice created successfully.',
            'notice'  => $notice,
        ]);
    }



    public function delete($id){

        $notice = Notice::find($id);

        if($notice->image && file_exists(public_path("upload/notice/". $notice->image))){
            unlink(public_path("upload/notice/". $notice->image));
        }
        

        if($notice->attachments){
            $attachments = json_decode($notice->attachments);
            if(is_array($attachments)){
                foreach($attachments as $file){
                    $filePath = public_path("upload/notice/attachments/". $file);
                    if(file_exists($filePath)){
                        unlink($filePath);
                    }
                }
            }
        }


        $notice->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Notice delete successfully.',
        ]);
    }



}
