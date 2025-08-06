<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Web\Controller;
use App\Helpers\Helper;
use App\Models\Shop;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use PHPUnit\TextUI\Help;
use Yajra\DataTables\DataTables;

class ShopController
{
    /**
     * Display a listing of all users.
     *
     * @param Request $request
     * @return JsonResponse|View
     */
    public function index(Request $request): JsonResponse|View
    {
        if ($request->ajax()) {
            $data = Shop::whereNull('deleted_at')
                ->latest()
                ->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('name', function ($data) {
                    return $data->name ?? '';
                })
                ->addColumn('stall_number', function ($data) {
                    return $data->stall_number ?? 'N/A';
                })
                ->addColumn('image', function ($data) {
                    $defaultImage = asset('frontend/no-image.jpg');
                    if ($data->images->count()) {
                        $url = asset($data->images->first()->image); // Assuming the first image is used for the thumbnail
                    } else {
                        $url = $defaultImage;
                    }
                    return '<img src="' . $url . '" alt="Image" width="50px" height="50px">';
                })
                ->addColumn('latitude', function ($data) {
                    return $data->latitude ?? 'N/A';
                })
                ->addColumn('longitude', function ($data) {
                    return $data->longitude ?? 'N/A';
                })

                ->addColumn('status', function ($data) {
                    $backgroundColor = $data->status == "active" ? '#4CAF50' : '#ccc';
                    $sliderTranslateX = $data->status == "active" ? '26px' : '2px';
                    $sliderStyles = "position: absolute; top: 2px; left: 2px; width: 20px; height: 20px; background-color: white; border-radius: 50%; transition: transform 0.3s ease; transform: translateX($sliderTranslateX);";

                    $status = '<div class="form-check form-switch" style="margin-left:40px; position: relative; width: 50px; height: 24px; background-color: ' . $backgroundColor . '; border-radius: 12px; transition: background-color 0.3s ease; cursor: pointer;">';
                    $status .= '<input onclick="showStatusChangeAlert(' . $data->id . ')" type="checkbox" class="form-check-input" id="customSwitch' . $data->id . '" getAreaid="' . $data->id . '" name="status" style="position: absolute; width: 100%; height: 100%; opacity: 0; z-index: 2; cursor: pointer;">';
                    $status .= '<span style="' . $sliderStyles . '"></span>';
                    $status .= '<label for="customSwitch' . $data->id . '" class="form-check-label" style="margin-left: 10px;"></label>';
                    $status .= '</div>';

                    return $status;
                })
                ->addColumn('action', function ($data) {
                    return '<div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                                <a href="' . route('shop.edit', $data->id) . '" type="button" class="btn btn-primary fs-14 edit-icn" title="Edit">
                                    <i class="fe fe-edit"></i>
                                </a>
                                <a href="#" type="button" onclick="showDeleteConfirm(' . $data->id . ')" class="btn btn-danger fs-14 text-white delete-icn" title="Delete">
                                    <i class="fe fe-trash"></i>
                                </a>
                            </div>';
                })
                ->rawColumns(['name','stall_number', 'image', 'latitude', 'longitude', 'status', 'action'])
                ->make();
        }

        return view('backend.layouts.shop.index');
    }

    public function create()
    {
        return view('backend.layouts.shop.create');
    }


    public function store(Request $request)
    {
        try {
            // Validate Request
            $request->validate([
                'name' => 'required|string|max:255',
                'stall_number' => 'required|string|max:255',
                'latitude' => 'required|string',
                'longitude' => 'required|string',
                'location' => 'required|string|max:255',
                'images' => 'required|array|max:5',
                'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:5120' // Individual image validation
            ]);

            // Create Shop
            $shop = new Shop();
            $shop->name = $request->name;
            $shop->stall_number = $request->stall_number;
            $shop->latitude = $request->latitude;
            $shop->longitude = $request->longitude;
            $shop->location = $request->location;
            $shop->save(); // Save shop before adding images

            // Upload and store images (if provided)
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $imagePath = Helper::fileUpload($image, 'Shop', time() . '_' . $image->getClientOriginalName());

                    // Save image to related table
                    $shop->images()->create([
                        'image' => $imagePath,
                    ]);
                }
            }

            return redirect()->route('shop.index')->with('t-success', 'Shop created with images successfully!');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('t-error', 'Something went wrong!');
        }
    }


    public function edit($id)
    {
        $shop = Shop::findOrFail($id);
        $shopImages = $shop->images;
        return view('backend.layouts.shop.edit', compact('shop', 'shopImages'));
    }


    public function update(Request $request, $id)
    {
        try {
            // Find Shop by ID
            $shop = Shop::findOrFail($id);

            // Validate Request (Including Optional Images)
            $request->validate([
                'name' => 'nullable|string|max:255',
                'stall_number' => 'nullable|string|max:255',
                'latitude' => 'nullable|string',
                'longitude' => 'nullable|string',
                'location' => 'nullable|string|max:255',
                'images' => 'nullable|array|max:5', // Maximum 5 images
                'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:5120', // Each image validation
            ]);

            // Update Shop basic details
            $shop->name = $request->name;
            $shop->stall_number = $request->stall_number;
            $shop->latitude = $request->latitude;
            $shop->longitude = $request->longitude;
            $shop->location = $request->location;

            // If new images are uploaded, handle them
            if ($request->hasFile('images')) {
                // Delete old images using Helper::fileDelete
                foreach ($shop->images as $image) {
                    Helper::fileDelete($image->image); // Delete each previous image
                }

                // Delete all old images from shop_images table
                $shop->images()->delete(); // Delete all previous images from database

                // Upload and store new images
                foreach ($request->file('images') as $image) {
                    $imagePath = Helper::fileUpload($image, 'Shop', time() . '_' . $image->getClientOriginalName());
                    $shop->images()->create([
                        'image' => $imagePath,
                    ]);
                }
            }

            // Save the updated shop details
            $shop->save();

            return redirect()->route('shop.index')->with('t-success', 'Shop updated with images successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('t-error', 'Something went wrong!');
        }
    }


    /* Shop Status start */
    public function status(int $id): JsonResponse
    {
        $data = Shop::findOrFail($id);
        if ($data->status == 'active') {
            $data->status = 'inactive';
            $data->save();

            return response()->json([
                'success' => false,
                'message' => 'Unpublished Successfully.',
                'data'    => $data,
            ]);
        } else {
            $data->status = 'active';
            $data->save();

            return response()->json([
                'success' => true,
                'message' => 'Published Successfully.',
                'data'    => $data,
            ]);
        }
    }
    /* Shop Status end */

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */

    /* shop Delete start */
    public function destroy(int $id): JsonResponse
    {
        $Shop = Shop::findOrFail($id);
        $Shop->delete();
        return response()->json([
            't-success' => true,
            'message'   => 'Deleted successfully.',
        ]);
    }
    /* shop Delete end */
}
