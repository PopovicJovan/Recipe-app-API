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

//    public static function shouldRelationBeIncluded(string $relation, ?array $relations=null)
//    {
//        $relations = $relations ?? Comment::$relationsForComment;
//        return in_array($relation, $relations);
//    }
//
//    public static function passedRelations(): array
//    {
//        $relations = request('include');
//        $relations = array_map('trim', explode(',', $relations));
//        return $relations;
//    }
//
//    public static function forwardedExpectedRelations(): array
//    {
//        $includedRelations = [];
//        foreach (Comment::passedRelations() as $passed){
//            if(Comment::shouldRelationBeIncluded($passed))
//                $includedRelations[] = $passed;
//        }
//
//        return $includedRelations;
//    }
}
