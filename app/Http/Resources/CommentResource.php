<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'recipe_id' => $this->recipe_id,
            'content' => $this->content,
            'user' => new UserResource($this->whenLoaded('user')),
            'recipe' => new RecipeResource($this->whenLoaded('recipe')),
            'user.recipes' => UserResource::collection($this->whenLoaded('user.recipes'))
        ];
    }
}
