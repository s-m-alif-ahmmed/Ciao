<?php

namespace App\Http\Controllers\Web\Backend;

use App\Helpers\Helper;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use PHPUnit\TextUI\Help;
use Yajra\DataTables\DataTables;

class CategoryController
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
            $data = Category::whereNull('deleted_at')
                ->latest()
                ->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('name', function ($data) {
                    return $data->name ?? '';
                })
                ->addColumn('image', function ($data) {
                    $defaultImage = asset('frontend/no-image.jpg');
                    $url = $data->image ? asset($data->image) : $defaultImage;
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
                                <a href="' . route('category.edit', $data->id) . '" type="button" class="btn btn-primary fs-14 edit-icn" title="Edit">
                                    <i class="fe fe-edit"></i>
                                </a>
                                <a href="#" type="button" onclick="showDeleteConfirm(' . $data->id . ')" class="btn btn-danger fs-14 text-white delete-icn" title="Delete">
                                    <i class="fe fe-trash"></i>
                                </a>
                            </div>';
                })
                ->rawColumns(['name', 'image','status', 'action'])
                ->make();
        }

        return view('backend.layouts.category.index');
    }

    public function create()
    {
        return view('backend.layouts.category.create');
    }

    public function store(Request $request)
    {
        try {
            // Validate Request
            $request->validate([
                'name' => 'required|string|max:255',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:5120' // Max 5MB
            ]);

            // Image Upload using Helper function
            $imagePath = Helper::fileUpload($request->file('image'), 'Category', time() . '_' . $request->file('image')->getClientOriginalName());

            // Create Shop
            $shop = new Category();
            $shop->name = $request->name;
            $shop->image = $imagePath; // Save Image Path
            $shop->save();

            return redirect()->route('category.index')->with('t-success', 'Category created successfully!');
        } catch (\Exception $e) {
            // Log::error($e->getMessage());
            return redirect()->back()->with('t-error', 'Something went to wrong!');
        }
    }
    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('backend.layouts.category.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        try {
            // Find Category by ID
            $category = Category::findOrFail($id);

            // Validate Request (Including Optional Image)
            $request->validate([
                'name' => 'required|string|max:255',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            ]);

            // Image Upload using Helper function
            if ($request->hasFile('image')) {
                if ($category->image) {
                    Helper::fileDelete($category->image); // Delete previous image
                }
                $imagePath = Helper::fileUpload($request->file('image'), 'Category', time() . '_' . $request->file('image')->getClientOriginalName());
                $category->image = $imagePath; // Save Image Path
            }

            // Update category
            $category->update([
                'name' => $request->name,
                'image' => $category->image, // Keep old image if new not uploaded
            ]);

            return redirect()->route('category.index')->with('t-success', 'Category updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('t-error', 'Something went to wrong!');
        }
    }


      /* Category Status start */
      public function status(int $id): JsonResponse
      {
          $data = Category::findOrFail($id);
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
      /* Category Status end */

      /**
       * Remove the specified resource from storage.
       *
       * @param int $id
       * @return JsonResponse
       */

      /* Category Delete start */
      public function destroy(int $id): JsonResponse
      {
          $Category = Category::findOrFail($id);
          $Category->delete();
          return response()->json([
              't-success' => true,
              'message'   => 'Deleted successfully.',
          ]);
      }
      /* Category Delete end */
}
