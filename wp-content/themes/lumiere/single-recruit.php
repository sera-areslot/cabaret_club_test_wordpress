<?php
/**
 * Single recruit position.
 *
 * @package lumiere
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();

while ( have_posts() ) :
	the_post();
	$rm = lumiere_recruit_meta();
	?>
	<div class="page-hero">
		<div class="page-hero__bg"></div>
		<p class="page-hero__en">Recruit</p>
		<p class="page-hero__ja"><?php the_title(); ?></p>
	</div>

	<article class="article">
		<dl class="recruit-spec">
			<?php if ( ! empty( $rm['employment'] ) ) : ?><div><dt>雇用形態</dt><dd><?php echo esc_html( $rm['employment'] ); ?></dd></div><?php endif; ?>
			<?php if ( ! empty( $rm['salary'] ) ) : ?><div><dt>給与</dt><dd><?php echo esc_html( $rm['salary'] ); ?></dd></div><?php endif; ?>
			<?php if ( ! empty( $rm['hours'] ) ) : ?><div><dt>勤務時間</dt><dd><?php echo esc_html( $rm['hours'] ); ?></dd></div><?php endif; ?>
			<?php if ( ! empty( $rm['holiday'] ) ) : ?><div><dt>休日・待遇</dt><dd><?php echo esc_html( $rm['holiday'] ); ?></dd></div><?php endif; ?>
		</dl>
		<div class="article__body"><?php the_content(); ?></div>
		<div style="margin-top:2.5rem;">
			<a class="link-underline" href="<?php echo esc_url( get_post_type_archive_link( 'recruit' ) ); ?>">採用情報一覧へ戻る</a>
		</div>
	</article>
	<?php
endwhile;

get_footer();
