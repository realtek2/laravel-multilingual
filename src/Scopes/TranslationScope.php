<?php

namespace Realtek2\LaravelMultilanguage\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class TranslationScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param Builder $builder
     * @param Model $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        $columns = substr_replace($model->descFields, "{$model->descTable}.", 0, 0);
        array_unshift($columns, "{$model->getTable()}.*");

        $builder
            ->addSelect($columns)
            ->join($model->descTable, "{$model->getTable()}.{$model->getKeyName()}", '=', "{$model->descTable}.{$model->descTableFK}", 'left')
            ->where("{$model->descTable}.language_code", app()->getLocale());
    }
}
