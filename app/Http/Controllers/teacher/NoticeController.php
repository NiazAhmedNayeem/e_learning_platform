<?php

namespace App\Http\Controllers\teacher;

use App\Http\Controllers\Controller;
use App\Jobs\SendNoticeJob;
use App\Models\Course;
use App\Models\Notice;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class NoticeController extends Controller
{
    public function index(){
        $teacherId = auth()->user()->id;
        $courses = Course::where('teacher_id', $teacherId)->where('status', 1)->get();
        return view('teacher.notice.index',  compact('courses'));
    }

    public function noticeData(Request $request){
        $teacherId = auth()->user()->id;
        $query = Notice::with('user','course');
        if($request->has('search') && $request->search != ''){
            $query->where('title', 'like', '%'. $request->search. '%');
        }
        $notices = $query->where('creator_id', $teacherId)->orderBy('id', 'desc')->paginate(5);
        return response()->json($notices);
    }

    public function store(Request $request)
    {
        //dd($request->all());
        $validator = Validator::make($request->all(), [
            'title'            => 'required|string|max:255',
            'description'      => 'nullable|string',
            'target_role'      => 'required|in:student',
            'target_course_id' => 'required|exists:courses,id',
            'start_at'         => 'required|date',
            'end_at'           => 'nullable|date|after_or_equal:start_at',
            'attachments.*'    => 'nullable|file|mimes:jpg,jpeg,png,gif,pdf,doc,docx|max:5120',
            'status'           => 'required|in:active,inactive,draft,schedule',
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
                $fileName = time().'_'.$file->getClientOriginalName();
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

        ///send users notification
        $startTime = \Carbon\Carbon::parse($notice->start_at, config('app.timezone'));
        if ($startTime->isPast()) {
            SendNoticeJob::dispatch($notice->id);
        } else {
            $delayInSeconds = now()->diffInSeconds($startTime, false);
            SendNoticeJob::dispatch($notice->id)->delay($delayInSeconds);
        }


        return response()->json([
            'status'  => 'success',
            'message' => 'Notice created successfully.',
            'notice'  => $notice,
        ]);
    }

    public function details($id){
        $notice = Notice::find($id);
        if(!$notice){
            return response()->json([
                'status' => 'error',
                'message' => 'Notice not found.',
            ]);
        }
        return response()->json([
            'status' => 'success',
            'data' => [
                'id'               => $notice->id,
                'title'            => $notice->title,
                'description'      => $notice->description,
                'target_role'      => $notice->target_role,
                'target_course_id' => $notice->target_course_id,
                'course'           => $notice->course ? [
                                                            'id'    => $notice->course->id,
                                                            'title' => $notice->course->title,
                                                        ] : null,
                'start_at'         => $notice->start_at ? $notice->start_at : null,
                'end_at'           => $notice->end_at ? $notice->end_at : null,
                'status'           => $notice->status,
                'image_show'       => $notice->image_show,
                'attachments'      => $notice->attachments ? json_decode($notice->attachments) : [],
            ],
        ]);
    }

    public function edit($id){
        $notice = Notice::find($id);

        if(!$notice){
            return response()->json([
                'status' => 'error',
                'message' => 'Notice not found.',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'id'               => $notice->id,
                'title'            => $notice->title,
                'description'      => $notice->description,
                'target_role'      => $notice->target_role,
                'target_course_id' => $notice->target_course_id,
                'start_at'         => $notice->start_at ? date('Y-m-d\TH:i', strtotime($notice->start_at)) : null,
                'end_at'           => $notice->end_at ? date('Y-m-d\TH:i', strtotime($notice->end_at)) : null,
                'status'           => $notice->status,
                'attachments'      => $notice->attachments ? json_decode($notice->attachments) : [],
            ],
        ]);
        
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'               => 'required|exists:notices,id',
            'title'            => 'required|string|max:255',
            'description'      => 'nullable|string',
            'target_role'      => 'required|in:student',
            'target_course_id' => 'required|exists:courses,id',
            'start_at'         => 'required|date',
            'end_at'           => 'nullable|date|after_or_equal:start_at',
            'attachments.*'    => 'nullable|file|mimes:jpg,jpeg,png,gif,pdf,doc,docx|max:5120',
            'status'           => 'required|in:active,inactive,draft,schedule',
            'image'            => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);


        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        
        $notice = Notice::findOrFail($request->id);


        $notice->title            = $request->title;
        $notice->description      = $request->description;
        $notice->target_role      = $request->target_role;
        $notice->target_course_id = $request->target_course_id;
        $notice->start_at         = date('Y-m-d H:i:s', strtotime($request->start_at));
        $notice->end_at           = $request->end_at ? date('Y-m-d H:i:s', strtotime($request->end_at)) : null;
        $notice->status           = $request->status;

        // slug handle
        $slug = Str::slug($request->name);

        if ($slug !== $notice->slug) {
            $originalSlug = $slug;
            $count = 1;

            while (Notice::where('slug', $slug)
                ->where('id', '!=', $notice->id)
                ->exists()) 
            {
                $slug = $originalSlug . '-' . $count;
                $count++;
            }

            $notice->slug = $slug;
        }

        // Handle attachments
        $newAttachments = [];
        // first database theke old file gula ana hocche
        $currentAttachments = $notice->attachments ? json_decode($notice->attachments, true) : [];
        // form theke je glla rakha holo
        $oldAttachments = $request->old_attachments ? json_decode($request->old_attachments, true) : [];
        // form theke remove kora file gula ber kora
        $deletedFiles = array_diff($currentAttachments, $oldAttachments);
        // form theke remove kora file gula delete kora
        foreach($deletedFiles as $file){
            $path = public_path('upload/notice/attachments/'.$file);
            if(file_exists($path)){
                unlink($path);
            }
        }

        ///new attachments insert
        if($request->hasFile('attachments')){
            foreach($request->file('attachments') as $file){
                $filename = time().'_'.$file->getClientOriginalName();
                $file->move(public_path('upload/notice/attachments'), $filename);
                $newAttachments[] = $filename;
            }
        }

        $allAttachments = array_merge($oldAttachments, $newAttachments);
        $notice->attachments = json_encode($allAttachments);

        // Update notice image if uploaded
        if($request->hasFile('image')){
            $image = $request->file('image');
            $imgName = time().'_'.$image->getClientOriginalName();
            $image->move(public_path('upload/notice'), $imgName);
            $notice->image = $imgName;
        }

        $notice->save();

        return response()->json([
            'status'  => 'success',
            'message' => 'Notice updated successfully!',
            'data'    => $notice
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
