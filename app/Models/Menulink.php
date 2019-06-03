<?php

namespace App\Models;

use App\Traits\ResourceVersioning;

class Menulink extends Post
{
    use ResourceVersioning;

    protected $postType = 'menulink';

    public function toArray()
    {
        return [
            'id' => $this->ID,
            'title' => $this->title,
            'url' => $this->meta->url,
        ];
    }
}
