<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Slide;
use App\Product;

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

    public function productType()
    {
        return view('page.product_type');
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
