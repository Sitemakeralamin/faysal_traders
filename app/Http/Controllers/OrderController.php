<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

use Auth;
use PDF;
use Session;
use Alert;
use App\Models\OrderStatus;
use Carbon\Carbon;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (user()->type == 1) {
            $orders = Order::orderBy('id', 'DESC')->get();
            return view('admin.order.index', compact('orders'));
        }
        else{
            Alert::toast('Access Denied !', 'error');
        }
    }

    public function current_year()
    {
        if (user()->type == 1) {
            $orders = Order::whereYear('created_at', Carbon::now()->year)->get();
            return view('admin.order.current-year', compact('orders'));
        }
        else{
            Alert::toast('Access Denied !', 'error');
        }
    }

    public function current_month()
    {
        if (user()->type == 1) {
            $orders = Order::whereYear('created_at', Carbon::now()->year)->get();
            return view('admin.order.current-month', compact('orders'));
        }
        else{
            Alert::toast('Access Denied !', 'error');
        }
    }

    public function today()
    {
        if (user()->type == 1) {
            $orders = Order::whereDate('created_at', Carbon::today())->get();
            return view('admin.order.current-month', compact('orders'));
        }
        else{
            Alert::toast('Access Denied !', 'error');
        }
    }

    public function search(Request $request)
    {
        if (user()->type == 1) {
            if (!empty($request->order_status_id) && !empty($request->date_from) && !empty($request->date_to)) {
                $start_date = Carbon::createFromFormat('Y-m-d H:i:s', $request->date_from.' 00:00:00');
                $end_date = Carbon::createFromFormat('Y-m-d H:i:s', $request->date_to.' 23:59:59');
                $order_status_id = $request->order_status_id;

                $orders = Order::where('order_status_id', $order_status_id)
                ->whereBetween('created_at', [$start_date,$end_date])->orderBy('id', 'DESC')->get();
            }
            if ((!empty($request->order_status_id) && empty($request->date_from) && empty($request->date_to)) || (!empty($request->order_status_id) && !empty($request->date_from) && empty($request->date_to)) || (!empty($request->order_status_id) && empty($request->date_from) && !empty($request->date_to))) {

                $order_status_id = $request->order_status_id;

                $orders = Order::where('order_status_id', $order_status_id)->orderBy('id', 'DESC')->get();
            }
            if (empty($request->order_status_id) && !empty($request->date_from) && !empty($request->date_to)) {
                $start_date = Carbon::createFromFormat('Y-m-d H:i:s', $request->date_from.' 00:00:00');
                $end_date = Carbon::createFromFormat('Y-m-d H:i:s', $request->date_to.' 23:59:59');
                $order_status_id = $request->order_status_id;
                $orders = Order::whereBetween('created_at', [$start_date,$end_date])->orderBy('id', 'DESC')->get();
            }
            if (empty($request->order_status_id) && (empty($request->date_from) || empty($request->date_to))) {
                $orders = Order::orderBy('id', 'DESC')->get();
            }

            // 2nd step filter
            $district_id = $request->district_id;

            if (!empty($request->district_id) && !empty($request->area_id)) {

                $orders = $orders->where('district_id', $request->district_id)->where('area_id', $request->area_id);
            }
            if (!empty($request->district_id) && empty($request->area_id)) {
                $orders = $orders->where('district_id', $request->district_id);
            }

            return view('admin.order.index', compact('orders'));
        }
        else{
            abort(403, 'Unauthorized action.');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (user()->type == 1) {
            $order = Order::find($id);
            if($order){
                return view('admin.order.edit', compact('order'));
            }else{
                return redirect()->route('home')->with('error','Data not found!');
            }
        }
        else{
            Alert::toast('Access Denied !', 'error');
            return back();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $order = Order::find($id);
        $order->update(['delivery_charge'=>$request->delivery_charge]);
        return back()->with('success', 'Order Updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (user()->type == 1) {
            $order = Order::find($id);
            if (!is_null($order)) {
                foreach ($order->order_product as $product) {
                    $product->delete();
                }
                $order->delete();
                Alert::toast('Order deleted successfully!', 'success');
                return redirect()->route('order.index');
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

    public function change_status(Request $request, $id)
    {
        if (user()->type == 1 || user()->type == 3) {
            $order = Order::find($id);
            if (!is_null($order)) {
                if($order->order_status <> $request->order_status_id) {
                    $order->order_status = $request->order_status_id;
                    $status = $order->save();
                    if($status) {
                        $order_status_info = new OrderStatus;
                        $order_status_info->order_code = $order->code;
                        $order_status_info->status = $request->order_status_id;
                        $order_status_info->status_changed_by = user()->id;
                        $order_status_info->created_at = now();
                        $order_status_info->save();
                    }
                    Alert::toast('Status Updated !', 'success');
                }
                else {
                    Alert::toast('Order Status Already '.$request->order_status_id.'!', 'error');
                }

                return back();
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

    public function change_payment_status(Request $request, $id)
    {
        if (user()->type == 1) {
            $order = Order::find($id);
            if (!is_null($order)) {
                $order->payment_status = $request->payment_status;
                $order->save();
                Alert::toast('Status Updated !', 'success');
                return back();
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

    public function generate_invoice($id)
    {
        $order =Order::find($id);
        if (!is_null($order)) {
            return view('admin.invoice.generate', compact('order'));

            // $pdf = PDF::loadView('admin.invoice.generate', compact('order'));
            // return $pdf->stream($order->code.'.pdf');
            // return $pdf->download($order->code.'.pdf');
        }
        else{
            Alert::toast('Invoice Not Found!', 'error');
            return back();
        }
    }

    public function orders_by_status($id)
    {
        if (user()->type == 1) {
            $orders = Order::where('order_status', $id)->orderBy('id', 'DESC')->get();
            return view('admin.order.index', compact('orders'));
        }
        else{
            Alert::toast('Access Denied !', 'error');
        }
    }
}
