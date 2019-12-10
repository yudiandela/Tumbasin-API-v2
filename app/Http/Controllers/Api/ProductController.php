<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('role:admin', ['except' => ['index', 'show']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $products = Product::latest();

        $search = Str::slug($request->search);

        if ($search) {
            $products->where('slug', 'LIKE', '%' . $search . '%');
        }

        $products = $products->paginate();

        return ProductResource::collection($products);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'category_id' => ['required', 'numeric'],
            'name'        => ['required', 'string', 'max:255', 'unique:products'],
            'description' => ['required', 'string'],
            'unit'        => ['required', 'string', 'max:50'],
            'count'       => ['required', 'numeric'],
            'price'       => ['required', 'numeric'],
        ]);

        $image = 'https://dummyimage.com/76x76/8a8a8a/000000&text=No+Image';

        if ($request->image !== null) {
            $image = uploadFile('image', $request);

            if (!isValidLink($image)) {
                return response()->json([
                    'error' => 'Image Fail'
                ], 400);
            }
        }

        $product = Product::create([
            'category_id' => $request->category_id,
            'name'        => $request->name,
            'slug'        => Str::slug($request->name),
            'image'       => $image,
            'description' => $request->description,
            'unit'        => $request->unit,
            'count'       => $request->count,
            'price'       => $request->price,
        ]);

        return new ProductResource($product);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return new ProductResource($product);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'category_id' => ['required', 'numeric'],
            'name'        => ['required', 'string', 'max:255', 'unique:products,name,' . $product->id],
            'description' => ['required', 'string'],
            'unit'        => ['required', 'string', 'max:50'],
            'count'       => ['required', 'numeric'],
            'price'       => ['required', 'numeric'],
        ]);

        $image = $product->image;

        if ($request->image !== null) {
            $image = uploadFile('image', $request);

            if (!isValidLink($image)) {
                return response()->json([
                    'error' => 'Image Fail'
                ], 400);
            }
        }

        $product->update([
            'category_id' => $request->category_id,
            'name'        => $request->name,
            'slug'        => Str::slug($request->name),
            'image'       => $image,
            'description' => $request->description,
            'unit'        => $request->unit,
            'count'       => $request->count,
            'price'       => $request->price,
        ]);

        return new ProductResource($product);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json([
            'message' => 'Data was deleted!'
        ], 200);
    }
}
