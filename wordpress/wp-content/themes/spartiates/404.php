<?php
/**
 * Page introuvable.
 *
 * @package Spartiates
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<section class="sombre hero hero--page">
	<div class="conteneur">
		<p class="cond surtitre surtitre--jaune" style="margin:0">Erreur 404</p>
		<h1 class="disp titre-page">Page <span class="jaune">introuvable</span></h1>
		<p class="chapo">Cette page n’existe pas ou a été déplacée. Les pages du site sont accessibles depuis le menu.</p>
		<div class="boutons" style="margin-top:32px">
			<a class="btn btn-y" href="<?php echo esc_url( spt_url( 'accueil' ) ); ?>">Retour à l’accueil</a>
			<a class="btn btn-o" href="<?php echo esc_url( spt_url( 'calendrier' ) ); ?>">Le calendrier</a>
		</div>
	</div>
	<?php echo spt_frise( 'hero__frise' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
</section>
<?php
get_footer();
