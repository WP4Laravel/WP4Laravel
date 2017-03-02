<?php

namespace WP4Laravel;

class Site
{
    protected $data = [];


    public function __construct()
    {
        $this->data = config('site');
    }

    public function model($model)
    {
        foreach ($this->data as $key=>$value) {
            if ($result = $model->$key) {
                $this->data[$key] = $model->$key;
            }
        }

        $this->data['model'] = $model;

        return $this;
    }


    public function get($key, $default='')
    {
        //	Check on dot notation
        if ($val = array_get($this->data, $key)) {
            return is_array($val) ? collect($val) : $val;
        }

        if (empty($this->data[$key])) {
            if ($default && isset($this->data[$default])) {
                return is_array($this->data[$default]) ? collect($this->data[$default]) : $this->data[$default];
            }
            if (!empty($default)) {
                return is_array($default) ? collect($default) : $default;
            }

            if (isset($this->data[$key])) {
                return is_array($this->data[$key]) ? collect($this->data[$key]) : $this->data[$key];
            }

            return null;
        }

        return is_array($this->data[$key]) ? collect($this->data[$key]) : $this->data[$key];
    }


    public function set($item, $value=null)
    {
        if (!is_array($item)) {
            if (str_contains($item, ".")) {
                array_set($this->data, $item, $value);

                return $this;
            }

            $item = [$item=>$value];
        }

        foreach ($item as $key=>$value) {
            if (!is_string($key)) {
                continue;
            }

            $this->data[$key] = $value;
        }

        return $this;
    }


    public function append($item, $value=null)
    {
        if (!is_array($item)) {
            if (str_contains($item, ".")) {
                $current = array_get($this->data, $item);
                $current[] = $value;
                array_set($this->data, $item, $current);

                return $this;
            }

            $item = [$item=>$value];
        }

        foreach ($item as $key=>$value) {
            if (!is_string($key)) {
                continue;
            }
            if (!is_array($this->data[$key])) {
                continue;
            }

            $this->data[$key][] = $value;
        }

        return $this;
    }
}
