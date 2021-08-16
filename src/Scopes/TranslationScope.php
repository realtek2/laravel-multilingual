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
        $columns = substr_replace($model->translateFields, "{$model->translateTable}.", 0, 0);
        array_unshift($columns, "{$model->getTable()}.*");

        $builder
            ->addSelect($columns)
            ->join($model->translateTable, "{$model->getTable()}.{$model->getKeyName()}", '=', "{$model->translateTable}.{$model->translateTableFK}", 'left')
            ->where("{$model->translateTable}.language_code", app()->getLocale());
    }
}
