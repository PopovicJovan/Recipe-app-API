<?php

namespace App\Http\Controllers;

use App\Models\Rate;
use App\Models\Recipe;

class RateController extends Controller
{
    public function store(int $recipe)
    {
       $recipe =  Recipe::find($recipe);
       if(Rate::where('user_id', request()->user()->id)
                ->where('recipe_id',$recipe->id )->get()){
           return response()->json([], 400);
       };
       if(!$recipe) return response()->json([], 204);
       if (!request('rate')) return response()->json([
           "error" => "You did not send rate"
       ], 400);
       $recipe->rates()->create([
           'rate' => request('rate'),
           'user_id' => request()->user()->id
       ]);

       return response()->json([], 201);
    }

    public function show(int $recipe)
    {
        $recipe =  Recipe::find($recipe);
        if(!$recipe) return response()->json([], 204);
        $rate = Rate::where('user_id', request()->user()->id)->where('recipe_id',$recipe->id )->get();
        return response()->json($rate, 200);

    }

    public function update(int $recipe)
    {
        $recipe =  Recipe::find($recipe);
        if(!$recipe) return response()->json([], 204);
        $rate = Rate::where('user_id', request()->user()->id)->where('recipe_id',$recipe->id )->get();
        if (!request('rate')) return response()->json([
            "error" => "You did not send rate"
        ], 400);
        $rate->update([
            'rate' => request('rate')
        ]);

        return response()->json([],200);
    }
}
