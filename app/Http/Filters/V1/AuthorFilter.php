<?php

namespace App\Http\Filters\V1;

use Illuminate\Database\Eloquent\Builder;

class AuthorFilter extends QueryFilter
{
    public function createdAt($value): Builder
    {
        $dates = explode(',', $value);
        if(count($dates) > 1) {
            return $this->builder->whereBetween('created_at', $dates);
        }
        return $this->builder->whereDate('created_at', $dates);
    }

    public function include($value): Builder
    {
        return $this->builder->with($value);
    }

    public function id($value): Builder
    {
        return $this->builder->whereIn('id', explode(',', $value));
    }

    public function email($value): Builder
    {
        $likeStr = str_replace('*', '%', $value);
        return $this->builder->where('email', 'LIKE', $likeStr);
    }

    public function name($value): Builder
    {
        $likeStr = str_replace('*', '%', $value);
        return $this->builder->where('name', 'LIKE', $likeStr);
    }
    public function updatedAt($value): Builder
    {
        $dates = explode(',', $value);
        if(count($dates) > 1) {
            return $this->builder->whereBetween('updated_at', $dates);
        }
        return $this->builder->whereDate('updated_at', $dates);
    }

}
