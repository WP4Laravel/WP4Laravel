<?php

Route::group([
    'prefix' => '{language}',
    'where' => ['language' => '(nl)']
], function () {
    // Activities
    Route::get('activities', 'Api\ActivityController@index')->name('activity_index');
    // Artworks
    Route::get('artwork/{id}', 'Api\ArtworkController@show')->name('artwork_show');
    // Exhibitions
    Route::get('exhibitions', 'Api\ExhibitionController@index')->name('exhibition_index');
    Route::get('exhibition/download', 'Api\ExhibitionController@download')->name('exhibition_download');
    Route::get('exhibition/download/{id}', 'Api\ExhibitionController@download')->name('exhibition_download');
    Route::get('exhibition', 'Api\ExhibitionController@show')->name('exhibition_show');
    Route::get('exhibition/{id}', 'Api\ExhibitionController@show')->name('exhibition_show');
    // Menulinks
    Route::get('menulinks', 'Api\MenulinkController@index')->name('menulink_show');
});
