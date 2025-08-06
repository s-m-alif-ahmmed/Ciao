<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Web\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Shop;
use App\Models\SubCategory;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the dashboard page.
     *
     * @return View
     */

    public function index(): View
    {
        $user = Auth::user();

        if (Auth::check() && $user->role == 'admin') {

            $total_users = User::where('role', 'user')->count();
            $total_valets = User::where('role', 'valet')
                ->whereNull('deleted_at')
                ->whereHas('valetProfile', function ($query) {
                    $query->where('status', 'Approved'); // Only get users with valetProfile status 'Pending'
                })
                ->with('valetProfile') // Eager load valetProfile after filtering
                ->count();

            $total_pending_valets = User::where('role', 'valet')
                    ->whereNull('deleted_at')
                    ->whereHas('valetProfile', function ($query) {
                        $query->where('status', 'Pending'); // Only get users with valetProfile status 'Pending'
                    })
                    ->with('valetProfile') // Eager load valetProfile after filtering
                    ->count();

            $shops = Shop::where('status', 'Active')->count();
            $categories = Category::where('status', 'Active')->count();
            $subCategories = SubCategory::where('status', 'Active')->count();
            $products = Product::where('status', 'Active')->count();
            $orders = Order::where('status', 'completed')->where('payment_status', 'paid')->count();
            $pending_orders = Order::where('status', 'pending')->where('payment_status', 'paid')->count();

            return view('backend.layouts.dashboard.index',compact(
                'total_users',
                'total_pending_valets',
                'total_valets',
                'shops',
                'categories',
                'subCategories',
                'products',
                'orders',
                'pending_orders',
            ));
        } elseif (Auth::check() && in_array($user->role, [ 'user', 'valet' ])) {
            return redirect()->route('user.delete');
        } else {
            return redirect()->back();
        }
    }

}
