<?php

//	Update the location of the uploads. Set to the public storage of wp4laravel

update_option('upload_path', WP_CONTENT_DIR . '/storage');
update_option('upload_url_path', '/storage');
