<?php
/**
 * サイト共通データ（店舗情報・料金・コンセプト等）。
 *
 * 既定値は lumiere_site_defaults() に定義し、管理画面「LUMIÈRE 設定」
 * （inc/settings.php）で保存された値で上書きします。さらに
 * `lumiere_site_data` フィルターでも上書き可能です。
 *
 * @package lumiere
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * 既定値。
 *
 * @return array
 */
function lumiere_site_defaults() {
	return array(
		'wordmark'     => 'LUMIÈRE',
		'area'         => '大阪・北新地',
		'hero_ja'      => '夜を、芸術に。',
		'hero_lead'    => '一夜の記憶を、最上の美と静けさで満たす。',

		'concept_lead' => '静謐な高揚。',
		'concept_text' => array(
			'きらびやかさではなく、静けさの中にこそ宿る品格を。設えのひとつひとつ、グラスの傾き、交わす言葉の余白まで。北新地の夜に、もうひとつの「特別」を仕立てました。',
			'派手さで魅せるのではなく、上質な時間そのもので満たす。それが LUMIÈRE のもてなしです。',
		),

		'system'       => array(
			array( 'name' => 'セット料金', 'note' => '60分', 'price' => 8000 ),
			array( 'name' => 'ご指名',     'note' => '1名',  'price' => 3000 ),
			array( 'name' => '同伴出勤',   'note' => '',      'price' => 5000 ),
			array( 'name' => 'VIP ルーム', 'note' => '1卓',  'price' => 20000 ),
		),
		'system_note'  => '表示は税・サービス料別の目安です。詳細は店舗までお問い合わせください。',

		'recruit_lead' => '北新地で、あなたらしい夜の物語を。',
		'recruit_text' => '未経験から活躍できる環境を整えています。体験入店・短期もお気軽にご相談ください。',

		'store'        => array(
			'店名'     => 'CLUB LUMIÈRE（仮）',
			'住所'     => '〒530-0002 大阪市北区曽根崎新地 1-0-00（仮）',
			'電話'     => '06-0000-0000',
			'営業時間' => '20:00 – 翌1:00',
			'定休日'   => '日曜・祝日',
		),

		'sns'          => array(
			'Instagram' => '#',
			'X'         => '#',
			'LINE'      => '#',
		),
	);
}

/**
 * 既定値に管理画面の保存値を反映したサイトデータ。
 *
 * @return array
 */
function lumiere_site() {
	$d = lumiere_site_defaults();
	$o = get_option( 'lumiere_settings', array() );

	if ( is_array( $o ) && ! empty( $o ) ) {
		// 単一テキスト項目。
		foreach ( array( 'wordmark', 'area', 'hero_ja', 'hero_lead', 'concept_lead', 'recruit_lead', 'recruit_text', 'system_note' ) as $k ) {
			if ( isset( $o[ $k ] ) && '' !== $o[ $k ] ) {
				$d[ $k ] = $o[ $k ];
			}
		}

		// 店舗情報。
		$store_map = array(
			'店名'     => 'store_name',
			'住所'     => 'store_address',
			'電話'     => 'store_tel',
			'営業時間' => 'store_hours',
			'定休日'   => 'store_holiday',
		);
		foreach ( $store_map as $label => $key ) {
			if ( isset( $o[ $key ] ) && '' !== $o[ $key ] ) {
				$d['store'][ $label ] = $o[ $key ];
			}
		}

		// SNS。
		$sns_map = array( 'Instagram' => 'sns_instagram', 'X' => 'sns_x', 'LINE' => 'sns_line' );
		foreach ( $sns_map as $label => $key ) {
			if ( isset( $o[ $key ] ) && '' !== $o[ $key ] ) {
				$d['sns'][ $label ] = $o[ $key ];
			}
		}

		// コンセプト本文（空行区切りで段落化）。
		if ( ! empty( $o['concept_text'] ) ) {
			$paras = preg_split( '/\n\s*\n/u', trim( $o['concept_text'] ) );
			$paras = array_values( array_filter( array_map( 'trim', $paras ) ) );
			if ( $paras ) {
				$d['concept_text'] = $paras;
			}
		}

		// 料金（1行＝「項目 | 補足 | 金額」）。
		if ( ! empty( $o['system'] ) ) {
			$rows = array();
			foreach ( preg_split( '/\r\n|\r|\n/', trim( $o['system'] ) ) as $line ) {
				$line = trim( $line );
				if ( '' === $line ) {
					continue;
				}
				$parts  = array_map( 'trim', explode( '|', $line ) );
				$rows[] = array(
					'name'  => isset( $parts[0] ) ? $parts[0] : '',
					'note'  => isset( $parts[1] ) ? $parts[1] : '',
					'price' => isset( $parts[2] ) ? (int) preg_replace( '/[^0-9]/', '', $parts[2] ) : 0,
				);
			}
			if ( $rows ) {
				$d['system'] = $rows;
			}
		}
	}

	return apply_filters( 'lumiere_site_data', $d );
}
