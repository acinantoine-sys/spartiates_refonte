<?php
/**
 * Palmarès du club : logique d'affichage (sans dépendance à WordPress).
 *
 * Une seule liste de résultats alimente :
 * - les cartes « Un palmarès qui compte » (page Football américain) ;
 * - la carte Seniors et les cartes par discipline (page Le club) ;
 * - les résultats par saison (page Cheerleading) ;
 * - le badge « Champion de France Senior en 2009 » (page Flag) ;
 * - le chiffre « 10 titres nationaux » (accueil, Le club), CALCULÉ.
 *
 * Un résultat est un tableau :
 * {
 *   disc            : foot | flag | cheer
 *   cat             : catégorie (Seniors, U19, Juniors…)
 *   annee           : année du résultat (pour le cheerleading : fin de saison, 2026 = saison 2025-2026)
 *   titre           : intitulé (« Champion de France D1 ») ou compétition (« SALC »)
 *   classement      : facultatif (« 2e ») ; 1er, 2e, 3e = podium surligné en jaune
 *   note            : facultatif (« Qualification aux championnats d’Europe »)
 *   titre_national  : bool, compte dans le nombre de titres nationaux
 *   page_club       : bool, affiché sur la page Le club
 *   libelle_club    : facultatif, remplace le libellé calculé sur la page Le club
 * }
 *
 * @package Spartiates_Club
 */

defined( 'ABSPATH' ) || defined( 'SPA_TESTS' ) || exit;

final class Spa_Palmares {

	/** Catégorie qui identifie l'équipe première de football. */
	const SENIORS = 'Seniors';

	/** @var array[] Dans l'ordre de saisie. */
	private $resultats;

	public function __construct( array $resultats ) {
		$liste = array();
		foreach ( array_values( $resultats ) as $i => $r ) {
			$r += array(
				'disc'           => '',
				'cat'            => '',
				'annee'          => '',
				'titre'          => '',
				'classement'     => '',
				'note'           => '',
				'titre_national' => false,
				'page_club'      => true,
				'libelle_club'   => '',
			);
			$r['_ordre'] = $i;
			$liste[]     = $r;
		}
		$this->resultats = $liste;
	}

	private function filtrer( callable $condition ) {
		return array_values( array_filter( $this->resultats, $condition ) );
	}

	private static function trier_par_annee( array $liste, $decroissant ) {
		usort(
			$liste,
			static function ( $a, $b ) use ( $decroissant ) {
				$c = strcmp( (string) $a['annee'], (string) $b['annee'] );
				return ( $decroissant ? -$c : $c ) ?: $a['_ordre'] - $b['_ordre'];
			}
		);
		return $liste;
	}

	/** Nombre de titres nationaux, toutes catégories confondues. */
	public function nombre_titres_nationaux( $disc = '', $cat = '' ) {
		return count(
			$this->filtrer(
				static function ( $r ) use ( $disc, $cat ) {
					return $r['titre_national']
						&& ( '' === $disc || $r['disc'] === $disc )
						&& ( '' === $cat || $r['cat'] === $cat );
				}
			)
		);
	}

	/** Un podium : 1er, 1re, 2e, 3e. */
	public static function est_podium( $classement ) {
		return (bool) preg_match( '/^\s*[123]\s*(er|re|e|ème|eme)?\s*$/iu', (string) $classement );
	}

	/**
	 * Regroupe des résultats par (catégorie, intitulé), années croissantes.
	 * Ordre des groupes : ordre de saisie du premier résultat.
	 */
	private static function grouper( array $liste ) {
		$groupes = array();
		foreach ( $liste as $r ) {
			$cle = $r['cat'] . '|' . $r['titre'];
			if ( ! isset( $groupes[ $cle ] ) ) {
				$groupes[ $cle ] = array(
					'cat'    => $r['cat'],
					'titre'  => $r['titre'],
					'ordre'  => $r['_ordre'],
					'annees' => array(),
				);
			}
			$groupes[ $cle ]['annees'][] = (string) $r['annee'];
		}
		$groupes = array_values( $groupes );
		usort(
			$groupes,
			static function ( $a, $b ) {
				return $a['ordre'] - $b['ordre'];
			}
		);
		foreach ( $groupes as &$g ) {
			sort( $g['annees'] );
			unset( $g['ordre'] );
		}
		return $groupes;
	}

	/** Page Football : une carte par catégorie et intitulé. */
	public function cartes_football() {
		return self::grouper(
			$this->filtrer(
				static function ( $r ) {
					return 'foot' === $r['disc'];
				}
			)
		);
	}

	/** Page Le club : titres de l'équipe Seniors, par intitulé. */
	public function seniors_football() {
		return self::grouper(
			$this->filtrer(
				static function ( $r ) {
					return 'foot' === $r['disc'] && self::SENIORS === $r['cat'];
				}
			)
		);
	}

	/** Libellé d'un résultat sur la page Le club. */
	public static function libelle_club( array $r ) {
		if ( '' !== trim( (string) $r['libelle_club'] ) ) {
			return $r['libelle_club'];
		}
		if ( '' !== trim( (string) $r['classement'] ) ) {
			$texte = $r['titre'] . ', ' . $r['cat'] . ' : ' . $r['classement'] . ' place';
			if ( '' !== trim( (string) $r['note'] ) ) {
				$texte .= ' et ' . self::premiere_minuscule( $r['note'] );
			}
			return $texte;
		}
		return trim( $r['titre'] . ' ' . $r['cat'] );
	}

	private static function premiere_minuscule( $texte ) {
		$premiere = mb_substr( $texte, 0, 1, 'UTF-8' );
		return mb_strtolower( $premiere, 'UTF-8' ) . mb_substr( $texte, 1, null, 'UTF-8' );
	}

	/**
	 * Page Le club : cartes Football · Jeunes, Flag, Cheerleading (années décroissantes).
	 */
	public function disciplines_club() {
		$cartes = array(
			'foot'  => 'Football · Jeunes',
			'flag'  => 'Flag',
			'cheer' => 'Cheerleading',
		);
		$resultat = array();
		foreach ( $cartes as $disc => $nom ) {
			$liste = $this->filtrer(
				static function ( $r ) use ( $disc ) {
					return $r['disc'] === $disc
						&& $r['page_club']
						&& ! ( 'foot' === $disc && self::SENIORS === $r['cat'] );
				}
			);
			$entrees = array();
			foreach ( self::trier_par_annee( $liste, true ) as $r ) {
				$entrees[] = array(
					'year'  => (string) $r['annee'],
					'label' => self::libelle_club( $r ),
				);
			}
			$resultat[] = array(
				'disc'    => $disc,
				'name'    => $nom,
				'entries' => $entrees,
			);
		}
		return $resultat;
	}

	/**
	 * Page Cheerleading : résultats par saison, la plus récente en premier.
	 */
	public function saisons_cheerleading() {
		$saisons = array();
		foreach ( self::trier_par_annee(
			$this->filtrer(
				static function ( $r ) {
					return 'cheer' === $r['disc'];
				}
			),
			true
		) as $r ) {
			$annee = (int) $r['annee'];
			$cle   = ( $annee - 1 ) . '-' . $annee;
			if ( ! isset( $saisons[ $cle ] ) ) {
				$saisons[ $cle ] = array( 'season' => $cle, 'items' => array() );
			}
			$saisons[ $cle ]['items'][] = array(
				'comp'   => $r['titre'],
				'cat'    => $r['cat'],
				'rank'   => $r['classement'],
				'note'   => $r['note'],
				'podium' => self::est_podium( $r['classement'] ),
			);
		}
		return array_values( $saisons );
	}

	/**
	 * Titres nationaux d'une discipline, du plus récent au plus ancien
	 * (badge « Champion de France Senior en 2009 » de la page Flag).
	 */
	public function titres_nationaux( $disc ) {
		$liste = $this->filtrer(
			static function ( $r ) use ( $disc ) {
				return $r['disc'] === $disc && $r['titre_national'];
			}
		);
		$textes = array();
		foreach ( self::trier_par_annee( $liste, true ) as $r ) {
			$textes[] = trim( $r['titre'] . ' ' . $r['cat'] ) . ' en ' . $r['annee'];
		}
		return $textes;
	}
}
