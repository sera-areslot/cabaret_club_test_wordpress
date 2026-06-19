<?php
/**
 * Phase 1 動作確認用のスタブ。
 * 登録済みコンテンツ（キャスト・お知らせ・求人）が CMS から取得できることを示します。
 * Phase 2 で design-preview のデザインに沿った本テンプレートへ置き換えます。
 *
 * @package lumiere
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>
<main class="lumiere-stub">
	<p class="lumiere-stub__eyebrow">CLUB LUMIÈRE</p>
	<h1>コンテンツ基盤 稼働中（Phase 1）</h1>
	<p>キャスト・お知らせ・求人のカスタム投稿タイプが有効です。下記は CMS から読み出したサンプルです。<br>
	デザイン（白×淡ピンク×ゴールド）の本テンプレート化は <strong>Phase 2</strong> で行います。
	現在のデザイン確認は <code>/design-preview/</code> をご覧ください。</p>

	<?php
	$sections = array(
		'cast'    => 'キャスト',
		'news'    => 'お知らせ',
		'recruit' => '求人',
	);

	foreach ( $sections as $post_type => $label ) :
		$query = new WP_Query( array(
			'post_type'      => $post_type,
			'posts_per_page' => 10,
			'orderby'        => ( 'news' === $post_type ) ? 'date' : 'menu_order',
			'order'          => ( 'news' === $post_type ) ? 'DESC' : 'ASC',
		) );
		?>
		<h2><?php echo esc_html( $label ); ?></h2>
		<?php if ( $query->have_posts() ) : ?>
			<ul>
				<?php
				while ( $query->have_posts() ) :
					$query->the_post();
					$extra = '';
					if ( 'cast' === $post_type ) {
						$romaji = get_post_meta( get_the_ID(), '_lumiere_romaji', true );
						$height = get_post_meta( get_the_ID(), '_lumiere_height', true );
						$extra  = trim( $romaji . ( $height ? " / T{$height}" : '' ) );
					} elseif ( 'recruit' === $post_type ) {
						$extra = get_post_meta( get_the_ID(), '_lumiere_employment', true );
					}
					?>
					<li>
						<?php the_title(); ?>
						<?php if ( $extra ) : ?><span style="color:#b0894e;"> — <?php echo esc_html( $extra ); ?></span><?php endif; ?>
					</li>
				<?php endwhile; ?>
			</ul>
		<?php else : ?>
			<p style="color:#999;">（まだ登録がありません）</p>
		<?php endif; ?>
		<?php wp_reset_postdata(); ?>
	<?php endforeach; ?>
</main>
<?php
get_footer();
