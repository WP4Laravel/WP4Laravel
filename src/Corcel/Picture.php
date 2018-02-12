<?php

namespace WP4Laravel\Corcel;

use Corcel\Acf\Field\Image;
use Corcel\Model\Meta\ThumbnailMeta;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class Picture
{
    /**
     * Compose a view file with data used to render the correct srcsets, etc.
     * @param  View   $view
     */
    public function compose(View $view)
    {
        if (!$view->picture || !$view->breakpoints) {
            throw new \InvalidArgumentException('Either $picture or $breakpoints not set');
        }

        $breakpoints = collect($view->breakpoints);
        $picture = $view->picture;
        $picture = $this->transform($picture, $breakpoints);

        $view->with('picture', $picture);
    }

    /**
     * Extend the picture object with extra fields to render a <picture> element
     * @param  ThumbnailMeta|Image     $picture
     * @param  Collection $breakpoints
     * @return ThumbnailMeta|Image
     */
    private function transform($picture, Collection $breakpoints)
    {
        $crops = collect(unserialize($picture->attachment->meta->_wp_attachment_metadata)['sizes']);

        $picture->sources = $this->calculateSrcSets($picture, $breakpoints, $crops);
        $crop = $picture->size('full');
        switch (gettype($crop)) {
            case 'array':
                $url = $crop['url'];
                break;
            case 'string':
                $url = $crop;
                break;
            case 'object':
                $url = $crop->url;
                break;
            default:
                $url = null;
                break;
        }
        $picture->src = $url;
        $picture->alt = $picture->attachment->meta->_wp_attachment_image_alt;

        return $picture;
    }

    /**
     * Create the appropriate srcsets
     * @param  ThumbnailMeta|Image $picture
     * @param  Collection $breakpoints
     * @param  Collection $crops
     * @return Collection
     */
    private function calculateSrcSets($picture, Collection $breakpoints, Collection $crops) : Collection
    {
        return $breakpoints->map(function ($crop, $query) use ($picture, $crops) {
            // Create an object representation of this <source>
            $breakpoint = new \StdClass;
            $breakpoint->mediaQuery = $query;

            // Filter all crops to find the ones we use in this srcset
            // Transform those crops in a valid srcset-attribute
            $breakpoint->srcset = $crops->filter(function ($data, $crop_name) use ($crop) {
                return strpos($crop_name, $crop) === 0;
            })->map(function ($data, $cropname) use ($picture) {
                $crop = $picture->size($cropname);
                switch (gettype($crop)) {
                    case 'array':
                        $url = $crop['url'];
                        break;
                    case 'string':
                        $url = $crop;
                        break;
                    case 'object':
                        $url = $crop->url;
                        break;
                    default:
                        $url = null;
                        break;
                }
                $sizeArray = explode('_', $cropname);
                $size = end($sizeArray);
                return "$url $size";
            })->implode(', ');

            return $breakpoint;
        });
    }
}
