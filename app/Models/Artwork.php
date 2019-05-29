<?php

namespace App\Models;

use App\Traits\ResourceVersioning;

class Artwork extends Post
{
    use ResourceVersioning;

    protected $postType = 'artwork';
}
