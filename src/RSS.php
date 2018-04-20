<?php

namespace WP4Laravel;

use Corcel\Model\Post;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Request;

class RSS
{
    /**
     * Generate a ATOM feed from a list of posts
     * @param  Collection $posts
     * @return View
     */
    public function feed(Collection $posts, string $title) : Response
    {
        // Sanity check: all items must be posts
        foreach ($posts as $post) {
            if (!$post instanceof Post) {
                throw \InvalidArgumentException('RSS feed can only be made out of \Corcel\Model\Post-objects');
            }
        }

        // Create feed
        $feed = App::make('feed');
        $feed->title = $title;
        $feed->link = Request::url();
        $feed->setDateFormat('datetime');
        $feed->pubdate = $posts[0]->created_at;

        // Add the posts in descending order of creation
        $posts->sortByDesc('post_date')->each(function ($post) use ($feed) {
            $feed->add(
                $post->title,
                config('app.name'),
                $post->url,
                $post->post_date,
                Wpautop::format($post->excerpt),
                Wpautop::format($post->content)
            );
        });

        return $feed->render('atom');
    }
}
