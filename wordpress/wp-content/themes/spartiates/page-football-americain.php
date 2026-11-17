<?php
/**
 * Football américain (maquette : design/maquettes/Football.dc.html).
 *
 * @package Spartiates
 */

defined( 'ABSPATH' ) || exit;

get_header();

$spt_adresse = spa_reglage( 'coordonnees.lieu' ) . ', ' . spt_adresse_ligne();
?>

<!-- TITRE DE PAGE -->
<section class="sombre hero debord">
	<div class="conteneur colonnes" style="gap:40px 56px">
		<div class="col" style="--base:520px">
			<?php echo spt_fil_ariane( 'Football américain' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			<h1 class="disp titre-page" style="font-size:clamp(56px,8.6vw,124px);margin-bottom:22px">Football <span class="jaune">américain</span></h1>
			<p class="chapo" style="max-width:600px;margin-bottom:32px;color:rgba(255,255,255,.86)">Deux équipes seniors, en D1 et en D2, des équipes de jeunes et un club qui forme ses joueurs depuis 1987.</p>
			<div class="boutons">
				<a class="btn btn-y" href="#calendrier">Voir le calendrier</a>
				<a class="btn btn-o" href="#entrainements">Les entraînements</a>
			</div>
		</div>
		<div class="col" style="--base:420px">
			<?php
			echo spt_cadre( // phpcs:ignore WordPress.Security.EscapeOutput
				'football-hero-joueur.jpg',
				'Un joueur des Spartiates, casque jaune et maillot marine, entre sur le terrain du Stade du Grand Marais',
				array(
					'hauteur'  => 420,
					'position' => '50% 30%',
					'marge'    => '0 22px',
					'image'    => array( 'loading' => false, 'fetchpriority' => 'high' ),
				)
			);
			?>
		</div>
	</div>
	<?php echo spt_frise( 'hero__frise' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
</section>

<!-- CALENDRIER (source : calendrier) -->
<section id="calendrier" class="sombre-2 section" style="padding-top:88px;padding-bottom:96px" aria-labelledby="titre-calendrier">
	<div class="conteneur">
		<div style="margin-bottom:36px">
			<h2 id="titre-calendrier" class="disp titre-section" style="margin:0">Calendrier <span class="jaune"><?php echo esc_html( spa_calendrier()->annees( 'foot' ) ); ?></span></h2>
			<p class="chapo" style="margin:14px 0 0;max-width:760px;font-size:18px;color:rgba(255,255,255,.86)">Les rencontres à domicile se jouent au <?php echo esc_html( $spt_adresse ); ?>. <span class="emplacement-texte">[Horaires de chaque rencontre à confirmer]</span></p>
		</div>
		<div class="listes-equipes">
			<?php foreach ( spa_calendrier()->listes_par_categorie( 'foot' ) as $spt_liste ) : ?>
				<section class="liste-equipe" aria-label="<?php echo esc_attr( 'Calendrier ' . $spt_liste['title'] ); ?>">
					<div class="liste-equipe__tete">
						<h3 class="disp"><?php echo esc_html( $spt_liste['title'] ); ?></h3>
						<span class="liste-equipe__compte"><?php echo esc_html( $spt_liste['countLabel'] ); ?></span>
					</div>
					<ol>
						<?php foreach ( $spt_liste['rows'] as $spt_r ) : ?>
							<li class="<?php echo $spt_r['past'] ? 'passe' : ''; ?>">
								<span class="quand">
									<span class="disp j"><?php echo esc_html( $spt_r['day'] ); ?></span>
									<span class="cond m"><?php echo esc_html( $spt_r['monthYear'] ); ?></span>
								</span>
								<span class="quoi">
									<span class="t"><?php echo esc_html( $spt_r['title'] ); ?></span>
									<span class="v"><?php echo esc_html( $spt_r['city'] ); ?></span>
								</span>
								<?php if ( $spt_r['isHome'] ) : ?>
									<span class="etiquette etiquette--plein">Domicile</span>
								<?php elseif ( $spt_r['isAway'] ) : ?>
									<span class="etiquette">Déplacement</span>
								<?php endif; ?>
								<?php if ( $spt_r['past'] ) : ?>
									<span class="visuellement-cache">(date passée)</span>
								<?php endif; ?>
							</li>
						<?php endforeach; ?>
					</ol>
				</section>
			<?php endforeach; ?>
		</div>
		<div style="margin-top:28px"><a class="btn btn-y" href="<?php echo esc_url( spt_url( 'calendrier' ) ); ?>">Voir tout le calendrier du club</a></div>
	</div>
</section>

<!-- VENEZ SUPPORTER -->
<section class="clair section debord" aria-labelledby="titre-supporter">
	<div class="conteneur colonnes">
		<div class="col" style="--base:440px">
			<p class="cond surtitre" style="margin:0">Au stade</p>
			<h2 id="titre-supporter" class="disp titre-section titre-section--56">Venez supporter <span class="surligne">les Spartiates</span></h2>
			<p class="texte">Les jours de match, le <?php echo esc_html( spa_reglage( 'coordonnees.lieu' ) ); ?> accueille supporters et familles, avec des bénévoles du club pour vous recevoir.</p>
			<p class="texte" style="margin-bottom:32px">Deux équipes seniors à encourager, en D1 comme en D2.</p>
			<a class="btn btn-n" href="#calendrier">Voir les rencontres à domicile</a>
		</div>
		<div class="col" style="--base:460px">
			<?php
			echo spt_cadre( // phpcs:ignore WordPress.Security.EscapeOutput
				'supporters-bord-terrain.jpg',
				'Supporters au bord du terrain, devant la bâche des partenaires du club',
				array(
					'hauteur' => 460,
					'miroir'  => true,
				)
			);
			?>
		</div>
	</div>
</section>

<!-- DANS L'ACTION -->
<section class="sombre section debord" aria-labelledby="titre-jeu">
	<div class="conteneur colonnes" style="gap:56px 48px">
		<div class="col" style="--base:320px">
			<p class="cond surtitre surtitre--jaune" style="margin:0">Sur le terrain</p>
			<h2 id="titre-jeu" class="disp titre-section titre-section--56">Ils vivent <span class="jaune">le jeu</span></h2>
			<p class="texte" style="max-width:420px">Plaquages, touchdowns, célébrations : le football américain se vit à fond, de l’entrée sur le terrain au dernier sifflet.</p>
		</div>
		<div class="col" style="--base:300px">
			<?php
			echo spt_cadre( // phpcs:ignore WordPress.Security.EscapeOutput
				'football-joie-maillot-blanc.jpg',
				'Un joueur des Spartiates en maillot blanc, le bras levé, crie sa joie après une action',
				array(
					'hauteur'  => 540,
					'pente'    => 10,
					'position' => '35% 40%',
				)
			);
			?>
		</div>
		<div class="col" style="--base:300px">
			<?php
			echo spt_cadre( // phpcs:ignore WordPress.Security.EscapeOutput
				'football-action-nuit.jpg',
				'Un joueur des Spartiates, ballon en main, célèbre une action avec ses coéquipiers lors d’un match de nuit',
				array(
					'hauteur'  => 540,
					'pente'    => 10,
					'miroir'   => true,
					'dy'       => -22,
					'position' => '60% 45%',
					'marge'    => '22px 22px 0',
				)
			);
			?>
		</div>
	</div>
</section>

<!-- PALMARÈS (source : palmarès) -->
<section class="blanc section" aria-labelledby="titre-palmares">
	<div class="conteneur">
		<p class="cond surtitre" style="margin:0">Palmarès</p>
		<h2 id="titre-palmares" class="disp titre-section" style="margin-bottom:40px">Un palmarès <span class="surligne">qui compte</span></h2>
		<ul class="grille-palmares">
			<?php foreach ( spa_palmares()->cartes_football() as $spt_g ) : ?>
				<li class="carte-titre">
					<span class="cond c"><?php echo esc_html( $spt_g['cat'] ); ?></span>
					<span class="disp t"><?php echo esc_html( $spt_g['titre'] ); ?></span>
					<span class="disp a"><?php echo esc_html( implode( ' · ', $spt_g['annees'] ) ); ?></span>
				</li>
			<?php endforeach; ?>
		</ul>
		<div style="margin-top:28px"><a class="tlink" href="<?php echo esc_url( spt_url( 'le-club', 'palmares' ) ); ?>">Tout le palmarès du club →</a></div>
	</div>
</section>

<!-- ENTRAÎNEMENTS (source : horaires) -->
<section id="entrainements" class="sombre section" aria-labelledby="titre-entrainements">
	<div class="conteneur colonnes colonnes--haut" style="gap:48px 64px">
		<div class="col" style="--base:440px">
			<p class="cond surtitre surtitre--jaune" style="margin:0">Rejoindre une équipe</p>
			<h2 id="titre-entrainements" class="disp titre-section titre-section--56">Les <span class="jaune">entraînements</span></h2>
			<p class="texte" style="max-width:520px;margin-bottom:32px">Les équipes de football américain s’entraînent au <?php echo esc_html( $spt_adresse ); ?>. Venez rejoindre l’une d’elles.</p>
			<div class="boutons">
				<?php echo spt_adhesion( 'Nous rejoindre' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				<a class="btn btn-o" href="<?php echo esc_url( spt_url( 'contact' ) ); ?>">Poser une question</a>
			</div>
		</div>
		<div class="col" style="--base:440px">
			<h3 class="cond surtitre-petit" style="margin:0 0 6px">Horaires par équipe</h3>
			<ul class="lignes lignes--groupe">
				<?php foreach ( spa_horaires()->lignes_entrainements( 'Football', true ) as $spt_ligne ) : ?>
					<li><?php echo esc_html( $spt_ligne ); ?></li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
</section>

<?php
get_footer();
