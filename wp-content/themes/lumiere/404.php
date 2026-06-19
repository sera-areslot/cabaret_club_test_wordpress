<?php
/**
 * 404.
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
	<p class="page-hero__en">404</p>
	<p class="page-hero__ja">ページが見つかりません</p>
</div>
<div class="content" style="text-align:center;">
	<p class="empty-note">お探しのページは見つかりませんでした。</p>
	<p><a class="link-underline" href="<?php echo esc_url( home_url( '/' ) ); ?>">トップへ戻る</a></p>
</div>
<?php get_footer(); ?>
