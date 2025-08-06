@extends('backend.app')

@section('title', 'Order details show')

@push('style')

@endpush

@section('content')
    {{-- PAGE-HEADER --}}
    <div class="page-header">
        <div>
            <h1 class="page-title">Order details show</h1>
        </div>
        <div class="ms-auto pageheader-btn">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Order</li>
            </ol>
        </div>
    </div>
    {{-- PAGE-HEADER END --}}


    <div class="row">
        <div class="col-lg-12 col-xl-12 col-md-12 col-sm-12">
            <div class="card box-shadow-0">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table border table-bordered table-hover text-center">
                            <tbody>
                                <tr>
                                    <th class="w-25 text-start">User Name</th>
                                    <td>{{ $order->user->name }}</td>
                                </tr>
                                @if($order->valet)
                                    <tr>
                                        <th class="w-25 text-start">Valet Name</th>
                                        <td>{{ $order->valet->name }}</td>
                                    </tr>
                                    <tr>
                                        <th class="w-25 text-start">Valet Email</th>
                                        <td>{{ $order->valet->email }}</td>
                                    </tr>
                                @endif
                                <tr>
                                    <th class="w-25 text-start">Shop Name</th>
                                    <td>{{ $order->shop->name }}</td>
                                </tr>
                                <tr>
                                    <th class="w-25 text-start">Store Number</th>
                                    <td>{{ $order->shop->stall_number }}</td>
                                </tr>
                                <tr>
                                    <th class="w-25 text-start">Order Number</th>
                                    <td>{{ $order->order_number }}</td>
                                </tr>
                                <tr>
                                    <th class="w-25 text-start">Payment Method</th>
                                    <td>{{ $order->payment_method }}</td>
                                </tr>
                                @if($order->payment_id)
                                    <tr>
                                        <th class="w-25 text-start">Payment ID</th>
                                        <td>{{ $order->payment_id }}</td>
                                    </tr>
                                @endif
                                @if($order->discount > 0)
                                    <tr>
                                        <th class="w-25 text-start">Discount</th>
                                        <td>{{ number_format($order->discount, 2) }}</td>
                                    </tr>
                                @endif
                                @if($order->tax_percentage)
                                    <tr>
                                        <th class="w-25 text-start">Tax Percentage</th>
                                        <td>{{ $order->tax_percentage }} %</td>
                                    </tr>
                                @endif
                                @if($order->tax)
                                    <tr>
                                        <th class="w-25 text-start">Tax</th>
                                        <td>{{ number_format($order->tax, 2) }}</td>
                                    </tr>
                                @endif
                                @if($order->valet_charge)
                                    <tr>
                                        <th class="w-25 text-start">Valet Charge</th>
                                        <td>{{ number_format($order->valet_charge, 2) }}</td>
                                    </tr>
                                @endif
                                @if($order->valet_charge_extra)
                                    <tr>
                                        <th class="w-25 text-start">Valet Extra Charge</th>
                                        <td>{{ number_format($order->valet_charge_extra, 2) }}</td>
                                    </tr>
                                @endif
                                @if($order->platform_fee)
                                    <tr>
                                        <th class="w-25 text-start">Platform Fee</th>
                                        <td>{{ number_format($order->platform_fee, 2) }}</td>
                                    </tr>
                                @endif
                                @if($order->sub_total)
                                    <tr>
                                        <th class="w-25 text-start">Sub Total Price</th>
                                        <td>{{ number_format($order->sub_total, 2) }}</td>
                                    </tr>
                                @endif
                                @if($order->not_found_total)
                                    <tr>
                                        <th class="w-25 text-start">Not Found Items Price</th>
                                        <td><strong>{{ number_format($order->not_found_total, 2) }}</strong></td>
                                    </tr>
                                @endif
                                @if($order->total_price)
                                    <tr>
                                        <th class="w-25 text-start">Total Price</th>
                                        <td><strong>{{ number_format($order->total_price, 2) }}</strong></td>
                                    </tr>
                                @endif
                                @if($order->not_found_total)
                                    <tr>
                                        <th class="w-25 text-start">Total Remain Amount</th>
                                        <td><strong>{{ number_format($order->total_price - $order->not_found_total, 2) }}</strong></td>
                                    </tr>
                                @endif
                                <tr>
                                    <th class="w-25 text-start">Valet Shopping Payment Status</th>
                                    <td><span class="badge bg-success">{{ ucfirst($order->shopping_payment) }}</span></td>
                                </tr>
                                <tr>
                                    <th class="w-25 text-start">Valet Payment Status</th>
                                    <td><span class="badge bg-success">{{ ucfirst($order->valet_payment) }}</span></td>
                                </tr>
                                <tr>
                                    <th class="w-25 text-start">Payment Status</th>
                                    <td><span class="badge bg-success">{{ ucfirst($order->payment_status) }}</span></td>
                                </tr>
                                <tr>
                                    <th class="w-25 text-start">OrderStatus</th>
                                    <td><span class="badge bg-success">{{ ucfirst($order->status) }}</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="table-responsive">
                        <h5>Order Details</h5>
                        <table class="table border table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Product Name</th>
                                    <th>Product Image</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th>Found or Not</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($order->orderDetails as $orderDetail)
                                    <tr>
                                        <td>{{ $orderDetail->product->name }}</td>
                                        <td>
                                            @if ($orderDetail->product->images->isNotEmpty())
                                                @foreach ($orderDetail->product->images as $image)
                                                    <img src="{{ asset($image->image_path) }}" class="img-fluid img-thumbnail" style="width: auto; height: 50px;" />
                                                @endforeach
                                            @else
                                                <img src="{{ asset('/frontend/no-image.jpg') }}" class="img-fluid img-thumbnail" style="width: auto; height: 50px; margin: 5px;" />
                                            @endif
                                        </td>
                                        <td>{{ $orderDetail->quantity }}</td>
                                        <td>{{ $orderDetail->price }}</td>
                                        <td>{{ $orderDetail->found_item }}</td>
                                        <td>
                                            @if($orderDetail->found_item == 'Yes')
                                                {{ $orderDetail->price * $orderDetail->quantity }}
                                            @else
                                                0
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

