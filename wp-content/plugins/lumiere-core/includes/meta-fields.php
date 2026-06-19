<?php
/**
 * カスタムフィールド（メタボックス）の定義・表示・保存
 *
 * ACF などの外部プラグインに依存せず、WordPress 標準機能のみで実装。
 * 管理画面に入力欄を表示し、フロント（テーマ）からは get_post_meta() で取得します。
 *
 * @package lumiere-core
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * 誕生月の選択肢（1〜12月）。
 *
 * @return array
 */
function lumiere_months() {
	$months = array( '' => '—' );
	for ( $i = 1; $i <= 12; $i++ ) {
		$months[ (string) $i ] = $i . '月';
	}
	return $months;
}

/**
 * 投稿タイプごとのフィールド定義。
 *
 * @return array
 */
function lumiere_meta_config() {
	return array(
		'cast'    => array(
			'title'  => 'キャスト情報',
			'fields' => array(
				array( 'key' => 'romaji',      'label' => 'ローマ字表記',         'type' => 'text' ),
				array( 'key' => 'catch',       'label' => 'キャッチコピー',       'type' => 'text' ),
				array( 'key' => 'height',      'label' => '身長 (cm)',            'type' => 'text' ),
				array( 'key' => 'blood',       'label' => '血液型',               'type' => 'select', 'options' => array( '' => '—', 'A' => 'A', 'B' => 'B', 'O' => 'O', 'AB' => 'AB' ) ),
				array( 'key' => 'birth_month', 'label' => '誕生月',               'type' => 'select', 'options' => lumiere_months() ),
				array( 'key' => 'hobby',       'label' => '趣味・特技',           'type' => 'text' ),
				array( 'key' => 'instagram',   'label' => 'Instagram URL',        'type' => 'url' ),
				array( 'key' => 'x',           'label' => 'X (Twitter) URL',      'type' => 'url' ),
				array( 'key' => 'featured',    'label' => 'おすすめキャストに表示する', 'type' => 'checkbox' ),
			),
		),
		'recruit' => array(
			'title'  => '募集要項',
			'fields' => array(
				array( 'key' => 'employment', 'label' => '雇用形態',     'type' => 'text' ),
				array( 'key' => 'salary',     'label' => '給与',         'type' => 'text' ),
				array( 'key' => 'hours',      'label' => '勤務時間',     'type' => 'text' ),
				array( 'key' => 'holiday',    'label' => '休日・待遇',   'type' => 'text' ),
			),
		),
	);
}

/**
 * REST API でも扱えるようメタを登録。
 */
add_action( 'init', 'lumiere_register_meta' );
function lumiere_register_meta() {
	foreach ( lumiere_meta_config() as $post_type => $conf ) {
		foreach ( $conf['fields'] as $field ) {
			register_post_meta( $post_type, '_lumiere_' . $field['key'], array(
				'type'              => 'string',
				'single'            => true,
				'show_in_rest'      => true,
				'sanitize_callback' => ( 'url' === $field['type'] ) ? 'esc_url_raw' : 'sanitize_text_field',
				'auth_callback'     => function () {
					return current_user_can( 'edit_posts' );
				},
			) );
		}
	}
}

/**
 * メタボックスを追加。
 */
add_action( 'add_meta_boxes', 'lumiere_add_meta_boxes' );
function lumiere_add_meta_boxes() {
	foreach ( lumiere_meta_config() as $post_type => $conf ) {
		add_meta_box(
			'lumiere_' . $post_type . '_meta',
			$conf['title'],
			'lumiere_render_meta_box',
			$post_type,
			'normal',
			'high',
			$conf
		);
	}
}

/**
 * メタボックスの描画。
 *
 * @param WP_Post $post 投稿。
 * @param array   $box  add_meta_box の引数（args に設定を含む）。
 */
function lumiere_render_meta_box( $post, $box ) {
	$conf = $box['args'];
	wp_nonce_field( 'lumiere_meta_save', 'lumiere_meta_nonce' );

	echo '<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:16px;margin-top:8px;">';
	foreach ( $conf['fields'] as $field ) {
		$value = get_post_meta( $post->ID, '_lumiere_' . $field['key'], true );
		$id    = 'lumiere_' . $field['key'];

		echo '<p style="margin:0;">';
		if ( 'checkbox' === $field['type'] ) {
			printf(
				'<label><input type="checkbox" name="%1$s" value="1" %2$s> %3$s</label>',
				esc_attr( $id ),
				checked( $value, '1', false ),
				esc_html( $field['label'] )
			);
		} else {
			printf(
				'<label for="%1$s" style="display:block;font-weight:600;margin-bottom:4px;">%2$s</label>',
				esc_attr( $id ),
				esc_html( $field['label'] )
			);

			if ( 'select' === $field['type'] ) {
				echo '<select id="' . esc_attr( $id ) . '" name="' . esc_attr( $id ) . '" style="width:100%;">';
				foreach ( $field['options'] as $opt_value => $opt_label ) {
					printf(
						'<option value="%1$s" %2$s>%3$s</option>',
						esc_attr( $opt_value ),
						selected( $value, $opt_value, false ),
						esc_html( $opt_label )
					);
				}
				echo '</select>';
			} else {
				$input_type = ( 'url' === $field['type'] ) ? 'url' : 'text';
				printf(
					'<input type="%1$s" id="%2$s" name="%2$s" value="%3$s" style="width:100%%;">',
					esc_attr( $input_type ),
					esc_attr( $id ),
					esc_attr( $value )
				);
			}
		}
		echo '</p>';
	}
	echo '</div>';
}

/**
 * メタの保存。
 *
 * @param int     $post_id 投稿ID。
 * @param WP_Post $post    投稿。
 */
add_action( 'save_post', 'lumiere_save_meta', 10, 2 );
function lumiere_save_meta( $post_id, $post ) {
	if ( ! isset( $_POST['lumiere_meta_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['lumiere_meta_nonce'] ) ), 'lumiere_meta_save' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$config = lumiere_meta_config();
	if ( ! isset( $config[ $post->post_type ] ) ) {
		return;
	}

	foreach ( $config[ $post->post_type ]['fields'] as $field ) {
		$input_name = 'lumiere_' . $field['key'];
		$meta_key   = '_lumiere_' . $field['key'];
		$raw        = isset( $_POST[ $input_name ] ) ? wp_unslash( $_POST[ $input_name ] ) : '';

		switch ( $field['type'] ) {
			case 'checkbox':
				update_post_meta( $post_id, $meta_key, isset( $_POST[ $input_name ] ) ? '1' : '' );
				break;
			case 'select':
				$clean = array_key_exists( $raw, $field['options'] ) ? $raw : '';
				update_post_meta( $post_id, $meta_key, $clean );
				break;
			case 'url':
				update_post_meta( $post_id, $meta_key, esc_url_raw( $raw ) );
				break;
			default:
				update_post_meta( $post_id, $meta_key, sanitize_text_field( $raw ) );
		}
	}
}

/**
 * キャスト一覧に「ローマ字」「おすすめ」列を追加。
 */
add_filter( 'manage_cast_posts_columns', function ( $columns ) {
	$new = array();
	foreach ( $columns as $key => $label ) {
		$new[ $key ] = $label;
		if ( 'title' === $key ) {
			$new['lumiere_romaji']   = 'ローマ字';
			$new['lumiere_featured'] = 'おすすめ';
		}
	}
	return $new;
} );

add_action( 'manage_cast_posts_custom_column', function ( $column, $post_id ) {
	if ( 'lumiere_romaji' === $column ) {
		echo esc_html( get_post_meta( $post_id, '_lumiere_romaji', true ) );
	}
	if ( 'lumiere_featured' === $column ) {
		echo '1' === get_post_meta( $post_id, '_lumiere_featured', true ) ? '★' : '—';
	}
}, 10, 2 );
