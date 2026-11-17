<?php
/**
 * Aperçu statique du thème, SANS WordPress.
 *
 * Simule les quelques fonctions WordPress utilisées par le thème, lit les
 * données de départ (wordpress/wp-content/plugins/spartiates-club/donnees-initiales)
 * et génère une page HTML par gabarit dans outils/apercu/site/.
 *
 * Usage :
 *   php outils/apercu/generer.php [AAAA-MM-JJ]     (date du jour simulée, facultative)
 *   php -S localhost:8080 -t outils/apercu/site
 *
 * Cet outil sert à vérifier la mise en page et à détecter les erreurs des gabarits.
 * Il ne remplace pas un test dans un vrai WordPress avant la mise en ligne.
 */

error_reporting( E_ALL );
ini_set( 'display_errors', '1' );
set_error_handler(
	static function ( $niveau, $message, $fichier, $ligne ) {
		if ( ! ( error_reporting() & $niveau ) ) {
			return false; // Erreur volontairement ignorée avec @.
		}
		fwrite( STDERR, "ERREUR PHP : $message ($fichier:$ligne)\n" );
		exit( 1 );
	}
);

define( 'ABSPATH', __DIR__ . '/' );
$racine  = dirname( __DIR__, 2 );
$theme   = $racine . '/wordpress/wp-content/themes/spartiates';
$plugin  = $racine . '/wordpress/wp-content/plugins/spartiates-club';
$sortie  = __DIR__ . '/site';
$GLOBALS['spa_aujourdhui'] = $argv[1] ?? date( 'Y-m-d' );

require $plugin . '/includes/class-spa-horaires.php';
require $plugin . '/includes/class-spa-calendrier.php';
require $plugin . '/includes/class-spa-palmares.php';

/* ---------------------------------------------------------------------- */
/* Données (remplacent includes/donnees.php de l'extension)                */
/* ---------------------------------------------------------------------- */

function apercu_json( $f ) {
	return json_decode( file_get_contents( $GLOBALS['plugin'] . '/donnees-initiales/' . $f ), true );
}
$GLOBALS['plugin'] = $plugin;

function spa_reglages() {
	static $r = null;
	if ( null === $r ) {
		$cal = apercu_json( 'calendrier.json' );
		$r   = apercu_json( 'reglages.json' ) + array(
			'adhesion_url' => apercu_json( 'adhesion.json' )['adhesion_url'],
			'categories'   => $cal['categories'],
		);
	}
	return $r;
}
function spa_reglage( $chemin ) {
	$v = spa_reglages();
	foreach ( explode( '.', $chemin ) as $k ) {
		$v = $v[ $k ] ?? null;
	}
	return $v;
}
function spa_adhesion_url() {
	return spa_reglage( 'adhesion_url' );
}
function spa_horaires() {
	$h = apercu_json( 'horaires.json' );
	return new Spa_Horaires( $h['bureau'], $h['entrainements'] );
}
function spa_calendrier() {
	$c = apercu_json( 'calendrier.json' );
	return new Spa_Calendrier( $c['evenements'], spa_reglage( 'categories' ), spa_reglage( 'lieu_domicile' ), $GLOBALS['spa_aujourdhui'] );
}
function spa_palmares() {
	return new Spa_Palmares( apercu_json( 'resultats.json' )['resultats'] );
}
function spa_nombre_titres_nationaux() {
	return spa_palmares()->nombre_titres_nationaux();
}
function spa_partenaires() {
	$l = array( 'public' => array(), 'prive' => array() );
	foreach ( apercu_json( 'partenaires.json' )['partenaires'] as $p ) {
		$l[ $p['type'] ][] = array( 'name' => $p['name'], 'type' => $p['type'], 'logo_id' => $p['logo'], 'url' => $p['url'] ?? '' );
	}
	return $l;
}
function spa_chronologie() {
	return apercu_json( 'historique.json' )['chronologie'];
}

/* ---------------------------------------------------------------------- */
/* Fonctions WordPress simulées                                            */
/* ---------------------------------------------------------------------- */

$GLOBALS['hooks']  = array();
$GLOBALS['styles'] = array();
$GLOBALS['page']   = 'accueil';

function add_action( $h, $f, $p = 10 ) {
	$GLOBALS['hooks'][ $h ][ $p ][] = $f;
}
function add_filter( $h, $f, $p = 10 ) {
	add_action( $h, $f, $p );
}
function do_action( $h, ...$a ) {
	$liste = $GLOBALS['hooks'][ $h ] ?? array();
	ksort( $liste );
	foreach ( $liste as $fs ) {
		foreach ( $fs as $f ) {
			$f( ...$a );
		}
	}
}
function add_theme_support() {}
function remove_post_type_support() {}
function get_template_directory() {
	return $GLOBALS['theme'];
}
$GLOBALS['theme'] = $theme;
function get_theme_file_uri( $f ) {
	return '/wp-content/themes/spartiates/' . $f;
}
function get_theme_file_path( $f ) {
	return $GLOBALS['theme'] . '/' . $f;
}
function get_stylesheet_uri() {
	return get_theme_file_uri( 'style.css' );
}
function wp_enqueue_style( $id, $src ) {
	$GLOBALS['styles'][ $id ] = $src;
}
function wp_dequeue_style() {}
function wp_enqueue_script( $id, $src ) {
	$GLOBALS['scripts'][ $id ] = $src;
}
function language_attributes() {
	echo 'lang="fr-FR"';
}
function bloginfo() {
	echo 'UTF-8';
}
function wp_head() {
	do_action( 'wp_enqueue_scripts' );
	$titres = array( 'accueil' => '' ) + spt_navigation() + array( 'mentions-legales' => 'Mentions légales' );
	$t      = $titres[ $GLOBALS['page'] ] ?? '';
	echo '<title>' . ( $t ? esc_html( $t ) . ' – ' : '' ) . 'Les Spartiates d’Amiens</title>' . "\n";
	do_action( 'wp_head' );
	foreach ( $GLOBALS['styles'] as $src ) {
		echo '<link rel="stylesheet" href="' . esc_url( $src ) . '">' . "\n";
	}
}
function wp_footer() {
	foreach ( $GLOBALS['scripts'] as $src ) {
		echo '<script src="' . esc_url( $src ) . '" defer></script>' . "\n";
	}
}
function body_class() {
	echo 'class="page-' . esc_attr( $GLOBALS['page'] ) . '"';
}
function wp_body_open() {}
function esc_html( $t ) {
	return htmlspecialchars( (string) $t, ENT_QUOTES, 'UTF-8', false );
}
function esc_attr( $t ) {
	return esc_html( $t );
}
function esc_url( $u ) {
	return esc_html( $u );
}
function wp_kses( $t ) {
	return $t;
}
function home_url( $p = '/' ) {
	return $p;
}
function get_page_by_path( $slug ) {
	return null;
}
function is_front_page() {
	return 'accueil' === $GLOBALS['page'];
}
function is_page( $slug = null ) {
	if ( null === $slug ) {
		return 'accueil' !== $GLOBALS['page'];
	}
	return in_array( $GLOBALS['page'], (array) $slug, true );
}
function is_singular() {
	return true;
}
function get_post_field() {
	return $GLOBALS['page'];
}
function get_queried_object_id() {
	return 1;
}
function get_template_part( $slug, $nom = null, $args = array() ) {
	require $GLOBALS['theme'] . '/' . $slug . '.php';
}
function get_header() {
	require $GLOBALS['theme'] . '/header.php';
}
function get_footer() {
	require $GLOBALS['theme'] . '/footer.php';
}
function wp_date( $format ) {
	return date( $format, strtotime( $GLOBALS['spa_aujourdhui'] ) );
}
function wp_json_encode( $v ) {
	return json_encode( $v, JSON_UNESCAPED_UNICODE );
}
function wp_get_attachment_image( $logo, $taille, $icone, $attrs ) {
	return '<img src="/donnees-initiales/' . esc_attr( $logo ) . '" alt="' . esc_attr( $attrs['alt'] ) . '" loading="lazy">';
}
function current_user_can() {
	return false;
}
$GLOBALS['boucle'] = 0;
function have_posts() {
	return $GLOBALS['boucle']++ < 1;
}
function the_post() {}
function get_the_title() {
	return 'Mentions légales';
}
function the_title() {
	echo esc_html( get_the_title() );
}
function the_content() {
	echo '<p>[Mentions légales et politique de confidentialité à rédiger]</p>';
}

require $theme . '/functions.php';

/* ---------------------------------------------------------------------- */
/* Génération                                                              */
/* ---------------------------------------------------------------------- */

$gabarits = array(
	'accueil'            => 'front-page.php',
	'football-americain' => 'page-football-americain.php',
	'flag'               => 'page-flag.php',
	'cheerleading'       => 'page-cheerleading.php',
	'calendrier'         => 'page-calendrier.php',
	'le-club'            => 'page-le-club.php',
	'contact'            => 'page-contact.php',
	'mentions-legales'   => 'page.php',
	'404'                => '404.php',
);

@mkdir( $sortie . '/wp-content/themes', 0777, true );
if ( ! file_exists( $sortie . '/wp-content/themes/spartiates' ) ) {
	symlink( $theme, $sortie . '/wp-content/themes/spartiates' );
}
if ( ! file_exists( $sortie . '/donnees-initiales' ) ) {
	symlink( $plugin . '/donnees-initiales', $sortie . '/donnees-initiales' );
}

foreach ( $gabarits as $slug => $fichier ) {
	$GLOBALS['page']    = $slug;
	$GLOBALS['styles']  = array();
	$GLOBALS['scripts'] = array();
	$GLOBALS['boucle']  = 0;
	ob_start();
	require $theme . '/' . $fichier;
	$html    = ob_get_clean();
	$dossier = 'accueil' === $slug ? $sortie : $sortie . '/' . $slug;
	@mkdir( $dossier, 0777, true );
	file_put_contents( $dossier . '/index.html', $html );
	echo "$slug : " . strlen( $html ) . " octets\n";
}
