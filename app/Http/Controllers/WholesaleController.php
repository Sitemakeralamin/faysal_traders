<?php

namespace App\Http\Controllers;

use App\Models\Wholesale;
use Illuminate\Http\Request;
use Alert;
use Auth;
use GrahamCampbell\ResultType\Success;

class WholesaleController extends Controller
{
    public function index()
    {
        return view("wholesale.index");
    }
    public function store(Request $request)
    {
        //  return ($request)->all();
        $wholesale = new Wholesale;
        $wholesale->name = $request->name;
        $wholesale->phone = $request->phone;
        // if($request->hasFile('image')){
        //     $image = $request->file('image');
        //     $img = time() . rand() . '.' . $image->getClientOriginalExtension();
        //     $location = public_path('images/product/' . $img);
        //     Image::make($image)->save($location);
        //     $wholesale->image = $img;
        // }
        if ($request->image) {
            $file = $request->file('image');
            $filename = time() . rand() . '.' . $file->getClientOriginalExtension();
            $path = public_path('images/product/');
            $file->move($path, $filename);
            $wholesale->image = $filename;
        }

        $wholesale->save();
        Alert::toast('Submition success');
        // Alert::toast('Payment Saved');

        // return back();
        return redirect()->route('index');

    }
        public function view(){
            $products=Wholesale::all();
            return view("admin.wholesale.all",compact('products'));
        }
public function destroy($id){
    if (user()->type == 1) {
        $order = Wholesale::find($id);
        if (!is_null($order)) {

                $order->delete();

            $order->delete();
            Alert::toast('Order deleted successfully!', 'success');
            return back();;
        }
        else{
            Alert::toast('Something went wrong !', 'error');
            return back();
        }
    }
    else{
        Alert::toast('Access Denied !', 'error');
        return back();
    }
}
}
