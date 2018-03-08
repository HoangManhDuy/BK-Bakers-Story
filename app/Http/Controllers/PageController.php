<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Slide;
use App\Product;
use App\ProductType;

class PageController extends Controller
{
    public function home()
    {
        $slide = Slide::all();
        $new_product = Product::where('new', '=', '1')->paginate(8);
        $sanpham_km = Product::where('promotion_price', '<>', '0')->paginate(8);

        return view('page.home', compact(
            'slide',
            'new_product',
            'sanpham_km'
        ));
    }

    public function productType($type)
    {
        $sp_theoloai = Product::where('id_type', $type)->get();
        $sp_khac = Product::where('id_type', '<>', $type)->paginate(3);
        $loai = ProductType::all();
        $loai_sp = ProductType::where('id', $type)->first();

        return view('page.product_type', compact(
            'sp_theoloai',
            'sp_khac',
            'loai',
            'loai_sp'
    ));
    }

    public function about()
    {
        return view('page.about');
    }

    public function contact()
    {
        return view('page.contact');
    }
}
