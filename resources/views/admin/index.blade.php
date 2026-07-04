@extends('admin.layouts.master')
@section('content')
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Dashboard</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="" target="_blank">Home</a></li>
          <li class="breadcrumb-item active">Dashboard</li>
        </ol>
      </div>
    </div>
  </div>
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    @if(user()->type == 1)
    <div class="row">
      <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
          <div class="inner">
            <h3>{{env('CURRENCY')}}
              {{ 
                $orders->filter(function($order){
                  return $order->order_status == 'delivered';
                })->sum('price')
              }} 
            </h3>
            <p>Total Accumulated Sales</p>
          </div>
          <div class="icon">
            <i class="ion ion-stats-bars"></i>
          </div>
          <a href="{{ route('order.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
      </div>
      <!-- ./col -->
      <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
          <div class="inner">
            <h3>{{env('CURRENCY')}}
              {{ 
                $yearly_orders->filter(function($order){
                  return $order->order_status == 'delivered';
                })->sum('price')
              }}
            </h3>
            <p>Current Year Sales</p>
          </div>
          <div class="icon">
            <i class="ion ion-person-add"></i>
          </div>
          <a href="{{ route('order.current.year') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
      </div>
      
      <!-- ./col -->
      <div class="col-lg-3 col-6">
        <div class="small-box bg-secondary">
          <div class="inner">
            <h3>{{env('CURRENCY')}}
              {{ 
                $monthly_orders->filter(function($order){
                  return $order->order_status == 'delivered';
                })->sum('price')
              }}
            </h3>
            <p>Current Month Sales</p>
          </div>
          <div class="icon">
            <i class="ion ion-pie-graph"></i>
          </div>
          <a href="{{ route('order.current.month') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
      </div>
      <!-- ./col -->
      <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
          <div class="inner">
            <h3>{{env('CURRENCY')}}
              {{ 
                $daily_orders->filter(function($order){
                  return $order->order_status == 'delivered';
                })->sum('price')
              }}
            </h3>

            <p>Today's Sales</p>
          </div>
          <div class="icon">
            <i class="ion ion-bag"></i>
          </div>
          <a href="{{ route('order.today') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
      </div>
      <!-- ./col -->
    </div>
    <!-- /.row -->
    <!-- Small boxes (Stat box) -->
    <div class="row">
      @php
          $statuses = ['pending', 'processing', 'shipped', 'delivered'];
      @endphp

      @foreach ($statuses as $status)
          <div class="col-lg-3 col-6">
              <div class="small-box bg-{{ order_status($status) }}">
                  <div class="inner">
                      <h3>
                          {{ $orders->filter(function($order) use ($status) {
                              return $order->order_status == $status;
                          })->count() }}
                      </h3>
                      <p>{{ ucfirst($status) }} Orders</p>
                  </div>
                  <div class="icon">
                      <i class="ion ion-bag"></i>
                  </div>
                  <a href="{{ route('order.status.filter', $status) }}" class="small-box-footer">More info 
                      <i class="fas fa-arrow-circle-right"></i>
                  </a>
              </div>
          </div>
      @endforeach
      <!-- ./col -->
    </div>
    <!-- /.row -->
    @endif
  </div><!-- /.container-fluid -->
</section>
<!-- /.content -->
@endsection
@section('scripts')

@endsection
