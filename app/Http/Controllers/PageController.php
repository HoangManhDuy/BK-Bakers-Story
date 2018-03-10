<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Slide;
use App\Product;
use App\ProductType;
use App\Cart;
use App\Customer;
use App\Bill;
use App\BillDetail;
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

    public function delCart($id)
    {
        $oldCart = Session('cart')?Session::get('cart'):null;
        $cart = new Cart($oldCart);
        $cart->removeItem($id);
        if(count($cart->items) > 0){
            Session::put('cart', $cart);
        }
        else{
            Session::forget('cart');
        }

        return redirect()->back();
    }

    public function order()
    {
        return view('page.order');
    }

    public function postOrder(Request $req)
    {
        $cart = Session::get('cart');

        $customer = new Customer;
        $customer->name = $req->name;
        $customer->gender = $req->gender;
        $customer->email = $req->email;
        $customer->address = $req->address;
        $customer->phone_number = $req->phone;
        $customer->note = $req->notes;
        $customer->save();

        $bill = new Bill;
        $bill->id_customer = $customer->id;
        $bill->date_order = date('Y-m-d');
        $bill->total = $cart->totalPrice;
        $bill->payment = $req->payment_method;
        $bill->note = $req->notes;
        $bill->save();

        foreach ($cart->items as $key => $value)
        {
            $bill_detail = new BillDetail;
            $bill_detail->id_bill = $bill->id;
            $bill_detail->id_product = $key;
            $bill_detail->quantity = $value['qty'];
            $bill_detail->unit_price = ($value['price']/$value['qty']);
            $bill_detail->save();
        }

        Session::forget('cart');

        return redirect()->back()->with('thongbao', 'Đặt hàng thành công');
    }

    public function getSearchProduct(Request $req)
    {
        $key = $req->key;
        $search = Product::where('name', 'like', '%'.$req->key.'%')
                            ->orWhere('unit_price', $req->key)->paginate(12);

        return view('page.search', compact('search','key'));
    }
}
