<?php

namespace App\Models;

use App\Traits\ResourceVersioning;

class Exhibition extends Post
{
    use ResourceVersioning;

    protected $postType = 'exhibition';
}
