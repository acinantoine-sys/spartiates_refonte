<?php
/**
 * Thème des Spartiates d'Amiens.
 *
 * Les données (calendrier, horaires, adhésion, partenaires, palmarès, histoire)
 * viennent toutes de l'extension « Spartiates – Données du club » : le thème ne
 * fait que les mettre en forme.
 *
 * @package Spartiates
 */

defined( 'ABSPATH' ) || exit;

define( 'SPT_VERSION', '1.0.0' );

require_once get_template_directory() . '/inc/gabarits.php';

/** Pages dont la mise en forme est fournie par un gabarit du thème. */
const SPT_PAGES_GABARIT = array( 'accueil', 'football-americain', 'flag', 'cheerleading', 'calendrier', 'le-club', 'contact' );

add_action(
	'after_setup_theme',
	static function () {
		add_theme_support( 'title-tag' );
		add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' ) );
		add_theme_support( 'responsive-embeds' );
	}
);

add_action(
	'wp_enqueue_scripts',
	static function () {
		wp_enqueue_style( 'spt-polices', get_theme_file_uri( 'assets/fonts/polices.css' ), array(), SPT_VERSION );
		wp_enqueue_style( 'spt-style', get_stylesheet_uri(), array( 'spt-polices' ), SPT_VERSION );
		wp_enqueue_script( 'spt-site', get_theme_file_uri( 'assets/js/site.js' ), array(), SPT_VERSION, array( 'strategy' => 'defer', 'in_footer' => true ) );
		// Le site n'utilise pas les blocs : on n'en charge pas les styles, sauf sur les pages libres.
		if ( is_page( SPT_PAGES_GABARIT ) || is_front_page() ) {
			wp_dequeue_style( 'wp-block-library' );
			wp_dequeue_style( 'global-styles' );
			wp_dequeue_style( 'classic-theme-styles' );
		}
	},
	20
);

/** Précharge les deux polices des titres et du texte courant. */
add_action(
	'wp_head',
	static function () {
		foreach ( array( 'barlow-400-latin.woff2', 'barlow-condensed-800-italic-latin.woff2' ) as $police ) {
			printf( '<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n", esc_url( get_theme_file_uri( 'assets/fonts/' . $police ) ) );
		}
		// Remplace la classe no-js dès que JavaScript est disponible (menu mobile, filtres du calendrier).
		echo "<script>document.documentElement.className=document.documentElement.className.replace('no-js','js');</script>\n";
		printf( '<link rel="icon" href="%s" sizes="512x512">' . "\n", esc_url( get_theme_file_uri( 'assets/logo/logo-spartiates-512.png' ) ) );
		printf( '<link rel="apple-touch-icon" href="%s">' . "\n", esc_url( get_theme_file_uri( 'assets/logo/logo-spartiates-240.png' ) ) );
		echo '<meta name="theme-color" content="#060640">' . "\n";
	},
	1
);

/** Le thème a besoin de l'extension de données. */
add_action(
	'template_redirect',
	static function () {
		if ( ! function_exists( 'spa_adhesion_url' ) ) {
			wp_die(
				'Le thème des Spartiates a besoin de l’extension « Spartiates – Données du club ». Activez-la dans le menu Extensions de l’administration.',
				'Extension manquante',
				array( 'response' => 503 )
			);
		}
	}
);
add_action(
	'admin_notices',
	static function () {
		if ( ! function_exists( 'spa_adhesion_url' ) && current_user_can( 'activate_plugins' ) ) {
			echo '<div class="notice notice-error"><p>Le thème des Spartiates a besoin de l’extension « Spartiates – Données du club ». Activez-la dans le menu Extensions.</p></div>';
		}
	}
);

/**
 * Pages mises en forme par le thème : pas d'éditeur de blocs, mais un message
 * qui indique où modifier les données.
 */
function spt_page_a_gabarit( $post ) {
	$post = get_post( $post );
	return $post && 'page' === $post->post_type && in_array( $post->post_name, SPT_PAGES_GABARIT, true );
}
add_filter(
	'use_block_editor_for_post',
	static function ( $utiliser, $post ) {
		return spt_page_a_gabarit( $post ) ? false : $utiliser;
	},
	10,
	2
);
add_action(
	'edit_form_after_title',
	static function ( $post ) {
		if ( ! spt_page_a_gabarit( $post ) ) {
			return;
		}
		remove_post_type_support( 'page', 'editor' );
		echo '<div class="notice notice-info inline" style="margin:16px 0"><p><strong>Cette page est mise en forme par le thème du club</strong>, d’après les maquettes validées. Son contenu ne se modifie pas ici.</p>';
		echo '<p>Les informations qu’elle affiche se modifient dans le menu <strong>Spartiates</strong> : Calendrier, Palmarès, Partenaires, Histoire du club, Horaires, Adhésion et réglages. Une modification met à jour toutes les pages du site.</p>';
		echo '<p>Attention : ne changez pas l’identifiant (slug) de cette page, il relie la page à sa mise en forme.</p></div>';
	}
);
add_action(
	'add_meta_boxes_page',
	static function ( $post ) {
		if ( spt_page_a_gabarit( $post ) ) {
			remove_post_type_support( 'page', 'editor' );
		}
	}
);

/** Titre des pages : « Football américain – Les Spartiates d’Amiens ». */
add_filter(
	'document_title_separator',
	static function () {
		return '–';
	}
);

/** Description pour les moteurs de recherche et le partage. */
add_action(
	'wp_head',
	static function () {
		$descriptions = array(
			'accueil'            => 'Les Spartiates d’Amiens : club de football américain, flag et cheerleading fondé en 1987. Calendrier, entraînements et adhésion.',
			'football-americain' => 'Football américain aux Spartiates d’Amiens : deux équipes seniors en D1 et D2, des équipes de jeunes, calendrier et entraînements.',
			'flag'               => 'Le flag aux Spartiates d’Amiens : sport mixte et sans contact, dès 8 ans. Entraînements, inscription et infos pratiques.',
			'cheerleading'       => 'Cheerleading aux Spartiates d’Amiens : danse, acrobaties et esprit d’équipe, quatre catégories, entraînements et résultats.',
			'calendrier'         => 'Toutes les dates des Spartiates d’Amiens : matchs, tournois et compétitions de football américain, flag et cheerleading.',
			'le-club'            => 'L’histoire des Spartiates d’Amiens depuis 1987, leur palmarès et leurs bénévoles.',
			'contact'            => 'Contacter les Spartiates d’Amiens : téléphone, e-mail, horaires du bureau et du Stade du Grand Marais à Amiens.',
		);
		$slug = is_front_page() ? 'accueil' : ( is_page() ? get_post_field( 'post_name', get_queried_object_id() ) : '' );
		if ( isset( $descriptions[ $slug ] ) ) {
			printf( '<meta name="description" content="%s">' . "\n", esc_attr( $descriptions[ $slug ] ) );
		}
	},
	2
);
