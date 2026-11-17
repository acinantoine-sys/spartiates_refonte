<?php
/**
 * Bandeau « Envie de les rejoindre ? » / « Envie de rejoindre l'aventure ? ».
 *
 * Arguments : titre (HTML limité à <span class="jaune">), texte, contact (libellé
 * du bouton vers la page Contact, facultatif).
 *
 * @package Spartiates
 */

defined( 'ABSPATH' ) || exit;
?>
<section class="sombre section cta-bandeau" style="padding-top:72px;padding-bottom:72px" aria-labelledby="titre-rejoindre">
	<div class="conteneur">
		<div class="col" style="--base:420px">
			<h2 id="titre-rejoindre" class="disp titre-section titre-section--cta"><?php echo wp_kses( $args['titre'] ?? '', array( 'span' => array( 'class' => true ) ) ); ?></h2>
			<p><?php echo esc_html( $args['texte'] ?? '' ); ?></p>
		</div>
		<div class="boutons">
			<?php echo spt_adhesion( 'Nous rejoindre' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			<?php if ( ! empty( $args['contact'] ) ) : ?>
				<a class="btn btn-o" href="<?php echo esc_url( spt_url( 'contact' ) ); ?>"><?php echo esc_html( $args['contact'] ); ?></a>
			<?php endif; ?>
		</div>
	</div>
</section>
