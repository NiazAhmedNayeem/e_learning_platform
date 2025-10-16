<?php

namespace App\Http\Controllers\siteSetting;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MenuController extends Controller
{
    public function menuData(){
        $menu = Menu::latest()->paginate(7);
        $menu->load('parent');
        return response()->json($menu);
    }

    //Ajax parent menu list
    public function menuList(){
        $menus = Menu::where('parent_id', null)->get();
        return response()->json($menus);
    }

    public function menuSave(Request $request){

        $validator = Validator::make($request->all(), [
            'title'     => 'required|string|max:100',
            'url'       => 'required|string|max:255',
            'location'  => 'required|in:header,footer_left,footer_right',
            'parent_id' => 'nullable|exists:menus,id',
            'order'     => 'required|numeric',
        ]);

        if($validator->fails()){
            return response()->json([
                'status'    => 'error',
                'message'   => $validator->errors(),
            ], 422);
        }

        $data = [
            'title'     => $request->title,
            'url'       => $request->url,
            'location'  => $request->location,
            'parent_id' => $request->parent_id ?? null,
            'order'     => $request->order,
        ];

        if($request->id){
            //update menu
            $menu = Menu::find($request->id);
            $menu->update($data);
        }else{
            //create menu
            $menu = Menu::create($data);
        }
        

        return response()->json([
            'status'    => 'success',
            'message'   => $request->id ? 'Menu updated successfully' : 'Menu created successfully',
            'data'      => $menu,
        ], 201);
    }

    public function menuUpdate($id)
    {
        $menu = Menu::with('parent')->find($id);
        if(!$menu){
            return response()->json([
                'status'=>'error',
                'message'=>'Menu not found'
            ], 404);
        }

        return response()->json([
            'status'=>'success',
            'menu'=>$menu
        ]);
    }

    public function menuDelete($id){
        $menu = Menu::find($id);
        if(!$menu){
            return response()->json([
                'status' => 'error',
                'message' => 'Menu not fond.',
            ], 404);
        }

        $menu->delete();
        Menu::where('parent_id', $id)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Menu delete successfully.',
        ], 200);
    }
}
