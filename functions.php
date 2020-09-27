<?php

/* THEME SETUP
------------------------------------------------ */

if ( ! function_exists( 'davis_setup' ) ) :
	function davis_setup() {
		
		// Automatic feed
		add_theme_support( 'automatic-feed-links' );
		
		// Set content-width
		global $content_width;
		if ( ! isset( $content_width ) ) $content_width = 620;
		
		// Post thumbnails
		add_theme_support( 'post-thumbnails' );
		set_post_thumbnail_size( 620, 9999 );
		
		// Title tag
		add_theme_support( 'title-tag' );
		
		// Post formats
		add_theme_support( 'post-formats', array( 'aside' ) );
		
		// Register nav menu
		register_nav_menu( 'primary-menu', __( 'Primary Menu', 'davis' ) );
		
		// Make the theme translation ready
		load_theme_textdomain( 'davis', get_template_directory() . '/languages' );
		
	}
	add_action( 'after_setup_theme', 'davis_setup' );
endif;


/* ENQUEUE STYLES
------------------------------------------------ */

if ( ! function_exists( 'davis_load_style' ) ) :
	function davis_load_style() {

		$theme_version = wp_get_theme( 'davis' )->get( 'Version' );

		wp_register_style( 'davis_fonts', '//fonts.googleapis.com/css?family=PT+Serif:400,700,400italic,700italic' );
		wp_enqueue_style( 'davis_style', get_stylesheet_uri(), array( 'davis_fonts' ) );

	}
	add_action( 'wp_enqueue_scripts', 'davis_load_style' );
endif;


/* ENQUEUE COMMENT-REPLY.JS
------------------------------------------------ */

if ( ! function_exists( 'davis_load_scripts' ) ) :
	function davis_load_scripts() {

		$theme_version = wp_get_theme( 'davis' )->get( 'Version' );

		wp_enqueue_script( 'davis_construct', get_template_directory_uri() . '/assets/js/construct.js', array( 'jquery' ), $theme_version, true );

		if ( ( ! is_admin() ) && is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}

	}
	add_action( 'wp_enqueue_scripts', 'davis_load_scripts' );
endif;


/* ---------------------------------------------------------------------------------------------
   SPECIFY GUTENBERG SUPPORT
------------------------------------------------------------------------------------------------ */

if ( ! function_exists( 'davis_add_block_editor_features' ) ) :
	function davis_add_block_editor_features() {

		/* Gutenberg Palette --------------------------------------- */

		add_theme_support( 'editor-color-palette', array(
			array(
				'name' 	=> __( 'Black', 'davis' ),
				'slug' 	=> 'black',
				'color' => '#000',
			),
			array(
				'name' 	=> __( 'White', 'davis' ),
				'slug' 	=> 'white',
				'color' => '#fff',
			),
		) );

	}
	add_action( 'after_setup_theme', 'davis_add_block_editor_features' );
endif;

/* MICROFORMATS V2 SUPPORT
------------------------------------------------ */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function body_classes( $classes ) {
	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
		$classes[] = 'h-feed';
	} else {
		if ( 'page' !== get_post_type() ) {
				$classes[] = 'hentry';
				$classes[] = 'h-entry';
		}
	}
	return $classes;
}
add_filter( 'body_class', 'body_classes' );

/**
 * Adds custom classes to the array of post classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function post_classes( $classes ) {
	$classes = array_diff( $classes, array( 'hentry' ) );
	if ( ! is_singular() ) {
		if ( 'page' !== get_post_type() ) {
			// Adds a class for microformats v2
			$classes[] = 'h-entry';
			// add hentry to the same tag as h-entry
			$classes[] = 'hentry';
		}
	}
	return $classes;
}

add_filter( 'post_class', 'post_classes' );

/**
 * Wraps the_content in e-content
 */
function davis_the_content( $content ) {
	if ( is_feed() ) {
		return $content;
	}
	$wrap = '<div class="entry-content e-content">';
	if ( empty( $content ) ) {
		return $content;
	}
	return $wrap . $content . '</div>';
}
add_filter( 'the_content', 'davis_the_content', 1 );