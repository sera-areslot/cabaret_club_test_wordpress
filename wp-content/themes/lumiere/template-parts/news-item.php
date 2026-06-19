<?php
/**
 * News list item (used on front page and news archive).
 *
 * @package lumiere
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$terms = get_the_terms( get_the_ID(), 'news_category' );
$cat   = ( ! is_wp_error( $terms ) && ! empty( $terms ) ) ? $terms[0]->name : 'News';
?>
<li class="news__item">
	<a href="<?php the_permalink(); ?>">
		<span class="news__date"><?php echo esc_html( get_the_date( 'Y.m.d' ) ); ?></span>
		<span class="news__cat"><?php echo esc_html( $cat ); ?></span>
		<span class="news__title"><?php the_title(); ?></span>
	</a>
</li>
