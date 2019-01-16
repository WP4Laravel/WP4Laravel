<?php

namespace WP4Laravel\Multilanguage;

use Corcel\Model\TermRelationship;
use Illuminate\Database\Eloquent\Builder;

class TranslatableTaxonomy
{
    /**
     * Filter a translatable taxonomy by language
     * @param  Builder $query
     * @param  string  $language two-letter language code
     * @return Builder
     */
    public function scopeLanguage(Builder $query, string $language) : Builder
    {
        $ids = TermRelationship::on($this->connection)->join('term_taxonomy', 'term_taxonomy.term_taxonomy_id', '=', 'term_relationships.term_taxonomy_id')
                ->join('terms', 'terms.term_id', '=', 'term_taxonomy.term_id')
                ->where('terms.slug', 'pll_' . $language)
                ->get()->each(function ($item) {
                    $item->incrementing = false;
                })->pluck('object_id')->all();

        if ($ids) {
            $query->whereIn('term_taxonomy.term_id', $ids);
        } else {
            $query->where('term_taxonomy.term_id', '-1');
        }

        return $query;
    }
}
