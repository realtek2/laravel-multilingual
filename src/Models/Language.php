<?php

namespace Realtek2\LaravelMultilanguage\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    /**
     * Get a default language of project
     * @throws Exception
     */
    public static function getDefault()
    {
        $default = self::wherePrimary(true)->first();

        if ($default === null) {
            throw new Exception('There isn\'t set default language for the App.');
        }
        return $default;
    }
}
