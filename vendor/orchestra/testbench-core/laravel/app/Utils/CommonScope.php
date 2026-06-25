<?php

namespace App\Utils;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

trait CommonScope
{
    public function scopeSearchData(Builder $builder, array $columns = [], ?string $search = ''): Builder
    {
        return $builder->when((!is_null($search) && strlen((string) $search) > 0), function (Builder $query) use ($search, $columns): void {
            $directColumns = [];
            $relationColumns = [];

            foreach ($columns as $column) {
                if (str_contains($column, ',')) {
                    $relationColumns[] = $column;
                } else {
                    $directColumns[] = $column;
                }
            }

            $query->where(function (Builder $q) use ($search, $directColumns, $relationColumns): void {
                if ($directColumns !== []) {
                    $q->whereAny($directColumns, 'LIKE', "%{$search}%");
                }

                foreach ($relationColumns as $relationColumn) {
                    [$relationPath, $column] = explode(',', $relationColumn, 2);
                    $relations = explode('.', $relationPath);

                    $q->orWhereHas($relations[0], function (Builder $inner) use ($search, $relations, $column): void {
                        $this->applyNestedRelationSearch($inner, array_slice($relations, 1), $column, $search);
                    });
                }
            });
        });
    }

    private function applyNestedRelationSearch(Builder $query, array $remainingRelations, string $column, string $search): void
    {
        if ($remainingRelations === []) {
            $query->where($column, 'LIKE', "%{$search}%");

            return;
        }

        $query->whereHas($remainingRelations[0], function (Builder $inner) use ($remainingRelations, $column, $search): void {
            $this->applyNestedRelationSearch($inner, array_slice($remainingRelations, 1), $column, $search);
        });
    }

    public function scopeSortData(Builder $builder, string $sort = 'updated_at', string $order = 'desc'): Builder
    {
        return $builder->when((!is_null($sort) && strlen((string) $sort) > 0), function (Builder $query) use ($order, $sort): void {
            $query->orderBy($sort, $order === 'desc' ? 'desc' : 'asc');
        });
    }

    public function scopeFilterOrganizationChart(Builder $builder, Collection $collection, $modelName = null): Builder
    {
        return $builder->when($collection->has('directorate_id'), function ($query) use ($collection, $modelName) {
            if ($modelName == null) {
                $query->where('directorate_id', $collection->get('directorate_id'));
            } else {
                $query->whereHas($modelName, function ($q) use ($collection) {
                    $q->where('directorate_id', $collection->get('directorate_id'));
                });
            }
        })
            ->when($collection->has('sub_directorate_id'), function ($query) use ($collection, $modelName) {
                if ($modelName == null) {
                    $query->where('sub_directorate_id', $collection->get('sub_directorate_id'));
                } else {
                    $query->whereHas($modelName, function ($q) use ($collection) {
                        $q->where('sub_directorate_id', $collection->get('sub_directorate_id'));
                    });
                }
            })
            ->when($collection->has('division_id'), function ($query) use ($collection, $modelName) {
                if ($modelName == null) {
                    $query->where('division_id', $collection->get('division_id'));
                } else {
                    $query->whereHas($modelName, function ($q) use ($collection) {
                        $q->where('division_id', $collection->get('division_id'));
                    });
                }
            })
            ->when($collection->has('department_id'), function ($query) use ($collection, $modelName) {
                if ($modelName == null) {
                    $query->where('department_id', $collection->get('department_id'));
                } else {
                    $query->whereHas($modelName, function ($q) use ($collection) {
                        $q->where('department_id', $collection->get('department_id'));
                    });
                }
            })
            ->when($collection->has('section_id'), function ($query) use ($collection, $modelName) {
                if ($modelName == null) {
                    $query->where('section_id', $collection->get('section_id'));
                } else {
                    $query->whereHas($modelName, function ($q) use ($collection) {
                        $q->where('section_id', $collection->get('section_id'));
                    });
                }
            });
    }
}
