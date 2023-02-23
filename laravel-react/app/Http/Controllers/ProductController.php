<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductVariantPrice;
use App\Models\Variant;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        if($request->ajax()){
            return $this->filter($request);
        }

        $products = Product::with('productVariantPrice', 'productVariant')->paginate(3);
        $productVariants = ProductVariant::all();
        //dd($products);
        return view('products.index')->with("products", $products)->with("productVariants", $productVariants);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function create()
    {
        $variants = Variant::all();
        return view('products.create', compact('variants'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {

    }


    /**
     * Display the specified resource.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function show($product)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
       // dd($product);
        $variants = Variant::all();
        // $productVariants = ProductVariant::all();
        // $product = Product::with('productVariantPrice')->where('id', $product->id)->get();

        //dd($product);
        return view('products.edit', compact('variants'));
        //return view('products.edit')->with("product", $product)->with("productVariants", $productVariants)->with("variants", $variants);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }

    private function filter(Request $request)
    {
        $productVariants = ProductVariant::all();
        $products = Product::with('productVariantPrice');

        $title = $request->title;
        $minPrice = $request->from;
        $maxPrice = $request->to;
        $variants = $request->variants;

        if($title){
            $products = $product->where("title", $title);
        }
        $products = $products->with(['productVariantPrice' => function ($query) use ($minPrice, $maxPrice) {
            $query->whereBetween('price', [$minPrice, $maxPrice]);
        }])->whereHas('productVariantPrice', function ($query) use ($minPrice, $maxPrice) {
            $query->whereBetween('price', [$minPrice, $maxPrice]);
        })->paginate(3);

        // if($variants)
        // {
        //     $variants = ProductVariant::whereIn('variant', $variants)->pluck('id');

        //     $products = Product::whereHas('productVariantPrice', function ($query) use ($minPrice, $maxPrice) {
        //                 $query->whereBetween('price', [$minPrice, $maxPrice]);
        //             })
        //             ->whereHas('productVariantPrice.productVariant', function ($query) use ($variants) {
        //                 $query->whereIn('id', $variants);
        //             })
        //             ->paginate(3);
        // }

        return view('products.index')->with("products", $products)->with("productVariants", $productVariants);
    }

    public function saveProduct(Request $request)
    {
        $product = new Product();
        $product->title = $request->title;
        $product->description = $request->description;
        $product->sku = $request->sku;
        $product->save();

        $variants = array();

        foreach ($request->product_variant as $product_variant) {
            foreach ($product_variant["tags"] as $tag) {
                $productVariant = new ProductVariant();
                $productVariant->product_id = $product->id;
                $productVariant->variant_id = $product_variant["option"];
                $productVariant->variant = $tag;
                $productVariant->save();

                $variants[$tag] = $productVariant->id;
            }
        }

        //return $variants;

        foreach ($request->product_variant_prices as $product_variant_price) {
            $productVariantPrice = new ProductVariantPrice();
            $tags = explode("/",$product_variant_price["title"]);
            array_pop($tags);
            
            $tags[0] ? $productVariantPrice->product_variant_one=$variants[$tags[0]] : $productVariantPrice->product_variant_one=null;
            $tags[1] ? $productVariantPrice->product_variant_two=$variants[$tags[1]] : $productVariantPrice->product_variant_two=null;
            $tags[2] ? $productVariantPrice->product_variant_three=$variants[$tags[2]] : $productVariantPrice->product_variant_three=null;

            $productVariantPrice->price = $product_variant_price["price"];
            $productVariantPrice->stock = $product_variant_price["stock"];
            $productVariantPrice->product_id = $product->id;

            $productVariantPrice->save();
        }

        return true;
    }
}
