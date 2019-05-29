<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Artwork;

class ArtworkController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(string $language, int $id)
    {
        $artwork = Artwork::published()->language($language)->findOrFail($id);

        return $this->resource('Api\Artwork', $artwork);
    }
}
