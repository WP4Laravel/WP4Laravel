<?php

/*
|--------------------------------------------------------------------------
| API Routes with versioning
|--------------------------------------------------------------------------
*/

Route::group([
    'middleware' => ['api', 'api.version:1.0'],
    'prefix' => 'api/v1.0',
], function () {
    require base_path('routes/api_v1.0.php');
});
