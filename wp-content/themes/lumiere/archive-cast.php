<?php
/**
 * Cast archive — grid of all cast.
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
	<p class="page-hero__en">Cast</p>
	<p class="page-hero__ja">キャスト</p>
</div>

<div class="cast-grid">
	<?php
	if ( have_posts() ) :
		while ( have_posts() ) :
			the_post();
			get_template_part( 'template-parts/cast-card' );
		endwhile;
	else :
		?>
		<p class="empty-note">キャスト情報は準備中です。</p>
	<?php endif; ?>
</div>

<?php the_posts_pagination( array( 'mid_size' => 1, 'prev_text' => '前へ', 'next_text' => '次へ' ) ); ?>
<?php get_footer(); ?>
