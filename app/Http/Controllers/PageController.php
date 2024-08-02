<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use App\Models\OrderDetail;
use Auth;

use Session;
class PageController extends Controller
{
    // dd(12);
    public function index(Request $request)
    {  
        // $my_cart_data = Session::flashs('cart_data');
        // dd($my_cart_data);
        $products = Product::where('status',1)->get();
        $categorys = Category::get();
        // dd($product);
        // $categorys = Category::orderBy('name','asc')->Pluck('name','id')->toArray();   
        return view('frontend.index',compact('categorys','products'));
    }
    public function blog()
    {
        $products = Product::get();
        $categorys = Category::get();
        return view('frontend.blog',compact('categorys','products'));
    }
    public function shopGrid()
    {
        $products = Product::get();
        $categorys = Category::get();
        return view('frontend.shop_grid',compact('categorys','products'));
    }
    public function shopDetails()
    {
        $products = Product::get();
        $categorys = Category::get();
        return view('frontend.shop-details',compact('categorys','products'));
    }




    public function shopingCart($product_id)
    {
        // dd($product_id);
        
        $response = array('status'=>false,'message'=>'');
        $product = Product::find($product_id)->toArray();

        $cart_data = Session::get('cart_data');

        
        if(isset($cart_data[$product_id])){
            $cart_data[$product_id]['order_quantity']=$cart_data[$product_id]['order_quantity'] + 1;
        }else{
            $cart_data[$product_id]= $product;
            $cart_data[$product_id]['order_quantity'] = 1;
        }


        Session::put('cart_data',$cart_data);
        
        $response['status'] = true;
        $response['data'] = array('item_count'=>count(Session::get('cart_data')));
        return response()->json($response);
      
    }



   

    public function showCart(Request $request)
    {   

        $categorys = Category::get();

        $cart = session()->get('cart_data',);
        // $products = Product::get();
        // dd($carts);
        return view('frontend.shoping-cart', compact('cart','categorys'));
    }

    public function addToQuantity(Request $request)
    {
        

        $response = array('status'=>false,'message'=>'');
        $product = Product::find($request->id);
          
        $cart = session()->get('cart_data');

        if(isset($cart[$request->product_id])) {
            $cart[$request->product_id]['order_quantity'] = $request->product_quantity;
            $cart = session()->put('cart_data',$cart);
        }
          
        // session()->put('cart_data', $cart);
        $response['status'] = true;
        $response['data'] = [];
        return response()->json($response);
    }


    public function remove(Request $request)
    {
        $response = array('status'=>false,'message'=>'');
            $cart = session()->get('cart_data');
            if(isset($cart[$request->id])) {
                unset($cart[$request->id]);
                session()->put('cart_data', $cart);
                $response['status'] = true;
            }


        return response()->json($response);
        
    }



    public function ProceedToCheckout(Request $request)
    {
        // $products = Product::get();
        // $categorys = Category::get();
            
            // $user = User::get('id')->toArray();
            // dd($user);
        $carts = Session::get('cart_data');
        if (Session::has('cart_data')){
            foreach($carts as $id=>$cart){
                         
                $order = new Order();
                $order->user_id = Auth::user()->id;
                $order->total_amount = $cart['amount']*$cart['order_quantity'];
                $order->save();
                Session::flush('cart_data');
                // Session::forget('cart_data');
            }
        }
        return redirect()->route('checkout');
    }

    public function checkout(Request $request){
        $products = Product::get();
        $categorys = Category::get();
        $orders = Order::get();
        // dd($order);
        return view('frontend.checkout',compact('orders','products','categorys'));
    }






    public function blogDetails()
    {
        $products = Product::get();
        $categorys = Category::get();
        return view('frontend.blog-details',compact('categorys','products'));
    }
    public function contact()
    {
        $products = Product::get();
        $categorys = Category::get();
        return view('frontend.contact',compact('categorys','products'));
    }

    public function categorysProduct($category_slug)
    {

        $categorys = Category::get();
        $category = Category::where('slug',$category_slug)->first();
       
        if(isset($category)){
        // dd($category->created_at);
            
            $products = Product::where('category_id',$category->id)->get();
            return view('frontend.categorys_product.category_product',compact('categorys','products'));
        }
        abort(404);
    }

    public function productDetail($product_slug)
    {
        $categorys = Category::get();
        $product = Product::where('slug',$product_slug)->first();
        if (isset($product)) {
            
        return view('frontend.categorys_product.product_details',compact('categorys','product'));
        }
        abort(404);

    }













}
