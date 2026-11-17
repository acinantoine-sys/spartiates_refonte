<?php
/**
 * Menu « Spartiates » et pages de réglages : horaires, lien d'adhésion et réglages du club.
 *
 * Les pages de réglages sont accessibles aux éditeurs (rôle « Éditeur » de
 * WordPress), pour que les bénévoles puissent les mettre à jour sans être
 * administrateurs du site.
 *
 * @package Spartiates_Club
 */

defined( 'ABSPATH' ) || exit;

/** Droit nécessaire pour modifier horaires et réglages. */
const SPA_DROIT = 'edit_pages';

/**
 * Réglages par défaut (structure de l'option spa_reglages).
 */
function spa_reglages_defaut() {
	return array(
		'adhesion_url'  => '',
		'lieu_domicile' => 'Stade du Grand Marais',
		'categories'    => array(
			'foot'  => array(),
			'flag'  => array(),
			'cheer' => array(),
		),
		'chiffres'      => array(
			'finales_nationales' => '',
			'benevoles'          => '',
			'licencies'          => '',
			'jours_de_match'     => '',
		),
		'coordonnees'   => array(
			'telephone' => '',
			'email'     => '',
			'lieu'      => '',
			'rue'       => '',
			'ville'     => '',
		),
		'reseaux'       => array(
			'instagram'        => '',
			'instagram_compte' => '',
			'facebook'         => '',
			'youtube'          => '',
			'tiktok'           => '',
		),
	);
}

/**
 * Champs de la page « Adhésion et réglages », par rubrique.
 */
function spa_champs_reglages() {
	return array(
		'adhesion'    => array(
			'titre'  => 'Lien d’adhésion HelloAsso',
			'intro'  => 'À changer une fois par saison. Tous les boutons d’adhésion du site utilisent ce lien : « Nous rejoindre », « Inscrire mon enfant », « Rejoindre l’équipe », « Devenir bénévole » et « Voir les tarifs et adhérer sur HelloAsso ».',
			'champs' => array(
				array( 'cle' => 'adhesion_url', 'label' => 'Adresse de la page HelloAsso de la saison', 'type' => 'url', 'requis' => true, 'placeholder' => 'https://www.helloasso.com/associations/…' ),
			),
		),
		'calendrier'  => array(
			'titre'  => 'Calendrier',
			'intro'  => 'Catégories proposées dans les filtres de la page Calendrier, une par ligne. Une catégorie utilisée dans une date mais absente d’ici est ajoutée automatiquement.',
			'champs' => array(
				array( 'cle' => 'lieu_domicile', 'label' => 'Lieu des matchs à domicile', 'type' => 'text', 'requis' => true ),
				array( 'cle' => 'categories.foot', 'label' => 'Catégories – Football américain', 'type' => 'textarea', 'lignes' => 4 ),
				array( 'cle' => 'categories.flag', 'label' => 'Catégories – Flag', 'type' => 'textarea', 'lignes' => 3 ),
				array( 'cle' => 'categories.cheer', 'label' => 'Catégories – Cheerleading', 'type' => 'textarea', 'lignes' => 4 ),
			),
		),
		'chiffres'    => array(
			'titre'  => 'Chiffres clés',
			'intro'  => 'Le nombre de titres nationaux n’est pas saisi ici : il est calculé à partir du palmarès (résultats cochés « Titre de champion de France »).',
			'champs' => array(
				array( 'cle' => 'chiffres.finales_nationales', 'label' => 'Finales nationales disputées', 'type' => 'text' ),
				array( 'cle' => 'chiffres.benevoles', 'label' => 'Bénévoles actifs (environ)', 'type' => 'text' ),
				array( 'cle' => 'chiffres.licencies', 'label' => 'Licenciés (environ)', 'type' => 'text' ),
				array( 'cle' => 'chiffres.jours_de_match', 'label' => 'Jours de match par saison', 'type' => 'text', 'aide' => 'Garder [XX] tant que le chiffre n’est pas connu.' ),
			),
		),
		'coordonnees' => array(
			'titre'  => 'Coordonnées du bureau',
			'champs' => array(
				array( 'cle' => 'coordonnees.telephone', 'label' => 'Téléphone', 'type' => 'text' ),
				array( 'cle' => 'coordonnees.email', 'label' => 'E-mail', 'type' => 'email' ),
				array( 'cle' => 'coordonnees.lieu', 'label' => 'Lieu', 'type' => 'text' ),
				array( 'cle' => 'coordonnees.rue', 'label' => 'Rue', 'type' => 'text' ),
				array( 'cle' => 'coordonnees.ville', 'label' => 'Code postal et ville', 'type' => 'text' ),
			),
		),
		'reseaux'     => array(
			'titre'  => 'Réseaux sociaux',
			'champs' => array(
				array( 'cle' => 'reseaux.instagram', 'label' => 'Instagram (adresse)', 'type' => 'url' ),
				array( 'cle' => 'reseaux.instagram_compte', 'label' => 'Instagram (nom du compte)', 'type' => 'text', 'placeholder' => '@spartiatesfoot' ),
				array( 'cle' => 'reseaux.facebook', 'label' => 'Facebook', 'type' => 'url' ),
				array( 'cle' => 'reseaux.youtube', 'label' => 'YouTube', 'type' => 'url' ),
				array( 'cle' => 'reseaux.tiktok', 'label' => 'TikTok', 'type' => 'url' ),
			),
		),
	);
}

/* -------------------------------------------------------------------------- */
/* Menu                                                                       */
/* -------------------------------------------------------------------------- */

add_action(
	'admin_menu',
	static function () {
		add_menu_page( 'Spartiates', 'Spartiates', 'edit_posts', 'spartiates', '__return_null', 'dashicons-shield', 3 );
		add_submenu_page( 'spartiates', 'Horaires', 'Horaires', SPA_DROIT, 'spartiates-horaires', 'spa_page_horaires' );
		add_submenu_page( 'spartiates', 'Adhésion et réglages', 'Adhésion et réglages', SPA_DROIT, 'spartiates-reglages', 'spa_page_reglages' );
		add_submenu_page( 'spartiates', 'Données de départ', 'Données de départ', 'manage_options', 'spartiates-import', 'spa_page_import' );
		// Le premier sous-menu (doublon automatique « Spartiates ») est retiré.
		remove_submenu_page( 'spartiates', 'spartiates' );
	},
	20
);

/* -------------------------------------------------------------------------- */
/* Enregistrement des options                                                 */
/* -------------------------------------------------------------------------- */

add_action(
	'admin_init',
	static function () {
		register_setting(
			'spa_reglages',
			'spa_reglages',
			array(
				'type'              => 'array',
				'sanitize_callback' => 'spa_nettoyer_reglages',
				'default'           => spa_reglages_defaut(),
			)
		);
		register_setting(
			'spa_horaires',
			'spa_horaires',
			array(
				'type'              => 'array',
				'sanitize_callback' => 'spa_nettoyer_horaires',
				'default'           => array(
					'bureau'        => array(),
					'entrainements' => array(),
				),
			)
		);
	}
);

// Les éditeurs peuvent enregistrer ces réglages via options.php.
add_filter( 'option_page_capability_spa_reglages', static fn() => SPA_DROIT );
add_filter( 'option_page_capability_spa_horaires', static fn() => SPA_DROIT );

/** Message d'erreur des réglages (sans effet hors de l'administration, par exemple avec WP-CLI). */
function spa_erreur( $reglage, $code, $message ) {
	if ( function_exists( 'add_settings_error' ) ) {
		add_settings_error( $reglage, $code, $message );
	}
}

/** Lit une valeur « a.b » dans un tableau. */
function spa_chemin( array $tableau, $chemin ) {
	foreach ( explode( '.', $chemin ) as $cle ) {
		if ( ! is_array( $tableau ) || ! array_key_exists( $cle, $tableau ) ) {
			return null;
		}
		$tableau = $tableau[ $cle ];
	}
	return $tableau;
}

/** Écrit une valeur « a.b » dans un tableau. */
function spa_chemin_ecrire( array &$tableau, $chemin, $valeur ) {
	$cles = explode( '.', $chemin );
	$ref  = &$tableau;
	foreach ( $cles as $cle ) {
		if ( ! isset( $ref[ $cle ] ) || ! is_array( $ref[ $cle ] ) ) {
			$ref[ $cle ] = isset( $ref[ $cle ] ) ? $ref[ $cle ] : array();
		}
		$ref = &$ref[ $cle ];
	}
	$ref = $valeur;
}

/**
 * Nettoie l'option spa_reglages.
 *
 * @param mixed $saisie Valeurs du formulaire.
 */
function spa_nettoyer_reglages( $saisie ) {
	$ancien  = get_option( 'spa_reglages', spa_reglages_defaut() );
	$propre  = spa_reglages_defaut();
	$saisie  = is_array( $saisie ) ? $saisie : array();
	foreach ( spa_champs_reglages() as $rubrique ) {
		foreach ( $rubrique['champs'] as $champ ) {
			$brut = spa_chemin( $saisie, $champ['cle'] );
			if ( 0 === strpos( $champ['cle'], 'categories.' ) ) {
				$lignes = is_array( $brut ) ? $brut : preg_split( '/\r\n|\r|\n/', (string) $brut );
				$valeur = array_values( array_filter( array_map( 'sanitize_text_field', $lignes ), 'strlen' ) );
			} else {
				$valeur = spa_nettoyer_champ( $champ, $brut );
			}
			spa_chemin_ecrire( $propre, $champ['cle'], $valeur );
		}
	}
	if ( '' === $propre['adhesion_url'] ) {
		spa_erreur( 'spa_reglages', 'spa_adhesion', 'Le lien d’adhésion HelloAsso est obligatoire : l’ancien lien a été conservé.' );
		$propre['adhesion_url'] = is_array( $ancien ) ? (string) ( $ancien['adhesion_url'] ?? '' ) : '';
	}
	return $propre;
}

/**
 * Nettoie l'option spa_horaires. En cas d'erreur, rien n'est enregistré.
 *
 * @param mixed $saisie Valeurs du formulaire (ou tableau déjà structuré lors de l'import).
 */
function spa_nettoyer_horaires( $saisie ) {
	$ancien  = get_option( 'spa_horaires', array() );
	$saisie  = is_array( $saisie ) ? $saisie : array();
	$erreurs = array();
	$bureau  = array();

	foreach ( Spa_Horaires::JOURS as $jour ) {
		$brut = $saisie['bureau'][ $jour ] ?? '';
		if ( is_array( $brut ) ) { // Import : déjà au format [[début, fin], …].
			$brut = Spa_Horaires::ecrire_plages( $brut );
		}
		$plages = Spa_Horaires::lire_plages( sanitize_text_field( $brut ) );
		if ( null === $plages ) {
			$erreurs[] = sprintf( 'Bureau, %1$s : « %2$s » n’est pas compris. Écrire par exemple 9h-17h ou 9h-12h, 14h-17h.', $jour, $brut );
		} elseif ( $plages ) {
			$bureau[ $jour ] = $plages;
		}
	}

	$entrainements = array();
	foreach ( (array) ( $saisie['entrainements'] ?? array() ) as $i => $ligne ) {
		$ligne = array_map( 'sanitize_text_field', (array) $ligne );
		$ligne += array( 'day' => '', 'from' => '', 'to' => '', 'label' => '', 'place' => '' );
		if ( '' === $ligne['from'] . $ligne['to'] . $ligne['label'] . $ligne['place'] ) {
			continue; // Ligne vide : ignorée (c'est ainsi qu'on supprime un créneau).
		}
		$debut = Spa_Horaires::en_minutes( $ligne['from'] );
		$fin   = Spa_Horaires::en_minutes( $ligne['to'] );
		if ( ! in_array( $ligne['day'], Spa_Horaires::JOURS, true ) || null === $debut || null === $fin || $fin <= $debut || '' === $ligne['label'] ) {
			$erreurs[] = sprintf( 'Entraînement n° %1$d (%2$s) : vérifier le jour, les heures (format 18h30) et l’équipe.', (int) $i + 1, $ligne['label'] ? $ligne['label'] : 'sans équipe' );
			continue;
		}
		$creneau = array(
			'day'   => $ligne['day'],
			'from'  => Spa_Horaires::formater( $debut ),
			'to'    => Spa_Horaires::formater( $fin ),
			'label' => $ligne['label'],
		);
		if ( '' !== $ligne['place'] ) {
			$creneau['place'] = $ligne['place'];
		}
		$entrainements[] = $creneau;
	}

	if ( $erreurs ) {
		foreach ( $erreurs as $n => $message ) {
			spa_erreur( 'spa_horaires', 'spa_horaires_' . $n, $message );
		}
		spa_erreur( 'spa_horaires', 'spa_horaires_rien', 'Les horaires n’ont pas été modifiés : corrigez les erreurs ci-dessus puis enregistrez de nouveau.' );
		return $ancien;
	}

	return array(
		'bureau'        => $bureau,
		'entrainements' => $entrainements,
	);
}

/* -------------------------------------------------------------------------- */
/* Pages                                                                      */
/* -------------------------------------------------------------------------- */

/**
 * Page « Horaires ».
 */
function spa_page_horaires() {
	if ( ! current_user_can( SPA_DROIT ) ) {
		return;
	}
	$horaires = get_option( 'spa_horaires', array() );
	$bureau   = (array) ( $horaires['bureau'] ?? array() );
	$lignes   = array_values( (array) ( $horaires['entrainements'] ?? array() ) );
	for ( $i = 0; $i < 3; $i++ ) {
		$lignes[] = array( 'day' => 'Lundi', 'from' => '', 'to' => '', 'label' => '', 'place' => '' );
	}
	$calcul = spa_horaires();
	?>
	<div class="wrap spa-page">
		<h1>Horaires du club</h1>
		<p class="spa-intro">Ces horaires sont la source unique de tout le site : pied de page, page Contact, pages Football américain, Flag et Cheerleading. Les horaires d’ouverture du stade ne se saisissent pas : ils sont calculés à partir du bureau et des entraînements au stade.</p>
		<?php settings_errors(); ?>
		<form method="post" action="options.php">
			<?php settings_fields( 'spa_horaires' ); ?>

			<h2>Ouverture du bureau</h2>
			<p class="description">Une ou plusieurs plages par jour, par exemple <code>9h-17h</code> ou <code>9h-12h, 14h-17h</code>. Laisser vide si le bureau est fermé ce jour-là.</p>
			<table class="form-table spa-table-bureau" role="presentation">
				<?php foreach ( Spa_Horaires::JOURS as $jour ) : ?>
					<tr>
						<th scope="row"><label for="spa-bureau-<?php echo esc_attr( $jour ); ?>"><?php echo esc_html( $jour ); ?></label></th>
						<td><input type="text" class="regular-text" id="spa-bureau-<?php echo esc_attr( $jour ); ?>" name="spa_horaires[bureau][<?php echo esc_attr( $jour ); ?>]" value="<?php echo esc_attr( Spa_Horaires::ecrire_plages( (array) ( $bureau[ $jour ] ?? array() ) ) ); ?>" placeholder="Fermé"></td>
					</tr>
				<?php endforeach; ?>
			</table>

			<h2>Entraînements</h2>
			<p class="description">Un créneau par ligne. <strong>Lieu</strong> : laisser vide si l’entraînement a lieu au stade (il compte alors dans l’ouverture du stade), sinon indiquer le lieu (par exemple Halle des sports d’Étouvie). Le nom de l’équipe doit commencer par la discipline (Football…, Flag…, Cheerleading…) pour apparaître sur la bonne page. Pour supprimer un créneau, videz sa ligne.</p>
			<table class="widefat striped spa-table-entrainements">
				<thead>
					<tr>
						<th scope="col">Jour</th>
						<th scope="col">De</th>
						<th scope="col">À</th>
						<th scope="col">Équipe</th>
						<th scope="col">Lieu (vide = au stade)</th>
						<th scope="col"><span class="screen-reader-text">Actions</span></th>
					</tr>
				</thead>
				<tbody data-spa-repeteur>
					<?php foreach ( $lignes as $i => $l ) : ?>
						<tr>
							<td>
								<label class="screen-reader-text" for="spa-e-<?php echo (int) $i; ?>-day">Jour</label>
								<select id="spa-e-<?php echo (int) $i; ?>-day" name="spa_horaires[entrainements][<?php echo (int) $i; ?>][day]">
									<?php foreach ( Spa_Horaires::JOURS as $jour ) : ?>
										<option <?php selected( $l['day'], $jour ); ?>><?php echo esc_html( $jour ); ?></option>
									<?php endforeach; ?>
								</select>
							</td>
							<?php
							foreach ( array(
								'from'  => array( 'De', '18h30', 'small-text' ),
								'to'    => array( 'À', '20h', 'small-text' ),
								'label' => array( 'Équipe', 'Football U19', 'regular-text' ),
								'place' => array( 'Lieu', 'Au stade', 'regular-text' ),
							) as $cle => $c ) :
								?>
								<td>
									<label class="screen-reader-text" for="spa-e-<?php echo (int) $i . '-' . esc_attr( $cle ); ?>"><?php echo esc_html( $c[0] ); ?></label>
									<input type="text" class="<?php echo esc_attr( $c[2] ); ?>" id="spa-e-<?php echo (int) $i . '-' . esc_attr( $cle ); ?>" name="spa_horaires[entrainements][<?php echo (int) $i; ?>][<?php echo esc_attr( $cle ); ?>]" value="<?php echo esc_attr( $l[ $cle ] ?? '' ); ?>" placeholder="<?php echo esc_attr( $c[1] ); ?>">
								</td>
							<?php endforeach; ?>
							<td><button type="button" class="button-link spa-vider" data-spa-vider>Vider la ligne</button></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
			<p><button type="button" class="button" data-spa-ajouter hidden>Ajouter un créneau</button></p>

			<?php submit_button( 'Enregistrer les horaires' ); ?>
		</form>

		<h2>Aperçu : ouverture du stade (calculée)</h2>
		<table class="widefat striped spa-apercu">
			<tbody>
				<?php foreach ( $calcul->ouverture_stade() as $j ) : ?>
					<tr><th scope="row"><?php echo esc_html( $j['day'] ); ?></th><td><?php echo esc_html( $j['hours'] ); ?></td></tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		<h2>Aperçu : pied de page</h2>
		<p><?php echo esc_html( implode( ' · ', $calcul->lignes_pied_de_page() ) ); ?></p>
	</div>
	<?php
}

/**
 * Page « Adhésion et réglages ».
 */
function spa_page_reglages() {
	if ( ! current_user_can( SPA_DROIT ) ) {
		return;
	}
	$reglages = spa_reglages();
	?>
	<div class="wrap spa-page">
		<h1>Adhésion et réglages</h1>
		<?php settings_errors(); ?>
		<form method="post" action="options.php">
			<?php settings_fields( 'spa_reglages' ); ?>
			<?php foreach ( spa_champs_reglages() as $id => $rubrique ) : ?>
				<section class="spa-rubrique spa-rubrique--<?php echo esc_attr( $id ); ?>">
					<h2><?php echo esc_html( $rubrique['titre'] ); ?></h2>
					<?php if ( ! empty( $rubrique['intro'] ) ) : ?>
						<p class="spa-intro"><?php echo esc_html( $rubrique['intro'] ); ?></p>
					<?php endif; ?>
					<?php
					foreach ( $rubrique['champs'] as $champ ) {
						$valeur = spa_chemin( $reglages, $champ['cle'] );
						if ( is_array( $valeur ) ) {
							$valeur = implode( "\n", $valeur );
						}
						$nom = 'spa_reglages[' . str_replace( '.', '][', $champ['cle'] ) . ']';
						spa_afficher_champ( $champ, $valeur, $nom );
					}
					?>
				</section>
			<?php endforeach; ?>
			<?php submit_button( 'Enregistrer les réglages' ); ?>
		</form>
	</div>
	<?php
}
