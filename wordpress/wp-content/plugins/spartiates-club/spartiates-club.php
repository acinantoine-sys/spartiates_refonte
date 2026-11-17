<?php
/**
 * Plugin Name:       Spartiates – Données du club
 * Description:       Sources uniques du site des Spartiates d’Amiens : calendrier, horaires, lien d’adhésion HelloAsso, partenaires, palmarès et histoire. Chaque information se modifie à un seul endroit et met à jour tout le site.
 * Version:           1.0.0
 * Requires at least: 6.4
 * Requires PHP:      7.4
 * Author:            Les Spartiates d’Amiens
 * License:           GPL-2.0-or-later
 * Text Domain:       spartiates-club
 *
 * @package Spartiates_Club
 */

defined( 'ABSPATH' ) || exit;

define( 'SPA_CLUB_VERSION', '1.0.0' );
define( 'SPA_CLUB_FICHIER', __FILE__ );
define( 'SPA_CLUB_DIR', plugin_dir_path( __FILE__ ) );
define( 'SPA_CLUB_URL', plugin_dir_url( __FILE__ ) );

require_once SPA_CLUB_DIR . 'includes/class-spa-horaires.php';
require_once SPA_CLUB_DIR . 'includes/class-spa-calendrier.php';
require_once SPA_CLUB_DIR . 'includes/class-spa-palmares.php';
require_once SPA_CLUB_DIR . 'includes/champs.php';
require_once SPA_CLUB_DIR . 'includes/types-de-contenu.php';
require_once SPA_CLUB_DIR . 'includes/reglages.php';
require_once SPA_CLUB_DIR . 'includes/donnees.php';
require_once SPA_CLUB_DIR . 'includes/import.php';

register_activation_hook(
	__FILE__,
	static function () {
		spa_enregistrer_types_de_contenu();
		if ( ! get_option( 'spa_import_fait' ) ) {
			spa_importer_donnees_initiales();
		}
		flush_rewrite_rules();
	}
);

add_action(
	'admin_enqueue_scripts',
	static function () {
		$ecran = get_current_screen();
		if ( ! $ecran || ( false === strpos( $ecran->post_type, 'spa_' ) && false === strpos( $ecran->id, 'spartiates' ) ) ) {
			return;
		}
		wp_enqueue_style( 'spa-admin', SPA_CLUB_URL . 'admin/admin.css', array(), SPA_CLUB_VERSION );
		wp_enqueue_script( 'spa-admin', SPA_CLUB_URL . 'admin/admin.js', array(), SPA_CLUB_VERSION, true );
	}
);
