<?php

namespace App\Helpers;

class OutputHelper
{
    public static function formatDate($date)
    {
        return $date->toIso8601String();
    }
}
