<?php
/**
 * Single cast — profile.
 *
 * @package lumiere
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();

while ( have_posts() ) :
	the_post();
	$m = lumiere_cast_meta();
	?>
	<article class="cast-single">
		<div class="cast-single__photo">
			<?php if ( has_post_thumbnail() ) { the_post_thumbnail( 'lumiere-portrait' ); } ?>
		</div>
		<div class="cast-single__info">
			<?php if ( ! empty( $m['catch'] ) ) : ?>
				<p class="cast-single__catch"><?php echo esc_html( $m['catch'] ); ?></p>
			<?php endif; ?>
			<h1 class="cast-single__name"><?php the_title(); ?><?php if ( ! empty( $m['romaji'] ) ) { printf( '<em>%s</em>', esc_html( $m['romaji'] ) ); } ?></h1>

			<dl class="cast-spec">
				<?php if ( ! empty( $m['height'] ) ) : ?><div><dt>身長</dt><dd><?php echo esc_html( $m['height'] ); ?> cm</dd></div><?php endif; ?>
				<?php if ( ! empty( $m['blood'] ) ) : ?><div><dt>血液型</dt><dd><?php echo esc_html( $m['blood'] ); ?> 型</dd></div><?php endif; ?>
				<?php if ( ! empty( $m['birth_month'] ) ) : ?><div><dt>誕生月</dt><dd><?php echo esc_html( $m['birth_month'] ); ?> 月</dd></div><?php endif; ?>
				<?php if ( ! empty( $m['hobby'] ) ) : ?><div><dt>趣味・特技</dt><dd><?php echo esc_html( $m['hobby'] ); ?></dd></div><?php endif; ?>
			</dl>

			<?php if ( get_the_content() ) : ?>
				<div class="cast-profile"><?php the_content(); ?></div>
			<?php endif; ?>

			<?php if ( ! empty( $m['instagram'] ) || ! empty( $m['x'] ) ) : ?>
				<div class="cast-sns">
					<?php
					if ( ! empty( $m['instagram'] ) ) { echo lumiere_sns_link( 'Instagram', $m['instagram'] ); } // phpcs:ignore WordPress.Security.EscapeOutput
					if ( ! empty( $m['x'] ) ) { echo lumiere_sns_link( 'X', $m['x'] ); } // phpcs:ignore WordPress.Security.EscapeOutput
					?>
				</div>
			<?php endif; ?>

			<div class="list-more">
				<a class="link-underline link-underline--back" href="<?php echo esc_url( get_post_type_archive_link( 'cast' ) ); ?>"><?php echo lumiere_icon( 'arrow-left', 'link-underline__arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>キャスト一覧へ戻る</a>
			</div>
		</div>
	</article>
	<?php
endwhile;

get_footer();
