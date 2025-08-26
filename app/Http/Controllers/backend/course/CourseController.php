<?php

namespace App\Http\Controllers\backend\course;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Course;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class CourseController extends Controller
{
    public function index(Request $request){
        $search = $request->input('search');
        // $courses = Course::where('status', 1)->paginate(5);

        $courses = Course::where(function($query) use ($search){
            $query->where('title', 'like', "%{$search}%")
            ->orWhereHas('category', function($q) use ($search){
            $q->where('name', 'like', "%{$search}%");
            });
        })
        ->orderBy('id', 'desc')
        ->paginate(10);
                        


        return view('backend.course.index', compact('courses', 'search'));
    }

    public function create(){
        $categories = Category::where('status', 1)->get();
        //$teacher = User::where('expertise_category_id', 'categories')->get();
        return view('backend.course.create', compact('categories'));
    }

    public function store(Request $request){
        $request->validate([
            'category_id'       => 'required|integer|exists:categories,id',
            'title'             => 'required|string|max:255',
            'price'             => 'required|numeric',
            'discount'          => 'nullable|numeric', 
            'prerequisite'      => 'nullable|string',
            'short_description' => 'required|string',
            'long_description'  => 'required|string',
            'status'            => 'required|integer|in:1,0',
        ]);

        DB::beginTransaction();

        try{
            $course = new Course();
            $course->category_id = $request->category_id;
            $course->title = $request->title;
            $course->price = $request->price;
            $course->discount = $request->discount ?? 0;
            if($request->discount){
                $course->discount_price =  $request->price - ($request->price * $request->discount / 100);
            }else{
                $course->discount_price =  $request->price;
            }
            $course->prerequisite = $request->prerequisite;
            $course->short_description = $request->short_description;
            $course->long_description = $request->long_description;
            $course->status = $request->status;

            $slug = Str::slug($request->title);
            $originalSlug = $slug;
            $count = 1;
            while(Course::where('slug', $slug)->exists()){
                $slug = $originalSlug . '-' . $count;
                $count++;
            }
            $course->slug = $slug;

            if($request->hasFile('image')){
                $fileName = rand().time(). '.' .request()->image->getClientOriginalExtension();
                request()->image->move(public_path('upload/courses/'), $fileName);
                $course->image = $fileName;
            }
            //dd($course);
            $course->save();

            DB::commit();

            return redirect()->route('admin.course.index')->with('success', 'Course create successfully. Thank you.');


        }catch(\Exception $e){
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }
}
