<?php

namespace WP4Laravel\Multilanguage;

use Illuminate\Database\Eloquent\Builder;

/**
 * Use in Corcel-based models to enable multilanguage related behaviour
 */
trait Translatable
{
    /**
     * Scope the Eloquent query on the selected language
     * @param  Illuminate\Database\Eloquent\Builder $query
     * @param  string  $language valid 2-language code
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function scopeLanguage(Builder $query, string $language)
    {
        return $query->taxonomy('language', $language);
    }

    /**
     * The language of this model
     * @return string 2-letter language code
     */
    public function getLanguageAttribute()
    {
        return $this->taxonomies()->where('taxonomy', 'language')->first()->slug;
    }

    /**
     * All translations of this model
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function translations()
    {
        return $this->taxonomies()->where('taxonomy', 'post_translations');
    }

    /**
     * Virtual property with all translations of this model
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function getTranslationsAttribute()
    {
        $translations = $this->translations();

        if ($translations->count()) {
            $translatedIds = array_values(unserialize($translations->first()->description));
            return static::whereIn('id', $translatedIds)->get()->keyBy('language');
        } else {
            return null;
        }
    }
}
