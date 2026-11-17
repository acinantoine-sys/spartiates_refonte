<?php
/**
 * Calendrier (maquettes : design/maquettes/PageCalendrier.dc.html et Calendrier.dc.html, variante « page »).
 *
 * Toutes les dates sont rendues côté serveur, groupées par mois : la page est
 * complète sans JavaScript. Les filtres (discipline, catégorie, type) sont
 * ajoutés par assets/js/site.js.
 *
 * @package Spartiates
 */

defined( 'ABSPATH' ) || exit;

get_header();

$spt_cal    = spa_calendrier();
$spt_groupes = $spt_cal->groupes_par_mois();
$spt_total   = count( $spt_cal->evenements() );
$spt_discs   = Spa_Calendrier::DISCIPLINES;
?>

<!-- TITRE DE PAGE -->
<section class="sombre hero hero--page">
	<div class="conteneur">
		<?php echo spt_fil_ariane( 'Calendrier' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
		<h1 class="disp titre-page">Le <span class="jaune">calendrier</span></h1>
		<p class="chapo" style="max-width:700px">Matchs, tournois et compétitions : toutes les dates des Spartiates, en football américain, en flag et en cheerleading, dans toutes les catégories.</p>
	</div>
	<?php echo spt_frise( 'hero__frise' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
</section>

<!-- FILTRES + DATES (source : calendrier) -->
<section id="dates" class="clair section" style="padding-top:56px;padding-bottom:96px">
	<div class="conteneur" data-calendrier data-disciplines="<?php echo esc_attr( wp_json_encode( $spt_discs ) ); ?>">
		<p class="texte texte--intro" style="margin-bottom:28px">Les rencontres à domicile se jouent au <?php echo esc_html( spa_reglage( 'lieu_domicile' ) . ', ' . spt_adresse_ligne() ); ?>. Choisissez une discipline, une catégorie ou un type d’événement pour affiner la liste.</p>

		<div class="filtres" data-filtres hidden>
			<div class="filtres__groupe">
				<h2 class="cond filtres__titre" id="f-disc">Discipline</h2>
				<div class="filtres__puces" role="group" aria-labelledby="f-disc">
					<button type="button" class="puce" data-filtre="disc" data-valeur="all" aria-pressed="true">Toutes</button>
					<?php foreach ( $spt_discs as $spt_k => $spt_nom ) : ?>
						<button type="button" class="puce" data-filtre="disc" data-valeur="<?php echo esc_attr( $spt_k ); ?>" aria-pressed="false"><?php echo esc_html( $spt_nom ); ?></button>
					<?php endforeach; ?>
				</div>
			</div>

			<div class="filtres__groupe" data-bloc-categories hidden>
				<h2 class="cond filtres__titre" id="f-cat">Catégorie</h2>
				<?php foreach ( $spt_discs as $spt_k => $spt_nom ) : ?>
					<div class="filtres__puces" role="group" aria-labelledby="f-cat" data-categories="<?php echo esc_attr( $spt_k ); ?>" hidden>
						<button type="button" class="puce" data-filtre="cat" data-valeur="all" aria-pressed="true">Toutes</button>
						<?php foreach ( $spt_cal->categories( $spt_k ) as $spt_c ) : ?>
							<button type="button" class="puce" data-filtre="cat" data-valeur="<?php echo esc_attr( $spt_c ); ?>" aria-pressed="false"><?php echo esc_html( $spt_c ); ?></button>
						<?php endforeach; ?>
					</div>
				<?php endforeach; ?>
			</div>

			<div class="filtres__groupe">
				<h2 class="cond filtres__titre" id="f-type">Type d’événement</h2>
				<div class="filtres__puces" role="group" aria-labelledby="f-type">
					<button type="button" class="puce" data-filtre="type" data-valeur="all" aria-pressed="true">Tous</button>
					<?php foreach ( Spa_Calendrier::TYPES_PLURIEL as $spt_k => $spt_nom ) : ?>
						<button type="button" class="puce" data-filtre="type" data-valeur="<?php echo esc_attr( $spt_k ); ?>" aria-pressed="false"><?php echo esc_html( $spt_nom ); ?></button>
					<?php endforeach; ?>
				</div>
			</div>
		</div>

		<p class="cond compteur" role="status" aria-live="polite" data-compteur><?php echo esc_html( Spa_Calendrier::pluriel( $spt_total, 'date', 'dates' ) ); ?></p>

		<?php foreach ( $spt_groupes as $spt_g ) : ?>
			<section class="mois" data-mois>
				<h2 class="disp"><span class="surligne"><?php echo esc_html( $spt_g['label'] ); ?></span></h2>
				<ol>
					<?php foreach ( $spt_g['rows'] as $spt_r ) : ?>
						<li class="date-ligne<?php echo $spt_r['past'] ? ' passe' : ''; ?>" data-date data-disc="<?php echo esc_attr( $spt_r['disc'] ); ?>" data-cat="<?php echo esc_attr( $spt_r['cat'] ); ?>" data-type="<?php echo esc_attr( $spt_r['type'] ); ?>">
							<span class="date-ligne__bloc">
								<span class="disp j"><?php echo esc_html( $spt_r['day'] ); ?></span>
								<span class="cond s"><?php echo esc_html( $spt_r['weekday'] ); ?></span>
							</span>
							<span class="date-ligne__infos">
								<span class="cond date-ligne__disc"><?php echo esc_html( $spt_r['discName'] . ' · ' . $spt_r['cat'] ); ?></span>
								<span class="date-ligne__titre"><?php echo esc_html( $spt_r['title'] ); ?></span>
								<span class="date-ligne__lieu"><?php echo esc_html( $spt_r['time'] . ' · ' . $spt_r['place'] ); ?></span>
							</span>
							<span class="date-ligne__tags">
								<span class="etiquette<?php echo $spt_r['typeMatch'] ? '' : ' etiquette--jaune'; ?>"><?php echo esc_html( $spt_r['typeLabel'] ); ?></span>
								<?php if ( $spt_r['isHome'] ) : ?>
									<span class="etiquette etiquette--plein">Domicile</span>
								<?php elseif ( $spt_r['isAway'] ) : ?>
									<span class="etiquette">Déplacement</span>
								<?php endif; ?>
								<?php if ( $spt_r['past'] ) : ?>
									<span class="visuellement-cache">(date passée)</span>
								<?php endif; ?>
							</span>
						</li>
					<?php endforeach; ?>
				</ol>
			</section>
		<?php endforeach; ?>

		<p class="cond aucune-date" data-vide<?php echo $spt_total ? ' hidden' : ''; ?>>Aucune date ne correspond à ces filtres.</p>
	</div>
</section>

<?php
spt_part(
	'bandeau-rejoindre',
	array(
		'titre' => 'Envie de <span class="jaune">les rejoindre ?</span>',
		'texte' => 'Football américain, flag ou cheerleading : venez essayer, puis inscrivez-vous en ligne.',
		'contact' => 'Nous contacter',
	)
);
get_footer();
