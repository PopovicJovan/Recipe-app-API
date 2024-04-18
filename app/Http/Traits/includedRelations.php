<?php

namespace App\Http\Traits;


use App\Models\Comment;


trait includedRelations
{
    protected function shouldRelationBeIncluded(string $relation, array $relations)
    {
        return in_array($relation, $relations);
    }

    protected function passedRelations(string $request): array
    {
        $relations = request($request);
        $relations = array_map('trim', explode(',', $relations));
        return $relations;
    }

    public function forwardedExpected(array $relations, string $request='include'): array
    {
        $includedRelations = [];
        foreach ($this->passedRelations($request) as $passed){
            if($this->shouldRelationBeIncluded($passed, $relations))
                $includedRelations[] = $passed;
        }

        return $includedRelations;
    }
}

