<?php
/**
 * 管理画面「LUMIÈRE 設定」ページ。
 * 店舗情報・ヒーロー・コンセプト・料金・採用・SNS を編集可能にする。
 *
 * @package lumiere
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'admin_init', function () {
	register_setting( 'lumiere_settings_group', 'lumiere_settings', array(
		'type'              => 'array',
		'sanitize_callback' => 'lumiere_sanitize_settings',
		'default'           => array(),
	) );
} );

add_action( 'admin_menu', function () {
	add_menu_page(
		'LUMIÈRE 設定',
		'LUMIÈRE 設定',
		'manage_options',
		'lumiere-settings',
		'lumiere_render_settings_page',
		'dashicons-admin-customizer',
		59
	);
} );

/**
 * 入力値のサニタイズ。
 *
 * @param array $input 入力。
 * @return array
 */
function lumiere_sanitize_settings( $input ) {
	$out = array();
	if ( ! is_array( $input ) ) {
		return $out;
	}

	$text_keys = array( 'wordmark', 'area', 'hero_ja', 'hero_lead', 'concept_lead', 'recruit_lead', 'system_note', 'store_name', 'store_address', 'store_tel', 'store_hours', 'store_holiday' );
	foreach ( $text_keys as $k ) {
		if ( isset( $input[ $k ] ) ) {
			$out[ $k ] = sanitize_text_field( $input[ $k ] );
		}
	}

	foreach ( array( 'concept_text', 'system', 'recruit_text' ) as $k ) {
		if ( isset( $input[ $k ] ) ) {
			$out[ $k ] = sanitize_textarea_field( $input[ $k ] );
		}
	}

	foreach ( array( 'sns_instagram', 'sns_x', 'sns_line' ) as $k ) {
		if ( isset( $input[ $k ] ) ) {
			$out[ $k ] = esc_url_raw( trim( $input[ $k ] ) );
		}
	}

	return $out;
}

/**
 * 設定ページの描画。
 */
function lumiere_render_settings_page() {
	$o = get_option( 'lumiere_settings', array() );
	$d = lumiere_site_defaults();

	// 既定値をプレースホルダー文字列に整形。
	$def_concept = implode( "\n\n", $d['concept_text'] );
	$def_system  = '';
	foreach ( $d['system'] as $r ) {
		$def_system .= $r['name'] . ' | ' . $r['note'] . ' | ' . $r['price'] . "\n";
	}
	$def_system = trim( $def_system );

	$text_row = function ( $key, $label, $placeholder, $type = 'text' ) use ( $o ) {
		$value = isset( $o[ $key ] ) ? $o[ $key ] : '';
		printf(
			'<tr><th scope="row"><label for="ls_%1$s">%2$s</label></th><td><input type="%5$s" id="ls_%1$s" name="lumiere_settings[%1$s]" value="%3$s" placeholder="%4$s" class="regular-text" style="width:34rem;max-width:100%%;"></td></tr>',
			esc_attr( $key ),
			esc_html( $label ),
			esc_attr( $value ),
			esc_attr( $placeholder ),
			esc_attr( $type )
		);
	};

	$area_row = function ( $key, $label, $placeholder, $desc = '' ) use ( $o ) {
		$value = isset( $o[ $key ] ) ? $o[ $key ] : '';
		printf(
			'<tr><th scope="row"><label for="ls_%1$s">%2$s</label></th><td><textarea id="ls_%1$s" name="lumiere_settings[%1$s]" rows="5" placeholder="%4$s" class="large-text">%3$s</textarea>%5$s</td></tr>',
			esc_attr( $key ),
			esc_html( $label ),
			esc_textarea( $value ),
			esc_attr( $placeholder ),
			$desc ? '<p class="description">' . esc_html( $desc ) . '</p>' : ''
		);
	};
	?>
	<div class="wrap">
		<h1>LUMIÈRE 設定</h1>
		<p>サイトの店舗情報・料金・SNS などを編集できます。<strong>空欄の場合は初期値（プレースホルダー表示）</strong>が使われます。</p>
		<form method="post" action="options.php">
			<?php settings_fields( 'lumiere_settings_group' ); ?>

			<h2 class="title">店舗情報</h2>
			<table class="form-table" role="presentation">
				<?php
				$text_row( 'store_name', '店名', $d['store']['店名'] );
				$text_row( 'store_address', '住所', $d['store']['住所'] );
				$text_row( 'store_tel', '電話番号', $d['store']['電話'] );
				$text_row( 'store_hours', '営業時間', $d['store']['営業時間'] );
				$text_row( 'store_holiday', '定休日', $d['store']['定休日'] );
				?>
			</table>

			<h2 class="title">ヒーロー / トップ</h2>
			<table class="form-table" role="presentation">
				<?php
				$text_row( 'wordmark', 'ワードマーク（欧文）', $d['wordmark'] );
				$text_row( 'area', 'エリア表記', $d['area'] );
				$text_row( 'hero_ja', '和文キャッチ', $d['hero_ja'] );
				$text_row( 'hero_lead', 'リード文', $d['hero_lead'] );
				?>
			</table>

			<h2 class="title">コンセプト</h2>
			<table class="form-table" role="presentation">
				<?php
				$text_row( 'concept_lead', '見出し', $d['concept_lead'] );
				$area_row( 'concept_text', '本文', $def_concept, '段落は空行で区切ってください。' );
				?>
			</table>

			<h2 class="title">料金システム</h2>
			<table class="form-table" role="presentation">
				<?php
				$area_row( 'system', '料金（1行＝「項目 | 補足 | 金額」）', $def_system, '例: セット料金 | 60分 | 8000 ／ 金額は数字のみ。補足が無ければ 項目 | | 金額 と入力。' );
				$text_row( 'system_note', '注記', $d['system_note'] );
				?>
			</table>

			<h2 class="title">採用</h2>
			<table class="form-table" role="presentation">
				<?php
				$text_row( 'recruit_lead', '見出し', $d['recruit_lead'] );
				$area_row( 'recruit_text', '本文', $d['recruit_text'] );
				?>
			</table>

			<h2 class="title">SNS（URL）</h2>
			<table class="form-table" role="presentation">
				<?php
				$text_row( 'sns_instagram', 'Instagram', 'https://instagram.com/...', 'url' );
				$text_row( 'sns_x', 'X (Twitter)', 'https://x.com/...', 'url' );
				$text_row( 'sns_line', 'LINE', 'https://lin.ee/...', 'url' );
				?>
			</table>

			<?php submit_button( '保存' ); ?>
		</form>
	</div>
	<?php
}
