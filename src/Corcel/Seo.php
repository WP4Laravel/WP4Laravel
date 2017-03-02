<?php

namespace WP4Laravel\Corcel;

trait Seo
{

    /**
     * Get the SEO data from the Yoast plugin in a formatted version
     * @return Collection
     */
    public function getSeoAttribute()
    {
        return collect([
            'keywords' => $this->meta->getAttribute('_yoast_wpseo_focuskw') ?: '',
            'title' => $this->meta->getAttribute('_yoast_wpseo_title') ?: $this->title,
            'description' => $this->meta->_yoast_wpseo_metadesc ?: $this->excerpt,
            'metakeywords' => $this->meta->_yoast_wpseo_metakeywords ?: '',
            'noindex' => $this->meta->getAttribute('_yoast_wpseo_meta-robots-noindex') ?: '',
            'nofollow' => $this->meta->getAttribute('_yoast_wpseo_meta-robots-nofollow') ?: '',
            'opengraph-title' => $this->getAttribute('meta->_yoast_wpseo_opengraph-title') ?: $this->title,
            'opengraph-description' => $this->meta->getAttribute('_yoast_wpseo_opengraph-description') ?: $this->excerpt,
            'image' => $this->meta->getAttribute('_yoast_wpseo_opengraph-image') ?: '',
            'twitter-title' => $this->meta->getAttribute('_yoast_wpseo_twitter-title') ?: '',
            'twitter-description' => $this->meta->getAttribute('_yoast_wpseo_twitter-description') ?: $this->excerpt,
            'twitter-image' => $this->meta->getAttribute('_yoast_wpseo_twitter-image') ?: '',
        ]);
    }
}
