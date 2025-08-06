<?php

namespace App\Http\Controllers\Web\Backend;

use App\Helpers\Helper;
use App\Models\CMS;
use App\Models\Shop;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use PHPUnit\TextUI\Help;
use Yajra\DataTables\DataTables;

class CmsController
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
            $data = CMS::whereNull('deleted_at')
                ->latest()
                ->get();

            return DataTables::of($data)
                ->addIndexColumn()

                ->addColumn('shop_name', function ($data) {
                    $shop_name = $data->shop->name ?? 'N/A';
                    return $shop_name;
                })
                ->addColumn('banner_image', function ($data) {
                    $defaultImage = asset('frontend/no-image.jpg');
                    $url = $data->banner_image ? asset($data->banner_image) : $defaultImage;
                    return '<img src="' . $url . '" alt="Image" width="50px" height="50px">';
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
                                <a href="' . route('banner.edit', $data->id) . '" type="button" class="btn btn-primary fs-14 edit-icn" title="Edit">
                                    <i class="fe fe-edit"></i>
                                </a>
                                <a href="#" type="button" onclick="showDeleteConfirm(' . $data->id . ')" class="btn btn-danger fs-14 text-white delete-icn" title="Delete">
                                    <i class="fe fe-trash"></i>
                                </a>
                            </div>';
                })
                ->rawColumns(['shop_name', 'banner_image', 'status', 'action'])
                ->make();
        }

        return view('backend.layouts.banner.index');
    }

    public function create()
    {
        $shops = Shop::all();
        return view('backend.layouts.banner.create', compact('shops'));
    }


    public function store(Request $request)
    {
        try {
            // Validate Request
            $request->validate([
                'shop_id' => 'required',
                'banner_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:5120' // Max 5MB
            ]);

            // Image Upload using Helper function
            $imagePath = Helper::fileUpload($request->file('banner_image'), 'Banner', time() . '_' . $request->file('banner_image')->getClientOriginalName());

            // Create Banner
            $banner = new CMS();
            $banner->shop_id = $request->shop_id;
            $banner->banner_image = $imagePath; // Save Image Path
            $banner->save();

            return redirect()->route('banner.index')->with('t-success', 'Banner created successfully!');
        } catch (\Exception $e) {
            // dd($e->getMessage());
            return redirect()->back()->with('t-error', 'Something went to wrong!');
        }
    }



    public function edit($id)
    {
        $banner = CMS::findOrFail($id);
        $shops = Shop::all();

        return view('backend.layouts.banner.edit', compact('banner', 'shops'));
    }

    public function update(Request $request, $id)
    {
        try {
            // Validate Request
            $request->validate([
                'shop_id' => 'required',
                'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5120' // Image optional
            ]);

            $banner = CMS::findOrFail($id);

            $banner->shop_id = $request->shop_id ?? $banner->shop_id;

            // Image Upload (optional)
            if ($request->hasFile('banner_image')) {
                // delete old image
                if (file_exists($banner->banner_image)) {
                    Helper::fileDelete($banner->banner_image);
                }

                // new image upload
                $imagePath = Helper::fileUpload($request->file('banner_image'), 'Banner', time() . '_' . $request->file('banner_image')->getClientOriginalName());
                $banner->banner_image = $imagePath;
            }

            $banner->save();

            return redirect()->route('banner.index')->with('t-success', 'Banner updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('t-error', 'Something went wrong!');
        }
    }


    /* CMS Status start */
    public function status(int $id): JsonResponse
    {
        $data = CMS::findOrFail($id);
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
    /* CMS Status end */

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */

    /* CMS Delete start */
    public function destroy(int $id): JsonResponse
    {
        $CMS = CMS::findOrFail($id);
        $CMS->delete();
        return response()->json([
            't-success' => true,
            'message'   => 'Deleted successfully.',
        ]);
    }
    /* CMS Delete end */
}
