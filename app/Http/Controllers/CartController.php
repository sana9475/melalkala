<?php

namespace App\Http\Controllers;

use App\products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class CartController extends Controller
{
    public function cart()
    {

        $Cproducts = Cookie::get('pay_product_cart');
//
        if(is_null($Cproducts))
            return redirect('/');
//
        $Cproducts=products::find($Cproducts);
//        return $Cproducts;
//
        return view('pages.cart',compact('Cproducts'))->with('title','سبدخرید');
    }

    public function addcart($product_id)
    {
        $product = products::find($product_id);

        if (is_null($product) || $product->price == 0)
            return redirect('/cart');

        $Cproducts = Cookie::get('pay_product_cart');


        if (is_null($Cproducts)) {

            $value=[$product->id => $product->id];
            Cookie::queue('pay_product_cart', $value, 1000 * 1000);
            return redirect('/cart');
        }

        if(in_array($product_id,$Cproducts))
            return redirect('/cart');


            $Cproducts[$product->product_id]=$product->product_id;

            Cookie::queue('pay_product_cart', $Cproducts, 1000 * 1000);

            return redirect('/cart');

    }

    public function deletecart($product_id)
    {
        $Cproducts = Cookie::get('pay_product_cart');
        if (($key=array_search($product_id,$Cproducts)) !==false){
            unset($Cproducts[$key]);

            if(count($Cproducts)==0){
               $cookie= Cookie::forget('pay_product_cart');

               return redirect('/products')->withCookie($cookie);
            }
            Cookie::queue('pay_product_cart', $Cproducts, 1000 * 1000);
            return redirect('/cart');
        }
        return redirect('/products');
    }
}
