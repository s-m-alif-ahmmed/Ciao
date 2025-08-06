<?php

namespace App\Http\Controllers\Web\Backend;

use App\Models\Order;
use App\Http\Controllers\Controller;
use App\Helpers\Helper;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;
use Exception;

class OrderController
{
    /**
     * Display a listing of dynamic page content.
     *
     * @param Request $request
     * @return View|JsonResponse
     * @throws Exception
     */
    public function index(Request $request): View | JsonResponse
    {
        if ($request->ajax()) {
            $data = Order::where('status','completed')->whereNotNull('valet_id')->latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('name', function ($data) {
                    return $data->user->name ?? 'N/A';
                })
                ->addColumn('valet_email', function ($data) {
                    return $data->valet->email ?? 'N/A';
                })
                ->addColumn('shop_name', function ($data) {
                    return $data->shop->name ?? 'N/A';
                })
                ->addColumn('order_number', function ($data) {
                    return $data->order_number;
                })
                ->addColumn('payment_method', function ($data) {
                    return $data->payment_method;
                })
                ->addColumn('payment_id', function ($data) {
                    return $data->payment_id;
                })
                ->addColumn('tax', function ($data) {
                    return $data->tax;
                })
                ->addColumn('valet_charge', function ($data) {
                    return $data->valet_charge;
                })
                ->addColumn('valet_charge_extra', function ($data) {
                    return $data->valet_charge_extra;
                })
                ->addColumn('platform_fee', function ($data) {
                    return $data->platform_fee;
                })
                ->addColumn('sub_total', function ($data) {
                    return $data->sub_total;
                })
                ->addColumn('total_price', function ($data) {
                    return $data->total_price;
                })
                ->addColumn('status', function ($data) {
                    $statusOptions = [
                        'pending' => 'Pending',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ];

                    $status = '<select class="form-select status-dropdown"
                                       data-id="' . $data->id . '" disabled
                                       onchange="showStatusChangeAlert(' . $data->id . ', this.value)"
                                       style="width: 90px;">';

                    foreach ($statusOptions as $key => $value) {
                        $selected = $data->status == $key ? 'selected' : '';
                        $status .= '<option value="' . $key . '" ' . $selected . '>' . $value . '</option>';
                    }

                    $status .= '</select>';

                    return $status;
                })


                ->addColumn('action', function ($data) {
                    $view = route('orders.show', $data->id);
                    return '<a href="' . $view . '" class="btn btn-info btn-sm"><i class="fe fe-eye"></i></a>';
                })
                ->rawColumns(['name', 'valet_email', 'shop_name', 'order_number', 'payment_method', 'payment_id', 'tax', 'valet_charge', 'valet_charge_extra', 'platform_fee', 'sub_total', 'total_price', 'status', 'action'])
                ->make();
        }
        return view('backend.layouts.orders.index');
    }
    public function indexPending(Request $request): View | JsonResponse
    {
        if ($request->ajax()) {
            $data = Order::where('status', 'pending')->whereNotNull('valet_id')->latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('name', function ($data) {
                    return $data->user->name ?? 'N/A';
                })
                ->addColumn('valet_email', function ($data) {
                    return $data->valet->email ?? 'N/A';
                })
                ->addColumn('shop_name', function ($data) {
                    return $data->shop->name ?? 'N/A';
                })
                ->addColumn('order_number', function ($data) {
                    return $data->order_number;
                })
                ->addColumn('payment_method', function ($data) {
                    return $data->payment_method;
                })
                ->addColumn('payment_id', function ($data) {
                    return $data->payment_id;
                })
                ->addColumn('tax', function ($data) {
                    return $data->tax;
                })
                ->addColumn('valet_charge', function ($data) {
                    return $data->valet_charge;
                })
                ->addColumn('valet_charge_extra', function ($data) {
                    return $data->valet_charge_extra;
                })
                ->addColumn('platform_fee', function ($data) {
                    return $data->platform_fee;
                })
                ->addColumn('sub_total', function ($data) {
                    return $data->sub_total;
                })
                ->addColumn('total_price', function ($data) {
                    return $data->total_price;
                })
                ->addColumn('status', function ($data) {
                    $statusOptions = [
                        'pending' => 'Pending',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ];

                    $status = '<select class="form-select status-dropdown"
                                       data-id="' . $data->id . '" disabled
                                       onchange="showStatusChangeAlert(' . $data->id . ', this.value)"
                                       style="width: 90px;">';

                    foreach ($statusOptions as $key => $value) {
                        $selected = $data->status == $key ? 'selected' : '';
                        $status .= '<option value="' . $key . '" ' . $selected . '>' . $value . '</option>';
                    }

                    $status .= '</select>';

                    return $status;
                })


                ->addColumn('action', function ($data) {
                    $view = route('orders.show', $data->id);
                    return '<a href="' . $view . '" class="btn btn-info btn-sm"><i class="fe fe-eye"></i></a>';
                })
                ->rawColumns(['name', 'valet_email', 'shop_name', 'order_number', 'payment_method', 'payment_id', 'tax', 'valet_charge', 'valet_charge_extra', 'platform_fee', 'sub_total', 'total_price', 'status', 'action'])
                ->make();
        }
        return view('backend.layouts.orders.pending-order');
    }
    public function indexValetPending(Request $request): View | JsonResponse
    {
        if ($request->ajax()) {
            $data = Order::where('status', 'pending')->where('valet_id', null)->latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('name', function ($data) {
                    return $data->user->name ?? 'N/A';
                })
                ->addColumn('shop_name', function ($data) {
                    return $data->shop->name ?? 'N/A';
                })
                ->addColumn('order_number', function ($data) {
                    return $data->order_number;
                })
                ->addColumn('payment_method', function ($data) {
                    return $data->payment_method;
                })
                ->addColumn('payment_id', function ($data) {
                    return $data->payment_id;
                })
                ->addColumn('tax', function ($data) {
                    return $data->tax;
                })
                ->addColumn('valet_charge', function ($data) {
                    return $data->valet_charge;
                })
                ->addColumn('valet_charge_extra', function ($data) {
                    return $data->valet_charge_extra;
                })
                ->addColumn('platform_fee', function ($data) {
                    return $data->platform_fee;
                })
                ->addColumn('sub_total', function ($data) {
                    return $data->sub_total;
                })
                ->addColumn('total_price', function ($data) {
                    return $data->total_price;
                })
                ->addColumn('status', function ($data) {
                    $statusOptions = [
                        'pending' => 'Pending',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ];

                    $status = '<select class="form-select status-dropdown"
                                       data-id="' . $data->id . '" disabled
                                       onchange="showStatusChangeAlert(' . $data->id . ', this.value)"
                                       style="width: 90px;">';

                    foreach ($statusOptions as $key => $value) {
                        $selected = $data->status == $key ? 'selected' : '';
                        $status .= '<option value="' . $key . '" ' . $selected . '>' . $value . '</option>';
                    }

                    $status .= '</select>';

                    return $status;
                })


                ->addColumn('action', function ($data) {
                    $view = route('orders.show', $data->id);
                    return '<a href="' . $view . '" class="btn btn-info btn-sm"><i class="fe fe-eye"></i></a>';
                })
                ->rawColumns(['name', 'shop_name', 'order_number', 'payment_method', 'payment_id', 'tax', 'valet_charge', 'valet_charge_extra', 'platform_fee', 'sub_total', 'total_price', 'status', 'action'])
                ->make();
        }
        return view('backend.layouts.orders.valet-pending-order');
    }


    public function show($id): View
    {
        $order = Order::with('orderDetails')->findOrFail($id);
        return view('backend.layouts.orders.show', compact('order'));
    }


    public function changeStatus(Request $request, $id)
    {
        // dd($request->all());
        try {
            $status = $request->input('status');

            // Validate the status
            if (!in_array($status, ['pending','completed', 'cancelled'])) {
                throw new Exception('Invalid status');
            }

            // Update the status in the database
            $Order = Order::find($id);
            if ($Order) {
                $Order->status = $status;
                $Order->save();

                return response()->json(['success' => true, 'message' => 'Status updated successfully']);
            }

            throw new Exception('Record not found');
        } catch (Exception $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()]);
        }
    }
}
