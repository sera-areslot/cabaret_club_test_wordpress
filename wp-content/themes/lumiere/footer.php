<?php
/**
 * Footer.
 *
 * @package lumiere
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$site     = lumiere_site();
$on_front = is_front_page();
$anchor   = function ( $id ) use ( $on_front ) {
	return $on_front ? '#' . $id : esc_url( home_url( '/#' . $id ) );
};
$foot_nav = array( 'concept' => 'Concept', 'cast' => 'Cast', 'system' => 'System', 'news' => 'News', 'recruit' => 'Recruit', 'access' => 'Access' );
?>
</main>

<footer class="footer">
	<div class="container footer__inner">
		<a class="footer__brand" href="<?php echo esc_url( home_url( '/' ) ); ?>">CLUB LUMIÈRE</a>
		<nav class="footer__nav" aria-label="フッターメニュー">
			<?php foreach ( $foot_nav as $id => $label ) : ?>
				<a href="<?php echo $anchor( $id ); // phpcs:ignore ?>" <?php echo $on_front ? 'data-scroll' : ''; ?>><?php echo esc_html( $label ); ?></a>
			<?php endforeach; ?>
		</nav>
		<p class="footer__sns">
			<?php foreach ( $site['sns'] as $name => $url ) { echo lumiere_sns_link( $name, $url ); } // phpcs:ignore WordPress.Security.EscapeOutput ?>
		</p>
		<p class="footer__copy">&copy; <?php echo esc_html( date( 'Y' ) ); ?> CLUB LUMIÈRE</p>
	</div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
