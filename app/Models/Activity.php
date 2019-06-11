<?php

namespace App\Models;

use App\Traits\ResourceVersioning;
use Illuminate\Database\Eloquent\Builder;

class Activity extends Post
{
    use ResourceVersioning;

    protected $postType = 'activity';

    public function toArray()
    {
        return [
            'id' => $this->ID,
            'name' => $this->title,
            'desciption' => $this->meta->description,
            'url' => $this->meta->url,
            'date_start' => $this->acf->date_start->format('d-m-Y'),
            'date_end' => ($this->meta->date_end) ? $this->acf->date_end->format('d-m-Y') : null,
            'calendar_event' => $this->getCalendarEvent(),
        ];
    }

    /**
     * Create calender event
     *
     * @return array
     */
    private function getCalendarEvent()
    {
        $event = [
            'date_start' => $this->acf->date_start->format('d-m-Y'),
            'date_end' => ($this->meta->date_end) ? $this->acf->date_end->format('d-m-Y') : null,
            'all_day' => (bool)$this->meta->date_all_day,
        ];

        if (!(bool)$this->meta->date_all_day) {
            $event = array_merge($event, [
                'time_start' => ((bool)$this->meta->date_all_day) ? null : $this->acf->date_time_start->format('H:i'),
                'time_end' => ((bool)$this->meta->date_all_day) ? null : $this->acf->date_time_end->format('H:i'),
            ]);
        }

        return $event;
    }

    /**
     * @param Builder $query
     * @param string $meta
     * @param mixed $value
     * @param string $operator
     * @return Builder
     */
    public function scopeHasMeta(Builder $query, $meta, $value = null, string $operator = '=')
    {
        if (!is_array($meta)) {
            $meta = [$meta => $value];
        }

        foreach ($meta as $key => $value) {
            $query->whereHas('meta', function (Builder $query) use ($key, $value, $operator) {
                if (!is_string($key)) {
                    return $query->where('meta_key', $operator, $value);
                }

                // $query->where('meta_key', $operator, $key); //--> WRONG, here operator must always be '='!!
                $query->where('meta_key', '=', $key);

                return is_null($value) ? $query :
                    $query->where(function ($query) use ($operator, $value) {
                        $query->where('meta_value', $operator, $value)->orWhere('meta_value', '');
                    });
            });
        }

        return $query;
    }
}
