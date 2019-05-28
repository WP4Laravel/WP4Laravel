<?php

namespace App\Traits;

trait ResourceVersioning
{
    /**
     * @param string $resourceName
     * @param array ...$args
     *
     * @return object
     */
    public function resource($resourceName, ...$args)
    {
        // Get's the request's api version, or the latest if undefined
        $v = config('app.api_version', config('app.api_latest'));

        // dd($v);

        $className = $this->getResourceClassname(
            $resourceName,
            str_replace('.', '_', $v)
        );

        // dd($className);

        if (!class_exists($className)) {
            $className = $this->getResourceClassname(
                $resourceName,
                config('app.api_latest')
            );
        }

        // dd($className);

        $class = new \ReflectionClass($className);
        return $class->newInstanceArgs($args);
    }

    /**
     * Parse Api\Resource for
     * App\Http\Resources\Api\v{$v}\Resource
     *
     * @param string $className
     * @param string $v
     *
     * @return string
     */
    protected function getResourceClassname($className, $v)
    {
        $re = '/.*\\\\(.*)/';
        return 'App\Http\Resources\\' .
            preg_replace($re, 'Api\v' . $v . '\\\$1', $className);
    }

    /**
     * Get current api_version from config
     *
     * @return string
     */
    public function getVersion()
    {
        return config('app.api_version', config('app.api_latest'));
    }
}
