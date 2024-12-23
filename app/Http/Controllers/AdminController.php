<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
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

public function brand_edit($id){

    $brands = Brand::find($id);
    return view('admin.brands.edit', compact('brands'));


}
public function brand_update(Request $request, $id)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'slug' => 'required|string|max:255|unique:brands,slug,' . $id,
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    $brand = Brand::findOrFail($id);
    $brand->name = $request->name;
    $brand->slug = Str::slug($request->slug);

    // Handle image upload
    if ($request->hasFile('image')) {
        // Delete the old image if it exists
        if (!empty($brand->image) && file_exists(public_path($brand->image))) {
            unlink(public_path($brand->image));
        }

        $imageName = time() . '.' . $request->image->extension();
        $request->image->move(public_path('uploads/brands'), $imageName);
        $brand->image = 'uploads/brands/' . $imageName;
    }

    $brand->save();

    return redirect()->route('admin.brand')->with('status', 'Brand updated successfully!');
}

public function brand_delete($id)
{
    $brand = Brand::findOrFail($id);

    // Delete the image if it exists
    if (!empty($brand->image) && file_exists(public_path($brand->image))) {
        unlink(public_path($brand->image));
    }

    $brand->delete();

    return redirect()->route('admin.brand')->with('status', 'Brand deleted successfully!');

}

public function categories(){
    $categories = Category::orderBy('id','DESC')->paginate(10);
    return view('admin.categories', compact('categories'));

}

public function category_add(){
    return view('admin.category-add');

}

public function category_store(Request $request){
    $request->validate([
        'name'=> 'required',
        'slug'=> 'required|unique:categories,slug',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    $categoryData = new Category();
    $categoryData->name = $request->name;
    $categoryData->slug = Str::slug($request->slug);

    if ($request->hasFile('image')) {
        // Delete the old image if it exists
        if (!empty($categoryData->image) && file_exists(public_path($categoryData->image))) {
            unlink(public_path($categoryData->image));
        }

        $imageName = time() . '.' . $request->image->extension();
        $request->image->move(public_path('uploads/categories'), $imageName);
        $categoryData->image = 'uploads/categories/' . $imageName;
    }

    $categoryData->save();

    return redirect()->route('admin.categories')->with('status', 'Category has been added Successfully!');

}

}
