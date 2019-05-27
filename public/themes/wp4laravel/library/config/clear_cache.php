<?php


add_action('admin_bar_menu', function ($wp_admin_bar) {
    $args = array(
        'id' => 'clear-cache',
        'title' => 'Clear cache',
        'href' => '#',
        'meta' => array(
            'class' => 'custom-button-class'
        )
    );
    $wp_admin_bar->add_node($args);
}, 50);

add_action('admin_head', function () {
    ?>
    <script>

        jQuery(document).ready(function() {

			jQuery('#wp-admin-bar-clear-cache').on('click', function(e) {
                e.preventDefault();

				jQuery.ajax({
					method: "get",
					//url: "/wp-admin/admin-ajax.php"+query_params,
					url: "/api/cache",
					data: { action: 'clear_cache'}
				})
				.done(function( msg ) {
					alert(msg);
				});
			});
		});

    </script>
<?php
});
