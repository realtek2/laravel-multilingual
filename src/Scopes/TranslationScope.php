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
        $builder
            ->when(!empty($model->getTranslationFields()), function($query) use ($model) {
                $columns = substr_replace($model->getTranslationFields(), "{$model->getTranslationTable()}.", 0, 0);
                array_unshift($columns, "{$model->getTable()}.*");

                $query
                    ->addSelect($columns)
                    ->join($model->getTranslationTable(), 
                         "{$model->getTable()}.{$model->getKeyName()}", 
                         '=', 
                         "{$model->getTranslationTable()}.{$model->getTranslationTableFK()}", 
                         'left')
                    ->where("{$model->getTranslationTable()}.{$model->getTranslationLanguageFK()}", app()->getLocale());
            });
    }
}
