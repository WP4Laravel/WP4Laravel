<?php

namespace App\Http\Resources\Api\v1_0\Traits;

use WP4Laravel\S3Media;

trait AcfFormatter
{
    public function formatOutput($type)
    {
        switch ($type) {
            case 'header_note':
                if ($this->meta->header_note_description) {
                    return [
                        'type' => $this->meta->header_note_type,
                        'value' => ($this->meta->header_note_description) ? $this->meta->header_note_description : null,
                    ];
                }
                return null;
            case 'geolocation':
                return [
                    'lat' => floatval($this->meta->geolocation_lat),
                    'lng' => floatval($this->meta->geolocation_lng),
                ];
            case 'highlight_marker':
                $selectName = $type . '_type';
                $itemType = $this->meta->$selectName;
                $markerValueTypes = ['primary', 'secondary', 'tertiary'];
                $markerValue = null;
                if (in_array($itemType, $markerValueTypes)) {
                    $markerValue = ($itemType == 'tertiary') ? $this->meta->highlight_marker_marker_value_text : (int)$this->meta->highlight_marker_marker_value_number;
                }
                $videoTypes = ['primary'];
                $output = [
                    'type' => $itemType,
                    'marker_value' => $markerValue,
                    'video' => (in_array($itemType, $videoTypes)) ? [
                        'file' => ($this->acf->file($type . '_video_file')->url) ? S3Media::handle($this->acf->file($type . '_video_file'))->url() : null,
                        'description' => $this->meta->highlight_marker_video_description,
                    ] : null,
                ];
                return $output;
            case 'tourhighlight_marker':
                $selectName = $type . '_type';
                $itemType = $this->meta->$selectName;
                $audioTypes = ['intro', 'highlight'];
                $videoTypes = ['special'];
                $output = [
                    'type' => $itemType,
                    'audio' => (in_array($itemType, $audioTypes) && $this->acf->file($type . '_audio_file')->url) ? S3Media::handle($this->acf->file($type . '_audio_file'))->url() : null,
                    'marker_value' => $this->index,
                    'video' => (in_array($itemType, $videoTypes)) ? $this->getTourhighlightMarkerVideo() : null,
                ];
                return $output;
            case 'hotspot_location':
                return [
                    'geolocation' => ($this->meta->location_geolocation_lat && $this->meta->location_geolocation_lng) ? [
                        'lat' => floatval($this->meta->location_geolocation_lat),
                        'lng' => floatval($this->meta->location_geolocation_lng),
                    ] : null,
                    'address' => ($this->meta->location_address) ? $this->meta->location_address : null
                ];
        }

        return null;
    }

    public function getBoundingBoxes()
    {
        return $this->acf->repeater('bounding_boxes')->map(function ($item, $key) {
            return [
                [
                    'lat' => floatval($item['north-east_lat']),
                    'lng' => floatval($item['north-east_lng']),
                ],
                [
                    'lat' => floatval($item['south-west_lat']),
                    'lng' => floatval($item['south-west_lng']),
                ]
            ];
        });
    }

    private function getTourhighlightMarkerVideo()
    {
        $video_type = $this->meta->tourhighlight_marker_video_type;

        $output = [
            'type' => $video_type,
            'file' => ($this->acf->file('tourhighlight_marker_video_file')->url) ? S3Media::handle($this->acf->file('tourhighlight_marker_video_file'))->url() : null,
            'thumbnail' => ($this->acf->image('tourhighlight_marker_video_thumbnail')->url) ? S3Media::handle($this->acf->image('tourhighlight_marker_video_thumbnail'))->url() : null,
        ];

        if ($video_type === 'ar') {
            $output = array_merge($output, [
                'geolocation' => [
                    'lat' => floatval($this->meta->tourhighlight_marker_ar_video_geolocation_lat),
                    'lng' => floatval($this->meta->tourhighlight_marker_ar_video_geolocation_lng),
                ]
            ]);
        }

        return $output;
    }
}
