<?php

namespace App\Http\Controllers\siteSetting;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MenuController extends Controller
{
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

        $menu = Menu::create($data);

        return response()->json([
            'status'    => 'success',
            'message'   => 'Menu create successfully',
            'menu'      => $menu,
        ], 201);
    }
}
