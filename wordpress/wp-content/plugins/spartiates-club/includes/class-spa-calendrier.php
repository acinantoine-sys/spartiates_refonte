<?php
/**
 * Calendrier du club : logique d'affichage (sans dépendance à WordPress).
 *
 * Reprend les règles de design/maquettes/Calendrier.dc.html et de
 * docs/03-sources-uniques.md :
 * - un événement est passé quand sa date de fin (ou de début) est avant aujourd'hui ;
 * - tri par date de début, puis discipline, puis catégorie ;
 * - titre d'un match : « Spartiates – Adversaire » ;
 * - « 20-21 » pour deux jours, « Sam. – Dim. », « Févr. », « Février 2027 ».
 *
 * Un événement est un tableau :
 * { disc, cat, type, start, end?, opp?, name?, city, time?, home? (bool|null) }
 *
 * @package Spartiates_Club
 */

defined( 'ABSPATH' ) || defined( 'SPA_TESTS' ) || exit;

final class Spa_Calendrier {

	const DISCIPLINES = array(
		'foot'  => 'Football américain',
		'flag'  => 'Flag',
		'cheer' => 'Cheerleading',
	);

	const TYPES = array(
		'match'       => 'Match',
		'tournoi'     => 'Tournoi',
		'competition' => 'Compétition',
		'detection'   => 'Détection',
	);

	/** Libellés des filtres de type (pluriel). */
	const TYPES_PLURIEL = array(
		'match'       => 'Matchs',
		'tournoi'     => 'Tournois',
		'competition' => 'Compétitions',
		'detection'   => 'Détections',
	);

	const MOIS        = array( 'Janv.', 'Févr.', 'Mars', 'Avr.', 'Mai', 'Juin', 'Juil.', 'Août', 'Sept.', 'Oct.', 'Nov.', 'Déc.' );
	const MOIS_COMPLET = array( 'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre' );
	const JOURS       = array( 'Dim.', 'Lun.', 'Mar.', 'Mer.', 'Jeu.', 'Ven.', 'Sam.' );

	/** En-tête des cartes « Prochaines rencontres ». */
	const CARTES = array(
		'foot'  => 'Prochaine rencontre',
		'flag'  => 'Prochain rendez-vous',
		'cheer' => 'Prochaine compétition',
	);

	/** @var array[] */
	private $evenements;

	/** @var array<string, string[]> */
	private $categories;

	/** @var string */
	private $lieu_domicile;

	/** @var string AAAA-MM-JJ */
	private $aujourdhui;

	/**
	 * @param array[] $evenements    Événements bruts.
	 * @param array   $categories    Catégories proposées par discipline.
	 * @param string  $lieu_domicile Lieu affiché pour un match à domicile.
	 * @param string  $aujourdhui    Date du jour, AAAA-MM-JJ (fuseau du site).
	 */
	public function __construct( array $evenements, array $categories, $lieu_domicile, $aujourdhui ) {
		$valides = array();
		foreach ( $evenements as $e ) {
			if ( empty( $e['start'] ) || ! self::date_valide( $e['start'] ) || empty( $e['disc'] ) ) {
				continue;
			}
			$e += array(
				'cat'  => '',
				'type' => 'match',
				'end'  => '',
				'opp'  => '',
				'name' => '',
				'city' => '',
				'time' => '',
				'home' => null,
			);
			if ( $e['end'] && ( ! self::date_valide( $e['end'] ) || $e['end'] <= $e['start'] ) ) {
				$e['end'] = '';
			}
			$valides[] = $e;
		}
		usort( $valides, array( __CLASS__, 'comparer' ) );
		$this->evenements    = $valides;
		$this->categories    = $categories;
		$this->lieu_domicile = (string) $lieu_domicile;
		$this->aujourdhui    = (string) $aujourdhui;
	}

	public static function date_valide( $date ) {
		if ( ! preg_match( '/^(\d{4})-(\d{2})-(\d{2})$/', (string) $date, $m ) ) {
			return false;
		}
		return checkdate( (int) $m[2], (int) $m[3], (int) $m[1] );
	}

	/** Tri : date de début, discipline, catégorie. */
	public static function comparer( $a, $b ) {
		return strcmp( $a['start'], $b['start'] )
			?: strcmp( $a['disc'], $b['disc'] )
			?: strcmp( $a['cat'], $b['cat'] );
	}

	/* ------------------------------------------------------------------ */
	/* Libellés                                                            */
	/* ------------------------------------------------------------------ */

	private static function parties( $date ) {
		list( $a, $m, $j ) = array_map( 'intval', explode( '-', $date ) );
		return array( $a, $m, $j );
	}

	private static function jour_semaine( $date ) {
		list( $a, $m, $j ) = self::parties( $date );
		return self::JOURS[ (int) gmdate( 'w', gmmktime( 12, 0, 0, $m, $j, $a ) ) ];
	}

	public function est_passe( array $e ) {
		return ( $e['end'] ? $e['end'] : $e['start'] ) < $this->aujourdhui;
	}

	public static function est_match( array $e ) {
		return 'match' === $e['type'];
	}

	/** « 23-24 » ou « 30 ». */
	public static function libelle_jour( array $e ) {
		$debut = self::parties( $e['start'] )[2];
		return $e['end'] ? $debut . '-' . self::parties( $e['end'] )[2] : (string) $debut;
	}

	/** « Févr. » */
	public static function libelle_mois( array $e ) {
		return self::MOIS[ self::parties( $e['start'] )[1] - 1 ];
	}

	/** « Sam. » ou « Sam. – Dim. » */
	public static function libelle_jour_semaine( array $e ) {
		$a = self::jour_semaine( $e['start'] );
		if ( ! $e['end'] ) {
			return $a;
		}
		$b = self::jour_semaine( $e['end'] );
		return $a === $b ? $a : $a . ' – ' . $b;
	}

	/** « Février 2027 » */
	public static function libelle_mois_annee( $date ) {
		list( $a, $m ) = self::parties( $date );
		return self::MOIS_COMPLET[ $m - 1 ] . ' ' . $a;
	}

	/** « Spartiates – Vikings » pour un match, le nom de l'événement sinon. */
	public static function titre( array $e ) {
		return self::est_match( $e ) ? 'Spartiates – ' . $e['opp'] : $e['name'];
	}

	/** Adversaire (match) ou nom de l'événement. */
	public static function intitule( array $e ) {
		return self::est_match( $e ) ? $e['opp'] : $e['name'];
	}

	public function lieu( array $e ) {
		return true === $e['home'] ? $this->lieu_domicile : $e['city'];
	}

	public static function heure( array $e ) {
		return $e['time'] ? $e['time'] : '[Heure]';
	}

	/** « Domicile », « Déplacement », ou le type d'événement. */
	public static function etiquette( array $e ) {
		if ( self::est_match( $e ) ) {
			if ( true === $e['home'] ) {
				return 'Domicile';
			}
			if ( false === $e['home'] ) {
				return 'Déplacement';
			}
		}
		return self::TYPES[ $e['type'] ] ?? '';
	}

	public static function pluriel( $n, $un, $plusieurs ) {
		return $n . ' ' . ( $n > 1 ? $plusieurs : $un );
	}

	/* ------------------------------------------------------------------ */
	/* Vues                                                                */
	/* ------------------------------------------------------------------ */

	/** Tous les événements, triés. */
	public function evenements() {
		return $this->evenements;
	}

	public function a_des_evenements( $disc ) {
		foreach ( $this->evenements as $e ) {
			if ( $e['disc'] === $disc ) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Années couvertes par les dates d'une discipline : « 2027 » ou « 2026-2027 ».
	 * Sert au titre « Calendrier 2027 » de la page Football américain.
	 */
	public function annees( $disc ) {
		$annees = array();
		foreach ( $this->evenements as $e ) {
			if ( $e['disc'] === $disc ) {
				$annees[] = (int) substr( $e['start'], 0, 4 );
			}
		}
		if ( ! $annees ) {
			return '';
		}
		$min = min( $annees );
		$max = max( $annees );
		return $min === $max ? (string) $min : $min . '-' . $max;
	}

	/**
	 * Carte « Prochaines rencontres » d'une discipline (accueil, page Cheerleading).
	 * Sans date à venir, la carte affiche les emplacements entre crochets de la maquette.
	 */
	public function carte( $disc ) {
		$a_venir = array();
		foreach ( $this->evenements as $e ) {
			if ( $e['disc'] === $disc && ! $this->est_passe( $e ) ) {
				$a_venir[] = $e;
			}
		}

		$carte = array(
			'disc'  => $disc,
			'name'  => self::DISCIPLINES[ $disc ] ?? $disc,
			'kind'  => self::CARTES[ $disc ] ?? 'Prochaine rencontre',
			'vide'  => ! $a_venir,
		);

		if ( $a_venir ) {
			$f = $a_venir[0];
			$carte += array(
				'day'      => self::libelle_jour( $f ),
				'month'    => self::libelle_mois( $f ),
				'isMatch'  => self::est_match( $f ),
				'versus'   => self::intitule( $f ),
				'time'     => self::heure( $f ),
				'place'    => $this->lieu( $f ),
				'venueTag' => self::etiquette( $f ),
				'next'     => array(),
			);
			foreach ( array_slice( $a_venir, 1, 2 ) as $m ) {
				$carte['next'][] = array(
					'date'  => self::libelle_jour( $m ) . ' ' . self::libelle_mois( $m ),
					'label' => $m['cat'] . ' · ' . self::intitule( $m ),
					'time'  => self::heure( $m ) . ' · ' . $this->lieu( $m ),
					'tag'   => self::etiquette( $m ),
				);
			}
			return $carte;
		}

		// Emplacements à compléter, repris tels quels de la maquette.
		$cheer = 'cheer' === $disc;
		return $carte + array(
			'day'      => '[JJ]',
			'month'    => '[MOIS]',
			'isMatch'  => ! $cheer,
			'versus'   => $cheer ? '[NOM DE LA COMPÉTITION]' : '[ADVERSAIRE]',
			'time'     => '[HEURE]',
			'place'    => '[LIEU]',
			'venueTag' => $cheer ? 'Compétition' : 'Domicile',
			'next'     => array(
				array(
					'date'  => '[JJ/MM]',
					'label' => $cheer ? '[Compétition]' : '[Adversaire]',
					'time'  => '[Heure] · [Lieu]',
					'tag'   => 'Déplacement',
				),
				array(
					'date'  => '[JJ/MM]',
					'label' => $cheer ? '[Compétition]' : '[Adversaire]',
					'time'  => '[Heure] · [Lieu]',
					'tag'   => 'Domicile',
				),
			),
		);
	}

	/**
	 * Listes d'une discipline, une par catégorie (page Football américain).
	 */
	public function listes_par_categorie( $disc ) {
		$listes = array();
		foreach ( $this->evenements as $e ) {
			if ( $e['disc'] !== $disc ) {
				continue;
			}
			if ( ! isset( $listes[ $e['cat'] ] ) ) {
				$listes[ $e['cat'] ] = array();
			}
			list( $annee ) = self::parties( $e['start'] );
			$listes[ $e['cat'] ][] = array(
				'day'       => self::libelle_jour( $e ),
				'monthYear' => self::libelle_mois( $e ) . ' ' . $annee,
				'title'     => self::intitule( $e ),
				'city'      => $e['city'],
				'isHome'    => self::est_match( $e ) && true === $e['home'],
				'isAway'    => self::est_match( $e ) && false === $e['home'],
				'past'      => $this->est_passe( $e ),
			);
		}
		$resultat = array();
		foreach ( $listes as $cat => $lignes ) {
			$resultat[] = array(
				'title'      => $cat,
				'countLabel' => self::pluriel( count( $lignes ), 'date', 'dates' ),
				'rows'       => $lignes,
			);
		}
		return $resultat;
	}

	/**
	 * Catégories proposées dans les filtres d'une discipline : la liste réglée,
	 * complétée par les catégories utilisées dans les événements.
	 */
	public function categories( $disc ) {
		$cats = isset( $this->categories[ $disc ] ) ? array_values( (array) $this->categories[ $disc ] ) : array();
		foreach ( $this->evenements as $e ) {
			if ( $e['disc'] === $disc && '' !== $e['cat'] && ! in_array( $e['cat'], $cats, true ) ) {
				$cats[] = $e['cat'];
			}
		}
		return $cats;
	}

	/**
	 * Page Calendrier : tous les événements groupés par mois.
	 *
	 * @return array<int, array{label:string, rows:array[]}>
	 */
	public function groupes_par_mois() {
		$groupes = array();
		foreach ( $this->evenements as $e ) {
			$label = self::libelle_mois_annee( $e['start'] );
			$n     = count( $groupes );
			if ( ! $n || $groupes[ $n - 1 ]['label'] !== $label ) {
				$groupes[] = array( 'label' => $label, 'rows' => array() );
				++$n;
			}
			$groupes[ $n - 1 ]['rows'][] = array(
				'disc'      => $e['disc'],
				'cat'       => $e['cat'],
				'type'      => $e['type'],
				'day'       => self::libelle_jour( $e ),
				'weekday'   => self::libelle_jour_semaine( $e ),
				'discName'  => self::DISCIPLINES[ $e['disc'] ] ?? $e['disc'],
				'title'     => self::titre( $e ),
				'time'      => self::heure( $e ),
				'place'     => $this->lieu( $e ),
				'typeLabel' => self::TYPES[ $e['type'] ] ?? $e['type'],
				'typeMatch' => self::est_match( $e ),
				'isHome'    => self::est_match( $e ) && true === $e['home'],
				'isAway'    => self::est_match( $e ) && false === $e['home'],
				'past'      => $this->est_passe( $e ),
			);
		}
		return $groupes;
	}
}
