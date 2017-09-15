<?php

namespace WP4Laravel\Middleware;

use Closure;
use Validator;
use Illuminate\Validation\Rule;

class Preview
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //  Validates the data wich matches a preview post
        $validator = Validator::make($request->all(), [
              'preview' => 'required|string|size:4',
              'p' => ['required_without:page_id', 'numeric'],
              'post_type' =>  ['required_without:page_id', Rule::in(array_keys(config('corcel.preview')))],
              'page_id' => ['required_without:p', 'numeric'],
        ]);

        //  If the validator fails this will be a normal request
        //  So return to the next middleware
        if ($validator->fails()) {
            return $next($request);
        }

        //  Get the post type to redirect to right route
        $post_type = $request->post_type;
        $post_type = !$post_type && $request->page_id ? 'page' : $post_type;
        $post_type = $post_type ?: 'post';

        //  If no route defined in the config, abort the request
        if (!$route = config('corcel.preview.'.$post_type)) {
            abort(404);
        }

        //  Redirect to the show route of the post type, with __preview as unique slug
        return redirect(route($route, '__preview').'?'.http_build_query($request->all()));
    }
}
