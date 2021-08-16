<?php

namespace Realtek2\LaravelMultilanguage\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Realtek2\LaravelMultilanguage\Scopes\TranslationScope;

trait HasTranslation
{
    /**
     * Related table for translation in Database
     * @string
     */
    public string $translateTable;

    /**
     * Array of fields to be description
     * @array
     */
    public array $translateFields;

    /**
     * Related FK for description in Database
     * @string
     */
    public string $translateTableFK;


    protected static function bootHasTranslation()
    {
        static::addGlobalScope(new TranslationScope);
    }

    public function __construct($args)
    {
        parent::__construct($args);

        $this->translateTable = $this->translateTable ?? "{$this->getTable()}_translation";
        $this->translateFields = $this->translateFields ?? ['name'];
        $this->translateTableFK = $this->translateTableFK ?? "id";
    }

    /**
     * Has Many relation instance;
     *
     * @return HasMany
     */
    public function translations(): HasMany
    {
        return $this->hasMany(get_class(new class($this->translateTable) extends Model {
            public function __construct($table)
            {
                $this->table = $table;
                parent::__construct();
            }
        }), $this->translateTableFK);
    }
}
