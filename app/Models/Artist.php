<?php

namespace App\Models;

use App\Traits\ResourceVersioning;

class Artist extends Post
{
    use ResourceVersioning;

    protected $postType = 'artist';

    public function toArray()
    {
        return [
            'id' => $this->ID,
            'name' => $this->title,
        ];
    }
}
