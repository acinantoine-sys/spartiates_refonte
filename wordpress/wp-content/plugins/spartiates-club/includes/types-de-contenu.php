<?php
/**
 * Types de contenu : Événement (calendrier), Résultat (palmarès), Partenaire, Étape de l'histoire.
 *
 * Tous sont regroupés dans le menu « Spartiates » de l'administration. Ils n'ont
 * pas de page publique propre : ils alimentent les gabarits du thème.
 *
 * @package Spartiates_Club
 */

defined( 'ABSPATH' ) || exit;

const SPA_TYPES = array( 'spa_evenement', 'spa_resultat', 'spa_partenaire', 'spa_histoire' );

/** Disciplines du club. */
function spa_disciplines() {
	return Spa_Calendrier::DISCIPLINES;
}

/**
 * Champs de chaque type de contenu.
 */
function spa_champs( $type ) {
	switch ( $type ) {
		case 'spa_evenement':
			$categories = array();
			foreach ( (array) spa_reglage( 'categories' ) as $liste ) {
				$categories = array_merge( $categories, (array) $liste );
			}
			return array(
				array( 'cle' => 'disc', 'label' => 'Discipline', 'type' => 'select', 'options' => spa_disciplines(), 'requis' => true, 'defaut' => 'foot' ),
				array( 'cle' => 'cat', 'label' => 'Catégorie', 'type' => 'text', 'requis' => true, 'liste' => array_values( array_unique( $categories ) ), 'aide' => 'Par exemple Seniors D1, U19, U13/U15, Minimes. Une nouvelle catégorie est ajoutée automatiquement aux filtres du calendrier.' ),
				array( 'cle' => 'type', 'label' => 'Type d’événement', 'type' => 'select', 'options' => Spa_Calendrier::TYPES, 'requis' => true, 'defaut' => 'match' ),
				array( 'cle' => 'start', 'label' => 'Date (ou premier jour)', 'type' => 'date', 'requis' => true ),
				array( 'cle' => 'end', 'label' => 'Dernier jour', 'type' => 'date', 'aide' => 'Seulement pour un événement sur plusieurs jours. Sinon, laisser vide.' ),
				array( 'cle' => 'opp', 'label' => 'Adversaire', 'type' => 'text', 'classe' => 'spa-si-match', 'aide' => 'Pour un match. Le titre affiché sera « Spartiates – Adversaire ».' ),
				array( 'cle' => 'name', 'label' => 'Nom de l’événement', 'type' => 'text', 'classe' => 'spa-si-autre', 'aide' => 'Pour un tournoi, une compétition ou une détection. Exemple : Tournoi U13/U15.' ),
				array( 'cle' => 'city', 'label' => 'Ville ou lieu', 'type' => 'text', 'placeholder' => '[Lieu à définir]', 'aide' => 'Pour un match à domicile, le site affiche automatiquement le lieu réglé dans « Adhésion et réglages ». Si le lieu n’est pas connu, écrire [Lieu à définir].' ),
				array( 'cle' => 'time', 'label' => 'Heure', 'type' => 'text', 'placeholder' => '14h30', 'aide' => 'Facultatif, au format 14h ou 14h30. Vide : le site affiche [Heure].' ),
				array(
					'cle'     => 'home',
					'label'   => 'Domicile ou déplacement',
					'type'    => 'radio',
					'options' => array(
						'1' => 'À domicile',
						'0' => 'En déplacement',
						''  => 'Sans objet (tournoi, compétition…)',
					),
					'defaut'  => '',
				),
			);

		case 'spa_resultat':
			return array(
				array( 'cle' => 'disc', 'label' => 'Discipline', 'type' => 'select', 'options' => spa_disciplines(), 'requis' => true, 'defaut' => 'foot' ),
				array( 'cle' => 'cat', 'label' => 'Catégorie', 'type' => 'text', 'requis' => true, 'liste' => array( 'Seniors', 'U19', 'U17', 'Senior', 'Minimes', 'Cadets', 'Juniors' ), 'aide' => 'Pour l’équipe première de football, écrire exactement « Seniors ».' ),
				array( 'cle' => 'annee', 'label' => 'Année', 'type' => 'number', 'requis' => true, 'aide' => 'Pour le cheerleading, année de fin de saison : 2026 pour la saison 2025-2026.' ),
				array( 'cle' => 'titre', 'label' => 'Titre ou compétition', 'type' => 'text', 'requis' => true, 'aide' => 'Exemples : Champion de France D1 ; Champion territorial Île-de-France ; SALC ; Open Cheer Center.' ),
				array( 'cle' => 'classement', 'label' => 'Classement', 'type' => 'text', 'placeholder' => '2e', 'aide' => 'Facultatif (cheerleading). 1er, 2e et 3e sont surlignés en jaune.' ),
				array( 'cle' => 'note', 'label' => 'Mention', 'type' => 'text', 'aide' => 'Facultatif. Exemple : Qualification aux championnats d’Europe.' ),
				array( 'cle' => 'titre_national', 'label' => 'Titre de champion de France (compte dans le nombre de titres nationaux affiché sur le site)', 'type' => 'checkbox' ),
				array( 'cle' => 'page_club', 'label' => 'Afficher sur la page Le club', 'type' => 'checkbox', 'defaut' => '1' ),
				array( 'cle' => 'libelle_club', 'label' => 'Libellé sur la page Le club', 'type' => 'text', 'aide' => 'Facultatif. Vide : libellé calculé (« Champion de France Senior », « SALC, Juniors : 2e place »).' ),
			);

		case 'spa_partenaire':
			return array(
				array(
					'cle'     => 'type',
					'label'   => 'Type de partenaire',
					'type'    => 'radio',
					'options' => array(
						'public' => 'Partenaire public (collectivité, institution)',
						'prive'  => 'Partenaire privé (entreprise)',
					),
					'defaut'  => 'prive',
				),
				array( 'cle' => 'url', 'label' => 'Site du partenaire', 'type' => 'url', 'placeholder' => 'https://', 'aide' => 'Facultatif. Si renseigné, le logo devient un lien.' ),
			);

		case 'spa_histoire':
			return array(
				array( 'cle' => 'annee', 'label' => 'Année', 'type' => 'text', 'requis' => true, 'aide' => 'Exemples : 1987 ; 2010 & 2012 ; [Année] si elle n’est pas encore connue.' ),
				array( 'cle' => 'texte', 'label' => 'Texte', 'type' => 'textarea', 'requis' => true, 'lignes' => 4 ),
			);
	}
	return array();
}

/**
 * Enregistre les types de contenu.
 */
function spa_enregistrer_types_de_contenu() {
	$commun = array(
		'public'              => false,
		'publicly_queryable'  => false,
		'exclude_from_search' => true,
		'show_ui'             => true,
		'show_in_menu'        => 'spartiates',
		'show_in_rest'        => false,
		'has_archive'         => false,
		'rewrite'             => false,
		'capability_type'     => 'post',
		'map_meta_cap'        => true,
	);

	register_post_type(
		'spa_evenement',
		$commun + array(
			'labels'   => array(
				'name'               => 'Calendrier',
				'singular_name'      => 'Événement',
				'menu_name'          => 'Calendrier',
				'all_items'          => 'Calendrier',
				'add_new'            => 'Ajouter une date',
				'add_new_item'       => 'Ajouter une date au calendrier',
				'edit_item'          => 'Modifier la date',
				'search_items'       => 'Rechercher',
				'not_found'          => 'Aucune date.',
				'not_found_in_trash' => 'Aucune date dans la corbeille.',
			),
			'supports' => false,
		)
	);

	register_post_type(
		'spa_resultat',
		$commun + array(
			'labels'   => array(
				'name'          => 'Palmarès',
				'singular_name' => 'Résultat',
				'all_items'     => 'Palmarès',
				'add_new'       => 'Ajouter un résultat',
				'add_new_item'  => 'Ajouter un résultat au palmarès',
				'edit_item'     => 'Modifier le résultat',
				'not_found'     => 'Aucun résultat.',
			),
			'supports' => array( 'page-attributes' ),
		)
	);

	register_post_type(
		'spa_partenaire',
		$commun + array(
			'labels'   => array(
				'name'                  => 'Partenaires',
				'singular_name'         => 'Partenaire',
				'all_items'             => 'Partenaires',
				'add_new'               => 'Ajouter un partenaire',
				'add_new_item'          => 'Ajouter un partenaire',
				'edit_item'             => 'Modifier le partenaire',
				'featured_image'        => 'Logo',
				'set_featured_image'    => 'Choisir le logo',
				'remove_featured_image' => 'Retirer le logo',
				'use_featured_image'    => 'Utiliser comme logo',
				'not_found'             => 'Aucun partenaire.',
			),
			'supports' => array( 'title', 'thumbnail', 'page-attributes' ),
		)
	);

	register_post_type(
		'spa_histoire',
		$commun + array(
			'labels'   => array(
				'name'          => 'Histoire du club',
				'singular_name' => 'Étape de l’histoire',
				'all_items'     => 'Histoire du club',
				'add_new'       => 'Ajouter une étape',
				'add_new_item'  => 'Ajouter une étape à la chronologie',
				'edit_item'     => 'Modifier l’étape',
				'not_found'     => 'Aucune étape.',
			),
			'supports' => array( 'title', 'page-attributes' ),
		)
	);
}
add_action( 'init', 'spa_enregistrer_types_de_contenu' );

/** Pas d'éditeur de blocs pour ces types : de simples formulaires. */
add_filter(
	'use_block_editor_for_post_type',
	static function ( $utiliser, $type ) {
		return in_array( $type, SPA_TYPES, true ) ? false : $utiliser;
	},
	10,
	2
);

/** Le logo des partenaires nécessite les images à la une. */
add_action(
	'after_setup_theme',
	static function () {
		add_theme_support( 'post-thumbnails', array( 'spa_partenaire' ) );
	},
	20
);

/** Titre du champ « Titre » pour les partenaires et l'histoire. */
add_filter(
	'enter_title_here',
	static function ( $texte, $post ) {
		if ( 'spa_partenaire' === $post->post_type ) {
			return 'Nom du partenaire (sert aussi de texte alternatif du logo)';
		}
		if ( 'spa_histoire' === $post->post_type ) {
			return 'Titre de l’étape';
		}
		return $texte;
	},
	10,
	2
);

/* -------------------------------------------------------------------------- */
/* Boîtes de saisie                                                           */
/* -------------------------------------------------------------------------- */

add_action(
	'add_meta_boxes',
	static function () {
		$titres = array(
			'spa_evenement'  => 'Informations sur la date',
			'spa_resultat'   => 'Résultat',
			'spa_partenaire' => 'Informations sur le partenaire',
			'spa_histoire'   => 'Contenu de l’étape',
		);
		foreach ( $titres as $type => $titre ) {
			add_meta_box( 'spa_champs', $titre, 'spa_boite_champs', $type, 'normal', 'high' );
		}
	}
);

/**
 * Affiche les champs d'un contenu.
 *
 * @param WP_Post $post Contenu.
 */
function spa_boite_champs( $post ) {
	wp_nonce_field( 'spa_enregistrer_' . $post->ID, 'spa_nonce' );
	$nouveau = 'auto-draft' === $post->post_status;
	echo '<div class="spa-champs">';
	foreach ( spa_champs( $post->post_type ) as $champ ) {
		$valeur = get_post_meta( $post->ID, $champ['cle'], true );
		if ( $nouveau && '' === $valeur && isset( $champ['defaut'] ) ) {
			$valeur = $champ['defaut'];
		}
		spa_afficher_champ( $champ, $valeur, 'spa[' . $champ['cle'] . ']' );
	}
	echo '</div>';
	if ( 'spa_partenaire' === $post->post_type ) {
		echo '<p class="description">Le logo se choisit dans l’encadré « Logo » à droite. Sans logo, le site affiche une case en pointillés avec le nom. Tous les logos sont affichés à la même taille. L’ordre d’affichage se règle avec le champ « Ordre ».</p>';
	}
	if ( in_array( $post->post_type, array( 'spa_resultat', 'spa_histoire' ), true ) ) {
		echo '<p class="description">Le champ « Ordre » (encadré « Attributs ») règle l’ordre d’affichage : les plus petits nombres d’abord.</p>';
	}
}

/**
 * Enregistre les champs.
 *
 * @param int $post_id Identifiant.
 */
function spa_enregistrer_champs( $post_id ) {
	if ( ! isset( $_POST['spa_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['spa_nonce'] ), 'spa_enregistrer_' . $post_id ) ) {
		return;
	}
	if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	$type   = get_post_type( $post_id );
	$saisie = isset( $_POST['spa'] ) && is_array( $_POST['spa'] ) ? wp_unslash( $_POST['spa'] ) : array(); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput -- nettoyé champ par champ.
	$valeurs = array();
	foreach ( spa_champs( $type ) as $champ ) {
		$valeurs[ $champ['cle'] ] = spa_nettoyer_champ( $champ, $saisie[ $champ['cle'] ] ?? '' );
	}

	if ( 'spa_evenement' === $type ) {
		if ( '' !== $valeurs['time'] ) {
			$heure            = Spa_Horaires::normaliser( $valeurs['time'] );
			$valeurs['time']  = $heure ? $heure : $valeurs['time'];
		}
		if ( '' !== $valeurs['end'] && $valeurs['end'] <= $valeurs['start'] ) {
			$valeurs['end'] = '';
		}
		if ( 'match' !== $valeurs['type'] ) {
			$valeurs['opp'] = '';
		} else {
			$valeurs['name'] = '';
		}
	}

	foreach ( $valeurs as $cle => $valeur ) {
		update_post_meta( $post_id, $cle, $valeur );
	}
}
add_action( 'save_post', 'spa_enregistrer_champs' );

/**
 * Titres automatiques (événements, résultats) pour des listes lisibles dans l'administration.
 */
add_filter(
	'wp_insert_post_data',
	static function ( $data, $postarr ) {
		if ( empty( $_POST['spa'] ) || ! is_array( $_POST['spa'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification -- simple lecture, la vérification a lieu à l'enregistrement.
			return $data;
		}
		$s = wp_unslash( $_POST['spa'] ); // phpcs:ignore
		if ( 'spa_evenement' === $data['post_type'] ) {
			$titre = ( $s['type'] ?? 'match' ) === 'match' ? 'Spartiates – ' . ( $s['opp'] ?? '' ) : ( $s['name'] ?? '' );
			$data['post_title'] = sanitize_text_field( trim( ( $s['start'] ?? '' ) . ' · ' . ( $s['cat'] ?? '' ) . ' · ' . $titre ) );
		} elseif ( 'spa_resultat' === $data['post_type'] ) {
			$data['post_title'] = sanitize_text_field( trim( ( $s['annee'] ?? '' ) . ' · ' . ( $s['titre'] ?? '' ) . ' · ' . ( $s['cat'] ?? '' ) . ( empty( $s['classement'] ) ? '' : ' · ' . $s['classement'] ) ) );
		}
		return $data;
	},
	10,
	2
);

/* -------------------------------------------------------------------------- */
/* Colonnes des listes                                                        */
/* -------------------------------------------------------------------------- */

add_filter(
	'manage_spa_evenement_posts_columns',
	static function () {
		return array(
			'cb'       => '<input type="checkbox">',
			'spa_date' => 'Date',
			'spa_disc' => 'Discipline',
			'spa_cat'  => 'Catégorie',
			'title'    => 'Événement',
			'spa_lieu' => 'Lieu',
			'spa_etat' => 'État',
		);
	}
);
add_action(
	'manage_spa_evenement_posts_custom_column',
	static function ( $colonne, $post_id ) {
		$m = static function ( $cle ) use ( $post_id ) {
			return (string) get_post_meta( $post_id, $cle, true );
		};
		switch ( $colonne ) {
			case 'spa_date':
				$debut = $m( 'start' );
				$fin   = $m( 'end' );
				echo esc_html( $debut ? wp_date( 'j M Y', strtotime( $debut . ' 12:00' ) ) : '—' );
				if ( $fin ) {
					echo esc_html( ' → ' . wp_date( 'j M', strtotime( $fin . ' 12:00' ) ) );
				}
				break;
			case 'spa_disc':
				echo esc_html( spa_disciplines()[ $m( 'disc' ) ] ?? '—' );
				break;
			case 'spa_cat':
				echo esc_html( $m( 'cat' ) );
				break;
			case 'spa_lieu':
				$home = $m( 'home' );
				echo esc_html( '1' === $home ? 'Domicile' : $m( 'city' ) );
				break;
			case 'spa_etat':
				$fin = $m( 'end' ) ? $m( 'end' ) : $m( 'start' );
				echo $fin && $fin < wp_date( 'Y-m-d' ) ? '<span class="spa-passe">Passé</span>' : 'À venir';
				break;
		}
	},
	10,
	2
);

add_filter(
	'manage_spa_resultat_posts_columns',
	static function () {
		return array(
			'cb'           => '<input type="checkbox">',
			'title'        => 'Résultat',
			'spa_disc'     => 'Discipline',
			'spa_national' => 'Titre national',
			'spa_club'     => 'Page Le club',
			'spa_ordre'    => 'Ordre',
		);
	}
);
add_filter(
	'manage_spa_partenaire_posts_columns',
	static function () {
		return array(
			'cb'        => '<input type="checkbox">',
			'spa_logo'  => 'Logo',
			'title'     => 'Nom',
			'spa_type'  => 'Type',
			'spa_ordre' => 'Ordre',
		);
	}
);
add_filter(
	'manage_spa_histoire_posts_columns',
	static function () {
		return array(
			'cb'        => '<input type="checkbox">',
			'spa_annee' => 'Année',
			'title'     => 'Titre',
			'spa_ordre' => 'Ordre',
		);
	}
);
foreach ( array( 'spa_resultat', 'spa_partenaire', 'spa_histoire' ) as $spa_type ) {
	add_action(
		'manage_' . $spa_type . '_posts_custom_column',
		static function ( $colonne, $post_id ) {
			switch ( $colonne ) {
				case 'spa_disc':
					echo esc_html( spa_disciplines()[ get_post_meta( $post_id, 'disc', true ) ] ?? '—' );
					break;
				case 'spa_national':
					echo get_post_meta( $post_id, 'titre_national', true ) ? 'Oui' : '—';
					break;
				case 'spa_club':
					echo get_post_meta( $post_id, 'page_club', true ) ? 'Oui' : '—';
					break;
				case 'spa_ordre':
					echo (int) get_post_field( 'menu_order', $post_id );
					break;
				case 'spa_type':
					echo 'public' === get_post_meta( $post_id, 'type', true ) ? 'Public' : 'Privé';
					break;
				case 'spa_annee':
					echo esc_html( get_post_meta( $post_id, 'annee', true ) );
					break;
				case 'spa_logo':
					echo has_post_thumbnail( $post_id ) ? get_the_post_thumbnail( $post_id, array( 80, 48 ), array( 'style' => 'max-height:48px;width:auto' ) ) : '<em>Logo à fournir</em>';
					break;
			}
		},
		10,
		2
	);
}

/** Ordre par défaut des listes de l'administration. */
add_action(
	'pre_get_posts',
	static function ( $requete ) {
		if ( ! is_admin() || ! $requete->is_main_query() || $requete->get( 'orderby' ) ) {
			return;
		}
		$type = $requete->get( 'post_type' );
		if ( 'spa_evenement' === $type ) {
			$requete->set( 'meta_key', 'start' );
			$requete->set( 'orderby', 'meta_value' );
			$requete->set( 'order', 'ASC' );
		} elseif ( in_array( $type, array( 'spa_resultat', 'spa_partenaire', 'spa_histoire' ), true ) ) {
			$requete->set( 'orderby', array( 'menu_order' => 'ASC', 'ID' => 'ASC' ) );
		}
	}
);

/** Filtre par discipline dans la liste du calendrier. */
add_action(
	'restrict_manage_posts',
	static function ( $type ) {
		if ( 'spa_evenement' !== $type ) {
			return;
		}
		$actuel = isset( $_GET['spa_disc'] ) ? sanitize_key( $_GET['spa_disc'] ) : ''; // phpcs:ignore WordPress.Security.NonceVerification
		echo '<label class="screen-reader-text" for="spa-filtre-disc">Discipline</label><select name="spa_disc" id="spa-filtre-disc"><option value="">Toutes les disciplines</option>';
		foreach ( spa_disciplines() as $cle => $nom ) {
			printf( '<option value="%s" %s>%s</option>', esc_attr( $cle ), selected( $actuel, $cle, false ), esc_html( $nom ) );
		}
		echo '</select>';
	}
);
add_action(
	'pre_get_posts',
	static function ( $requete ) {
		if ( is_admin() && $requete->is_main_query() && 'spa_evenement' === $requete->get( 'post_type' ) && ! empty( $_GET['spa_disc'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
			$requete->set(
				'meta_query',
				array(
					array(
						'key'   => 'disc',
						'value' => sanitize_key( $_GET['spa_disc'] ), // phpcs:ignore WordPress.Security.NonceVerification
					),
				)
			);
		}
	}
);
