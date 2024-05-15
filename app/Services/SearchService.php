<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class SearchService
{
    private string $key = 'keyword';

    public function handle(Request $request, object $table, array $conditions, ?array $relations = null)
    {
        $model = $table;

        if ($model instanceof \Illuminate\Database\Eloquent\Builder) {
            $model = $model->getModel();
            $columns = Schema::getColumnListing($model->getTable());
            $validConditions = array_filter($conditions, function ($condition) use ($columns) {
                return in_array($condition, $columns);
            });

            $model = $table;
        } else {
            $columns = Schema::getColumnListing($model->getTable());
            $validConditions = array_filter($conditions, function ($condition) use ($columns) {
                return in_array($condition, $columns);
            });
        }

        if (! empty($request->get($this->key))) {

            foreach ($validConditions as $key => $value) {
                if ($key === 0) {
                    $model = $model->where($value, 'LIKE', '%'.$request->get($this->key).'%');

                    continue;
                }

                $model = $model->orWhere($value, 'LIKE', '%'.$request->get($this->key).'%');
            }

            if (! empty($relations)) {
                foreach ($relations as $relation) {
                    $model = $model->with($relation);
                    $relatedModel = $model->getRelation($relation)->getRelated();
                    $relatedColumns = Schema::getColumnListing($relatedModel->getTable());
                    $validRelationConditions = array_filter($conditions, function ($condition) use ($relatedColumns) {
                        return in_array($condition, $relatedColumns);
                    });

                    $model = $model->orWhereHas($relation, function ($query) use ($validRelationConditions, $request) {
                        foreach ($validRelationConditions as $key => $value) {
                            if ($key === 0) {
                                $query->where($value, 'LIKE', '%'.$request->get($this->key).'%');

                                continue;
                            }

                            $query->orWhere($value, 'LIKE', '%'.$request->get($this->key).'%');
                        }
                    });
                }
            }

            session()->flash('keyword', $request->get($this->key));
        }

        return $model;
    }

    public function setKey(string $key): SearchService
    {
        $this->key = $key;

        return $this;
    }
}