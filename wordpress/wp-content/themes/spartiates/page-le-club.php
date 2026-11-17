<?php
/**
 * Le club (maquette : design/maquettes/Club.dc.html).
 *
 * @package Spartiates
 */

defined( 'ABSPATH' ) || exit;

get_header();

$spt_pal     = spa_palmares();
$spt_seniors = $spt_pal->seniors_football();
$spt_nb_sen  = $spt_pal->nombre_titres_nationaux( 'foot', Spa_Palmares::SENIORS );
?>

<!-- TITRE DE PAGE -->
<section class="sombre hero hero--page">
	<div class="conteneur">
		<?php echo spt_fil_ariane( 'Le club' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
		<h1 class="disp titre-page">Le club <span class="jaune">historique</span></h1>
		<p class="chapo">L’un des premiers clubs de football américain en France, un palmarès riche dans plusieurs disciplines et une association portée par ses bénévoles.</p>
	</div>
	<?php echo spt_frise( 'hero__frise' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
</section>

<!-- HISTOIRE : INTRODUCTION -->
<section class="sombre-2 section" style="padding-top:96px;padding-bottom:40px" aria-labelledby="titre-histoire">
	<div class="conteneur colonnes">
		<div class="col" style="--base:480px">
			<p class="cond surtitre surtitre--jaune" style="margin:0">Notre histoire</p>
			<h2 id="titre-histoire" class="disp titre-section titre-section--xl" style="margin-bottom:28px">L’un des premiers clubs de football américain <span class="jaune">en France</span></h2>
			<p class="texte" style="max-width:600px;margin-bottom:20px">Fondé en 1987, le club des Spartiates d’Amiens Picardie est l’un des clubs historiques du football américain en France. Il est né de la rencontre entre Marc Gambetti, ancien joueur des Iroquois de Rouen, et Xavier Thuilot, passionné de sports américains.</p>
			<p class="texte" style="max-width:600px">En raison de sa situation géographique, le club a mis un point d’honneur à intégrer la formation des jeunes joueurs dans son plan de développement. Une stratégie qui a porté ses fruits.</p>
		</div>
		<div class="col" style="--base:420px">
			<p class="disp" aria-hidden="true" style="margin:0 0 -12px;font-size:clamp(120px,18vw,260px);line-height:.8;color:#fcc409">1987</p>
			<div class="emplacement" style="min-height:360px;background:#0e0e6a;border:2px solid #fcc409">
				<p class="cond" style="margin:0;color:rgba(255,255,255,.85);font-size:18px;max-width:260px;line-height:1.3">[Photo d’archives — premiers matchs du club]</p>
			</div>
		</div>
	</div>
</section>

<!-- HISTOIRE : CHRONOLOGIE (source : Spartiates > Histoire du club) -->
<section class="sombre-2 section" style="padding-top:40px" aria-label="Chronologie du club">
	<div class="conteneur">
		<ol class="chronologie">
			<?php foreach ( spa_chronologie() as $spt_e ) : ?>
				<li>
					<p class="disp a" style="margin:0"><?php echo esc_html( $spt_e['year'] ); ?></p>
					<div class="c">
						<h3><?php echo esc_html( $spt_e['title'] ); ?></h3>
						<p><?php echo esc_html( $spt_e['text'] ); ?></p>
					</div>
				</li>
			<?php endforeach; ?>
		</ol>
	</div>
</section>

<!-- PALMARÈS (source : Spartiates > Palmarès) -->
<section id="palmares" class="clair section" aria-labelledby="titre-palmares">
	<div class="conteneur">
		<p class="cond surtitre" style="margin:0">Palmarès</p>
		<h2 id="titre-palmares" class="disp titre-section titre-section--xl" style="margin-bottom:40px">Des titres, <span class="surligne">dans chaque discipline</span></h2>

		<ul class="stats-club">
			<li><span class="disp n"><?php echo esc_html( spa_nombre_titres_nationaux() ); ?></span><span class="l">titres nationaux, toutes catégories</span></li>
			<li><span class="disp n"><?php echo esc_html( spa_reglage( 'chiffres.finales_nationales' ) ); ?></span><span class="l">finales nationales disputées</span></li>
			<li><span class="disp n n--mot">Europe</span><span class="l">Quart de finale d’Eurobowl, demi-finale d’EFAF Cup</span></li>
		</ul>

		<?php if ( $spt_seniors ) : ?>
			<article class="carte-seniors" aria-labelledby="titre-seniors">
				<div class="carte-seniors__tete">
					<span class="icone-carre icone-carre--jaune icone-carre--56"><?php echo spt_icone( 'foot', 32, '#060640' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
					<h3 id="titre-seniors" class="disp">Football américain · Seniors</h3>
					<p class="cond carte-seniors__total" style="margin-bottom:0;margin-top:0"><?php echo esc_html( $spt_nb_sen . ' ' . ( $spt_nb_sen > 1 ? 'titres' : 'titre' ) . ' de champion de France' ); ?></p>
				</div>
				<div class="carte-seniors__corps">
					<?php foreach ( $spt_seniors as $spt_g ) : ?>
						<div class="carte-seniors__col">
							<h4 class="cond c"><?php echo esc_html( $spt_g['titre'] ); ?></h4>
							<ul class="carte-seniors__annees">
								<?php foreach ( $spt_g['annees'] as $spt_a ) : ?>
									<li class="disp"><?php echo esc_html( $spt_a ); ?></li>
								<?php endforeach; ?>
							</ul>
						</div>
					<?php endforeach; ?>
				</div>
			</article>
		<?php endif; ?>

		<ul class="cartes-disc">
			<?php foreach ( $spt_pal->disciplines_club() as $spt_d ) : ?>
				<?php
				if ( ! $spt_d['entries'] ) {
					continue;
				}
				?>
				<li class="carte-disc">
					<div class="carte-disc__tete">
						<span class="icone-carre"><?php echo spt_icone( $spt_d['disc'], 30, '#fcc409' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
						<h3 class="disp"><?php echo esc_html( $spt_d['name'] ); ?></h3>
					</div>
					<ul>
						<?php foreach ( $spt_d['entries'] as $spt_e ) : ?>
							<li><span class="disp a"><?php echo esc_html( $spt_e['year'] ); ?></span><span class="l"><?php echo esc_html( $spt_e['label'] ); ?></span></li>
						<?php endforeach; ?>
					</ul>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>

<!-- VIE ASSOCIATIVE ET BÉNÉVOLES -->
<section id="benevoles" class="fond-jaune section" aria-labelledby="titre-benevoles">
	<div class="conteneur colonnes">
		<div class="col" style="--base:420px">
			<figure style="position:relative;margin:0">
				<?php echo spt_image( 'images/club-benevoles-mosaique.jpg', 'Bénévoles des Spartiates un jour de match : deux bénévoles souriantes à la buvette, deux bénévoles au barbecue et des supporters au bord du terrain', array( 'style' => 'display:block;width:100%;height:auto' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				<figcaption class="disp" style="position:absolute;left:0;bottom:0;background:#fcc409;color:#060640;padding:12px 22px;font-size:clamp(22px,2.4vw,32px);line-height:1">Nos bénévoles en action</figcaption>
			</figure>
		</div>
		<div class="col" style="--base:480px">
			<p class="cond surtitre surtitre--sombre" style="margin:0">Vie associative</p>
			<h2 id="titre-benevoles" class="disp titre-section titre-section--xl" style="margin-bottom:24px">Le cœur du club : ses bénévoles</h2>
			<p class="texte" style="color:#060640">Les Spartiates sont avant tout une association. Toute la saison, et les jours de match en particulier, des bénévoles donnent de leur temps pour que le club vive : accueillir le public, organiser les rencontres, accompagner les jeunes.</p>
			<p class="texte" style="color:#060640;font-weight:600;margin-bottom:36px">Sans eux, pas de Spartiates.</p>

			<ul class="benevoles-chiffres">
				<li><span class="cond e">Environ</span><span class="disp n"><?php echo esc_html( spa_reglage( 'chiffres.benevoles' ) ); ?></span><span class="l">bénévoles actifs</span></li>
				<li><span class="cond e">Environ</span><span class="disp n"><?php echo esc_html( spa_reglage( 'chiffres.licencies' ) ); ?></span><span class="l">licenciés</span></li>
				<li><span class="disp n"><?php echo esc_html( spa_reglage( 'chiffres.jours_de_match' ) ); ?></span><span class="l">jours de match par saison</span></li>
			</ul>

			<div class="boutons">
				<?php echo spt_adhesion( 'Devenir bénévole', 'navy' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			</div>
		</div>
	</div>
</section>

<?php
get_footer();
