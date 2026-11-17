<?php
/**
 * Horaires du club : logique d'affichage (sans dépendance à WordPress).
 *
 * Reprend les règles de design/maquettes/Horaires.dc.html et de
 * docs/03-sources-uniques.md :
 * - tableau du bureau sur 7 jours (« Fermé » si aucune plage) ;
 * - pied de page : jours regroupés quand les plages sont identiques ;
 * - ouverture du stade CALCULÉE : plages du bureau + entraînements sans lieu,
 *   fusionnées par jour quand elles se chevauchent ou se touchent ;
 * - lignes d'entraînements (toutes, une équipe, ou une discipline).
 *
 * @package Spartiates_Club
 */

defined( 'ABSPATH' ) || defined( 'SPA_TESTS' ) || exit;

final class Spa_Horaires {

	const JOURS = array( 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche' );

	/** @var array<string, array<int, array{0:string,1:string}>> */
	private $bureau;

	/** @var array<int, array{day:string,from:string,to:string,label:string,place?:string}> */
	private $entrainements;

	/**
	 * @param array $bureau        Jour => liste de plages [ début, fin ] (« 9h », « 19h30 »).
	 * @param array $entrainements Liste de créneaux { day, from, to, label, place? }.
	 */
	public function __construct( array $bureau, array $entrainements ) {
		$this->bureau        = $bureau;
		$this->entrainements = array_values( $entrainements );
	}

	/**
	 * « 19h30 » → 1170 minutes. Renvoie null si le format est invalide.
	 */
	public static function en_minutes( $heure ) {
		$heure = strtolower( str_replace( ' ', '', (string) $heure ) );
		if ( ! preg_match( '/^(\d{1,2})h(\d{2})?$/', $heure, $m ) ) {
			return null;
		}
		$h  = (int) $m[1];
		$mn = isset( $m[2] ) ? (int) $m[2] : 0;
		if ( $h > 24 || $mn > 59 || ( 24 === $h && $mn > 0 ) ) {
			return null;
		}
		return $h * 60 + $mn;
	}

	/**
	 * 1170 → « 19h30 », 540 → « 9h ».
	 */
	public static function formater( $minutes ) {
		$h  = intdiv( (int) $minutes, 60 );
		$mn = (int) $minutes % 60;
		return $h . 'h' . ( $mn ? str_pad( (string) $mn, 2, '0', STR_PAD_LEFT ) : '' );
	}

	/**
	 * Normalise une heure saisie (« 9H00 » → « 9h »). Chaîne vide si invalide.
	 */
	public static function normaliser( $heure ) {
		$m = self::en_minutes( $heure );
		return null === $m ? '' : self::formater( $m );
	}

	private static function plage( array $p, $separateur ) {
		return self::formater( self::en_minutes( $p[0] ) ) . $separateur . self::formater( self::en_minutes( $p[1] ) );
	}

	private function plages_bureau( $jour ) {
		return isset( $this->bureau[ $jour ] ) && is_array( $this->bureau[ $jour ] ) ? $this->bureau[ $jour ] : array();
	}

	/**
	 * Tableau du bureau (page Contact) : 7 lignes { day, hours }.
	 */
	public function tableau_bureau() {
		$lignes = array();
		foreach ( self::JOURS as $jour ) {
			$plages = $this->plages_bureau( $jour );
			$textes = array();
			foreach ( $plages as $p ) {
				$textes[] = self::plage( $p, ' – ' );
			}
			$lignes[] = array(
				'day'   => $jour,
				'hours' => $textes ? implode( ' et ', $textes ) : 'Fermé',
			);
		}
		return $lignes;
	}

	/**
	 * Pied de page : « Lundi, jeudi, vendredi 9h–17h », « Mardi 9h–13h ».
	 *
	 * @return string[]
	 */
	public function lignes_pied_de_page() {
		$ordre    = array();
		$par_cles = array();
		foreach ( self::JOURS as $jour ) {
			$plages = $this->plages_bureau( $jour );
			if ( ! $plages ) {
				continue;
			}
			$textes = array();
			foreach ( $plages as $p ) {
				$textes[] = self::plage( $p, '–' );
			}
			$cle = implode( ' et ', $textes );
			if ( ! isset( $par_cles[ $cle ] ) ) {
				$par_cles[ $cle ] = array();
				$ordre[]          = $cle;
			}
			$par_cles[ $cle ][] = $jour;
		}
		$lignes = array();
		foreach ( $ordre as $cle ) {
			$jours = array();
			foreach ( $par_cles[ $cle ] as $i => $jour ) {
				$jours[] = $i ? self::minuscule( $jour ) : $jour;
			}
			$lignes[] = implode( ', ', $jours ) . ' ' . $cle;
		}
		return $lignes;
	}

	/**
	 * Ouverture du stade : calculée, jamais saisie.
	 * Pour chaque jour : plages du bureau + entraînements sans « place »,
	 * triées puis fusionnées si elles se chevauchent ou se touchent.
	 *
	 * @return array<int, array{day:string,hours:string}>
	 */
	public function ouverture_stade() {
		$resultat = array();
		foreach ( self::JOURS as $jour ) {
			$segments = array();
			foreach ( $this->plages_bureau( $jour ) as $p ) {
				$segments[] = array( self::en_minutes( $p[0] ), self::en_minutes( $p[1] ) );
			}
			foreach ( $this->entrainements as $e ) {
				if ( $e['day'] === $jour && empty( $e['place'] ) ) {
					$segments[] = array( self::en_minutes( $e['from'] ), self::en_minutes( $e['to'] ) );
				}
			}
			if ( ! $segments ) {
				$resultat[] = array( 'day' => $jour, 'hours' => 'Fermé' );
				continue;
			}
			usort(
				$segments,
				static function ( $a, $b ) {
					return $a[0] - $b[0];
				}
			);
			$fusion = array();
			foreach ( $segments as $s ) {
				$n = count( $fusion );
				if ( $n && $s[0] <= $fusion[ $n - 1 ][1] ) {
					$fusion[ $n - 1 ][1] = max( $fusion[ $n - 1 ][1], $s[1] );
				} else {
					$fusion[] = $s;
				}
			}
			$textes = array();
			foreach ( $fusion as $f ) {
				$textes[] = self::formater( $f[0] ) . ' – ' . self::formater( $f[1] );
			}
			$resultat[] = array( 'day' => $jour, 'hours' => implode( ' et ', $textes ) );
		}
		return $resultat;
	}

	/**
	 * Lignes d'entraînement, les jours au même horaire étant regroupés.
	 *
	 * - sans filtre : tous les créneaux, « Flag jeunes : mercredi et samedi, de 14h à 16h » ;
	 * - filtre exact (page d'une équipe, ex. « Flag jeunes ») : « Le mercredi et le samedi, de 14h à 16h » ;
	 * - filtre préfixe (page d'une discipline, ex. « Football ») : comme sans filtre, limité à la discipline.
	 *
	 * @param string $filtre  Libellé d'équipe ou début de libellé.
	 * @param bool   $prefixe true = le libellé doit commencer par $filtre.
	 * @return string[]
	 */
	public function lignes_entrainements( $filtre = '', $prefixe = false ) {
		$groupes = array();
		$cles    = array();
		foreach ( $this->entrainements as $e ) {
			if ( '' !== $filtre ) {
				$ok = $prefixe ? 0 === strpos( $e['label'], $filtre ) : $e['label'] === $filtre;
				if ( ! $ok ) {
					continue;
				}
			}
			$lieu = isset( $e['place'] ) ? (string) $e['place'] : '';
			$cle  = $e['label'] . '|' . $e['from'] . '|' . $e['to'] . '|' . $lieu;
			if ( ! isset( $groupes[ $cle ] ) ) {
				$groupes[ $cle ] = array( 'e' => $e, 'jours' => array() );
				$cles[]          = $cle;
			}
			$groupes[ $cle ]['jours'][] = self::minuscule( $e['day'] );
		}
		$lignes = array();
		foreach ( $cles as $cle ) {
			$g     = $groupes[ $cle ];
			$e     = $g['e'];
			$plage = 'de ' . self::normaliser( $e['from'] ) . ' à ' . self::normaliser( $e['to'] );
			$lieu  = ! empty( $e['place'] ) ? ' (' . $e['place'] . ')' : '';
			if ( '' !== $filtre && ! $prefixe ) {
				$lignes[] = 'Le ' . implode( ' et le ', $g['jours'] ) . ', ' . $plage . $lieu;
			} else {
				$lignes[] = $e['label'] . ' : ' . implode( ' et ', $g['jours'] ) . ', ' . $plage . $lieu;
			}
		}
		return $lignes;
	}

	private static function minuscule( $texte ) {
		return function_exists( 'mb_strtolower' ) ? mb_strtolower( $texte, 'UTF-8' ) : strtolower( $texte );
	}

	/**
	 * Lit une saisie de plages du bureau : « 9h-12h, 14h-17h ».
	 *
	 * @return array|null Liste de [ début, fin ] normalisés, ou null si la saisie est invalide.
	 */
	public static function lire_plages( $saisie ) {
		$saisie = trim( (string) $saisie );
		if ( '' === $saisie ) {
			return array();
		}
		$plages = array();
		foreach ( preg_split( '/\s*(?:,|;|\bet\b)\s*/u', $saisie ) as $morceau ) {
			if ( '' === $morceau ) {
				continue;
			}
			$bornes = preg_split( '/\s*[-–—àa]\s*/u', $morceau );
			if ( 2 !== count( $bornes ) ) {
				return null;
			}
			$debut = self::en_minutes( $bornes[0] );
			$fin   = self::en_minutes( $bornes[1] );
			if ( null === $debut || null === $fin || $fin <= $debut ) {
				return null;
			}
			$plages[] = array( self::formater( $debut ), self::formater( $fin ) );
		}
		return $plages;
	}

	/**
	 * Inverse de lire_plages() : pour préremplir le formulaire d'administration.
	 */
	public static function ecrire_plages( array $plages ) {
		$textes = array();
		foreach ( $plages as $p ) {
			$textes[] = $p[0] . '-' . $p[1];
		}
		return implode( ', ', $textes );
	}
}
