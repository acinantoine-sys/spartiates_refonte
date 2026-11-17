<?php
/**
 * Champs de formulaire génériques pour l'administration (sans extension tierce).
 *
 * Un champ est décrit par un tableau :
 * { cle, label, type (text|date|url|email|textarea|select|radio|checkbox|number), options?, aide?,
 *   requis?, placeholder?, liste? (suggestions), defaut?, classe? }
 *
 * @package Spartiates_Club
 */

defined( 'ABSPATH' ) || exit;

/**
 * Affiche un champ.
 *
 * @param array  $champ  Description du champ.
 * @param mixed  $valeur Valeur actuelle.
 * @param string $nom    Attribut name du champ.
 */
function spa_afficher_champ( array $champ, $valeur, $nom ) {
	$id     = 'spa-' . sanitize_html_class( str_replace( array( '[', ']' ), '-', $nom ) );
	$type   = $champ['type'] ?? 'text';
	$requis = ! empty( $champ['requis'] );
	$aide   = $champ['aide'] ?? '';
	$aide_id = $aide ? $id . '-aide' : '';
	$classe = 'spa-champ spa-champ--' . $type . ( isset( $champ['classe'] ) ? ' ' . $champ['classe'] : '' );

	echo '<div class="' . esc_attr( $classe ) . '" data-champ="' . esc_attr( $champ['cle'] ) . '">';

	if ( 'checkbox' === $type ) {
		printf(
			'<label for="%1$s"><input type="checkbox" id="%1$s" name="%2$s" value="1" %3$s %4$s> %5$s</label>',
			esc_attr( $id ),
			esc_attr( $nom ),
			checked( (bool) $valeur, true, false ),
			$aide_id ? 'aria-describedby="' . esc_attr( $aide_id ) . '"' : '',
			esc_html( $champ['label'] )
		);
	} elseif ( 'radio' === $type ) {
		echo '<fieldset' . ( $aide_id ? ' aria-describedby="' . esc_attr( $aide_id ) . '"' : '' ) . '><legend>' . esc_html( $champ['label'] ) . '</legend>';
		foreach ( $champ['options'] as $cle => $libelle ) {
			printf(
				'<label class="spa-radio"><input type="radio" name="%1$s" value="%2$s" %3$s> %4$s</label>',
				esc_attr( $nom ),
				esc_attr( $cle ),
				checked( (string) $valeur, (string) $cle, false ),
				esc_html( $libelle )
			);
		}
		echo '</fieldset>';
	} else {
		printf(
			'<label for="%1$s">%2$s%3$s</label>',
			esc_attr( $id ),
			esc_html( $champ['label'] ),
			$requis ? ' <span class="spa-requis" aria-hidden="true">*</span>' : ''
		);
		$attrs = sprintf(
			'id="%1$s" name="%2$s" %3$s %4$s',
			esc_attr( $id ),
			esc_attr( $nom ),
			$requis ? 'required' : '',
			$aide_id ? 'aria-describedby="' . esc_attr( $aide_id ) . '"' : ''
		);
		if ( 'select' === $type ) {
			echo '<select ' . $attrs . '>'; // phpcs:ignore WordPress.Security.EscapeOutput -- attributs échappés ci-dessus.
			foreach ( $champ['options'] as $cle => $libelle ) {
				printf( '<option value="%1$s" %2$s>%3$s</option>', esc_attr( $cle ), selected( (string) $valeur, (string) $cle, false ), esc_html( $libelle ) );
			}
			echo '</select>';
		} elseif ( 'textarea' === $type ) {
			printf( '<textarea %1$s rows="%2$d">%3$s</textarea>', $attrs, (int) ( $champ['lignes'] ?? 4 ), esc_textarea( (string) $valeur ) ); // phpcs:ignore WordPress.Security.EscapeOutput
		} else {
			$liste_id = '';
			if ( ! empty( $champ['liste'] ) ) {
				$liste_id = $id . '-liste';
				echo '<datalist id="' . esc_attr( $liste_id ) . '">';
				foreach ( $champ['liste'] as $suggestion ) {
					echo '<option value="' . esc_attr( $suggestion ) . '"></option>';
				}
				echo '</datalist>';
			}
			printf(
				'<input type="%1$s" %2$s value="%3$s" %4$s %5$s class="regular-text">',
				esc_attr( $type ),
				$attrs, // phpcs:ignore WordPress.Security.EscapeOutput
				esc_attr( (string) $valeur ),
				isset( $champ['placeholder'] ) ? 'placeholder="' . esc_attr( $champ['placeholder'] ) . '"' : '',
				$liste_id ? 'list="' . esc_attr( $liste_id ) . '" autocomplete="off"' : ''
			);
		}
	}

	if ( $aide ) {
		echo '<p class="description" id="' . esc_attr( $aide_id ) . '">' . esc_html( $aide ) . '</p>';
	}
	echo '</div>';
}

/**
 * Nettoie une valeur saisie selon le type du champ.
 *
 * @param array $champ  Description du champ.
 * @param mixed $valeur Valeur brute (déjà passée par wp_unslash).
 * @return mixed
 */
function spa_nettoyer_champ( array $champ, $valeur ) {
	$type = $champ['type'] ?? 'text';
	switch ( $type ) {
		case 'checkbox':
			return $valeur ? '1' : '0';
		case 'url':
			return esc_url_raw( trim( (string) $valeur ) );
		case 'email':
			return sanitize_email( (string) $valeur );
		case 'textarea':
			return sanitize_textarea_field( (string) $valeur );
		case 'date':
			$valeur = trim( (string) $valeur );
			return Spa_Calendrier::date_valide( $valeur ) ? $valeur : '';
		case 'select':
		case 'radio':
			$valeur = (string) $valeur;
			return array_key_exists( $valeur, $champ['options'] ) ? $valeur : (string) ( $champ['defaut'] ?? '' );
		default:
			return sanitize_text_field( (string) $valeur );
	}
}
