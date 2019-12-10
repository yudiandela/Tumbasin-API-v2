<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MarketResource;
use App\Market;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MarketController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin', ['except' => ['index', 'show']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $markets = Market::latest()->paginate();
        return MarketResource::collection($markets);
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
            'name' => ['required', 'string', 'max:255', 'unique:markets']
        ]);

        $image = 'https://dummyimage.com/600x400/8a8a8a/000000&text=No+Image+Available';

        if ($request->image !== null) {
            $image = uploadFile('image', $request);

            if (!isValidLink($image)) {
                return response()->json([
                    'error' => 'Image Fail'
                ], 400);
            }
        }

        $market = Market::create([
            'name'  => $request->name,
            'slug'  => Str::slug($request->name),
            'image' => $image
        ]);

        return new MarketResource($market);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Market  $market
     * @return \Illuminate\Http\Response
     */
    public function show(Market $market)
    {
        return new MarketResource($market);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Market  $market
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Market $market)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:markets,name,' . $market->id]
        ]);

        $image = $market->image;

        if ($request->image !== null) {
            $image = uploadFile('image', $request);

            if (!isValidLink($image)) {
                return response()->json([
                    'error' => 'Image Fail'
                ], 400);
            }
        }

        $market->update([
            'name'  => $request->name,
            'slug'  => Str::slug($request->name),
            'image' => $image
        ]);

        return new MarketResource($market);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Market  $market
     * @return \Illuminate\Http\Response
     */
    public function destroy(Market $market)
    {
        $market->delete();
        return response()->json([
            'message' => 'Data was deleted!'
        ], 200);
    }
}
