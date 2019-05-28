<?php

Route::group([
    'prefix' => '{language}',
    'where' => ['language' => '(nl)']
], function () {
    Route::get('exhibitions', 'Api\ExhibitionController@index')->name('exhibition_index');
    Route::get('exhibitions/{id}', 'Api\ExhibitionController@show')->name('exhibition_show');
    Route::get('exhibitions/{id}/download', 'Api\ExhibitionController@download')->name('exhibition_download');
});
