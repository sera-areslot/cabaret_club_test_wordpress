<?php
/**
 * Cast card (used on front page track and cast archive grid).
 *
 * @package lumiere
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$m    = lumiere_cast_meta();
$attr = lumiere_cast_attr_line( $m );
$tone = ( get_the_ID() % 5 ) + 1;
?>
<article class="cast-card">
	<a class="cast-card__link" href="<?php the_permalink(); ?>">
		<div class="cast-card__photo"<?php echo has_post_thumbnail() ? '' : ' data-tone="' . esc_attr( $tone ) . '"'; ?>>
			<?php if ( has_post_thumbnail() ) { the_post_thumbnail( 'lumiere-portrait' ); } ?>
		</div>
		<div class="cast-card__meta">
			<?php if ( ! empty( $m['catch'] ) ) : ?>
				<span class="cast-card__no"><?php echo esc_html( $m['catch'] ); ?></span>
			<?php endif; ?>
			<h3 class="cast-card__name"><?php the_title(); ?><?php if ( ! empty( $m['romaji'] ) ) { printf( '<em>%s</em>', esc_html( $m['romaji'] ) ); } ?></h3>
			<?php if ( $attr ) : ?>
				<p class="cast-card__attr"><?php echo esc_html( $attr ); ?></p>
			<?php endif; ?>
		</div>
	</a>
</article>
