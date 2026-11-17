<?php
/**
 * Fonctions d'aide des gabarits : liens, images, icônes, frise, boutons d'adhésion.
 *
 * @package Spartiates
 */

defined( 'ABSPATH' ) || exit;

/** Menu principal : slug de page => libellé. */
function spt_navigation() {
	return array(
		'football-americain' => 'Football américain',
		'flag'               => 'Flag',
		'cheerleading'       => 'Cheerleading',
		'calendrier'         => 'Calendrier',
		'le-club'            => 'Le club',
		'contact'            => 'Contact',
	);
}

/**
 * Adresse d'une page du site à partir de son slug.
 *
 * @param string $slug  Slug de la page (« accueil » = page d'accueil).
 * @param string $ancre Ancre facultative, sans #.
 */
function spt_url( $slug, $ancre = '' ) {
	if ( 'accueil' === $slug ) {
		$url = home_url( '/' );
	} else {
		$page = get_page_by_path( $slug );
		$url  = $page ? get_permalink( $page ) : home_url( '/' . $slug . '/' );
	}
	return $url . ( $ancre ? '#' . $ancre : '' );
}

/** La page courante est-elle celle-ci ? */
function spt_est_page( $slug ) {
	return 'accueil' === $slug ? is_front_page() : is_page( $slug );
}

/** aria-current pour un lien vers la page courante. */
function spt_aria_current( $slug ) {
	return spt_est_page( $slug ) ? ' aria-current="page"' : '';
}

/**
 * Lien d'adhésion : TOUS les boutons d'adhésion passent par ici
 * (« Nous rejoindre », « Inscrire mon enfant », « Rejoindre l'équipe »,
 * « Devenir bénévole », « Voir les tarifs et adhérer sur HelloAsso »).
 *
 * @param string $libelle  Texte du bouton.
 * @param string $variante jaune | navy | lien | lien-souligne.
 * @param string $classe   Classes supplémentaires.
 */
function spt_adhesion( $libelle = 'Nous rejoindre', $variante = 'jaune', $classe = '' ) {
	$classes = array(
		'jaune'         => 'btn btn-y',
		'navy'          => 'btn btn-plein',
		'lien'          => 'tlink',
		'lien-souligne' => 'tlink tlink--souligne',
	);
	return sprintf(
		'<a class="%1$s" href="%2$s">%3$s</a>',
		esc_attr( trim( ( $classes[ $variante ] ?? $classes['jaune'] ) . ' ' . $classe ) ),
		esc_url( spa_adhesion_url() ),
		esc_html( $libelle )
	);
}

/** Chemin et adresse d'un fichier du thème. */
function spt_asset_url( $chemin ) {
	return get_theme_file_uri( 'assets/' . $chemin );
}

/**
 * Balise <img> d'une image du thème, avec dimensions (pas de décalage au chargement)
 * et chargement différé par défaut.
 *
 * @param string $fichier Chemin sous assets/ (ex. images/flag-sprint-nuit.jpg).
 * @param string $alt     Texte alternatif (repris des maquettes).
 * @param array  $attrs   Attributs supplémentaires (class, style, loading, fetchpriority…).
 */
function spt_image( $fichier, $alt, array $attrs = array() ) {
	static $tailles = array();
	if ( ! isset( $tailles[ $fichier ] ) ) {
		$infos               = @getimagesize( get_theme_file_path( 'assets/' . $fichier ) ); // phpcs:ignore WordPress.PHP.NoSilencedErrors
		$tailles[ $fichier ] = $infos ? array( $infos[0], $infos[1] ) : array( 0, 0 );
	}
	list( $l, $h ) = $tailles[ $fichier ];
	$attrs        += array(
		'loading'  => 'lazy',
		'decoding' => 'async',
	);
	$html = sprintf( '<img src="%s" alt="%s"', esc_url( spt_asset_url( $fichier ) ), esc_attr( $alt ) );
	if ( $l && $h && ! isset( $attrs['width'] ) ) {
		$html .= sprintf( ' width="%d" height="%d"', $l, $h );
	}
	foreach ( $attrs as $nom => $valeur ) {
		if ( false === $valeur || null === $valeur ) {
			continue;
		}
		$html .= sprintf( ' %s="%s"', esc_attr( $nom ), esc_attr( $valeur ) );
	}
	return $html . '>';
}

/**
 * Logo du club, en PNG allégé à la bonne taille (128, 240 ou 400 px).
 *
 * @param int    $taille Taille d'affichage en pixels.
 * @param string $alt    Texte alternatif (vide si décoratif).
 * @param array  $attrs  Attributs supplémentaires.
 */
function spt_logo( $taille, $alt, array $attrs = array() ) {
	$srcset = array();
	foreach ( array( 128, 240, 400 ) as $s ) {
		$srcset[] = spt_asset_url( 'logo/logo-spartiates-' . $s . '.png' ) . ' ' . $s . 'w';
	}
	$attrs += array(
		'srcset'   => implode( ', ', $srcset ),
		'sizes'    => $taille . 'px',
		'width'    => $taille,
		'height'   => $taille,
		'decoding' => 'async',
	);
	return spt_image( 'logo/logo-spartiates-' . ( $taille <= 64 ? 128 : ( $taille <= 120 ? 240 : 400 ) ) . '.png', $alt, $attrs );
}

/**
 * Cadre de photo en parallélogramme avec bloc jaune décalé.
 *
 * @param string $fichier Image sous assets/images/.
 * @param string $alt     Texte alternatif.
 * @param array  $o       Options : hauteur (px), hauteur_mobile, miroir (bool), position (object-position),
 *                        pente (%, 16 par défaut), dy (décalage vertical du bloc jaune), marge (CSS), image (attributs).
 */
function spt_cadre( $fichier, $alt, array $o = array() ) {
	$o += array(
		'hauteur'        => 460,
		'hauteur_mobile' => 420,
		'miroir'         => false,
		'position'       => '50% 50%',
		'pente'          => 16,
		'dy'             => 22,
		'marge'          => '',
		'image'          => array(),
		'apres'          => '',
	);
	$p    = (int) $o['pente'];
	$q    = 100 - $p;
	$clip = $o['miroir']
		? "polygon(0 0, {$q}% 0, 100% 100%, {$p}% 100%)"
		: "polygon({$p}% 0, 100% 0, {$q}% 100%, 0 100%)";
	$style = sprintf(
		'--h:%1$dpx;--hm:%2$dpx;--clip:%3$s;--dx:%4$dpx;--dy:%5$dpx;%6$s',
		(int) $o['hauteur'],
		(int) $o['hauteur_mobile'],
		$clip,
		$o['miroir'] ? 22 : -22,
		(int) $o['dy'],
		$o['marge'] ? 'margin:' . $o['marge'] . ';' : ''
	);
	$image = spt_image(
		'images/' . $fichier,
		$alt,
		$o['image'] + array(
			'class' => 'cadre__photo',
			'style' => 'object-position:' . $o['position'],
		)
	);
	return '<div class="cadre" style="' . esc_attr( $style ) . '"><div class="cadre__fond" aria-hidden="true"></div>' . $image . $o['apres'] . '</div>';
}

/**
 * Frise grecque (méandre) jaune de 18 px.
 *
 * @param string $classe Classes supplémentaires.
 */
function spt_frise( $classe = '' ) {
	static $n = 0;
	$id = 'meandre-' . ( ++$n );
	return '<svg class="frise ' . esc_attr( $classe ) . '" width="100%" height="18" aria-hidden="true" focusable="false"><defs><pattern id="' . $id . '" width="24" height="18" patternUnits="userSpaceOnUse"><path d="M0 15H6V3H24V15H12V9H18" fill="none" stroke="#fcc409" stroke-width="2.5"/></pattern></defs><rect width="100%" height="18" fill="url(#' . $id . ')"/></svg>';
}

/**
 * Icônes SVG des maquettes (décoratives : aria-hidden).
 *
 * @param string $nom     Nom de l'icône.
 * @param int    $taille  Taille en pixels.
 * @param string $couleur Couleur du trait.
 */
function spt_icone( $nom, $taille = 32, $couleur = 'currentColor' ) {
	$icones = array(
		'foot'       => array( '0 0 32 32', '<ellipse cx="16" cy="16" rx="13" ry="8" transform="rotate(-35 16 16)"/><path d="M12.5 19.5l7-7M14.5 14.5l2 2M17 12l2 2M12 17l2 2"/>' ),
		'flag'       => array( '0 0 32 32', '<path d="M8 4v24M8 6h16l-4 5 4 5H8"/>' ),
		'cheer'      => array( '0 0 32 32', '<path d="M4 12h6l12-6v20l-12-6H4z"/><path d="M10 20l2 7h3l-1.5-7"/><path d="M25 12a6 6 0 010 8"/>' ),
		'horloge'    => array( '0 0 24 24', '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>' ),
		'groupe'     => array( '0 0 24 24', '<circle cx="8" cy="8" r="3"/><circle cx="16" cy="8" r="3"/><path d="M2 20c0-3 3-5 6-5s6 2 6 5"/><path d="M14 15c3 0 8 1 8 5"/>' ),
		'bouclier'   => array( '0 0 24 24', '<path d="M12 3l8 3v6c0 5-3.5 8-8 9-4.5-1-8-4-8-9V6z"/>' ),
		'trophee'    => array( '0 0 24 24', '<path d="M8 21h8M12 17v4M7 4h10v5a5 5 0 01-10 0z"/><path d="M7 6H4v2a3 3 0 003 3M17 6h3v2a3 3 0 01-3 3"/>' ),
		'courbe'     => array( '0 0 24 24', '<path d="M4 18c3-9 8-9 16-9"/><path d="M16 5l4 4-4 4"/>' ),
		'eclair'     => array( '0 0 24 24', '<path d="M13 2L4 14h7l-1 8 9-12h-7z"/>' ),
		'cible'      => array( '0 0 24 24', '<circle cx="12" cy="12" r="9"/><circle cx="12" cy="12" r="4"/><path d="M12 3v3M12 18v3M3 12h3M18 12h3"/>' ),
		'telephone'  => array( '0 0 24 24', '<path d="M5 4h4l2 5-2.5 1.5a11 11 0 005 5L15 13l5 2v4a2 2 0 01-2 2A16 16 0 013 6a2 2 0 012-2z"/>' ),
		'repere'     => array( '0 0 24 24', '<path d="M12 21s-7-6.2-7-11a7 7 0 0114 0c0 4.8-7 11-7 11z"/><circle cx="12" cy="10" r="2.5"/>' ),
		'instagram'  => array( '0 0 24 24', '<rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="0.8"/>' ),
		'menu'       => array( '0 0 24 24', '<path d="M3 6h18M3 12h18M3 18h18"/>' ),
		'fermer'     => array( '0 0 24 24', '<path d="M5 5l14 14M19 5L5 19"/>' ),
	);
	if ( ! isset( $icones[ $nom ] ) ) {
		return '';
	}
	return sprintf(
		'<svg width="%1$d" height="%1$d" viewBox="%2$s" fill="none" stroke="%3$s" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">%4$s</svg>',
		(int) $taille,
		esc_attr( $icones[ $nom ][0] ),
		esc_attr( $couleur ),
		$icones[ $nom ][1]
	);
}

/** Lien tel: à partir d'un numéro français (03 22 52 99 24 → tel:+33322529924). */
function spt_tel( $numero ) {
	$chiffres = preg_replace( '/\D+/', '', (string) $numero );
	if ( 10 === strlen( $chiffres ) && '0' === $chiffres[0] ) {
		$chiffres = '+33' . substr( $chiffres, 1 );
	}
	return 'tel:' . $chiffres;
}

/** Adresse postale du bureau, sur une ligne. */
function spt_adresse_ligne() {
	return spa_reglage( 'coordonnees.rue' ) . ', ' . spa_reglage( 'coordonnees.ville' );
}

/** Lien d'itinéraire vers le stade (Google Maps). */
function spt_itineraire_url() {
	$requete = spa_reglage( 'coordonnees.lieu' ) . ' ' . spa_reglage( 'coordonnees.rue' ) . ' ' . spa_reglage( 'coordonnees.ville' );
	return 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode( $requete );
}

/** Réseaux sociaux : libellé => adresse (non vides). */
function spt_reseaux() {
	$liste = array(
		'Instagram' => spa_reglage( 'reseaux.instagram' ),
		'Facebook'  => spa_reglage( 'reseaux.facebook' ),
		'YouTube'   => spa_reglage( 'reseaux.youtube' ),
		'TikTok'    => spa_reglage( 'reseaux.tiktok' ),
	);
	return array_filter( $liste );
}

/** Fil d'Ariane des pages intérieures. */
function spt_fil_ariane( $titre ) {
	return '<nav class="cond fil-ariane" aria-label="Fil d’Ariane"><a href="' . esc_url( spt_url( 'accueil' ) ) . '">Accueil</a> <span class="sep" aria-hidden="true">/</span> <span aria-current="page">' . esc_html( $titre ) . '</span></nav>';
}

/**
 * Affiche un morceau de gabarit avec des arguments.
 *
 * @param string $nom  Nom sous template-parts/.
 * @param array  $args Arguments.
 */
function spt_part( $nom, array $args = array() ) {
	get_template_part( 'template-parts/' . $nom, null, $args );
}
