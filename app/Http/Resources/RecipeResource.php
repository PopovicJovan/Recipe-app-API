<?php

namespace App\Http\Resources;

use App\Models\Rate;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RecipeResource extends JsonResource
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
            'user_id'=> $this->user_id,
            'title'=> $this->title,
            'content'=> $this->content,
            'category'=> $this->category,
            'avg_rate' => Rate::where('recipe_id', $this->id)->avg('rate'),
            'num_rate' => Rate::where('recipe_id', $this->id)->count(),
            'components'=> array_values(explode(',', $this->components)),
            'user' => new UserResource($this->whenLoaded('user')),
            'comments' => CommentResource::collection($this->whenLoaded('comments')),
            'user.comments' => new UserResource($this->whenLoaded('user.comments'))

        ];
    }
}
