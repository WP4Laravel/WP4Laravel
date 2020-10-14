<?php

namespace WP4Laravel\Yoast\Redirects;

use Corcel\Model\Option;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;

/**
 * Translates requests into redirects, if an applicable redirect is defined in
 * Yoast Premium.
 */
class Service
{
    /**
     * Returns the given request with either a Redirect or null
     */
    public function handle(Request $request): ?RedirectResponse
    {
        $path = $request->path();
        $match = $this->match($path);

        if (!$match) {
            return null;
        }

        return redirect($match['url'], $match['type']);
    }


    /**
     * Matches a given path against redirects
     */
    private function match(string $path): ?array
    {
        // Escape the path to be able to handle (legacy) urls
        $path = addslashes($path);

        $redirects = $this->redirects();
        return $redirects[$path] ?? null;
    }

    /**
     * Primes and returns a filtered and cleaned list of redirects
     */
    private function redirects(): array
    {
        $redirects = Cache::remember('wp4laravel_yoast_redirects', 1440, function () {
            $optionField = Option::where('option_name', '=', 'wpseo-premium-redirects-base')->first();
            $filtered = [];
            if ($optionField !== null) {
                foreach (unserialize($optionField['option_value'], ['allowed_classes' => false]) as $item) {
                    $filtered[addslashes($item['origin'])] = $item;
                }
            }
            return $filtered;
        });

        return $redirects;
    }
}
