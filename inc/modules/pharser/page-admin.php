<?php

function es_parser_squarespace_page_callback() {
    $last_sync = time(); //get_option( 'centro_parser_last_run' );
    $last_sync_date = date('Y-m-d H:i', $last_sync);
    ?>
    <div class="wrap">
        <h1>Parser Squarespace</h1>
        <button class="button button-primary btn-run-sync"> Run Sync </button>
        <div class="logs">
            <h3 style="display: inline-block">Logs</h3>  <?= $last_sync ? sprintf('<span>Last sync %s</span>', $last_sync_date) : '' ?>
            <div class="logs__wrap">
                <pre><?= es_get_parser_log_content($last_sync_date); ?></pre>
            </div>
        </div>
    </div>
    <script>
        (function ($){
            $( document ).ready(function() {

                $('.btn-run-sync').click(function (){
                    const button = $(this)
                    const button_text = button.text();

                    $.ajax({
                        url: '/wp-admin/admin-ajax.php',
                        data: {
                          'action': 'es_run_sync_squarespace'
                        },
                        type: 'POST',
                        beforeSend: function () {
                            button.text('Loading...')
                        },
                        success: function (response) {
                            window.location.reload();
                        },
                        complete: function () {
                            button.text(button_text)
                        },
                    })
                });


            });
        })(jQuery);
    </script>
    <?php
}

// Hook into the admin menu to create the sub-menu page
function es_add_tools_parser_squarespace_submenu_page() {
    add_submenu_page(
        'tools.php',                  // Parent slug (main Tools page)
        'Parser Squarespace',              // Page title
        'Parser Squarespace',              // Menu title
        'manage_options',             // Capability required to access the page
        'parser-squarespace',  // Menu slug (unique identifier)
        'es_parser_squarespace_page_callback' // Callback function to render the page
    );
}

add_action('admin_menu', 'es_add_tools_parser_squarespace_submenu_page');

function es_ajax_es_run_sync_squarespace(){
 		update_option( PARSER['slug'] . '_feed_hash', '');
 		es_insert_parsed_posts();
}

add_action( 'wp_ajax_es_run_sync_squarespace', 'es_ajax_es_run_sync_squarespace' );