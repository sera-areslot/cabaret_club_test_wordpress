<?php
/**
 * Header: head + fixed nav + fullscreen overlay menu + loader.
 *
 * @package lumiere
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$site     = lumiere_site();
$on_front = is_front_page();

/** Anchor helper: smooth-scroll on front page, jump to home#section elsewhere. */
$anchor = function ( $id ) use ( $on_front ) {
	return $on_front ? '#' . $id : esc_url( home_url( '/#' . $id ) );
};

$nav = array(
	'concept' => array( 'en' => 'Concept', 'ja' => 'コンセプト' ),
	'cast'    => array( 'en' => 'Cast',    'ja' => 'キャスト' ),
	'system'  => array( 'en' => 'System',  'ja' => '料金システム' ),
	'news'    => array( 'en' => 'News',    'ja' => 'お知らせ' ),
	'recruit' => array( 'en' => 'Recruit', 'ja' => '採用情報' ),
	'access'  => array( 'en' => 'Access',  'ja' => 'アクセス' ),
);
?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<script>document.documentElement.className = 'js';</script>
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div class="loader" id="loader" aria-hidden="true">
	<div class="loader__inner">
		<span class="loader__mark"><?php echo esc_html( $site['wordmark'] ); ?></span>
		<span class="loader__line"></span>
	</div>
</div>

<header class="nav" id="nav">
	<a class="nav__brand" href="<?php echo esc_url( home_url( '/' ) ); ?>">CLUB<span>LUMIÈRE</span></a>
	<button class="nav__toggle" id="navToggle" aria-label="メニューを開く" aria-expanded="false" aria-controls="overlay">
		<span class="nav__toggle-label" data-label-open="MENU" data-label-close="CLOSE">MENU</span>
		<span class="nav__toggle-lines" aria-hidden="true"><i></i><i></i></span>
	</button>
</header>

<div class="overlay" id="overlay" aria-hidden="true">
	<nav class="overlay__nav" aria-label="メインメニュー">
		<ul>
			<?php $i = 1; foreach ( $nav as $id => $labels ) : ?>
				<li>
					<a href="<?php echo $anchor( $id ); // phpcs:ignore ?>" <?php echo $on_front ? 'data-scroll' : ''; ?>>
						<span class="overlay__en"><?php echo esc_html( $labels['en'] ); ?></span>
						<em><?php echo esc_html( $labels['ja'] ); ?></em>
						<span class="overlay__no"><?php echo esc_html( sprintf( '%02d', $i++ ) ); ?></span>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>
	</nav>
	<div class="overlay__foot">
		<p><?php echo esc_html( $site['store']['住所'] ); ?></p>
		<p>OPEN <?php echo esc_html( $site['store']['営業時間'] ); ?> ／ <?php echo esc_html( $site['store']['定休日'] ); ?>定休</p>
		<p class="overlay__links">
			<?php foreach ( $site['sns'] as $name => $url ) : ?>
				<a href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( $name ); ?></a>
			<?php endforeach; ?>
		</p>
	</div>
</div>
