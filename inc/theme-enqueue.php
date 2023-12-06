<?php

//Enqueue scripts and styles.
add_action('wp_enqueue_scripts', 'misfit_enqueue_scripts', 100);

function misfit_enqueue_scripts()
{

    wp_enqueue_style('misfit-style', get_stylesheet_uri(), array(), filemtime(THEME_DIRECTORY . '/style.css'), true);

    wp_enqueue_style('misfit-theme', THEME_URI . '/assets/css/theme.css', array(), filemtime(THEME_DIRECTORY . '/assets/css/theme.css'), false);
    wp_enqueue_script('misfit-theme', THEME_URI . '/assets/js/theme.js', array('jquery', 'owl'), filemtime(THEME_DIRECTORY . '/assets/js/theme.js'), true);

    if (is_user_logged_in()) {
        wp_enqueue_style('misfit-member', THEME_URI . '/assets/css/theme-member.css', array(),
            filemtime(THEME_DIRECTORY . '/assets/css/theme-member.css'), false);
    }

    //LIBS
    wp_register_script('owl', 'https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js', array('jquery'), '2.3.4', true);
    wp_register_style('owl', 'https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.css', array('misfit-theme'), '2.3.4', false);

    //add select2.css
	wp_register_style( 'sel2css', "https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/css/select2.min.css" );
	wp_register_script( 'sel2js', "https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/js/select2.min.js", array( 'jquery' ), '', true );

	//enqueue
	wp_enqueue_style( 'sel2css' );
	wp_enqueue_script( 'sel2js' );
	

    wp_register_style('fancybox', 'https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css');
    wp_register_script('fancybox', 'https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js', array('jquery'), '3.5.7',
        true);

    //Loc
    wp_localize_script('misfit-theme', 'misfit', ['ajaxurl' => admin_url('admin-ajax.php')]);


//	if( in_array( $post_type, array('team','weakness_templates') ) || is_front_page() ){
//		wp_enqueue_style( 'owl');
//		wp_enqueue_script( 'owl');
//	}
//
//	if(is_post_type_archive('movement')) {
//		wp_enqueue_style( 'fancybox' );
//		wp_enqueue_script( 'fancybox' );
//	}
//
//	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
//		wp_enqueue_script( 'comment-reply' );
//	}

//	if(is_page('profile')){
//		wp_enqueue_script( 'enspire-profile');
//	}
//
//	if(is_page('profile-edit')){
//		wp_enqueue_script( 'enspire-profile-edit');
//	}


//	global $post;
//
//	if (! $post) {
//		return;
//	}

}


/**
 * Admins Enqueue
 */
add_action('admin_enqueue_scripts', 'misfit_enqueue_admin', 99999);
function misfit_enqueue_admin()
{
    wp_enqueue_style('misfit-admin', THEME_URI . '/assets/css/theme-admin.css', array(),
        filemtime(THEME_DIRECTORY . '/assets/css/theme-admin.css'), false);
    wp_enqueue_script('misfit-gutenberg', THEME_URI . '/assets/js/theme-gutenberg.js', array(),
        filemtime(THEME_DIRECTORY . '/assets/js/theme-gutenberg.js'), true);
}


/**
 * Global Enqueue (Admin && Front)
 */
add_action('enqueue_block_assets', 'misfit_enqueue_global');
function misfit_enqueue_global()
{
    wp_enqueue_style('misfit-gutenberg', THEME_URI . '/assets/css/theme-gutenberg.css', array(),
        filemtime(THEME_DIRECTORY . '/assets/css/theme-gutenberg.css'), false);
    wp_enqueue_style('misfit-icons', THEME_URI . '/assets/fontello/css/fontello.css', array(),
        filemtime(THEME_DIRECTORY . '/assets/fontello/css/fontello.css'), false);
}


/**
 * Inline CSS
 */
add_action('wp_head', function () {
    $arrContextOptions = stream_context_create(array(
        "ssl" => array(
            "verify_peer" => false,
            "verify_peer_name" => false,
        ),
    ));

    $css = file_get_contents(THEME_URI . '/assets/css/theme-inline.css', false, $arrContextOptions);

    if ($css) {
        echo '<style type="text/css">';
        echo $css;
        echo '</style>';
    }
}, 100);