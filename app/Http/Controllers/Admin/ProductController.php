<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class ProductController extends Controller
{

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductRequest $request)
    {
        $data = $request->validated();
        $data['visible'] = true;
        $data['restaurant_id'] = Auth::user()->restaurant->id;
        if ($request->hasFile('image')) {
            $path = Storage::disk('public')->put('product_images', $request->image);
            $data['image'] = $path;
        }
        $product = Product::create($data);

        if ($request->has('categories')) {
            $product->categories()->attach($request->categories);
        }

        return redirect()->route('admin.dashboard')->with('message', "{$product->name} è stato creato con successo");;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        if ($product->restaurant->id === Auth::user()->restaurant->id) {
            return view('admin.products.show', compact('product'));
        } else {

            return view('errors.403');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        $categories = Category::all();
        if ($product->restaurant->user_id === Auth::user()->id) {

            return view('admin.products.edit', compact('product', 'categories'));
        } else {
            return view('errors.403');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        if ($product->restaurant->user?->id === Auth::user()->id) {

            $data = $request->validated();
            // $data['image'] = null;

            if ($request->hasFile('image')) {

                Storage::delete($product->image);

                $path = Storage::disk('public')->put('image', $request->image);
                $data['image'] = $path;
            }

            $product->update($data);
            return redirect()->route('admin.dashboard')->with('message', "{$product->name} è stato modificato con successo");
        } else {
            return view('errors.403');
        }
    }

    public function toggleVisible(Request $request, Product $product)
    {
        if ($product->restaurant->user?->id === Auth::user()->id) {

            $data = $request->all();
            $product->update($data);
            return redirect()->route('admin.dashboard')->with('message', "{$product->name} è stato modificato con successo");
        } else {
            return view('errors.403');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        if ($product->restaurant->user?->id === Auth::user()->id) {

            if ($product->image) {
                Storage::delete($product->image);
            }
            $product->delete();
            return redirect()->route('admin.dashboard')->with('message', "{$product->name} è sato cancellato");
        } else {
            return view('errors.403');
        }
    }
}
