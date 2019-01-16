<?php

namespace WP4Laravel\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class WPURLReplace extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wp:urlreplace {url}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update hardcoded urls of other environments to the current';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $result = DB::table("wp_posts")
            ->update(['post_content' => DB::raw("replace(post_content, '" . $this->argument('url') . "', '" . env('APP_URL') . "')")]);

        if ($result) {
            $this->info("Replaced {$result} items in wp_posts");
        } else {
            $this->error("No results found in wp_posts");
        }

        $result = DB::table("wp_postmeta")
            ->update(['meta_value' => DB::raw("replace(meta_value, '" . $this->argument('url') . "', '" . env('APP_URL') . "')")]);

        if ($result) {
            $this->info("Replaced {$result} items in wp_postmeta");
        } else {
            $this->error("No results found in wp_postmeta");
        }

        $result = DB::table("wp_options")
            ->update(['option_value' => DB::raw("replace(option_value, '" . $this->argument('url') . "', '" . env('APP_URL') . "')")]);

        if ($result) {
            $this->info("Replaced {$result} items in wp_options");
        } else {
            $this->error("No results found in wp_options");
        }
    }
}
