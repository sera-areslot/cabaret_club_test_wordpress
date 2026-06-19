<?php
/**
 * Front page — one-page composition wired to CMS data.
 *
 * @package lumiere
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
$site = lumiere_site();
?>

	<!-- Hero -->
	<section class="hero" id="hero">
		<div class="hero__bg"><div class="hero__bg-inner"></div></div>
		<div class="hero__veil"></div>
		<div class="hero__inner">
			<p class="hero__eyebrow"><?php echo esc_html( $site['area'] ); ?></p>
			<h1 class="hero__title">
				<span class="hero__title-en" data-split><?php echo esc_html( $site['wordmark'] ); ?></span>
				<span class="hero__title-ja"><?php echo esc_html( $site['hero_ja'] ); ?></span>
			</h1>
			<p class="hero__lead"><?php echo esc_html( $site['hero_lead'] ); ?></p>
		</div>
		<div class="hero__scroll"><span>SCROLL</span><i></i></div>
	</section>

	<!-- Concept -->
	<section class="section concept" id="concept">
		<span class="concept__vertical" aria-hidden="true"><?php echo esc_html( $site['area'] ); ?></span>
		<div class="container concept__grid">
			<div class="section__head">
				<span class="section__index">01</span>
				<h2 class="section__title" data-split>Concept</h2>
				<span class="section__sub" data-reveal>コンセプト</span>
			</div>
			<div class="concept__body">
				<p class="concept__lead" data-reveal><?php echo esc_html( $site['concept_lead'] ); ?></p>
				<?php foreach ( $site['concept_text'] as $paragraph ) : ?>
					<p class="concept__text" data-reveal><?php echo esc_html( $paragraph ); ?></p>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<!-- Cast -->
	<section class="section cast" id="cast">
		<div class="cast__head container">
			<span class="section__index">02</span>
			<h2 class="section__title" data-split>Cast</h2>
			<span class="section__sub" data-reveal>キャスト</span>
		</div>
		<div class="cast__viewport">
			<div class="cast__track" id="castTrack">
				<?php
				$cast = new WP_Query( array(
					'post_type'      => 'cast',
					'posts_per_page' => 12,
					'orderby'        => 'menu_order',
					'order'          => 'ASC',
				) );
				if ( $cast->have_posts() ) :
					while ( $cast->have_posts() ) :
						$cast->the_post();
						get_template_part( 'template-parts/cast-card' );
					endwhile;
					wp_reset_postdata();
				else :
					?>
					<p class="cast__empty">キャスト情報は準備中です。</p>
				<?php endif; ?>
			</div>
		</div>
		<div class="container list-more">
			<a class="link-underline" href="<?php echo esc_url( get_post_type_archive_link( 'cast' ) ); ?>">キャスト一覧を見る<?php echo lumiere_icon( 'arrow', 'link-underline__arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></a>
		</div>
	</section>

	<!-- System -->
	<section class="section system" id="system">
		<div class="container">
			<div class="section__head section__head--center">
				<span class="section__index">03</span>
				<h2 class="section__title" data-split>System</h2>
				<span class="section__sub" data-reveal>料金システム</span>
			</div>
			<div class="system__list">
				<?php foreach ( $site['system'] as $row ) : ?>
					<div class="system__row" data-reveal>
						<span class="system__name"><?php echo esc_html( $row['name'] ); ?><?php if ( $row['note'] ) { printf( '<em>%s</em>', esc_html( $row['note'] ) ); } ?></span>
						<span class="system__lead-dot"></span>
						<span class="system__price"><i data-count="<?php echo esc_attr( $row['price'] ); ?>"><?php echo esc_html( number_format( $row['price'] ) ); ?></i>円</span>
					</div>
				<?php endforeach; ?>
			</div>
			<p class="system__note" data-reveal><?php echo esc_html( $site['system_note'] ); ?></p>
		</div>
	</section>

	<!-- News -->
	<section class="section news" id="news">
		<div class="container">
			<div class="section__head">
				<span class="section__index">04</span>
				<h2 class="section__title" data-split>News</h2>
				<span class="section__sub" data-reveal>お知らせ</span>
			</div>
			<ul class="news__list">
				<?php
				$news = new WP_Query( array( 'post_type' => 'news', 'posts_per_page' => 4 ) );
				if ( $news->have_posts() ) :
					while ( $news->have_posts() ) :
						$news->the_post();
						get_template_part( 'template-parts/news-item' );
					endwhile;
					wp_reset_postdata();
				else :
					?>
					<li class="empty-note">お知らせは準備中です。</li>
				<?php endif; ?>
			</ul>
			<div class="list-more">
				<a class="link-underline" href="<?php echo esc_url( get_post_type_archive_link( 'news' ) ); ?>">お知らせ一覧を見る<?php echo lumiere_icon( 'arrow', 'link-underline__arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></a>
			</div>
		</div>
	</section>

	<!-- Recruit -->
	<section class="section recruit" id="recruit">
		<div class="recruit__bg"><div class="recruit__bg-inner" data-parallax="16"></div></div>
		<div class="recruit__veil"></div>
		<div class="container recruit__inner">
			<span class="section__index">05</span>
			<h2 class="recruit__title" data-split>Recruit</h2>
			<p class="recruit__sub" data-reveal>採用情報</p>
			<p class="recruit__text" data-reveal><?php echo esc_html( $site['recruit_text'] ); ?></p>
			<a class="link-underline" href="<?php echo esc_url( get_post_type_archive_link( 'recruit' ) ); ?>" data-reveal>採用情報を見る<?php echo lumiere_icon( 'arrow', 'link-underline__arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></a>
		</div>
	</section>

	<!-- Access -->
	<section class="section access" id="access">
		<div class="container">
			<div class="section__head section__head--center">
				<span class="section__index">06</span>
				<h2 class="section__title" data-split>Access</h2>
				<span class="section__sub" data-reveal>アクセス</span>
			</div>
			<div class="access__grid">
				<dl class="access__info" data-reveal>
					<?php foreach ( $site['store'] as $label => $value ) : ?>
						<div><dt><?php echo esc_html( $label ); ?></dt><dd><?php echo esc_html( $value ); ?></dd></div>
					<?php endforeach; ?>
				</dl>
				<a class="access__map" href="<?php echo esc_url( 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode( $site['store']['住所'] ) ); ?>" target="_blank" rel="noopener" data-reveal>
						<?php echo lumiere_icon( 'pin' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
						<span>Google マップで見る</span>
					</a>
			</div>
		</div>
	</section>

<?php
get_footer();
