<?php

Route::group([
    'prefix' => '{language}',
    'where' => ['language' => '(nl)']
], function () {
    // Menulinks
    Route::get('menulinks', 'Api\MenulinkController@index')->name('menulink_show');
    // Exhibitions
    Route::get('exhibitions', 'Api\ExhibitionController@index')->name('exhibition_index');
    Route::get('exhibitions/{id}', 'Api\ExhibitionController@show')->name('exhibition_show');
    Route::get('exhibitions/{id}/download', 'Api\ExhibitionController@download')->name('exhibition_download');
    // Artworks
    Route::get('artworks/{id}', 'Api\ArtworkController@show')->name('artwork_show');
});
