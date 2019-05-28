<?php

namespace App\Services;

use ZipArchive;
use Storage;
use Illuminate\Http\Resources\Json\Resource;

/**
 * Zip all Media files of a Resource
 */
class ZipResource
{
    /**
     * The resource to handle
     *
     * @var Resource
     */
    protected $resource;

    /**
     * Holds the parsed array from the resource
     *
     * @var array
     */
    protected $arrayData;

    /**
     * The storage disk to get and put files
     *
     * @var string
     */
    protected $disk = 's3';

    /**
     * The identifier or folder where the media is located within the storage
     *
     * @var string
     */
    protected $file_identifier = 'storage';

    /**
     * The name of the temporary zip archive on the local disk
     *
     * @var string
     */
    protected $tempArchiveName = 'archive.zip';

    /**
     * Create new instance for the given resource
     *
     * @param Resource $resource
     */
    public function __construct(Resource $resource)
    {
        $this->resource = $resource;
    }

    /**
     * Get the URL of the Archive on the Storage
     *
     * @return void
     */
    public function url()
    {
        return $this->exists() ? $this->storage()->url($this->getZipPath()) : null;
    }

    /**
     * Get the size of the Archive on the Storage
     *
     * @return void
     */
    public function size()
    {
        $size = $this->storage()->size($this->getZipPath());
        return $this->calcFileSize($size);
    }

    /**
     * Get the size of the temp Archive on the local Storage
     *
     * @return void
     */
    public function tempArchiveSize()
    {
        $size = filesize(Storage::path($this->tempArchiveName));
        return $this->calcFileSize($size);
    }

    /**
     * Convert bytes to biggest output option
     *
     * @return string
     */
    private function calcFileSize($size, $precision = 2)
    {
        $base = log($size, 1024);
        $suffixes = ['B', 'KB', 'MB', 'GB', 'TB'];

        return round(pow(1024, $base - floor($base)), $precision) . ' ' . $suffixes[floor($base)];
    }

    /**
     * Get the storage disk, send a string to switch to other disk
     *
     * @param string $disk
     * @return Storage
     */
    public function storage($disk = null)
    {
        if ($disk) {
            $this->disk = $disk;
        }

        return Storage::disk($this->disk);
    }

    /**
     * Parse an array from the resource and save it to a local property
     *
     * @return array
     */
    public function data()
    {
        if ($this->arrayData) {
            return $this->arrayData;
        }

        return $this->arrayData = $this->resource->toResponse(request())->getData(true);
    }

    /**
     * Get all media files which are in the array
     *
     * @return Collection
     */
    public function files()
    {
        $data = $this->data();

        return collect(array_flatten($data))
        ->filter(function ($item) {
            return str_contains($item, '/' . $this->file_identifier . '/');
        })
        ->unique()
        ->map(function ($item) {
            return str_replace($this->storage()->url($this->file_identifier), $this->file_identifier, $item);
        });
    }

    /**
     * Archive all files with the json
     *
     * @return ZipResource
     */
    public function zip()
    {
        $files = $this->files();

        $zip = new ZipArchive;

        if ($zip->open(Storage::path($this->tempArchiveName), ZipArchive::CREATE) === true) {
            $zip->addFromString('data.json', str_replace(trim(json_encode(config('filesystems.disks.s3.url')), '"'), '', json_encode($this->data())));

            $zip->addEmptyDir($this->file_identifier);
            foreach ($files as $item) {
                $content = \Storage::disk('s3')->get($item);

                $zip->addFromString($item, $content);
            }
        }

        $zip->close();

        return $this;
    }

    /**
     * Get the data of the archive file from the local storage
     *
     * @return void
     */
    public function zipped()
    {
        return Storage::get($this->tempArchiveName);
    }

    /**
     * Save the archived file to the remote storage
     *
     * @return ZipResource;
     */
    public function save()
    {
        $path = $this->getZipPath();
        $data = $this->zipped();

        $this->storage()->put($path, $data, 'public');

        Storage::delete($this->tempArchiveName);

        return $this;
    }

    /**
     * Download the local archive file
     *
     * @return Response
     */
    public function download()
    {
        return Storage::download($this->tempArchiveName);
    }

    /**
     * Remove the current archive from the storage to create a new instance
     *
     * @return ZipResource
     */
    public function fresh()
    {
        $this->storage()->delete($this->getZipPath());

        return $this;
    }

    /**
     * Get the path of the archive on the storage
     *
     * @return string
     */
    public function getZipPath()
    {
        return collect([
            'downloads',
            $this->getZipFilename(),
        ])->implode('/');
    }

    /**
     * Create the filename of the archive on the storage
     *
     * @return void
     */
    protected function getZipFilename()
    {
        $data = $this->data();

        return collect([
            $data['id'],
            '-',
            sha1($data['id'] . '_' . $data['last_modified_date']),
            '.zip'
        ])->implode('');
    }

    /**
     * Does the archive exists on the storage
     *
     * @return void
     */
    protected function exists()
    {
        return $this->storage()->exists($this->getZipPath());
    }
}
