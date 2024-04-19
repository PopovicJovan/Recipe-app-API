<?php

namespace App\Models;

use App\Http\Controllers\Controller;
use App\Http\Controllers\RecipeController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;

class Recipe extends Model
{
    use HasFactory;

    public $fillable = ['content', 'title', 'category', 'components'];
    public static array $relationsForRecipe = ['user', 'comments', 'user.comments'];
    public static array $category = ['cookie', 'pasta', 'other'];
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function rates(): HasMany
    {
        return $this->hasMany(Rate::class);
    }

    public function scopeFilter(Builder|QueryBuilder $query, array $filters): Builder|QueryBuilder
    {
        return $query->when($filters['text'] ?? null, function ($query) use ($filters){
            $query->where(function ($query) use ($filters){
                $query->where('title', 'like', '%'. $filters['text'] .'%')
                        ->orWhere('content', 'like', '%'. $filters['text'] .'%');
            });
        })->when($filters['category'] ?? null, function ($query) use ($filters){
            $query->where('category', $filters['category']);
        });
    }



}
