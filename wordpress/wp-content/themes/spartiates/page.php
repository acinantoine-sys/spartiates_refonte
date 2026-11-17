<?php
/**
 * Page libre (mentions légales, politique de confidentialité…), modifiable dans l'éditeur.
 *
 * @package Spartiates
 */

defined( 'ABSPATH' ) || exit;

get_header();
while ( have_posts() ) :
	the_post();
	?>
	<section class="sombre hero hero--page">
		<div class="conteneur">
			<?php echo spt_fil_ariane( get_the_title() ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			<h1 class="disp titre-page" style="font-size:clamp(48px,7vw,96px)"><?php the_title(); ?></h1>
		</div>
		<?php echo spt_frise( 'hero__frise' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
	</section>
	<section class="blanc section">
		<div class="conteneur contenu-libre">
			<?php the_content(); ?>
		</div>
	</section>
	<?php
endwhile;
get_footer();
