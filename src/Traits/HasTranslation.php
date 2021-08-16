<?php

namespace Realtek2\LaravelMultilanguage\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Realtek2\LaravelMultilanguage\Scopes\TranslationScope;

trait HasTranslation
{
    protected static function bootHasTranslation()
    {
        static::addGlobalScope(new TranslationScope);
    }

    public function __construct()
    {
        $this->translateTable = $this->translateTable ?? "{$this->getTable()}_translation";
        $this->translateFields = $this->translateFields ?? [];
        $this->translateTableFK = $this->translateTableFK ?? "entity_id";

        parent::__construct();
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
