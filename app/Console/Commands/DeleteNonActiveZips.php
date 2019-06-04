<?php

namespace App\Console\Commands;

use App\Models\Exhibition;
use App\Services\ZipResource;
use App\Traits\ResourceVersioning;
use Illuminate\Console\Command;

class DeleteNonActiveZips extends Command
{
    use ResourceVersioning;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'exhibitions:cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete non-active zip files of exhibitions';

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
        $records = Exhibition::published()->get();

        $this->info('Found ' . $records->count() . ' exhibitions');

        $active_zips = [];

        foreach ($records as $item) {
            $this->line($item->title);

            $resource = $this->resource('Api\Exhibition', $item);

            $zipper = new ZipResource($resource);

            if ($zipper->url()) {
                $path = $zipper->getZipPath();
                $active_zips[] = $path;
                $this->info('Current zip: ' . $path);
            }
        }

        $all_zips = \Storage::disk('s3')->files('downloads');

        foreach ($all_zips as $zip) {
            if (!in_array($zip, $active_zips)) {
                \Storage::disk('s3')->delete($zip);
                $this->info('Delete non-active zip: ' . $zip);
            }
        }
    }
}
