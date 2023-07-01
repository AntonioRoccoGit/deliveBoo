<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRestaurantRequest;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Termwind\Components\Dd;

class RestaurantController extends Controller
{

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $restaurant = Restaurant::where('user_id', Auth::user()->id)->first();

        if (Auth::user()->id == $restaurant->user_id) {
            return redirect()->route('admin.dashboard')->with('message', "Possiedi già un ristorante");
        } else {
            return view('admin.restaurants.create');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRestaurantRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::user()->id;
        $path = Storage::disk('public')->put('img', $request->image);
        $data['image'] = $path;
        Restaurant::create($data);
        return redirect()->route('admin.dashboard');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}