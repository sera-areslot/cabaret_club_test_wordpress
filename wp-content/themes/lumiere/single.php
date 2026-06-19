<?php
/**
 * Generic single (default posts).
 *
 * @package lumiere
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();

while ( have_posts() ) :
	the_post();
	?>
	<article class="article">
		<div class="article__meta">
			<span class="article__date"><?php echo esc_html( get_the_date( 'Y.m.d' ) ); ?></span>
		</div>
		<h1 class="article__title"><?php the_title(); ?></h1>
		<?php if ( has_post_thumbnail() ) : ?>
			<div class="article__thumb"><?php the_post_thumbnail( 'lumiere-wide' ); ?></div>
		<?php endif; ?>
		<div class="article__body"><?php the_content(); ?></div>
	</article>
	<?php
endwhile;

get_footer();
