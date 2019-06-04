<?php

namespace App\Console\Commands;

use App\Models\Exhibition;
use App\Services\ZipResource;
use App\Traits\ResourceVersioning;
use Illuminate\Console\Command;

class CreateExhibitionDownloads extends Command
{
    use ResourceVersioning;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'exhibitions:zip';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create zip files of exhibitions';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        ini_set('memory_limit', '512M');

        $records = Exhibition::published()->get();

        $this->info('Found ' . $records->count() . ' exhibitions');

        foreach ($records as $item) {
            $this->line($item->title);

            if (!$item->meta->zip_size) {
                $this->warn('Update dates for correct caching');
                $item->post_modified = \Carbon\Carbon::now();
                $item->post_modified_gmt = \Carbon\Carbon::now('UTC');
                $item->save();
            }

            $resource = $this->resource('Api\Exhibition', $item);

            $zipper = new ZipResource($resource);

            if (!$item->meta->zip_size || !$zipper->url()) {
                $this->warn('Zip does not exist, creating one');
                $zipper->zip();

                $this->warn('Add zip size to database as meta value zip_size');
                $item->saveMeta('zip_size', $zipper->tempArchiveSize());

                $this->warn('Save newly created zip');
                $zipper->save();
            }

            $this->info('Zip available on: ' . $zipper->url());
        }
    }
}
