<?php

namespace App\Http\Controllers\api\backend\admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ApiCategoryController extends Controller
{
    public function index(){
        $categories = Category::all();
        return response()->json($categories);
    }

    public function store(Request $request){

        try{
            $request->validate([
                'name' => 'required|string|max:100',
                'image' => 'nullable|image|mimes:png,jpg,jpeg,gif|max:2048',
                'status' => 'required|in:0,1',
            ]);


            $category = new Category();

            $category->name = $request->name;
            $category->status = $request->status;

            if($request->hasFile('image')){
                $image = $request->file('image');
                $imageName = time().uniqid().'.'.$image->getClientOriginalExtension();
                $image->move(public_path("upload/category/"), $imageName);
                $category->image = $imageName;
            }

            $slug = Str::slug($request->name);
            $originalSlug = $slug;
            $count = 1;
            while(Category::where('slug', $slug)->exists()){
                $slug = $originalSlug . '-' . $count;
                $count++;
            }
            $category->slug = $slug;

            $category->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Category create successfully.',
                'category' => $category,
            ], 201);

        }catch (ValidationException $e) {
            return response()->json([
                'status' => 'validation_error',
                'errors' => $e->errors()
            ], 422);

        }catch(\Exception $e){
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function show($id){
        $category = Category::find($id);
        if(!$category){
            return response()->json([
                'status' => 'error',
                'message' => 'Category not fond.'
            ], 404);
        }
        return response()->json($category);
    }

    public function update(Request $request, $id){

        $category = Category::find($id);

        if(!$category){
            return response()->json([
                'status' => 'error',
                'message' => 'Category not fond.'
            ], 404);
        }

        $request->validate([
            'name' => 'required|string',
            'image' => 'nullable|image|mimes:png,jpg,jpeg,gif|max:2048',
            'status' => 'required|in:0,1',
        ]);


        $category->name = $request->name;
        $category->status = $request->status;

        if($request->hasFile('image')){
            @unlink(public_path("upload/category/". $category->image));
            $image = $request->file('image');
            $imageName = time().uniqid().'.'.$image->getClientOriginalExtension();
            $image->move(public_path("upload/category/"), $imageName);
            $category->image = $imageName;
        }

        $slug = Str::slug($request->name);
        if ($slug !== $category->slug) {
            $originalSlug = $slug;
            $count = 1;
            while (Category::where('slug', $slug)
                ->where('id', '!=', $category->id)
                ->exists()){
                    $slug = $originalSlug . '-' . $count;
                    $count++;
                }
            $category->slug = $slug;
        }

        $category->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Category create successfully.',
            'category' => $category,
        ], 200);
    }

    public function delete($id){
        $category = Category::find($id);

        if(!$category){
            return response()->json([
                'status' => 'error',
                'message' => 'Category not fond.'
            ], 404);
        }

        if($category->image && file_exists(public_path("upload/category/". $category->image))){
            @unlink(public_path('upload/category/'. $category->image));
        }

        $category->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Category delete successfully.',
        ], 200);
    }
}
