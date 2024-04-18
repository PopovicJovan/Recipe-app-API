<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    use HasFactory;
    public $fillable = ['content', 'user_id', 'recipe_id'];
    public static array $relationsForComment = ['user', 'recipe', 'user.recipes'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function Recipe(): BelongsTo
    {
        return $this->belongsTo(Recipe::class);
    }

}
