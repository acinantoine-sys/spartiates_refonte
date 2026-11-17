<?php
/**
 * Carte « Prochaines rencontres » d'une discipline : la prochaine date en grand,
 * puis les deux suivantes. Alimentée par le calendrier ; les dates passées disparaissent.
 *
 * Arguments : disc (foot | flag | cheer), niveau (niveau du titre, 3 par défaut).
 *
 * @package Spartiates
 */

defined( 'ABSPATH' ) || exit;

$spt_carte  = spa_calendrier()->carte( $args['disc'] ?? 'foot' );
$spt_niveau = (int) ( $args['niveau'] ?? 3 );
?>
<article class="carte-rdv">
	<div class="carte-rdv__tete">
		<?php echo spt_icone( $spt_carte['disc'], 32, '#060640' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
		<h<?php echo $spt_niveau; // phpcs:ignore WordPress.Security.EscapeOutput ?> class="disp"><?php echo esc_html( $spt_carte['name'] ); ?></h<?php echo $spt_niveau; // phpcs:ignore WordPress.Security.EscapeOutput ?>>
	</div>

	<div class="carte-rdv__corps">
		<p class="cond carte-rdv__type"><?php echo esc_html( $spt_carte['kind'] ); ?></p>
		<div class="carte-rdv__principal">
			<p class="carte-rdv__date" style="margin:0">
				<span class="disp j"><?php echo esc_html( $spt_carte['day'] ); ?></span>
				<span class="disp m"><?php echo esc_html( $spt_carte['month'] ); ?></span>
			</p>
			<div class="carte-rdv__infos">
				<?php if ( $spt_carte['isMatch'] ) : ?>
					<span class="carte-rdv__nom">Spartiates</span>
					<span class="cond carte-rdv__contre">contre</span>
				<?php endif; ?>
				<span class="carte-rdv__nom"><?php echo esc_html( $spt_carte['versus'] ); ?></span>
				<span class="carte-rdv__lieu"><?php echo esc_html( $spt_carte['time'] . ' · ' . $spt_carte['place'] ); ?></span>
				<span class="etiquette etiquette--plein"><?php echo esc_html( $spt_carte['venueTag'] ); ?></span>
			</div>
		</div>
	</div>

	<?php if ( $spt_carte['next'] ) : ?>
		<ul class="carte-rdv__suivants" aria-label="Dates suivantes">
			<?php foreach ( $spt_carte['next'] as $spt_m ) : ?>
				<li>
					<div>
						<span class="t" style="display:block"><?php echo esc_html( $spt_m['date'] . ' · ' . $spt_m['label'] ); ?></span>
						<span class="h"><?php echo esc_html( $spt_m['time'] ); ?></span>
					</div>
					<span class="etiquette etiquette--petite"><?php echo esc_html( $spt_m['tag'] ); ?></span>
				</li>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>

	<div class="carte-rdv__pied">
		<a class="tlink" href="<?php echo esc_url( spt_url( 'calendrier' ) ); ?>">Tout le calendrier <span aria-hidden="true">→</span><span class="visuellement-cache"> <?php echo esc_html( $spt_carte['name'] ); ?></span></a>
	</div>
</article>
