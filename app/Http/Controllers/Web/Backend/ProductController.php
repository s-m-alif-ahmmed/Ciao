<?php

namespace App\Http\Controllers\Web\Backend;

use App\Helpers\Helper;
use App\Http\Controllers\Web\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Shop;
use App\Models\SubCategory;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;

class ProductController extends Controller
{
    /**
     * Display a listing of all users.
     *
     * @param Request $request
     * @return JsonResponse|View
     */
    public function index(Request $request, $id): JsonResponse|View
    {
        $category = Category::find($id);
        $shops = Shop::all();
        if ($request->ajax()) {
            // Eager load the relationships: category, subcategory, shop, images
            $data = Product::with(['category', 'subcategory', 'shop', 'images'])
                ->where('category_id', $category->id)
                ->whereHas('shop') // Ensures only products with a shop are included
                ->orderBy(function ($query) {
                    $query->select('name')
                        ->from('shops')
                        ->whereColumn('shops.id', 'products.shop_id')
                        ->limit(1);
                })
                ->latest()
                ->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('select', function ($data) {
                    // Add a checkbox for selecting the product
                    return '<input type="checkbox" class="product-select" value="' . $data->id . '">';
                })
                ->addColumn('product_name', function ($data) {
                    return $data->name . ' (' . ($data->quantity ?? 'N/A') . ')';
                })
                ->addColumn('image', function ($data) {
                    $defaultImage = asset('frontend/default-avatar-profile.jpg');
                    if ($data->thumbnail) {
                        $url = asset($data->thumbnail); // Assuming the first image is used for the thumbnail
                    } else {
                        $url = $defaultImage;
                    }
                    return '<img src="' . $url . '" alt="Image" width="50px" height="50px">';
                })
                ->addColumn('subcategory', function ($data) {
                    return $data->subcategory->name ?? 'N/A';
                })
                ->addColumn('shop', function ($data) {
                    $shops = \App\Models\Shop::orderBy('name', 'asc')->get(); // Fetch all shops sorted by name
                    $options = '';

                    foreach ($shops as $shop) {
                        $selected = $data->shop_id == $shop->id ? 'selected' : '';
                        $options .= '<option value="' . $shop->id . '" ' . $selected . '>' . $shop->name . ' (' . ($shop->location ?? 'N/A') . ')</option>';
                    }

                    return '<select class="form-control form-select shop-select" data-product-id="' . $data->id . '">' . $options . '</select>';
                })

                ->addColumn('stock', function ($data) {
                    return $data->stock ?? 'N/A';
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
                            <button type="button" class="btn btn-success fs-14 details-icn" title="Add Shops" data-id="' . $data->id . '" data-bs-toggle="modal" data-bs-target="#shopModel">
                                <i class="fe fe-plus"></i>
                            </button>
                            <a href="' . route('product.details', $data->id) . '" type="button" class="btn btn-info fs-14 details-icn" title="Details">
                                <i class="fe fe-eye"></i>
                            </a>

                            <a href="' . route('product.edit', $data->id) . '" type="button" class="btn btn-primary fs-14 edit-icn" title="Edit">
                                <i class="fe fe-edit"></i>
                            </a>
                            <a href="#" type="button" onclick="showDeleteConfirm(' . $data->id . ')" class="btn btn-danger fs-14 text-white delete-icn" title="Delete">
                                <i class="fe fe-trash"></i>
                            </a>
                        </div>';
                })
                ->rawColumns(['select', 'product_name', 'image', 'price', 'subcategory', 'shop', 'status', 'action'])
                ->make();
        }

        return view('backend.layouts.product.index',compact('category','shops'));
    }

    public function updateShop(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'shop_id' => 'required|exists:shops,id',
        ]);

        $product = Product::find($request->product_id);
        $product->shop_id = $request->shop_id;
        $product->save();

        return response()->json([
            'success' => true,
            'message' => 'Shop updated successfully!'
        ]);
    }

    public function bulkDelete(Request $request)
    {
        try {
            if (!$request->has('product_ids')) {
                return response()->json(['error' => 'No products selected.'], 400);
            }

            // Retrieve the products first
            $products = Product::whereIn('id', $request->product_ids)->get();

            foreach ($products as $product) {
                // Delete associated images
                if ($product->images) {
                    foreach ($product->images as $image) {
                        Helper::fileDelete(public_path($image->image_path)); // Delete image file
                        $image->delete(); // Delete image record from DB
                    }
                }

                // Delete thumbnail if exists
                if ($product->thumbnail) {
                    Helper::fileDelete(public_path($product->thumbnail)); // Delete thumbnail file
                }
            }

            // Delete the products after removing images
            $product = Product::whereIn('id', $request->product_ids)->delete();

            $product->images()->delete();
            $product->favourites()->delete();
            $product->carts()->delete();

            return response()->json(['message' => 'Selected products deleted successfully.'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete products.'], 500);
        }
    }


    public function duplicate(Request $request)
    {
        // Validation: Ensure there are products selected to duplicate
        $request->validate([
            'product_ids' => 'required|array',
            'product_ids.*' => 'exists:products,id'
        ]);

        // Begin Transaction
        DB::beginTransaction();

        try {
            foreach ($request->product_ids as $productId) {
                // Fetch the product with its related images
                $product = Product::with('images')->findOrFail($productId);

                // Duplicate the product and append 'copy' to the name
                $duplicatedProduct = $product->replicate();
                $duplicatedProduct->name = $duplicatedProduct->name;
                // Duplicate the thumbnail image using the Helper method
                if ($duplicatedProduct->thumbnail) {
                    $newThumbnailPath = Helper::duplicateImage($product->thumbnail, 'Products/Thumbnail', 'copy_' . uniqid());
                    if ($newThumbnailPath) {
                        $duplicatedProduct->thumbnail = $newThumbnailPath; // Assign the new thumbnail path
                    }
                }
                $duplicatedProduct->save();

                if ($product->images){
                    // Duplicate images associated with the product
                    foreach ($product->images as $image) {
                        // Duplicate the image using the Helper method
                        $newImagePath = Helper::duplicateImage($image->image_path, 'Products/Images', 'copy_' . uniqid());

                        if ($newImagePath) {
                            // Store the new image path in the database
                            ProductImage::create([
                                'product_id' => $duplicatedProduct->id,
                                'image_path' => $newImagePath // Save the new image path
                            ]);
                        }
                    }
                }


                // Optionally, duplicate other relationships like categories, shops, etc.
                if ($product->category) {
                    $duplicatedProduct->category()->associate($product->category);
                }
                if ($product->shop) {
                    $duplicatedProduct->shop()->associate($product->shop);
                }
                $duplicatedProduct->save();
            }

            // Commit Transaction
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Product(s) successfully duplicated.'
            ]);

        } catch (\Exception $e) {
            // Rollback Transaction in case of an error
            DB::rollBack();

            // Log the exception for debugging purposes
            \Log::error('Product duplication failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again.'
            ]);
        }
    }

    public function duplicateMultipleShops(Request $request)
    {
        // Validation: Ensure there is a product to duplicate and multiple shop IDs are provided
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'shop_id' => 'required|array',
            'shop_id.*' => 'exists:shops,id'
        ]);

        // Begin Transaction
        DB::beginTransaction();

        try {
            // Fetch the product with its related images
            $product = Product::with('images', 'category', 'shop')->findOrFail($request->product_id);

            // Loop through each shop ID to duplicate the product
            foreach ($request->shop_id as $shopId) {
                // Duplicate the product and append 'copy' to the name
                $duplicatedProduct = $product->replicate();
                $duplicatedProduct->name = $duplicatedProduct->name;

                // Duplicate the thumbnail image using the Helper method
                if ($duplicatedProduct->thumbnail) {
                    $newThumbnailPath = Helper::duplicateImage($product->thumbnail, 'Products/Thumbnail', 'copy_' . uniqid());
                    if ($newThumbnailPath) {
                        $duplicatedProduct->thumbnail = $newThumbnailPath; // Assign the new thumbnail path
                    }
                }

                // Associate the duplicated product with the new shop
                $duplicatedProduct->shop_id = $shopId;

                // Save the duplicated product
                $duplicatedProduct->save();

                // Duplicate images associated with the product
                if ($product->images) {
                    foreach ($product->images as $image) {
                        // Duplicate the image using the Helper method
                        $newImagePath = Helper::duplicateImage($image->image_path, 'Products/Images', 'copy_' . uniqid());

                        if ($newImagePath) {
                            // Store the new image path in the database
                            ProductImage::create([
                                'product_id' => $duplicatedProduct->id,
                                'image_path' => $newImagePath // Save the new image path
                            ]);
                        }
                    }
                }

                // Optionally, associate the category if the product has one
                if ($product->category) {
                    $duplicatedProduct->category()->associate($product->category);
                }

                // Save the duplicated product after associating all relationships
                $duplicatedProduct->save();
            }

            // Commit Transaction
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Product successfully duplicated for the selected shops.'
            ]);

        } catch (\Exception $e) {
            // Rollback Transaction in case of an error
            DB::rollBack();

            // Log the exception for debugging purposes
            \Log::error('Product duplication failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again.'
            ]);
        }
    }

    /* Product Create start */
    public function create(): View
    {
        $shops = Shop::where('status', 'active')->get();
        $categories = Category::where('status', 'active')->get();

        return view('backend.layouts.product.create', compact('shops', 'categories'));
    }
    /* Product Create end */

    /* Product Store start */
    public function store(Request $request)
    {
        // Validation
        $request->validate([
            'name' => 'required|string|max:255',
            'shop_id' => 'required|exists:shops,id',
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'nullable|exists:sub_categories,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'nullable|integer|min:0',
            'quantity' => 'nullable|string',
            'description' => 'nullable|string',
            'thumbnail' => 'image|mimes:jpeg,png,jpg,gif,webp,svg|max:5120',
            'images' => 'required|array|max:5', // Maximum 5 images
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp,svg|max:5120',
        ]);

        // Begin Transaction
        DB::beginTransaction();

        try {

            // Image Upload using Helper function
            $thumbnail = Helper::fileUpload($request->file('thumbnail'), 'Products/Thumbnail', time() . '_' . $request->file('thumbnail')->getClientOriginalName());

            // Product Create
            $product = Product::create([
                'name' => $request->name,
                'shop_id' => $request->shop_id,
                'category_id' => $request->category_id,
                'sub_category_id' => $request->subcategory_id,
                'price' => $request->price,
                'stock' => $request->stock ?? null,
                'quantity' => $request->quantity,
                'description' => $request->description,
                'thumbnail' => $thumbnail,
            ]);

            // Check if images are uploaded
            if ($request->hasFile('images')) {
                $images = $request->file('images');

                // Ensure that no more than 5 images are uploaded
                if (count($images) > 5) {
                    // Rollback Transaction if images are more than 5
                    DB::rollBack();
                    return back()->with('t-error', 'Max image will be 5!');
                }

                // Save images
                foreach ($images as $image) {
                    $imageName = Helper::fileUpload($image, 'Products/Images', time() . '_' . uniqid() . '.' . $image->extension());

                    // Save image path in database
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $imageName,
                    ]);
                }
            } else {
                // Rollback Transaction if no images are uploaded
                DB::rollBack();
                return back()->with('t-error', 'At least one image is required!');
            }

            // Commit the transaction if everything is successful
            DB::commit();

            return redirect()->route('product.index', ['id' => $product->category_id])->with('t-success', 'Product created successfully!');
        } catch (\Exception $e) {
            // Rollback Transaction in case of any exception
            DB::rollBack();
            return back()->with('t-error', 'Something went wrong, please try again!');
        }
    }

    /* Product Store end */

    /*   Get subcategories via ajax start */
    public function getSubcategories(Request $request)
    {
        $subcategories = SubCategory::where('category_id', $request->category_id)
            ->where('status', 'active')
            ->get();

        return response()->json($subcategories);
    }
    /* Get subcategories via ajax end */

    /* Product Edit start */
    public function edit($id): View
    {
        $product = Product::findOrFail($id);
        $shops = Shop::where('status', 'active')->get();
        $categories = Category::where('status', 'active')->get();
        $subcategories = SubCategory::where('category_id', $product->category_id)->get();
        $productImages = $product->images; // Product images

        return view('backend.layouts.product.edit', compact('product', 'shops', 'categories', 'subcategories', 'productImages'));
    }
    /* Product Edit end */

    /* Product Update start */
    public function update(Request $request, $id)
    {
        // Validation
        $request->validate([
            'name' => 'required|string|max:255',
            'shop_id' => 'required|exists:shops,id',
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'nullable|exists:sub_categories,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'nullable|integer|min:0',
            'quantity' => 'nullable|string',
            'description' => 'nullable|string',
            'thumbnail' => 'image|mimes:jpeg,png,jpg,gif,webp,svg|max:5120',
            'images' => 'nullable|array|max:5', // Maximum 5 images
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp,svg|max:10240',
            'remove_images' => 'nullable|array', // Images to remove
            'remove_images.*' => 'exists:product_images,id',
        ]);

        // Find Product
        $product = Product::findOrFail($id);

        // Begin Transaction
        DB::beginTransaction();

        try {

            // Image Upload using Helper function
            if ($request->hasFile('thumbnail')) {
                if ($product->thumbnail) {
                    Helper::fileDelete($product->thumbnail); // Delete previous image
                }
                $thumbnail = Helper::fileUpload($request->file('thumbnail'), 'Products/Thumbnail', time() . '_' . $request->file('thumbnail')->getClientOriginalName());
                $product->thumbnail = $thumbnail; // Save Image Path
            }

            // Update Product Data
            $product->update([
                'name' => $request->name,
                'shop_id' => $request->shop_id,
                'category_id' => $request->category_id,
                'sub_category_id' => $request->subcategory_id,
                'price' => $request->price,
                'stock' => $request->stock ?? null,
                'quantity' => $request->quantity,
                'description' => $request->description,
                'thumbnail' => $product->thumbnail,
            ]);

            // **Handle Image Deletion**
            if ($request->remove_images) {
                foreach ($request->remove_images as $imageId) {
                    $image = ProductImage::find($imageId);
                    if ($image) {
                        Helper::fileDelete($image->image_path); // Delete file from storage
                        $image->delete(); // Remove from database
                    }
                }
            }

            // **Handle Adding New Images**
            $existingImagesCount = $product->images()->count();
            $newImages = $request->file('images', []);
            if ($existingImagesCount + count($newImages) > 5) {
                DB::rollBack();
                return back()->with('t-error', 'Total images cannot exceed 5!');
            }

            foreach ($newImages as $image) {
                $imageName = Helper::fileUpload($image, 'Products/Images', time() . '_' . uniqid() . '.' . $image->extension());

                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $imageName,
                ]);
            }

            // **Ensure at least one image remains**
            if ($product->images()->count() == 0) {
                DB::rollBack();
                return back()->with('t-error', 'At least one image is required!');
            }

            // Commit the transaction if everything is successful
            DB::commit();

            return redirect()->route('product.index', ['id' => $product->category_id])->with('t-success', 'Product updated successfully!');
        } catch (\Exception $e) {
            // Rollback Transaction in case of any exception
            DB::rollBack();
            return back()->with('t-error', 'Something went wrong, please try again!');
        }
    }
    /* Product Update end */

    /**
     * Change the status of the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */

    /* Product Status start */
    public function status(int $id): JsonResponse
    {
        $data = Product::findOrFail($id);
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
    /* Product Status end */

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */

    /* Product Delete start */
    public function destroy(int $id): JsonResponse
    {
        // Fetch the product with its images
        $product = Product::with('images')->findOrFail($id);

        if ($product->images){
            // Delete the product images from the public folder
            foreach ($product->images as $image) {
                Helper::fileDelete(public_path($image->image_path)); // Delete each associated image
            }
        }
        if ($product->thumbnail){
            // Delete the product thumbnail
            Helper::fileDelete(public_path($product->thumbnail)); // Delete the product's thumbnail

        }

        $product->delete();
        $product->images()->delete();
        $product->favourites()->delete();
        $product->carts()->delete();

        return response()->json([
            't-success' => true,
            'message'   => 'Deleted successfully.',
        ]);
    }
    /* Product Delete end */

    /* Product Details start */
    public function details(int $id): View
    {
        $product = Product::with(['shop', 'category', 'subcategory', 'images'])
            ->where('status', 'active')
            ->findOrFail($id);
        return view('backend.layouts.product.details', compact('product'));
    }

    /* Product Details end */
}
