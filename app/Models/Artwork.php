<?php

namespace App\Models;

use App\Traits\ResourceVersioning;
use WP4Laravel\S3Media;

class Artwork extends Post
{
    use ResourceVersioning;

    protected $postType = 'artwork';

    public function toArray()
    {
        return [
            'id' => $this->ID,
            'title' => $this->title,
            'images' => $this->getPostImages(),
            'content_type' => $this->meta->type,
            'content' => $this->getContent($this->meta->type),
        ];
    }

    private function getContent(string $type)
    {
        $data = null;

        switch ($type) {
            case 'audio': {
                $data = [
                    'title' => $this->meta->type_audio_title,
                    'description' => $this->meta->type_audio_description,
                    'image' => ($this->acf->file('type_audio_image')->url) ? S3Media::handle($this->acf->file('type_audio_image'))->url() : null,
                    'audio' => ($this->acf->file('type_audio_audio')->url) ? S3Media::handle($this->acf->file('type_audio_audio'))->url() : null,
                ];
                break;
            }
            case 'carousel': {
                $data = $this->acf->repeater('type_carousel')->map(function ($item) {
                    return [
                        'image' => ($item['image']->url) ? S3Media::handle($item['image'])->url() : null,
                        'video' => ($item['video']->url) ? S3Media::handle($item['video'])->url() : null,
                    ];
                });
                break;
            }
            case 'video': {
                $data = [
                    'title' => $this->meta->type_video_title,
                    'description' => $this->meta->type_video_description,
                    'image' => ($this->acf->file('type_video_image')->url) ? S3Media::handle($this->acf->file('type_video_image'))->url() : null,
                    'video' => ($this->acf->file('type_video_video')->url) ? S3Media::handle($this->acf->file('type_video_video'))->url() : null,
                ];
                break;
            }
        }

        return $data;
    }
}
