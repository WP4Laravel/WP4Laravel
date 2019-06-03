<?php

namespace App\Models;

use App\Traits\ResourceVersioning;

class Exhibition extends Post
{
    use ResourceVersioning;

    protected $postType = 'exhibition';

    public function artists()
    {
        if ($artists = $this->acf->relationship('artists')) {
            $artists = $artists->where('post_status', 'publish');
        }

        return ($artists) ? $this->resource('Api\ArtistCollection', $artists) : [];
    }

    public function artworks()
    {
        if ($artworks = $this->acf->relationship('artworks')) {
            $artworks = $artworks->where('post_status', 'publish');
        }

        return ($artworks) ? $this->resource('Api\ArtworkCollection', $artworks) : [];
    }

    public function toArray()
    {
        return [
            'id' => $this->ID,
            'title' => $this->title,
            'images' => $this->getPostImages(),
            'description' => $this->meta->description,
            'description_short' => $this->meta->description_short,
            'last_modified_date' => \OutputHelper::formatDate($this->post_modified),
        ];
    }
}
