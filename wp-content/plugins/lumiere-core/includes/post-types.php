<?php
/**
 * カスタム投稿タイプ・タクソノミーの登録
 *
 * @package lumiere-core
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'init', 'lumiere_register_post_types' );

/**
 * キャスト / お知らせ / 求人 を登録する。
 */
function lumiere_register_post_types() {

	// キャスト
	register_post_type( 'cast', array(
		'labels'        => lumiere_pt_labels( 'キャスト', 'キャスト' ),
		'public'        => true,
		'has_archive'   => true,
		'menu_position' => 5,
		'menu_icon'     => 'dashicons-groups',
		'supports'      => array( 'title', 'editor', 'thumbnail', 'page-attributes', 'excerpt' ),
		'rewrite'       => array( 'slug' => 'cast' ),
		'show_in_rest'  => true,
	) );

	// お知らせ
	register_post_type( 'news', array(
		'labels'        => lumiere_pt_labels( 'お知らせ', 'お知らせ' ),
		'public'        => true,
		'has_archive'   => true,
		'menu_position' => 6,
		'menu_icon'     => 'dashicons-megaphone',
		'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
		'rewrite'       => array( 'slug' => 'news' ),
		'show_in_rest'  => true,
	) );

	// 求人
	register_post_type( 'recruit', array(
		'labels'        => lumiere_pt_labels( '求人', '求人' ),
		'public'        => true,
		'has_archive'   => true,
		'menu_position' => 7,
		'menu_icon'     => 'dashicons-id',
		'supports'      => array( 'title', 'editor', 'thumbnail', 'page-attributes' ),
		'rewrite'       => array( 'slug' => 'recruit' ),
		'show_in_rest'  => true,
	) );

	// お知らせカテゴリー
	register_taxonomy( 'news_category', 'news', array(
		'labels'       => array(
			'name'          => 'お知らせカテゴリー',
			'singular_name' => 'カテゴリー',
			'add_new_item'  => 'カテゴリーを追加',
		),
		'hierarchical' => true,
		'show_in_rest' => true,
		'rewrite'      => array( 'slug' => 'news-category' ),
	) );
}

/**
 * 投稿タイプ用ラベルを生成するヘルパー。
 *
 * @param string $singular 単数形ラベル。
 * @param string $plural   複数形ラベル。
 * @return array
 */
function lumiere_pt_labels( $singular, $plural ) {
	return array(
		'name'               => $plural,
		'singular_name'      => $singular,
		'menu_name'          => $plural,
		'add_new'            => '新規追加',
		'add_new_item'       => $singular . 'を追加',
		'edit_item'          => $singular . 'を編集',
		'new_item'           => '新規' . $singular,
		'view_item'          => $singular . 'を表示',
		'search_items'       => $singular . 'を検索',
		'all_items'          => $plural . '一覧',
		'not_found'          => $singular . 'が見つかりません',
		'not_found_in_trash' => 'ゴミ箱に' . $singular . 'はありません',
	);
}
