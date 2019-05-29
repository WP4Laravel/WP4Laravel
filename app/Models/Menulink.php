<?php

namespace App\Models;

use App\Traits\ResourceVersioning;

class Menulink extends Post
{
    use ResourceVersioning;

    protected $postType = 'menulink';
}
