<?php

namespace Realtek2\LaravelMultilanguage\Traits;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Realtek2\LaravelMultilanguage\Models\Language;
use Realtek2\LaravelMultilanguage\Scopes\TranslationScope;

trait HasTranslation
{
    protected static function bootHasTranslation()
    {
        static::addGlobalScope(new TranslationScope);
    }
    
    /**
     * Relationship model.
     *
     * @var string
     */
    public string $relationshipModel;

    /**
     * Suffix for Translation table.
     *
     * @var string
     */
    public static $tnSuffix = 'translation';

    /**
     * Foreign Key of model in translation table.
     *
     * @var string
     */
    public static $tnTableFK = 'entity_id';

    /**
     * Foreign key of language from translation table
     *
     * @var string
     */
    public static $tnLanguageFK = 'language_code';

    //Getters
    /**
     * Get translation table value from model.
     *
     * @return string
     */
    public function getTranslationTable(): string
    {
        return $this->getTable() . "_" . self::$tnSuffix;
    }

    /**
     * Get translation fields values from model.
     *
     * @return array
     */
    public function getTranslationFields(): array
    {
        return [];
    }

    /**
     * Get translation table Foreign Key.
     *
     * @return ?string
     */
    public function getTranslationTableFK(): string
    {
        return self::$tnTableFK;
    }
    
    /**
     * Get foreign key of language from translation table.
     *
     * @return ?string
     */
    public function getTranslationLanguageFK(): string
    {
        return self::$tnLanguageFK;
    }

    public function __construct()
    {
        $class = "\$this->relationshipModel = get_class(new class  extends \\Illuminate\\Database\\Eloquent\\Model {public \$table = '{$this->getTranslationTable()}';});";
        eval($class);

        parent::__construct();
    }

    /**
     * Has Many relation instance;
     *
     * @return HasMany
     */
    public function translations(): HasMany
    {
        return $this->hasMany($this->relationshipModel, $this->getTranslationTableFK());
    }

    /**
     * Insert, update or delete translation for model.
     *
     * @param array $request
     * @throws Exception
     * @return void
     */
    public function handleTranslations($translations): void
    {
        try {
            foreach (Language::all() as $language) {
                if(isset($translations[$language->code])){
                    foreach($this->getTranslationFields() as $field){
                        $insertValue = [
                            $this->getTranslationLanguageFK() => $language->code, 
                            $this->getTranslationTableFK() => $this->id,
                            $field => $translations[$language->code][$field]
                        ];
    
                        $entity = [
                            $this->getTranslationLanguageFK() => $language->code,
                            $this->getTranslationTableFK() => $this->id
                        ];
                        switch ($translations[$language->code]['action']) {
                            case 'update':
                                $this->relationshipModel::where($entity)->delete();
    
                                $this->relationshipModel::insert($insertValue);
                                break;
                            case 'delete':
                                $this->relationshipModel::where($entity)->delete();
                                break;
                            default:
                                if($this->relationshipModel::where([
                                    $this->getTranslationLanguageFK() => $language->code, 
                                    $this->getTranslationTableFK() => $this->id
                                ])->doesntExist()){
                                    $this->relationshipModel::insert($insertValue);
                                    break;
                                }
                                break;
                        }
                    }
                }
            }
        } catch (Exception $e) {
            throw $e;
        }
    }
}
