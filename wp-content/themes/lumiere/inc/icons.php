<?php
/**
 * インラインSVGアイコン。外部アイコンフォントを使わず軽量に保つ。
 *
 * すべて currentColor 連動なので、適用先の文字色・ホバー色をそのまま継承する。
 *
 * @package lumiere
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * アイコン1個分のインラインSVGを返す（自前の静的マークアップ）。
 *
 * @param string $name  アイコン名。
 * @param string $class 追加クラス。
 * @return string
 */
function lumiere_icon( $name, $class = '' ) {
	$paths = array(
		'instagram'  => '<rect x="3" y="3" width="18" height="18" rx="5" fill="none" stroke="currentColor" stroke-width="1.7"/><circle cx="12" cy="12" r="4" fill="none" stroke="currentColor" stroke-width="1.7"/><circle cx="17.2" cy="6.8" r="1.2" fill="currentColor"/>',
		'x'          => '<path fill="currentColor" d="M18.244 2.25h3.308l-7.227 8.26L23 21.75h-6.615l-5.18-6.776L5.28 21.75H1.97l7.73-8.835L1 2.25h6.78l4.683 6.213L18.244 2.25Zm-1.16 17.52h1.833L7.084 4.126H5.117L17.084 19.77Z"/>',
		'line'       => '<path fill="currentColor" d="M20 4H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h3v3.2c0 .51.6.79 1 .47l4.5-3.67H20a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2Z"/>',
		'arrow'      => '<path fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" d="M4 12h14M12 6l6 6-6 6"/>',
		'arrow-left' => '<path fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" d="M20 12H6M12 6l-6 6 6 6"/>',
		'pin'        => '<path fill="none" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round" d="M12 21c4.5-4.2 6.5-7.4 6.5-10.5a6.5 6.5 0 1 0-13 0C5.5 13.6 7.5 16.8 12 21Z"/><circle cx="12" cy="10.5" r="2.4" fill="none" stroke="currentColor" stroke-width="1.6"/>',
	);

	if ( empty( $paths[ $name ] ) ) {
		return '';
	}

	$cls = 'lum-icon' . ( $class ? ' ' . $class : '' );

	return sprintf(
		'<svg class="%s" viewBox="0 0 24 24" width="1em" height="1em" fill="none" aria-hidden="true" focusable="false">%s</svg>',
		esc_attr( $cls ),
		$paths[ $name ] // 自前の静的SVG。
	);
}

/**
 * SNS名からアイコンのスラッグへ変換。未対応なら空文字。
 *
 * @param string $name SNS名（Instagram / X / LINE など）。
 * @return string
 */
function lumiere_sns_slug( $name ) {
	$slug = strtolower( trim( (string) $name ) );
	if ( 'twitter' === $slug ) {
		$slug = 'x';
	}
	return in_array( $slug, array( 'instagram', 'x', 'line' ), true ) ? $slug : '';
}

/**
 * SNSリンク（アイコン＋ラベル）のマークアップを返す。
 *
 * @param string $name SNS名。
 * @param string $url  URL。
 * @return string
 */
function lumiere_sns_link( $name, $url ) {
	$slug = lumiere_sns_slug( $name );
	$icon = $slug ? lumiere_icon( $slug ) : '';

	return sprintf(
		'<a class="sns-link" href="%s" target="_blank" rel="noopener">%s<span class="sns-link__label">%s</span></a>',
		esc_url( $url ),
		$icon, // 自前の静的SVG。
		esc_html( $name )
	);
}
