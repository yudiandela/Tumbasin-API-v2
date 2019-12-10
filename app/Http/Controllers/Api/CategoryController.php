<?php

namespace App\Http\Controllers\Api;

use App\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;

class CategoryController extends Controller
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
        $categories = Category::latest();

        $search = Str::slug($request->search);

        if ($search) {
            $categories->where('slug', 'LIKE', '%' . $search . '%');
        }

        $categories = $categories->paginate();

        return CategoryResource::collection($categories);
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
            'title' => ['required', 'string', 'max:255', 'unique:categories']
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

        $category = Category::create([
            'title'       => $request->title,
            'slug'        => Str::slug($request->title),
            'description' => $request->description,
            'image'       => $image
        ]);

        return new CategoryResource($category);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        return new CategoryResource($category);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255', 'unique:categories,title,' . $category->id]
        ]);

        $image = $category->image;

        if ($request->image !== null) {
            $image = uploadFile('image', $request);

            if (!isValidLink($image)) {
                return response()->json([
                    'error' => 'Image Fail'
                ], 400);
            }
        }

        $category->update([
            'title'       => $request->title,
            'slug'        => Str::slug($request->title),
            'description' => $request->description,
            'image'       => $image
        ]);

        return new CategoryResource($category);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        $category->delete();
        return response()->json([
            'message' => 'Data was deleted!'
        ], 200);
    }
}
