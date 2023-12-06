<?php
add_filter( 'esa_blocks_disabled', function(){
	return ['es-user-badge', 'es-post', 'es-post-slider', 'es-testimonial', 'es-team', 'es-resources-list', 'es-tab'];
}, 100 );



//add_filter( 'get_the_archive_title', 'misfit_get_the_archive_title' );
add_filter( 'page_template', 'misfit_single_page_template', 100 );
add_filter( 'the_content', 'responsive_embeds', 100 );


add_action('after_setup_theme', 'remove_admin_bar', 999);
function remove_admin_bar() {
    if (!current_user_can('administrator') && !is_admin()) {
        show_admin_bar(false);
    }
}

	


/**
 * Default page template by slug doesn't work
 * This problem is only for 'profile' slug
 * Fix this with template filter
 *
 * @param $single_template
 *
 * @return string
 */
function misfit_single_page_template( $single_template ) {
	if ( is_page( 'profile' ) ) {
		$single_template = get_stylesheet_directory() . '/page-profile.php';
	}

	return $single_template;
}



/**
 * Filter for adding wrappers around embedded objects
 */
function responsive_embeds( $content ) {
	$content = preg_replace( "/<object/Si", '<div class="embed-container is-style-ratio-16-9"><object', $content );
	$content = preg_replace( "/<\/object>/Si", '</object></div>', $content );

	/**
	 * Added iframe filtering, iframes are bad.
	 */
	$content = preg_replace( "/<iframe.+?src=\"(.+?)\"/Si",
		'<div class="embed-container is-style-ratio-16-9"><iframe src="\1" frameborder="0" allowfullscreen>', $content );
	$content = preg_replace( "/<\/iframe>/Si", '</iframe></div>', $content );

	return $content;
}

