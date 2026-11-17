<?php
/**
 * Accès aux données pour le thème. Ce sont les SEULES fonctions que les
 * gabarits utilisent pour lire calendrier, horaires, adhésion, partenaires,
 * palmarès et histoire : aucune de ces données n'est écrite en dur dans le thème.
 *
 * @package Spartiates_Club
 */

defined( 'ABSPATH' ) || exit;

/**
 * Réglages du club, complétés par les valeurs par défaut.
 */
function spa_reglages() {
	$reglages = get_option( 'spa_reglages', array() );
	return array_replace_recursive( spa_reglages_defaut(), is_array( $reglages ) ? $reglages : array() );
}

/**
 * Un réglage, par chemin : spa_reglage( 'coordonnees.telephone' ).
 */
function spa_reglage( $chemin ) {
	return spa_chemin( spa_reglages(), $chemin );
}

/** Lien d'adhésion HelloAsso de la saison : utilisé par TOUS les boutons d'adhésion. */
function spa_adhesion_url() {
	return (string) spa_reglage( 'adhesion_url' );
}

/** Nombre de titres nationaux, calculé à partir du palmarès. */
function spa_nombre_titres_nationaux() {
	return spa_palmares()->nombre_titres_nationaux();
}

/**
 * Horaires (bureau, entraînements, ouverture du stade calculée).
 */
function spa_horaires() {
	$h = get_option( 'spa_horaires', array() );
	return new Spa_Horaires( (array) ( $h['bureau'] ?? array() ), (array) ( $h['entrainements'] ?? array() ) );
}

/** Contenus publiés d'un type, triés. */
function spa_contenus( $type, array $tri = array( 'menu_order' => 'ASC', 'ID' => 'ASC' ) ) {
	return get_posts(
		array(
			'post_type'        => $type,
			'post_status'      => 'publish',
			'posts_per_page'   => -1,
			'orderby'          => $tri,
			'no_found_rows'    => true,
			'suppress_filters' => false,
		)
	);
}

/**
 * Événements du calendrier, au format des données (disc, cat, type, start…).
 */
function spa_evenements() {
	static $cache = null;
	if ( null !== $cache ) {
		return $cache;
	}
	$cache = array();
	foreach ( spa_contenus( 'spa_evenement', array( 'ID' => 'ASC' ) ) as $post ) {
		$m    = static function ( $cle ) use ( $post ) {
			return (string) get_post_meta( $post->ID, $cle, true );
		};
		$home = $m( 'home' );
		$cache[] = array(
			'disc' => $m( 'disc' ),
			'cat'  => $m( 'cat' ),
			'type' => $m( 'type' ) ? $m( 'type' ) : 'match',
			'start'=> $m( 'start' ),
			'end'  => $m( 'end' ),
			'opp'  => $m( 'opp' ),
			'name' => $m( 'name' ),
			'city' => $m( 'city' ),
			'time' => $m( 'time' ),
			'home' => '1' === $home ? true : ( '0' === $home ? false : null ),
		);
	}
	return $cache;
}

/**
 * Calendrier prêt à afficher, à la date du jour (fuseau horaire du site).
 */
function spa_calendrier() {
	static $calendrier = null;
	if ( null === $calendrier ) {
		$calendrier = new Spa_Calendrier(
			spa_evenements(),
			(array) spa_reglage( 'categories' ),
			(string) spa_reglage( 'lieu_domicile' ),
			wp_date( 'Y-m-d' )
		);
	}
	return $calendrier;
}

/**
 * Palmarès.
 */
function spa_palmares() {
	static $palmares = null;
	if ( null !== $palmares ) {
		return $palmares;
	}
	$resultats = array();
	foreach ( spa_contenus( 'spa_resultat' ) as $post ) {
		$m = static function ( $cle ) use ( $post ) {
			return (string) get_post_meta( $post->ID, $cle, true );
		};
		$resultats[] = array(
			'disc'           => $m( 'disc' ),
			'cat'            => $m( 'cat' ),
			'annee'          => $m( 'annee' ),
			'titre'          => $m( 'titre' ),
			'classement'     => $m( 'classement' ),
			'note'           => $m( 'note' ),
			'titre_national' => '1' === $m( 'titre_national' ),
			'page_club'      => '1' === $m( 'page_club' ),
			'libelle_club'   => $m( 'libelle_club' ),
		);
	}
	$palmares = new Spa_Palmares( $resultats );
	return $palmares;
}

/**
 * Partenaires : publics puis privés, dans l'ordre réglé.
 *
 * @return array<int, array{name:string,type:string,logo_id:int,url:string}>
 */
function spa_partenaires() {
	$liste = array(
		'public' => array(),
		'prive'  => array(),
	);
	foreach ( spa_contenus( 'spa_partenaire' ) as $post ) {
		$type             = 'public' === get_post_meta( $post->ID, 'type', true ) ? 'public' : 'prive';
		$liste[ $type ][] = array(
			'name'    => get_the_title( $post ),
			'type'    => $type,
			'logo_id' => (int) get_post_thumbnail_id( $post ),
			'url'     => (string) get_post_meta( $post->ID, 'url', true ),
		);
	}
	return $liste;
}

/**
 * Chronologie de la page Le club.
 *
 * @return array<int, array{year:string,title:string,text:string}>
 */
function spa_chronologie() {
	$etapes = array();
	foreach ( spa_contenus( 'spa_histoire' ) as $post ) {
		$etapes[] = array(
			'year'  => (string) get_post_meta( $post->ID, 'annee', true ),
			'title' => get_the_title( $post ),
			'text'  => (string) get_post_meta( $post->ID, 'texte', true ),
		);
	}
	return $etapes;
}
