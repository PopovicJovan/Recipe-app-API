<?php

namespace App\Http\Controllers;

use App\Http\Resources\RecipeResource;
use App\Http\Traits\includedRelations;
use App\Models\Recipe;


class RecipeController extends Controller
{
    use includedRelations;

    public function index()
    {
        $recipes = Recipe::all();
        $this->authorize('viewAny', [Recipe::class]);

        if(!$recipes) return response()->json([],204);

        $includedRelations = $this->forwardedExpected(Recipe::$relationsForRecipe);
        $category = $this->forwardedExpected(Recipe::$category, 'category');


        if(!empty($includedRelations)) $recipes = Recipe::with(...$includedRelations)->get();
        if(!empty($category)) {
            $helperList = []; $recipes = [];

            foreach ($category as $cat) {
                $helperList[] = Recipe::with($includedRelations)
                    ->latest()
                    ->filter(['category' => $cat,
                        'text' => request('text')])
                    ->get();
            }

            foreach ($helperList as $list) {
                foreach ($list as $item) {
                    $recipes[] = new RecipeResource($item);
                }
            }

        }

        return response()->json([
            "recipes" => RecipeResource::collection($recipes)
        ], 200);

    }


    public function show(int $recipe)
    {
        $recipe = Recipe::find($recipe);
        $this->authorize('view', [Recipe::class, $recipe]);

        if(!$recipe) return response()->json([
            "error" => "Recipe does not exist!"
        ], 400);

        $includedRelations = $this->forwardedExpected(Recipe::$relationsForRecipe);

        if(!empty($includedRelations)) $recipe = Recipe::with(...$includedRelations)->find($recipe->id);

        return response()->json([
            "recipes" =>  new RecipeResource($recipe)
        ], 200);
    }

}
