<?php

/**
 * Configuration for the Picture ViewComposer-class
 */
return [

    /**
     * URL-prefix to the WP uploads folder, e.g. if your uploads are served from
     * website.com/storage/uploads/2016/05/test.jpg, set '/storage/uploads/' here.
     *
     * Add a leading and trailing slash.
     */
    'uploads_path' => '/storage/',

    /**
     * The Picture class can reference crops from both S3 and local disk storage. The
     * default is local storage. Change this to true to use the S3 storage driver.
     */
    'use_aws_storage' => false,
];
