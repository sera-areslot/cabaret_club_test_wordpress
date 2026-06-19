<?php
/**
 * LUMIÈRE theme functions (Phase 1 minimal scaffold).
 *
 * Phase 2 でテンプレート（front-page / archive-cast / single-cast など）と
 * design-preview のCSS/JS（GSAP・Lenis）を本実装します。
 *
 * @package lumiere
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'after_setup_theme', function () {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'style', 'script', 'navigation-widgets' ) );
	add_theme_support( 'automatic-feed-links' );
	register_nav_menus( array( 'primary' => 'メインメニュー' ) );
} );

add_action( 'wp_enqueue_scripts', function () {
	wp_enqueue_style( 'lumiere-style', get_stylesheet_uri(), array(), '0.1.0' );
} );
