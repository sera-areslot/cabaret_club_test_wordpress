<?php
/**
 * SEO / OGP / 構造化データ。
 *
 * @package lumiere
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * 現在ページのURL。
 *
 * @return string
 */
function lumiere_current_url() {
	if ( is_front_page() ) {
		return home_url( '/' );
	}
	if ( is_singular() ) {
		return get_permalink();
	}
	if ( is_post_type_archive() ) {
		$link = get_post_type_archive_link( get_query_var( 'post_type' ) );
		if ( $link ) {
			return $link;
		}
	}
	global $wp;
	return home_url( user_trailingslashit( isset( $wp->request ) ? $wp->request : '' ) );
}

/**
 * メタディスクリプション・canonical・OGP / Twitter カードを出力。
 */
add_action( 'wp_head', 'lumiere_head_meta', 5 );
function lumiere_head_meta() {
	$site = lumiere_site();

	if ( is_singular() ) {
		$raw  = has_excerpt() ? get_the_excerpt() : wp_strip_all_tags( get_post_field( 'post_content', get_the_ID() ) );
		$desc = trim( preg_replace( '/\s+/u', ' ', (string) $raw ) );
	} else {
		$desc = $site['hero_lead'];
	}
	if ( '' === $desc ) {
		$desc = $site['hero_lead'];
	}
	$desc  = mb_substr( $desc, 0, 120 );
	$title = wp_get_document_title();
	$url   = lumiere_current_url();

	$image = '';
	if ( is_singular() && has_post_thumbnail() ) {
		$image = get_the_post_thumbnail_url( null, 'lumiere-wide' );
	}
	if ( ! $image ) {
		$image = get_theme_file_uri( 'assets/og-default.png' );
	}

	echo "\n<meta name=\"description\" content=\"" . esc_attr( $desc ) . "\">\n";

	// canonical は singular ではコア（rel_canonical）が出力するため、それ以外で補う。
	if ( ! is_singular() ) {
		echo '<link rel="canonical" href="' . esc_url( $url ) . "\">\n";
	}

	echo '<meta property="og:type" content="' . ( is_singular() ? 'article' : 'website' ) . "\">\n";
	echo '<meta property="og:title" content="' . esc_attr( $title ) . "\">\n";
	echo '<meta property="og:description" content="' . esc_attr( $desc ) . "\">\n";
	echo '<meta property="og:url" content="' . esc_url( $url ) . "\">\n";
	echo '<meta property="og:site_name" content="' . esc_attr( get_bloginfo( 'name' ) ) . "\">\n";
	echo '<meta property="og:locale" content="ja_JP">' . "\n";
	if ( $image ) {
		echo '<meta property="og:image" content="' . esc_url( $image ) . "\">\n";
	}
	echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
	if ( $image ) {
		echo '<meta name="twitter:image" content="' . esc_url( $image ) . "\">\n";
	}
}

/**
 * トップページに NightClub の構造化データ（JSON-LD）を出力。
 */
add_action( 'wp_head', 'lumiere_jsonld', 20 );
function lumiere_jsonld() {
	if ( ! is_front_page() ) {
		return;
	}
	$site = lumiere_site();
	$data = array(
		'@context'     => 'https://schema.org',
		'@type'        => 'NightClub',
		'name'         => get_bloginfo( 'name' ),
		'description'  => $site['hero_lead'],
		'url'          => home_url( '/' ),
		'telephone'    => isset( $site['store']['電話'] ) ? $site['store']['電話'] : '',
		'image'        => get_theme_file_uri( 'assets/og-default.png' ),
		'address'      => array(
			'@type'           => 'PostalAddress',
			'addressCountry'  => 'JP',
			'addressRegion'   => '大阪府',
			'streetAddress'   => isset( $site['store']['住所'] ) ? $site['store']['住所'] : '',
		),
		'openingHours' => 'Mo-Sa 20:00-25:00',
	);
	echo "\n<script type=\"application/ld+json\">" . wp_json_encode( $data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . "</script>\n";
}
