<?php

namespace App\Http\Controllers\Web\Backend;

use App\Helpers\Helper;
use App\Models\Category;
use Illuminate\Validation\Rule;
use App\Models\Shop;
use App\Models\SubCategory;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;

class SubCategoryCreateController
{
    /**
     * Display a listing of all users.
     *
     * @param Request $request
     * @return JsonResponse|View
     */
    /* category list start */
    public function index(Request $request): JsonResponse|View
    {
        if ($request->ajax()) {
            // Eager load shops with their respective subcategories and category information
            $data = SubCategory::whereNull('deleted_at')
                ->latest()
                ->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('category_name', function ($data) {
                    return $data->category->name ?? 'N/A'; // Show shop name
                })
                ->addColumn('name', function ($data) {
                    return $data->name ?? 'N/A'; // Show shop name
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
                                <a href="' . route('subcategory.edit', $data->id) . '" type="button" class="btn btn-primary fs-14 edit-icn" title="Edit">
                                    <i class="fe fe-edit"></i>
                                </a>
                                <a href="#" type="button" onclick="showDeleteConfirm(' . $data->id . ')" class="btn btn-danger fs-14 text-white delete-icn" title="Delete">
                                    <i class="fe fe-trash"></i>
                                </a>
                            </div>';
                })
                ->rawColumns(['shop_name', 'category_name', 'name', 'image', 'status', 'action'])
                ->make();
        }

        return view('backend.layouts.subcategory.index');
    }
    /* category list end */

    /* subcategory create start */
    public function create(): View
    {
        $categories = Category::all();
        return view('backend.layouts.subcategory.create', compact('categories'));
    }
    /* subcategory create end */

    /* SubCategory Store start */
    public function store(Request $request)
    {
        try {
            // Validation
            $request->validate([
                'category_id' => 'required|exists:categories,id',
                'name' => 'required|string|max:255|unique:sub_categories,name,',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            // Image Upload using Helper function
            $imagePath = Helper::fileUpload($request->file('image'), 'SubCategory', time() . '_' . $request->file('image')->getClientOriginalName());

            // Create the new subcategory
            SubCategory::create([
                'category_id' => $request->category_id,
                'name' => $request->name,
                'image' => $imagePath,
            ]);

            // Success message
            return redirect()->route('subcategory.index')->with('t-success', 'Sub Category created successfully!');
        } catch (QueryException $e) {
            // Error message
            return back()->with('t-error', 'Failed to create subcategory. Please try again!')->withInput();
        }
    }


    public function edit($id)
    {
        $data = SubCategory::findOrFail($id);
        $categories = Category::all();
        return view('backend.layouts.subcategory.edit', compact('categories', 'data'));
    }

    public function update(Request $request, $id)
    {
        try {
            // Find Category by ID
            $category = SubCategory::findOrFail($id);

            // Validate Request (Including Optional Image)
            $request->validate([
                'category_id' => 'required|exists:categories,id',
                'name' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('sub_categories', 'name')->ignore($id),
                ],
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            // Image Upload using Helper function
            if ($request->hasFile('image')) {
                if ($category->image) {
                    Helper::fileDelete($category->image); // Delete previous image
                }
                $imagePath = Helper::fileUpload($request->file('image'), 'SubCategory', time() . '_' . $request->file('image')->getClientOriginalName());
                $category->image = $imagePath; // Save Image Path
            }

            // Update category
            $category->update([
                'category_id' => $request->category_id,
                'name' => $request->name,
                'image' => $category->image, // Keep old image if new not uploaded
            ]);

            return redirect()->route('subcategory.index')->with('t-success', 'Sub Category updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('t-error', 'Something went to wrong!');
        }
    }

    /* Category Status start */
    public function status(int $id): JsonResponse
    {
        $data = SubCategory::findOrFail($id);
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

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    /* SubCategory remove start*/
    public function destroy($id)
    {
        $subcategory = SubCategory::find($id);

        if (!$subcategory) {
            return response()->json(['t-error' => 'Sub Category not found'], 404);
        }

        $subcategory->delete();

        return response()->json(['t-success' => 'Sub Category deleted successfully']);
    }


    /* SubCategory remove end*/
}
