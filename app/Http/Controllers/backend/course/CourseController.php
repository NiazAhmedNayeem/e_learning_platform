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

            //corse slug (category-course title)
            $category = Category::find($request->category_id);
            $category_slug = $category?->name ?? 'category';
            $slug = Str::slug($category_slug.'-'.$request->title);
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

    public function edit($id){
        $categories = Category::where('status', 1)->get();
        $course = Course::find($id);
        return view('backend.course.edit', compact('categories', 'course'));
    }

    public function update(Request $request, $id){
        $request->validate([
            'category_id'        => 'required|integer|exists:categories,id',
            'title'              => 'required|string|max:255',
            'price'              => 'required|numeric',
            'discount'           => 'nullable|numeric|min:0|max:100',
            'prerequisite'       => 'nullable|string',
            'short_description'  => 'nullable|string',
            'long_description'   => 'nullable|string',
            'status'             => 'required|integer|in:0,1',
            'image'              => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        DB::beginTransaction();

        try{
            $course = Course::find($id);
            $course->category_id = $request->category_id;
            $course->title = $request->title;
            $course->price = $request->price;
            $course->discount = $request->discount ?? 0;
            if($request->discount){
                $course->discount_price = $request->price - ($request->price * $request->discount / 100);
            }else{
                $course->discount_price = $request->price;
            }
            $course->prerequisite = $request->prerequisite;
            $course->short_description = $request->short_description;
            $course->long_description = $request->long_description;
            $course->status = $request->status;


            $category = Category::find($request->category_id);
            $category_slug = $category?->name ?? 'category';
            $slug = Str::slug($category_slug.'-'.$request->title);
            if($slug !== $course->slug){
                $originalSlug = $slug;
                $count = 1;
                while(Course::where('slug', $slug)->where('id', '!=', $course->id)->exists()){
                    $slug = $originalSlug . '-' . $count;
                    $count++;
                }
                $course->slug = $slug;
            }
            

            if($request->hasFile('image')){
                @unlink(public_path("upload/courses/".$course->image) );
                $fileName = rand().time().'.'.request()->image->getClientOriginalExtension();
                request()->image->move(public_path('upload/courses/'), $fileName);
                $course->image = $fileName;
            }

            $course->save();
            //dd($course);
            DB::commit();

            return redirect()->route('admin.course.index')->with('success', 'Course Update Successfully.');

        } catch(\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
        
    }

    public function delete($id){
        $course = Course::find($id);

        if(!$course){
            return back()->with('error', 'Course not found');
        }
        if($course->image && file_exists(public_path("upload/courses/". $course->image))){
            unlink(public_path("upload/courses/". $course->image));
        }
        $course->delete();
        return redirect()->route('admin.course.index')->with('success', 'Course delete successfully.');
    }
    
}
