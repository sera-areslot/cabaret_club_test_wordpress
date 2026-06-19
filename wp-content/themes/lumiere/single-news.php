<?php
/**
 * Single news article.
 *
 * @package lumiere
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();

while ( have_posts() ) :
	the_post();
	$terms = get_the_terms( get_the_ID(), 'news_category' );
	$cat   = ( ! is_wp_error( $terms ) && ! empty( $terms ) ) ? $terms[0]->name : '';
	?>
	<article class="article">
		<div class="article__meta">
			<span class="article__date"><?php echo esc_html( get_the_date( 'Y.m.d' ) ); ?></span>
			<?php if ( $cat ) : ?><span class="article__cat"><?php echo esc_html( $cat ); ?></span><?php endif; ?>
		</div>
		<h1 class="article__title"><?php the_title(); ?></h1>
		<?php if ( has_post_thumbnail() ) : ?>
			<div class="article__thumb"><?php the_post_thumbnail( 'lumiere-wide' ); ?></div>
		<?php endif; ?>
		<div class="article__body"><?php the_content(); ?></div>
		<div style="margin-top:2.5rem;">
			<a class="link-underline" href="<?php echo esc_url( get_post_type_archive_link( 'news' ) ); ?>">お知らせ一覧へ戻る</a>
		</div>
	</article>
	<?php
endwhile;

get_footer();
