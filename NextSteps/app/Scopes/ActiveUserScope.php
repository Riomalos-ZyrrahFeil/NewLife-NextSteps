<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class ActiveUserScope implements Scope
{
    /**
     * This automatically adds WHERE is_deleted = 0 to every query on the User model.
     */
    public function apply(Builder $builder, Model $model)
    {
        $builder->where('is_deleted', false);
    }
}