<?php
/**
 * LUMIÈRE theme functions.
 *
 * @package lumiere
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'LUMIERE_THEME_VER', '0.4.0' );

require_once get_template_directory() . '/inc/site-data.php';
require_once get_template_directory() . '/inc/icons.php';
require_once get_template_directory() . '/inc/settings.php';
require_once get_template_directory() . '/inc/cleanup.php';
require_once get_template_directory() . '/inc/seo.php';

/**
 * テーマの基本サポート。
 */
add_action( 'after_setup_theme', function () {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'style', 'script', 'caption', 'gallery', 'navigation-widgets' ) );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'responsive-embeds' );

	// キャスト写真は 3:4 縦長を基本に。
	add_image_size( 'lumiere-portrait', 720, 960, true );
	add_image_size( 'lumiere-wide', 1280, 720, true );

	register_nav_menus( array( 'primary' => 'メインメニュー' ) );
} );

/**
 * フォント・スタイル・スクリプトの読み込み。
 */
add_action( 'wp_enqueue_scripts', function () {
	// Google Fonts（明朝主体 + 欧文セリフ）。
	wp_enqueue_style(
		'lumiere-fonts',
		'https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;1,400&family=Noto+Serif+JP:wght@300;400;500;600&family=Shippori+Mincho:wght@500;600;700;800&family=Zen+Kaku+Gothic+New:wght@300;400;500&display=swap',
		array(),
		null
	);

	wp_enqueue_style( 'lumiere-style', get_stylesheet_uri(), array( 'lumiere-fonts' ), LUMIERE_THEME_VER );
	wp_enqueue_style( 'lumiere-theme', get_theme_file_uri( 'assets/css/theme.css' ), array( 'lumiere-style' ), LUMIERE_THEME_VER );

	// アニメーションライブラリ（CDN）。defer で読み込みを最適化。
	$script_args = array( 'in_footer' => true, 'strategy' => 'defer' );
	wp_enqueue_script( 'gsap', 'https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js', array(), null, $script_args );
	wp_enqueue_script( 'gsap-scrolltrigger', 'https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/ScrollTrigger.min.js', array( 'gsap' ), null, $script_args );
	wp_enqueue_script( 'lenis', 'https://cdn.jsdelivr.net/npm/lenis@1.1.14/dist/lenis.min.js', array(), null, $script_args );
	wp_enqueue_script( 'lumiere-main', get_theme_file_uri( 'assets/js/main.js' ), array( 'gsap', 'gsap-scrolltrigger', 'lenis' ), LUMIERE_THEME_VER, $script_args );
} );

/**
 * 抜粋の調整。
 */
add_filter( 'excerpt_more', function () {
	return '…';
} );
add_filter( 'excerpt_length', function () {
	return 60;
} );

/**
 * キャストのメタをまとめて取得。
 *
 * @param int|null $id 投稿ID。
 * @return array
 */
function lumiere_cast_meta( $id = null ) {
	$id   = $id ? $id : get_the_ID();
	$keys = array( 'romaji', 'catch', 'height', 'blood', 'birth_month', 'hobby', 'instagram', 'x', 'featured' );
	$out  = array();
	foreach ( $keys as $k ) {
		$out[ $k ] = get_post_meta( $id, '_lumiere_' . $k, true );
	}
	return $out;
}

/**
 * キャストの属性表示（例: T165 ／ 12月 ／ A型）。
 *
 * @param array $m lumiere_cast_meta() の戻り値。
 * @return string
 */
function lumiere_cast_attr_line( $m ) {
	$parts = array();
	if ( ! empty( $m['height'] ) ) {
		$parts[] = 'T' . $m['height'];
	}
	if ( ! empty( $m['birth_month'] ) ) {
		$parts[] = $m['birth_month'] . '月';
	}
	if ( ! empty( $m['blood'] ) ) {
		$parts[] = $m['blood'] . '型';
	}
	return implode( ' ／ ', $parts );
}

/**
 * 求人のメタをまとめて取得。
 *
 * @param int|null $id 投稿ID。
 * @return array
 */
function lumiere_recruit_meta( $id = null ) {
	$id   = $id ? $id : get_the_ID();
	$keys = array( 'employment', 'salary', 'hours', 'holiday' );
	$out  = array();
	foreach ( $keys as $k ) {
		$out[ $k ] = get_post_meta( $id, '_lumiere_' . $k, true );
	}
	return $out;
}

/**
 * アーカイブの並び順・表示件数を調整。
 */
add_action( 'pre_get_posts', function ( $query ) {
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}
	if ( $query->is_post_type_archive( 'cast' ) ) {
		$query->set( 'orderby', 'menu_order' );
		$query->set( 'order', 'ASC' );
		$query->set( 'posts_per_page', 24 );
	} elseif ( $query->is_post_type_archive( 'recruit' ) ) {
		$query->set( 'orderby', 'menu_order' );
		$query->set( 'order', 'ASC' );
	} elseif ( $query->is_post_type_archive( 'news' ) ) {
		$query->set( 'posts_per_page', 12 );
	}
} );
