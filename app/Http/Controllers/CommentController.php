<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommentResource;
use App\Http\Traits\includedRelations;
use App\Models\Comment;
use App\Models\Recipe;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    use includedRelations;
    public function index(int $recipe)
    {
        $this->authorize('viewAny', [Comment::class, request()->user()]);
        $includedRelations = $this->forwardedExpected(Comment::$relationsForComment);
        $comments = Comment::all()->where('recipe_id', $recipe);

        if ($includedRelations) $comments = Comment::with(...$includedRelations)
                                                    ->where('recipe_id', $recipe)
                                                    ->get()->sortByDesc('created_at');

        return response()->json([
            "comments" => CommentResource::collection($comments)
        ]);
    }

    public function store(Request $request, int $recipe)
    {
        $this->authorize('create', [Comment::class, request()->user()]);
        $recipe = Recipe::find($recipe);
        if(!$recipe) return response()->json([], 400);

        $request->validate(['content' => 'required|string']);

        $comment = $recipe->comments()->create([
            'user_id' => $request->user()->id,
            'content' => $request->input('content')
        ]);

        return response()->json([
            "comments" => new CommentResource($comment)
        ]);
    }

    public function show(int $recipe, int $comment)
    {
        $this->authorize('view', [Comment::class, request()->user()]);
        $comment = Comment::find($comment);
        if(!$comment) return response()->json([], 204);

        $includedRelations = $this->forwardedExpected(Comment::$relationsForComment);
        if ($includedRelations) $comment = Comment::with(...$includedRelations)
                                                    ->find($comment)->first();

        return response()->json([
            "comments" => new CommentResource($comment)
        ]);
    }

    public function update(Request $request, int $recipe, int $comment)
    {
        $comment = Comment::find($comment);
        $this->authorize('update', [Comment::class, request()->user(), $comment]);
        if(!$request->input('content')) return response()->json([
            "error" =>'No content!'
        ], 400);
        $comment->update([
            'content' => $request->input('content')
        ]);
        return response()->json([
            "comments" => new CommentResource($comment)
        ]);
    }

    public function destroy(int $recipe, int $comment)
    {
        $comment = Comment::find($comment);
        $this->authorize('delete', [Comment::class, request()->user(), $comment]);
        if(!$comment) return response()->json([],400);
        $comment->delete();
        return response()->json([],204);
    }
}
