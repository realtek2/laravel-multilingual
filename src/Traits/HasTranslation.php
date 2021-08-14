<?php

namespace Realtek2\LaravelMultilanguage\Traits;

use Realtek2\LaravelMultilanguage\Scopes\TranslationScope;

trait HasTranslation
{
    protected static function bootHasTranslation()
    {
        static::addGlobalScope(new TranslationScope);
    }
}
