<?php

namespace App\Http\Filters\V1;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class QueryFilter
{
    protected Builder $builder;
    protected Request $request;
    protected array $sortable = [];

    public function apply(Builder $builder)
    {
        $this->builder = $builder;

        foreach ($this->request->all() as $key => $value) {
            if (method_exists($this, $key)) {
                $this->$key($value);
            }
        }

        return $this->builder;
    }

    public function __construct(Request $request)
    {
        return $this->request = $request;
    }

    public function filter($arr)
    {
        foreach ($arr as $key => $value) {
            if (method_exists($this, $key)) {
                $this->$key($value);
            }
        }
        return $this->builder;

    }

    public function sort($value)
    {
        $sortAttributes = explode(',', $value);
        foreach ($sortAttributes as $sortAttribute) {
            $direction = !str_starts_with($sortAttribute, '-') ? 'asc' : 'desc';
            if ($direction === 'desc') {
                $sortAttribute = substr($sortAttribute, 1);
            }
            if (!in_array($sortAttribute, $this->sortable) && !array_key_exists($sortAttribute, $this->sortable)) {
                continue;
            }
            if($this->sortable[$sortAttribute] !== null) {
                $sortAttribute = $this->sortable[$sortAttribute];
            }
            return $this->builder->orderBy($sortAttribute, $direction);
        }

    }

}
