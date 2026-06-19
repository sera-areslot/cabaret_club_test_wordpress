<?php
/**
 * Plugin Name:  LUMIÈRE Core
 * Description:  CLUB LUMIÈRE のコンテンツ基盤。キャスト・お知らせ・求人のカスタム投稿タイプとカスタムフィールドを提供します。
 * Version:      0.1.0
 * Author:       CLUB LUMIÈRE
 * Text Domain:  lumiere
 *
 * デザイン（テーマ）とは独立した「データ構造」を担うプラグインです。
 * テーマを差し替えてもコンテンツ定義は保持されます。
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'LUMIERE_CORE_DIR', plugin_dir_path( __FILE__ ) );

require_once LUMIERE_CORE_DIR . 'includes/post-types.php';
require_once LUMIERE_CORE_DIR . 'includes/meta-fields.php';

/**
 * 有効化時に投稿タイプを登録してリライトルールを更新する。
 */
register_activation_hook( __FILE__, function () {
	lumiere_register_post_types();
	flush_rewrite_rules();
} );

register_deactivation_hook( __FILE__, 'flush_rewrite_rules' );
