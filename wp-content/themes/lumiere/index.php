<?php
/**
 * Generic fallback (blog index / search / taxonomy など).
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
	<p class="page-hero__en"><?php echo is_search() ? 'Search' : 'Archive'; ?></p>
	<p class="page-hero__ja"><?php echo esc_html( is_search() ? sprintf( '「%s」の検索結果', get_search_query() ) : wp_strip_all_tags( get_the_archive_title() ) ); ?></p>
</div>

<div class="content">
	<?php if ( have_posts() ) : ?>
		<ul class="news__list">
			<?php
			while ( have_posts() ) :
				the_post();
				?>
				<li class="news__item">
					<a href="<?php the_permalink(); ?>">
						<span class="news__date"><?php echo esc_html( get_the_date( 'Y.m.d' ) ); ?></span>
						<span class="news__cat">&nbsp;</span>
						<span class="news__title"><?php the_title(); ?></span>
					</a>
				</li>
			<?php endwhile; ?>
		</ul>
	<?php else : ?>
		<p class="empty-note">表示できる記事がありません。</p>
	<?php endif; ?>
</div>

<?php the_posts_pagination( array( 'mid_size' => 1, 'prev_text' => '前へ', 'next_text' => '次へ' ) ); ?>
<?php get_footer(); ?>
