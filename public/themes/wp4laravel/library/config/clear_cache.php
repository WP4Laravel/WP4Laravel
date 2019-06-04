<?php

add_action('admin_bar_menu', function ($wp_admin_bar) {
    $args = [
        'id' => 'clear-cache',
        'title' => 'Clear cache',
        'href' => '#',
        'meta' => [
            'class' => 'custom-button-class'
        ]
    ];
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
                    url: "/api/cache",
                    data: {
                        action: 'clear_cache'
                    }
                })
                .done(function(msg) {
                    alert(msg);
                });
        });
    });
</script>
<?php
});
