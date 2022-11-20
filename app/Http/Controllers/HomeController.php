<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class HomeController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index() {        
        $getData = DB::table('products')->where('Status', 'Active')->get();
        return view("products", compact('getData'));
    }

    public function products(Request $request) {
        $getData = DB::table('products')->where('Status', 'Active')->get();
        return view("products", compact('getData'));
    }

    public function productsshow($id) {
        $getData = DB::table('products')->where('id', $id)->first();
        $intent = auth()->user()->createSetupIntent();
        return view("productsshow", compact('getData', 'intent'));
    }

    public function purchase(Request $request) {
        $user = $request->user();
        $paymentMethod = $request->input('payment_method');
        $data=$request->all();
        $id=array_key_last($data);
        $product = DB::table('products')->where('id', $id)->first();     
        try {
            $user->createOrGetStripeCustomer();
            $user->updateDefaultPaymentMethod($paymentMethod);
            $user->charge($product->Price * 100, $paymentMethod);
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }

        return back()->with('message', 'Product purchased successfully!');
    }

}
