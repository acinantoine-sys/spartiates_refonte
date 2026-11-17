<?php
/**
 * Gabarit de repli. Le site n'a pas d'actualités : on affiche le contenu demandé simplement.
 *
 * @package Spartiates
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<section class="sombre hero hero--page">
	<div class="conteneur">
		<h1 class="disp titre-page" style="font-size:clamp(48px,7vw,96px)"><?php echo esc_html( is_singular() ? get_the_title() : wp_get_document_title() ); ?></h1>
	</div>
	<?php echo spt_frise( 'hero__frise' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
</section>
<section class="blanc section">
	<div class="conteneur contenu-libre">
		<?php
		while ( have_posts() ) :
			the_post();
			if ( ! is_singular() ) {
				echo '<h2><a href="' . esc_url( get_permalink() ) . '">' . esc_html( get_the_title() ) . '</a></h2>';
			}
			the_content();
		endwhile;
		?>
	</div>
</section>
<?php
get_footer();
