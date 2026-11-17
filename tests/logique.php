<?php
/**
 * Tests de la logique métier (horaires, calendrier, palmarès), sans WordPress.
 *
 * Lancer : php tests/logique.php
 *
 * Les valeurs attendues sont celles des maquettes (design/maquettes) et des
 * données du dossier de transmission (data/).
 */

define( 'SPA_TESTS', true );

$racine = dirname( __DIR__ );
$plugin = $racine . '/wordpress/wp-content/plugins/spartiates-club';
require $plugin . '/includes/class-spa-horaires.php';
require $plugin . '/includes/class-spa-calendrier.php';
require $plugin . '/includes/class-spa-palmares.php';

$echecs = 0;
$total  = 0;
function verifier( $nom, $obtenu, $attendu ) {
	global $echecs, $total;
	++$total;
	if ( $obtenu === $attendu ) {
		return;
	}
	++$echecs;
	echo "ÉCHEC : $nom\n  attendu : " . var_export( $attendu, true ) . "\n  obtenu  : " . var_export( $obtenu, true ) . "\n";
}
function json( $chemin ) {
	return json_decode( file_get_contents( $chemin ), true );
}

/* ---------------------------------------------------------------------- */
/* Horaires                                                                */
/* ---------------------------------------------------------------------- */

$h = json( $racine . '/data/horaires.json' );
$horaires = new Spa_Horaires( $h['bureau'], $h['entrainements'] );

verifier( 'formater 9h', Spa_Horaires::formater( 540 ), '9h' );
verifier( 'formater 19h30', Spa_Horaires::formater( 1170 ), '19h30' );
verifier( 'heure invalide', Spa_Horaires::en_minutes( '25h' ), null );
verifier( 'normaliser 9H00', Spa_Horaires::normaliser( '9H00' ), '9h' );

verifier(
	'pied de page : jours regroupés',
	$horaires->lignes_pied_de_page(),
	array( 'Lundi, jeudi, vendredi 9h–17h', 'Mardi 9h–13h' )
);

verifier(
	'tableau du bureau',
	array_column( $horaires->tableau_bureau(), 'hours', 'day' ),
	array(
		'Lundi'    => '9h – 17h',
		'Mardi'    => '9h – 13h',
		'Mercredi' => 'Fermé',
		'Jeudi'    => '9h – 17h',
		'Vendredi' => '9h – 17h',
		'Samedi'   => 'Fermé',
		'Dimanche' => 'Fermé',
	)
);

// Ouverture du stade : bureau + entraînements sans lieu, fusionnés.
// Mardi : bureau 9h-13h ; Football seniors 19h30-21h30 + Cheer 18h30-20h30 → 18h30-21h30.
// Mercredi : Flag jeunes 14h-16h ; U15 16h30-18h30 + U19 18h30-20h + Flag seniors 20h-22h (se touchent) → 16h30-22h.
// Vendredi : bureau 9h-17h ; U19 18h30-20h ; le cheer du Petit gymnase d'Étouvie ne compte pas.
verifier(
	'ouverture du stade (calculée)',
	array_column( $horaires->ouverture_stade(), 'hours', 'day' ),
	array(
		'Lundi'    => '9h – 17h et 19h – 21h',
		'Mardi'    => '9h – 13h et 18h30 – 21h30',
		'Mercredi' => '14h – 16h et 16h30 – 22h',
		'Jeudi'    => '9h – 17h et 19h30 – 21h30',
		'Vendredi' => '9h – 17h et 18h30 – 20h',
		'Samedi'   => '14h – 16h',
		'Dimanche' => 'Fermé',
	)
);

verifier(
	'entraînements Flag jeunes (page Flag)',
	$horaires->lignes_entrainements( 'Flag jeunes' ),
	array( 'Le mercredi et le samedi, de 14h à 16h' )
);
verifier(
	'entraînements Flag seniors (page Flag)',
	$horaires->lignes_entrainements( 'Flag seniors' ),
	array( 'Le lundi, de 19h à 21h', 'Le mercredi, de 20h à 22h' )
);
verifier(
	'entraînements Football (page Football)',
	$horaires->lignes_entrainements( 'Football', true ),
	array(
		'Football seniors : mardi et jeudi, de 19h30 à 21h30',
		'Football U19 : mercredi et vendredi, de 18h30 à 20h',
		'Football U15 : mercredi, de 16h30 à 18h30',
	)
);
verifier(
	'entraînements Cheerleading (lieux hors stade)',
	$horaires->lignes_entrainements( 'Cheerleading', true ),
	array(
		'Cheerleading minimes, cadets et juniors : mardi, de 18h30 à 20h30',
		'Cheerleading seniors : mercredi, de 18h30 à 21h30 (Complexe sportif Dupontreue)',
		'Cheerleading : vendredi, de 19h à 21h30 (Petit gymnase d’Étouvie)',
		'Cheerleading minimes et cadets : samedi, de 10h à 12h (Halle des sports d’Étouvie)',
	)
);
verifier( 'toutes les lignes (Contact)', count( $horaires->lignes_entrainements() ), 10 );

verifier( 'saisie de plages', Spa_Horaires::lire_plages( '9h-12h, 14h00 – 17h' ), array( array( '9h', '12h' ), array( '14h', '17h' ) ) );
verifier( 'saisie vide = fermé', Spa_Horaires::lire_plages( '' ), array() );
verifier( 'saisie invalide', Spa_Horaires::lire_plages( '17h-9h' ), null );
verifier( 'saisie réécrite', Spa_Horaires::ecrire_plages( array( array( '9h', '12h' ), array( '14h', '17h' ) ) ), '9h-12h, 14h-17h' );

/* ---------------------------------------------------------------------- */
/* Calendrier                                                              */
/* ---------------------------------------------------------------------- */

$c = json( $racine . '/data/calendrier.json' );
$cal = new Spa_Calendrier( $c['evenements'], $c['categories'], $c['lieu_domicile'], '2026-11-17' );

$foot = $cal->carte( 'foot' );
verifier( 'carte foot : prochaine date', array( $foot['day'], $foot['month'], $foot['versus'], $foot['place'], $foot['venueTag'], $foot['time'] ), array( '23-24', 'Janv.', 'Vikings', 'Villeneuve-d’Ascq', 'Déplacement', '[Heure]' ) );
verifier(
	'carte foot : deux suivantes',
	$foot['next'],
	array(
		array( 'date' => '30 Janv.', 'label' => 'Seniors D1 · Cougars', 'time' => '[Heure] · Stade du Grand Marais', 'tag' => 'Domicile' ),
		array( 'date' => '30-31 Janv.', 'label' => 'Seniors D2 · Mustangs', 'time' => '[Heure] · Hénin-Beaumont', 'tag' => 'Déplacement' ),
	)
);
$flag = $cal->carte( 'flag' );
verifier( 'carte flag', array( $flag['day'], $flag['month'], $flag['versus'], $flag['isMatch'], $flag['venueTag'] ), array( '29', 'Nov.', 'Tournoi U13/U15', false, 'Tournoi' ) );
verifier( 'carte flag : suivante', $flag['next'][0]['date'] . ' · ' . $flag['next'][0]['label'], '20-21 Févr. · U13/U15 · Tournoi U13/U15' );
$cheer = $cal->carte( 'cheer' );
verifier( 'carte cheer sans date : emplacements', array( $cheer['vide'], $cheer['day'], $cheer['versus'], $cheer['kind'] ), array( true, '[JJ]', '[NOM DE LA COMPÉTITION]', 'Prochaine compétition' ) );

// Le 30 novembre 2026, le tournoi de Lille (29/11) est passé : il disparaît de la carte.
$plus_tard = new Spa_Calendrier( $c['evenements'], $c['categories'], $c['lieu_domicile'], '2026-11-30' );
verifier( 'date passée : disparaît des cartes', $plus_tard->carte( 'flag' )['day'], '20-21' );
// Un événement sur deux jours n'est passé qu'après sa date de fin.
$deux_jours = new Spa_Calendrier( $c['evenements'], $c['categories'], $c['lieu_domicile'], '2027-01-24' );
verifier( 'événement en cours : encore affiché', $deux_jours->carte( 'foot' )['day'], '23-24' );

$listes = $cal->listes_par_categorie( 'foot' );
verifier( 'page Football : catégories', array_column( $listes, 'title' ), array( 'Seniors D1', 'Seniors D2' ) );
verifier( 'page Football : compteur', $listes[0]['countLabel'], '8 dates' );
verifier( 'page Football : 1re ligne', array( $listes[0]['rows'][0]['day'], $listes[0]['rows'][0]['monthYear'], $listes[0]['rows'][0]['title'], $listes[0]['rows'][0]['isAway'] ), array( '23-24', 'Janv. 2027', 'Vikings', true ) );
$passe = new Spa_Calendrier( $c['evenements'], $c['categories'], $c['lieu_domicile'], '2027-02-01' );
verifier( 'page Football : date passée grisée', $passe->listes_par_categorie( 'foot' )[0]['rows'][0]['past'], true );

$groupes = $cal->groupes_par_mois();
verifier( 'page Calendrier : mois', array_column( $groupes, 'label' ), array( 'Novembre 2026', 'Janvier 2027', 'Février 2027', 'Mars 2027', 'Avril 2027', 'Mai 2027', 'Juin 2027' ) );
$jan = $groupes[1]['rows'];
verifier( 'page Calendrier : titre d’un match', $jan[0]['title'], 'Spartiates – Vikings' );
verifier( 'page Calendrier : jours de semaine', $jan[0]['weekday'], 'Sam. – Dim.' );
verifier( 'page Calendrier : un seul jour', $jan[1]['weekday'], 'Sam.' );
verifier( 'page Calendrier : tri date puis catégorie', array( $jan[1]['cat'], $jan[2]['cat'] ), array( 'Seniors D1', 'Seniors D2' ) );
verifier( 'page Calendrier : tournoi', array( $groupes[0]['rows'][0]['title'], $groupes[0]['rows'][0]['typeLabel'], $groupes[0]['rows'][0]['place'] ), array( 'Tournoi U13/U15', 'Tournoi', 'Lille' ) );
verifier( 'total des dates', count( $cal->evenements() ), 23 );
verifier( 'catégories flag', $cal->categories( 'flag' ), array( 'U13/U15', 'Jeunes', 'Seniors' ) );
$extra = new Spa_Calendrier( array( array( 'disc' => 'foot', 'cat' => 'U17', 'type' => 'match', 'start' => '2027-03-01', 'opp' => 'X', 'city' => 'Y', 'home' => true ) ), $c['categories'], 'Stade', '2026-11-17' );
verifier( 'catégorie ajoutée automatiquement', $extra->categories( 'foot' ), array( 'Seniors D1', 'Seniors D2', 'U19', 'U15', 'U17' ) );
verifier( 'années du calendrier foot', $cal->annees( 'foot' ), '2027' );
verifier( 'années du calendrier flag', $cal->annees( 'flag' ), '2026-2027' );
verifier( 'pluriel', Spa_Calendrier::pluriel( 1, 'date', 'dates' ), '1 date' );

/* ---------------------------------------------------------------------- */
/* Palmarès                                                                */
/* ---------------------------------------------------------------------- */

$r   = json( $plugin . '/donnees-initiales/resultats.json' );
$pal = new Spa_Palmares( $r['resultats'] );
$ref = json( $racine . '/data/palmares.json' );

verifier( '10 titres nationaux (calculé)', $pal->nombre_titres_nationaux(), 10 );
verifier( '6 titres seniors (page Le club)', $pal->nombre_titres_nationaux( 'foot', 'Seniors' ), 6 );

$cartes = array();
foreach ( $pal->cartes_football() as $g ) {
	$cartes[] = $g['cat'] . ' | ' . $g['titre'] . ' | ' . implode( ' · ', $g['annees'] );
}
verifier(
	'page Football : cartes palmarès',
	$cartes,
	array(
		'Seniors | Champion de France D1 | 2004 · 2010 · 2012',
		'Seniors | Champion de France D2 | 1997 · 2018 · 2026',
		'U19 | Champion de France | 1999 · 2010 · 2011',
		'U17 | Champion territorial Île-de-France | 2025',
	)
);

$seniors = array();
foreach ( $pal->seniors_football() as $g ) {
	$seniors[] = array( 'titre' => $g['titre'], 'annees' => array_map( 'intval', $g['annees'] ) );
}
verifier( 'page Le club : seniors', $seniors, $ref['football_seniors'] );

$club = array();
foreach ( $pal->disciplines_club() as $d ) {
	$club[] = array( 'discipline' => $d['name'], 'resultats' => array_map( static function ( $e ) { return array( 'year' => $e['year'], 'label' => $e['label'] ); }, $d['entries'] ) );
}
verifier( 'page Le club : cartes par discipline', $club, $ref['jeunes_flag_cheer_resume_page_club'] );

$saisons = array();
foreach ( $pal->saisons_cheerleading() as $s ) {
	$items = array();
	foreach ( $s['items'] as $i ) {
		$item = array( 'comp' => $i['comp'], 'cat' => $i['cat'], 'rank' => $i['rank'] );
		if ( $i['podium'] ) {
			$item['podium'] = true;
		}
		if ( '' !== $i['note'] ) {
			$item['note'] = $i['note'];
		}
		$items[] = $item;
	}
	$saisons[] = array( 'season' => $s['season'], 'items' => $items );
}
$attendu = $ref['cheerleading_par_saison'];
$normaliser = static function ( $saisons ) {
	foreach ( $saisons as &$s ) {
		foreach ( $s['items'] as &$i ) {
			ksort( $i );
		}
	}
	return $saisons;
};
verifier( 'page Cheerleading : saisons', $normaliser( $saisons ), $normaliser( $attendu ) );
verifier( 'page Flag : badge', $pal->titres_nationaux( 'flag' ), array( 'Champion de France Senior en 2009' ) );
verifier( 'podium 3e', Spa_Palmares::est_podium( '3e' ), true );
verifier( 'pas de podium 10e', Spa_Palmares::est_podium( '10e' ), false );

echo "\n" . ( $total - $echecs ) . " / $total vérifications réussies\n";
exit( $echecs ? 1 : 0 );
