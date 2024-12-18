<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index(){
        return view('admin.index');
    }

    public function brands(){
        $brands = Brand::orderBy('id','DESC')->paginate();
        return view('admin.brands', compact('brands'));
    }
}
