<?php
/**
 * 開発用サンプルデータ投入スクリプト（WP-CLI 専用）。
 *
 *   docker compose run --rm wpcli wp eval-file wp-content/plugins/lumiere-core/dev-seed.php
 *
 * 通常の読み込みでは実行されません（WP_CLI のときのみ動作）。
 *
 * @package lumiere-core
 */

if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) {
	return;
}

// 既にキャストがあれば二重投入しない。
$existing = get_posts( array( 'post_type' => 'cast', 'numberposts' => 1, 'fields' => 'ids', 'post_status' => 'any' ) );
if ( ! empty( $existing ) ) {
	WP_CLI::log( '既存のサンプルが見つかったため投入をスキップしました。' );
	return;
}

/**
 * キャストを1件作成。
 *
 * @param string $title 源氏名。
 * @param array  $meta  メタ（profile は本文に入る）。
 * @param int    $order 表示順。
 */
function lumiere_seed_cast( $title, $meta, $order ) {
	$post_id = wp_insert_post( array(
		'post_type'    => 'cast',
		'post_status'  => 'publish',
		'post_title'   => $title,
		'post_content' => isset( $meta['profile'] ) ? $meta['profile'] : '',
		'menu_order'   => $order,
	) );
	foreach ( $meta as $key => $value ) {
		if ( 'profile' === $key ) {
			continue;
		}
		update_post_meta( $post_id, '_lumiere_' . $key, $value );
	}
	return $post_id;
}

lumiere_seed_cast( '月城 さき', array(
	'romaji' => 'Saki', 'catch' => '静かな気品', 'height' => '165', 'blood' => 'A',
	'birth_month' => '12', 'hobby' => 'ワイン・読書', 'featured' => '1',
	'profile' => '落ち着いた語り口と上品な所作で、ゆったりとした時間をお届けします。',
), 1 );

lumiere_seed_cast( '花宮 れい', array(
	'romaji' => 'Rei', 'catch' => '華やかな笑顔', 'height' => '168', 'blood' => 'O',
	'birth_month' => '7', 'hobby' => 'ダンス・旅行', 'featured' => '1',
	'profile' => '明るい笑顔と気配りで、初めての方も安心してお過ごしいただけます。',
), 2 );

lumiere_seed_cast( '綾瀬 みや', array(
	'romaji' => 'Miya', 'catch' => '凛とした佇まい', 'height' => '160', 'blood' => 'B',
	'birth_month' => '3', 'hobby' => '茶道・美術鑑賞', 'featured' => '',
	'profile' => '凛とした佇まいの中に、ふとした優しさを。会話を大切にいたします。',
), 3 );

// お知らせカテゴリー
$cat_ids = array();
foreach ( array( 'Event' => 'イベント', 'Info' => 'お知らせ', 'Cast' => 'キャスト' ) as $slug => $name ) {
	$term = term_exists( $name, 'news_category' );
	if ( ! $term ) {
		$term = wp_insert_term( $name, 'news_category' );
	}
	if ( ! is_wp_error( $term ) ) {
		$cat_ids[ $slug ] = (int) ( is_array( $term ) ? $term['term_id'] : $term );
	}
}

/**
 * お知らせを1件作成。
 */
function lumiere_seed_news( $title, $content, $term_id ) {
	$post_id = wp_insert_post( array(
		'post_type'    => 'news',
		'post_status'  => 'publish',
		'post_title'   => $title,
		'post_content' => $content,
	) );
	if ( $term_id ) {
		wp_set_object_terms( $post_id, array( $term_id ), 'news_category' );
	}
	return $post_id;
}

lumiere_seed_news( '夏季スペシャルイベント開催のご案内', '今年の夏も特別な夜をご用意いたします。詳細は店舗までお問い合わせください。', isset( $cat_ids['Event'] ) ? $cat_ids['Event'] : 0 );
lumiere_seed_news( '新キャストが入店いたしました', '新たなキャストが仲間入りいたしました。ぜひ会いにいらしてください。', isset( $cat_ids['Cast'] ) ? $cat_ids['Cast'] : 0 );

// 求人
$recruit_id = wp_insert_post( array(
	'post_type'    => 'recruit',
	'post_status'  => 'publish',
	'post_title'   => 'フロアキャスト募集',
	'post_content' => '未経験から活躍できる環境です。体験入店・短期もご相談ください。',
	'menu_order'   => 1,
) );
update_post_meta( $recruit_id, '_lumiere_employment', 'アルバイト / 正社員' );
update_post_meta( $recruit_id, '_lumiere_salary', '時給 3,000円〜（経験・能力により優遇）' );
update_post_meta( $recruit_id, '_lumiere_hours', '20:00 〜 翌1:00（応相談）' );
update_post_meta( $recruit_id, '_lumiere_holiday', '日曜・週休応相談 / 送迎あり' );

WP_CLI::success( 'サンプルデータ（キャスト3・お知らせ2・求人1）を投入しました。' );
