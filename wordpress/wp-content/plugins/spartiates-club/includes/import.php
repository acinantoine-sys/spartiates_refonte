<?php
/**
 * Import des données de départ (dossier donnees-initiales/, copie de data/ du
 * dossier de transmission) et création des pages du site.
 *
 * Après l'import, la source unique de chaque donnée est l'administration de
 * WordPress (menu « Spartiates ») : les fichiers JSON ne servent plus.
 *
 * Lancement : automatiquement à l'activation de l'extension, depuis
 * « Spartiates > Données de départ », ou avec WP-CLI : wp spartiates importer
 *
 * @package Spartiates_Club
 */

defined( 'ABSPATH' ) || exit;

/** Pages du site : slug => titre. Le thème fournit un gabarit pour chacune. */
const SPA_PAGES = array(
	'accueil'            => 'Accueil',
	'football-americain' => 'Football américain',
	'flag'               => 'Flag',
	'cheerleading'       => 'Cheerleading',
	'calendrier'         => 'Calendrier',
	'le-club'            => 'Le club',
	'contact'            => 'Contact',
	'mentions-legales'   => 'Mentions légales',
);

/**
 * L'option existe-t-elle vraiment en base ? (get_option() sans valeur par défaut
 * explicite renverrait la valeur par défaut déclarée par register_setting().)
 */
function spa_option_existe( $nom ) {
	return false !== get_option( $nom, false );
}

function spa_lire_json( $fichier ) {
	$chemin = SPA_CLUB_DIR . 'donnees-initiales/' . $fichier;
	if ( ! is_readable( $chemin ) ) {
		return array();
	}
	$donnees = json_decode( (string) file_get_contents( $chemin ), true ); // phpcs:ignore WordPress.WP.AlternativeFunctions
	return is_array( $donnees ) ? $donnees : array();
}

/**
 * Supprime les contenus d'un type (pour un import forcé).
 */
function spa_vider_type( $type ) {
	foreach ( get_posts( array( 'post_type' => $type, 'post_status' => 'any', 'posts_per_page' => -1, 'fields' => 'ids' ) ) as $id ) {
		wp_delete_post( $id, true );
	}
}

/**
 * Crée un contenu avec ses champs.
 */
function spa_creer( $type, $titre, array $champs, $ordre = 0 ) {
	$id = wp_insert_post(
		array(
			'post_type'   => $type,
			'post_status' => 'publish',
			'post_title'  => $titre,
			'menu_order'  => $ordre,
		),
		true
	);
	if ( is_wp_error( $id ) ) {
		return 0;
	}
	foreach ( $champs as $cle => $valeur ) {
		update_post_meta( $id, $cle, is_bool( $valeur ) ? ( $valeur ? '1' : '0' ) : (string) $valeur );
	}
	return $id;
}

/**
 * Ajoute un fichier de donnees-initiales/ à la médiathèque.
 */
function spa_importer_image( $fichier, $titre ) {
	$chemin = SPA_CLUB_DIR . 'donnees-initiales/' . $fichier;
	if ( ! is_readable( $chemin ) ) {
		return 0;
	}
	require_once ABSPATH . 'wp-admin/includes/image.php';
	$envoi = wp_upload_bits( basename( $chemin ), null, (string) file_get_contents( $chemin ) ); // phpcs:ignore WordPress.WP.AlternativeFunctions
	if ( ! empty( $envoi['error'] ) ) {
		return 0;
	}
	$type = wp_check_filetype( $envoi['file'] );
	$id   = wp_insert_attachment(
		array(
			'post_mime_type' => $type['type'],
			'post_title'     => $titre,
			'post_status'    => 'inherit',
		),
		$envoi['file']
	);
	if ( ! $id || is_wp_error( $id ) ) {
		return 0;
	}
	wp_update_attachment_metadata( $id, wp_generate_attachment_metadata( $id, $envoi['file'] ) );
	update_post_meta( $id, '_wp_attachment_image_alt', $titre );
	return $id;
}

/**
 * Importe toutes les données de départ.
 *
 * @param bool $forcer true = remplace les contenus existants.
 * @return string[] Compte rendu.
 */
function spa_importer_donnees_initiales( $forcer = false ) {
	$journal = array();
	spa_enregistrer_types_de_contenu();

	// Réglages (adhésion, catégories, chiffres, coordonnées, réseaux).
	$reglages = spa_lire_json( 'reglages.json' );
	$adhesion = spa_lire_json( 'adhesion.json' );
	$cal      = spa_lire_json( 'calendrier.json' );
	if ( $forcer || ! spa_option_existe( 'spa_reglages' ) ) {
		$valeurs = array_replace_recursive(
			spa_reglages_defaut(),
			$reglages,
			array(
				'adhesion_url' => $adhesion['adhesion_url'] ?? '',
				'categories'   => $cal['categories'] ?? array(),
			)
		);
		if ( ! empty( $cal['lieu_domicile'] ) ) {
			$valeurs['lieu_domicile'] = $cal['lieu_domicile'];
		}
		update_option( 'spa_reglages', $valeurs );
		$journal[] = 'Réglages et lien d’adhésion importés.';
	}

	// Horaires.
	if ( $forcer || ! spa_option_existe( 'spa_horaires' ) ) {
		update_option( 'spa_horaires', spa_nettoyer_horaires( spa_lire_json( 'horaires.json' ) ) );
		$journal[] = 'Horaires importés.';
	}

	// Calendrier.
	if ( $forcer ) {
		spa_vider_type( 'spa_evenement' );
	}
	if ( ! get_posts( array( 'post_type' => 'spa_evenement', 'post_status' => 'any', 'numberposts' => 1, 'fields' => 'ids' ) ) ) {
		$n = 0;
		foreach ( (array) ( $cal['evenements'] ?? array() ) as $e ) {
			$titre = ( $e['type'] ?? 'match' ) === 'match' ? 'Spartiates – ' . ( $e['opp'] ?? '' ) : ( $e['name'] ?? '' );
			$n    += (bool) spa_creer(
				'spa_evenement',
				$e['start'] . ' · ' . ( $e['cat'] ?? '' ) . ' · ' . $titre,
				array(
					'disc'  => $e['disc'] ?? '',
					'cat'   => $e['cat'] ?? '',
					'type'  => $e['type'] ?? 'match',
					'start' => $e['start'] ?? '',
					'end'   => $e['end'] ?? '',
					'opp'   => $e['opp'] ?? '',
					'name'  => $e['name'] ?? '',
					'city'  => $e['city'] ?? '',
					'time'  => $e['time'] ?? '',
					'home'  => array_key_exists( 'home', $e ) ? ( $e['home'] ? '1' : '0' ) : '',
				)
			);
		}
		$journal[] = sprintf( 'Calendrier : %d dates importées.', $n );
	}

	// Palmarès.
	if ( $forcer ) {
		spa_vider_type( 'spa_resultat' );
	}
	if ( ! get_posts( array( 'post_type' => 'spa_resultat', 'post_status' => 'any', 'numberposts' => 1, 'fields' => 'ids' ) ) ) {
		$n = 0;
		foreach ( (array) ( spa_lire_json( 'resultats.json' )['resultats'] ?? array() ) as $i => $r ) {
			$titre = $r['annee'] . ' · ' . $r['titre'] . ' · ' . $r['cat'] . ( $r['classement'] ? ' · ' . $r['classement'] : '' );
			$n    += (bool) spa_creer( 'spa_resultat', $titre, $r, ( $i + 1 ) * 10 );
		}
		$journal[] = sprintf( 'Palmarès : %d résultats importés.', $n );
	}

	// Partenaires.
	if ( $forcer ) {
		spa_vider_type( 'spa_partenaire' );
	}
	if ( ! get_posts( array( 'post_type' => 'spa_partenaire', 'post_status' => 'any', 'numberposts' => 1, 'fields' => 'ids' ) ) ) {
		$n = 0;
		foreach ( (array) ( spa_lire_json( 'partenaires.json' )['partenaires'] ?? array() ) as $i => $p ) {
			$id = spa_creer( 'spa_partenaire', $p['name'], array( 'type' => $p['type'], 'url' => $p['url'] ?? '' ), ( $i + 1 ) * 10 );
			if ( $id && ! empty( $p['logo'] ) ) {
				$logo = spa_importer_image( $p['logo'], $p['name'] );
				if ( $logo ) {
					set_post_thumbnail( $id, $logo );
				}
			}
			$n += (bool) $id;
		}
		$journal[] = sprintf( 'Partenaires : %d importés.', $n );
	}

	// Histoire.
	if ( $forcer ) {
		spa_vider_type( 'spa_histoire' );
	}
	if ( ! get_posts( array( 'post_type' => 'spa_histoire', 'post_status' => 'any', 'numberposts' => 1, 'fields' => 'ids' ) ) ) {
		$n = 0;
		foreach ( (array) ( spa_lire_json( 'historique.json' )['chronologie'] ?? array() ) as $i => $h ) {
			$n += (bool) spa_creer( 'spa_histoire', $h['title'], array( 'annee' => $h['year'], 'texte' => $h['text'] ), ( $i + 1 ) * 10 );
		}
		$journal[] = sprintf( 'Histoire : %d étapes importées.', $n );
	}

	$journal = array_merge( $journal, spa_creer_pages() );
	update_option( 'spa_import_fait', current_time( 'mysql' ) );
	return $journal;
}

/**
 * Crée les pages du site (si elles n'existent pas) et règle la page d'accueil,
 * les permaliens et le fuseau horaire.
 */
function spa_creer_pages() {
	$journal = array();
	foreach ( SPA_PAGES as $slug => $titre ) {
		if ( get_page_by_path( $slug ) ) {
			continue;
		}
		$contenu = 'mentions-legales' === $slug
			? '<!-- wp:paragraph --><p>[Mentions légales et politique de confidentialité à rédiger]</p><!-- /wp:paragraph -->'
			: '';
		wp_insert_post(
			array(
				'post_type'    => 'page',
				'post_status'  => 'publish',
				'post_name'    => $slug,
				'post_title'   => $titre,
				'post_content' => $contenu,
			)
		);
		$journal[] = sprintf( 'Page « %s » créée.', $titre );
	}
	$accueil = get_page_by_path( 'accueil' );
	if ( $accueil && 'page' !== get_option( 'show_on_front' ) ) {
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $accueil->ID );
		$journal[] = 'Page d’accueil réglée.';
	}
	if ( ! get_option( 'permalink_structure' ) ) {
		update_option( 'permalink_structure', '/%postname%/' );
		$journal[] = 'Permaliens réglés sur /nom-de-la-page/.';
	}
	if ( ! get_option( 'timezone_string' ) ) {
		update_option( 'timezone_string', 'Europe/Paris' );
		$journal[] = 'Fuseau horaire réglé sur Europe/Paris (utile pour griser les dates passées).';
	}
	flush_rewrite_rules();
	return $journal;
}

/**
 * Page « Données de départ ».
 */
function spa_page_import() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$journal = array();
	if ( isset( $_POST['spa_importer'] ) && check_admin_referer( 'spa_importer' ) ) {
		$journal = spa_importer_donnees_initiales( ! empty( $_POST['spa_forcer'] ) );
	}
	$fait = get_option( 'spa_import_fait' );
	?>
	<div class="wrap spa-page">
		<h1>Données de départ</h1>
		<p class="spa-intro">Les données du dossier de transmission (calendrier, horaires, lien d’adhésion, partenaires, palmarès, histoire) sont importées une fois, à l’installation. Ensuite, tout se modifie dans les écrans du menu « Spartiates ».</p>
		<?php if ( $journal ) : ?>
			<div class="notice notice-success"><ul>
				<?php foreach ( $journal as $ligne ) : ?>
					<li><?php echo esc_html( $ligne ); ?></li>
				<?php endforeach; ?>
			</ul></div>
		<?php endif; ?>
		<p><?php echo $fait ? esc_html( 'Dernier import : ' . $fait . '.' ) : 'Aucun import pour l’instant.'; ?></p>
		<form method="post">
			<?php wp_nonce_field( 'spa_importer' ); ?>
			<p><label><input type="checkbox" name="spa_forcer" value="1"> Remplacer les données existantes (attention : les modifications faites depuis l’installation seront perdues)</label></p>
			<?php submit_button( 'Importer les données de départ', 'secondary', 'spa_importer' ); ?>
		</form>
	</div>
	<?php
}

if ( defined( 'WP_CLI' ) && WP_CLI ) {
	/**
	 * Importe les données de départ des Spartiates.
	 *
	 * ## OPTIONS
	 *
	 * [--forcer]
	 * : Remplace les données existantes.
	 *
	 * ## EXEMPLES
	 *
	 *     wp spartiates importer
	 *     wp spartiates importer --forcer
	 */
	WP_CLI::add_command(
		'spartiates importer',
		static function ( $args, $options ) {
			foreach ( spa_importer_donnees_initiales( ! empty( $options['forcer'] ) ) as $ligne ) {
				WP_CLI::log( $ligne );
			}
			WP_CLI::success( 'Import terminé.' );
		}
	);
}
