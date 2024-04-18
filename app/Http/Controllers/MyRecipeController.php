<?php

namespace App\Http\Controllers;

use App\Http\Resources\RecipeResource;
use App\Http\Traits\includedRelations;
use App\Models\Recipe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MyRecipeController extends Controller
{
    use includedRelations;
    public function index(Request $request)
    {
        $this->authorize('viewAny', Recipe::class);
        $recipes = Recipe::all()->where('user_id', $request->user()->id);

        if(!$recipes) return response()->json([], 204);

        $includedRelations = $this->forwardedExpected(Recipe::$relationsForRecipe);
        $category = $this->forwardedExpected(Recipe::$category, 'category');

        if(!empty($includedRelations)) $recipes = Recipe::with(...$includedRelations)->where('user_id', $request->user()->id)->get();
        if(!empty($category)) {
            $helperList = []; $recipes = [];

            foreach ($category as $cat) {
                $helperList[] = Recipe::with($includedRelations)->where('user_id',$request->user()->id )
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

    public function store(Request $request)
    {
        $this->authorize('create', Recipe::class);
        $user = $request->user();

        $validatedValues = Validator::make($request->only(['title', 'content', 'category', 'components']),[
            'title' => 'required|string',
            'content' => 'required|string',
            'category' => 'required|in:cookie,pasta,other',
            'components' => 'required'
        ]);

        if($validatedValues->fails()) return response()->json([
            "error" => "Validation error!"
        ], 400);


        $recipe = $user->recipes()->create([
            'title' => $request->input('title'),
            'content' => $request->input('content'),
            'components' => implode(',',
                array_map('trim',
                    explode(',',$request->input('components'))
                )
            ),
            'category' => $request->input('category')
        ]);

        return new RecipeResource($recipe);
    }


    public function show(int $my_recipe)
    {
        $my_recipe = Recipe::find($my_recipe);
        if($my_recipe->user_id != request()->user()->id) return response()->json([
            "error" => "Not authorized!"
        ], 400);

        $this->authorize('view', [Recipe::class, $my_recipe]);

        if(!$my_recipe) return response()->json([], 204);

        $includedRelations = $this->forwardedExpected(Recipe::$relationsForRecipe);

        if(!$includedRelations) $my_recipe = Recipe::with(...$includedRelations)->find($my_recipe->id);


        return response()->json([
            "recipes" => new RecipeResource($my_recipe)
        ], 200);


    }


    public function update(Request $request, int $my_recipe)
    {
        if(!$request->only(['title', 'content', 'category', 'components']))
            return response()->json([
            "error" => "You haven't sent anything!"
            ], 400);

        $my_recipe = Recipe::find($my_recipe);
        $this->authorize('update', [Recipe::class, $my_recipe]);

        if(!$my_recipe) return response()->json([], 204);

        $my_recipe->update($request->only(['title', 'content', 'category', 'components']));

        return response()->json([
            "recipes" => new RecipeResource($my_recipe)
        ], 200);

    }


    public function destroy(int $my_recipe)
    {
        $my_recipe = Recipe::find($my_recipe);
        $this->authorize('delete', [Recipe::class, $my_recipe]);

        if(!$my_recipe) return response()->json([], 400);

        return response()->json([
            "recipes" => new RecipeResource($my_recipe)
        ], 200);
    }
}
