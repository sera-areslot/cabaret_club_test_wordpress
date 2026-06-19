<?php
/**
 * Generic page template.
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
	<div class="page-hero">
		<div class="page-hero__bg"></div>
		<p class="page-hero__en">Page</p>
		<p class="page-hero__ja"><?php the_title(); ?></p>
	</div>
	<div class="content article__body">
		<?php the_content(); ?>
	</div>
	<?php
endwhile;

get_footer();
