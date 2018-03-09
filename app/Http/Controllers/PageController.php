<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Slide;
use App\Product;
use App\ProductType;
use App\Cart;
use Session;

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

    public function getProductDetail(Request $req)
    {
        $sp_detail = Product::where('id', '=', $req->id)->first();
        $sp_tuongtu = Product::where('id_type', $sp_detail->id_type)->paginate(3);

        return view('page.product_detail', compact(
            'sp_detail',
            'sp_tuongtu'
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

    public function addCart(Request $req, $id)
    {
        $product = Product::find($id);
        $oldCart = Session('cart')?Session::get('cart'):null;
        $cart = new Cart($oldCart);
        $cart->add($product, $id);
        $req->session()->put('cart', $cart);

        return redirect()->back();
    }
}
