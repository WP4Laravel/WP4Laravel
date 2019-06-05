<?php

Route::group([
    'prefix' => '{language}',
    'where' => ['language' => '(nl)']
], function () {
    // Activities
    Route::get('activities', 'Api\ActivityController@index')->name('activity_index');
    // Artworks
    Route::get('artworks/{id}', 'Api\ArtworkController@show')->name('artwork_show');
    // Exhibitions
    Route::get('exhibitions', 'Api\ExhibitionController@index')->name('exhibition_index');
    Route::get('exhibitions/{id}', 'Api\ExhibitionController@show')->name('exhibition_show');
    Route::get('exhibitions/{id}/download', 'Api\ExhibitionController@download')->name('exhibition_download');
    // Menulinks
    Route::get('menulinks', 'Api\MenulinkController@index')->name('menulink_show');
});
