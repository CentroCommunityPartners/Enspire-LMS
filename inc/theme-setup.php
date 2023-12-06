<?php
add_action( 'after_setup_theme', 'misfit_setup' );

function misfit_setup() {

	load_theme_textdomain( 'misfit', get_template_directory() . '/languages' );

	add_theme_support( 'align-wide' );

	// Editor Color Palette
	add_theme_support( 'editor-color-palette', array(
		array(
			'name'  => 'Black',
			'slug'  => 'black',
			'color' => '#262626',
		),
		array(
			'name'  => 'Green',
			'slug'  => 'green',
			'color' => '#B0BF3C',
		),
		array(
			'name'  => 'Red',
			'slug'  => 'red',
			'color' => '##E72D3A',
		),
		array(
			'name'  => 'Light Gray',
			'slug'  => 'lightgray',
			'color' => '#979797',
		),
		array(
			'name'  => 'Ash white',
			'slug'  => 'ash_white',
			'color' => '#f5f5f5',
		),
		array(
			'name'  => 'White',
			'slug'  => 'white',
			'color' => '#ffffff',
		),
	) );


	if ( function_exists( 'acf_add_options_page' ) ) {
		acf_add_options_page( 'Theme Options' );
	}

	$GLOBALS['content_width'] = apply_filters( 'ensp_content_width', 940 );
}

add_action( 'init', 'ensp_add_gutenberg_paterns' );
function ensp_add_gutenberg_paterns() {

	if ( ! function_exists( 'register_block_pattern' ) ) {
		return;
	}

	$patterns = glob( THEME_DIRECTORY . "/inc/patterns/*/*.php" );

	if ( $patterns ) {
		foreach ( $patterns as $file ) {
			include $file;
			$pattern_id = $pattern['id'];
			unset( $pattern['id'] );
			register_block_pattern( 'enspire/' . $pattern_id, $pattern );
		}
	}

	//Categories
	register_block_pattern_category( 'call2action', array( 'label' => 'Call to action' ) );
	register_block_pattern_category( 'form', array( 'label' => 'Forms' ) );
	register_block_pattern_category( 'hero', array( 'label' => 'Hero' ) );
	register_block_pattern_category( 'slider', array( 'label' => 'Sliders' ) );
	register_block_pattern_category( 'template', array( 'label' => 'Templates' ) );
	register_block_pattern_category( 'template_part', array( 'label' => 'Template Parts' ) );
	register_block_pattern_category( 'media_text', array( 'label' => 'Text and Media' ) );
	register_block_pattern_category( 'text_columns', array( 'label' => 'Text columns' ) );

	unregister_block_pattern( 'core/two-images' );
	unregister_block_pattern( 'core/two-buttons' );
	unregister_block_pattern( 'core/three-buttons' );
	unregister_block_pattern( 'core/heading-paragraph' );
	unregister_block_pattern( 'core/large-header' );
	unregister_block_pattern( 'core/large-header-button' );
	unregister_block_pattern( 'core/quote' );
	unregister_block_pattern( 'core/text-three-columns-buttons' );
	unregister_block_pattern( 'core/text-two-columns-with-images' );
	unregister_block_pattern( 'core/text-two-columns' );
}

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus(
		array(
			'menu-footer'         => esc_html__( 'Footer', 'enspire' ),
			'menu-footer-utility' => esc_html__( 'Footer Utility', 'enspire' ),
		)
	);

	/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function load_widget() {

	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer', 'enspire' ),
			'id'            => 'footer',
			'description'   => esc_html__( 'Add widgets here.', 'enspire' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}


add_action( 'widgets_init', 'load_widget' );