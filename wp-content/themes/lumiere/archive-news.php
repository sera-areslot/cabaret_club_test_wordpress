<?php
/**
 * News archive.
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
	<p class="page-hero__en">News</p>
	<p class="page-hero__ja">お知らせ</p>
</div>

<div class="content">
	<ul class="news__list">
		<?php
		if ( have_posts() ) :
			while ( have_posts() ) :
				the_post();
				get_template_part( 'template-parts/news-item' );
			endwhile;
		else :
			?>
			<li class="empty-note">お知らせは準備中です。</li>
		<?php endif; ?>
	</ul>
</div>

<?php the_posts_pagination( array( 'mid_size' => 1, 'prev_text' => '前へ', 'next_text' => '次へ' ) ); ?>
<?php get_footer(); ?>
