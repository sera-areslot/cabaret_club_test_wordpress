<?php
/**
 * Recruit archive — list of positions.
 *
 * @package lumiere
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();
?>
<div class="page-hero">
	<div class="page-hero__bg"></div>
	<p class="page-hero__en">Recruit</p>
	<p class="page-hero__ja">採用情報</p>
</div>

<div class="recruit-list">
	<?php
	if ( have_posts() ) :
		while ( have_posts() ) :
			the_post();
			$rm    = lumiere_recruit_meta();
			$parts = array();
			if ( ! empty( $rm['employment'] ) ) {
				$parts[] = $rm['employment'];
			}
			if ( ! empty( $rm['salary'] ) ) {
				$parts[] = $rm['salary'];
			}
			?>
			<a class="recruit-list__item" href="<?php the_permalink(); ?>">
				<h2><?php the_title(); ?></h2>
				<?php if ( $parts ) : ?><p class="meta"><?php echo esc_html( implode( '　/　', $parts ) ); ?></p><?php endif; ?>
			</a>
			<?php
		endwhile;
	else :
		?>
		<p class="empty-note">募集情報は準備中です。</p>
	<?php endif; ?>
</div>

<?php get_footer(); ?>
