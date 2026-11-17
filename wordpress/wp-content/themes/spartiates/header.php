<?php
/**
 * En-tête commun : logo, menu principal, bouton « Nous rejoindre » (lien d'adhésion).
 *
 * @package Spartiates
 */

defined( 'ABSPATH' ) || exit;
?><!doctype html>
<html <?php language_attributes(); ?> class="no-js">
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="lien-evitement" href="#contenu">Aller au contenu</a>

<header class="entete" data-entete>
	<div class="entete__barre">
		<a class="entete__logo" href="<?php echo esc_url( spt_url( 'accueil' ) ); ?>" aria-label="Les Spartiates d’Amiens, accueil"<?php echo spt_aria_current( 'accueil' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>>
			<?php echo spt_logo( 64, '', array( 'loading' => false, 'fetchpriority' => 'high' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			<span class="disp entete__nom">Les Spartiates<span>d’Amiens</span></span>
		</a>

		<nav class="nav" id="navigation" aria-label="Navigation principale">
			<ul class="nav__liste">
				<?php foreach ( spt_navigation() as $slug => $libelle ) : ?>
					<li><a class="navlink" href="<?php echo esc_url( spt_url( $slug ) ); ?>"<?php echo spt_aria_current( $slug ); // phpcs:ignore WordPress.Security.EscapeOutput ?>><?php echo esc_html( $libelle ); ?></a></li>
				<?php endforeach; ?>
			</ul>
			<?php echo spt_adhesion( 'Nous rejoindre', 'jaune', 'nav__adhesion' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
		</nav>

		<?php echo spt_adhesion( 'Nous rejoindre', 'jaune', 'entete__adhesion' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>

		<button class="entete__menu" type="button" aria-expanded="false" aria-controls="navigation" data-menu>
			<span class="ouvrir"><?php echo spt_icone( 'menu', 24 ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
			<span class="fermer"><?php echo spt_icone( 'fermer', 24 ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
			Menu
		</button>
	</div>
</header>

<main id="contenu" tabindex="-1">
