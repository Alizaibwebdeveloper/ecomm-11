<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;


class AdminController extends Controller
{
    public function index(){
        return view('admin.index');
    }

    public function brands(){
        $brands = Brand::orderBy('id','DESC')->paginate();
        return view('admin.brands', compact('brands'));
    }

    public function brand_add(){
        return view('admin.brand-add');
    }

    public function brand_store(Request $request){

        $request->validate([
            'name'=> 'required',
            'slug'=> 'required|unique:brands,slug',
             'image'=> 'mimes:png,jpg,jpeg|max:2048',

        ]);

        $brandData = new Brand();
        $brandData->name = $request->name;
        $brandData->slug = Str::slug($request->slug);

        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('uploads/brands'), $imageName);
            $brandData->image = 'uploads/brands/' . $imageName;
    
        
        $brandData->save();

        return redirect()->route('admin.brand')->with('status', 'Brands has been added Successfully!');


    }

    

}
}
