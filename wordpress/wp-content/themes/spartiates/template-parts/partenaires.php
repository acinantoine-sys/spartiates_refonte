<?php
/**
 * Bandeau « Nos Spartenaires », en bas de toutes les pages.
 * Source unique : Spartiates > Partenaires. Partenaires publics puis privés,
 * logos à taille identique ; sans logo, case en pointillés avec le nom.
 *
 * @package Spartiates
 */

defined( 'ABSPATH' ) || exit;

$spt_groupes = spa_partenaires();
$spt_titres  = array(
	'public' => 'Partenaires publics',
	'prive'  => 'Partenaires privés',
);
?>
<section class="partenaires" aria-labelledby="spartenaires">
	<div class="conteneur">
		<div class="partenaires__entete">
			<div>
				<h2 id="spartenaires" class="disp partenaires__titre">Nos <span class="surligne">Spartenaires</span></h2>
				<p class="partenaires__intro">Collectivités, institutions et entreprises : ils font vivre les Spartiates, saison après saison.</p>
			</div>
			<a class="tlink" href="<?php echo esc_url( spt_url( 'contact' ) ); ?>">Devenir partenaire →</a>
		</div>

		<?php foreach ( $spt_titres as $spt_type => $spt_titre ) : ?>
			<?php
			if ( empty( $spt_groupes[ $spt_type ] ) ) {
				continue;
			}
			?>
			<h3 class="cond"><?php echo esc_html( $spt_titre ); ?></h3>
			<ul class="partenaires__grille">
				<?php foreach ( $spt_groupes[ $spt_type ] as $spt_p ) : ?>
					<li>
						<?php
						$spt_balise = $spt_p['url'] ? 'a' : 'div';
						$spt_lien   = $spt_p['url'] ? ' href="' . esc_url( $spt_p['url'] ) . '"' : '';
						if ( $spt_p['logo_id'] ) {
							echo '<' . $spt_balise . ' class="partenaire"' . $spt_lien . '>'; // phpcs:ignore WordPress.Security.EscapeOutput
							echo wp_get_attachment_image( $spt_p['logo_id'], 'medium', false, array( 'alt' => $spt_p['name'], 'loading' => 'lazy' ) );
						} else {
							echo '<' . $spt_balise . ' class="partenaire partenaire--vide"' . $spt_lien . '>'; // phpcs:ignore WordPress.Security.EscapeOutput
							echo '<span>' . esc_html( $spt_p['name'] ) . '</span>';
						}
						echo '</' . $spt_balise . '>'; // phpcs:ignore WordPress.Security.EscapeOutput
						?>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php endforeach; ?>
	</div>
</section>
