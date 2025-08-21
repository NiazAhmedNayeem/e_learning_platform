<?php

namespace App\Http\Controllers\backend\admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request){
        $search = $request->input('search');
        $categories = Category::when($search, function($query, $search){
            return $query->where('name', 'like', "%{$search}%")
            ->orWhere('slug', 'like', "%{$search}%");
        })->paginate(5);
        return view('backend.category.index', compact('categories', 'search'));
    }

    public function create(){
        return view('backend.category.create');
    }

    public function store(Request $request){
        $request->validate([
            'name'      => 'required|string|max:255',
            'status'    => 'required|boolean',
            'image'     => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $category = new Category();
        $category->name = $request->name;
        $category->status = $request->status;

        // slug generate
        $slug = Str::slug($request->name);

        // check uniqueness
        $originalSlug = $slug;
        $count = 1;
        while (Category::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        $category->slug = $slug;

        if($request->hasFile('image')){ 
                $fileName = rand().time().'.'.request()->image->getClientOriginalExtension(); 
                request()->image->move(public_path('upload/category/'),$fileName); 
                $category->image = $fileName; 
        }
        $category->save();
        return redirect()->route('admin.category.index')->with('success', 'Category create successfully. Thank you.');

    }
    public function edit($id){
        $category = Category::find($id);
        return view('backend.category.edit', compact('category'));
    }
    public function update(Request $request, $id){

        $request->validate([
            'name'      => 'required|string|max:255',
            'status'    => 'required|boolean',
            'image'     => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $category = Category::find($id);
        $category->name = $request->name;
        $category->status = $request->status;

        // slug handle
        $slug = Str::slug($request->name);

        if ($slug !== $category->slug) {
            $originalSlug = $slug;
            $count = 1;

            while (Category::where('slug', $slug)
                ->where('id', '!=', $category->id)
                ->exists()) 
            {
                $slug = $originalSlug . '-' . $count;
                $count++;
            }

            $category->slug = $slug;
        }

        if($request->hasFile('image')){ 
                @unlink(public_path("upload/category/".$category->image));
                $fileName = rand().time().'.'.request()->image->getClientOriginalExtension(); 
                request()->image->move(public_path('upload/category/'),$fileName); 
                $category->image = $fileName; 
        }
        $category->save();

        return redirect()->route('admin.category.index')->with('success', 'Category create successfully. Thank you.');
    }

    public function delete($id){
        $category = Category::find($id);
        if(!$category){
            return redirect()->route('admin.category.index')->with('error', 'Category not found.');
        }

        if($category->image && file_exists(public_path("upload/category/".$category->image))){
            unlink(public_path("upload/category/".$category->image));
        }
        $category->delete();
        return redirect()->route('admin.category.index')->with('success', 'Category delete successfully. Thank you.');
    }
}
