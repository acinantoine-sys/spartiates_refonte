<?php
/**
 * Accueil (maquette : design/maquettes/Main.dc.html).
 *
 * @package Spartiates
 */

defined( 'ABSPATH' ) || exit;

get_header();

$spt_titres  = spa_nombre_titres_nationaux();
$spt_insta   = spa_reglage( 'reseaux.instagram' );
$spt_compte  = spa_reglage( 'reseaux.instagram_compte' );
$spt_sports  = array(
	array(
		'disc'  => 'foot',
		'slug'  => 'football-americain',
		'name'  => 'Football américain',
		'texte' => 'Stratégie, puissance et esprit d’équipe, du jeune joueur au senior.',
		'cta'   => 'Découvrir le football américain',
	),
	array(
		'disc'  => 'flag',
		'slug'  => 'flag',
		'name'  => 'Flag',
		'texte' => 'La version sans plaquage : rapide, accessible et ludique.',
		'cta'   => 'Découvrir le flag',
	),
	array(
		'disc'  => 'cheer',
		'slug'  => 'cheerleading',
		'name'  => 'Cheerleading',
		'texte' => 'Danse, acrobaties et énergie au service du spectacle.',
		'cta'   => 'Découvrir le cheerleading',
	),
);
?>

<!-- HÉROS -->
<section class="sombre hero hero--accueil">
	<div class="conteneur colonnes" style="gap:48px">
		<div class="col" style="--base:520px">
			<p class="cond surtitre surtitre--jaune" style="margin:0">Football américain · Flag · Cheerleading</p>
			<h1 class="disp titre-hero">
				<span>Force.</span>
				<span>Passion.</span>
				<span class="jaune">Volonté.</span>
			</h1>
			<p class="chapo">Depuis 1987, les Spartiates d’Amiens portent haut le sport américain en France : <?php echo esc_html( $spt_titres ); ?> titres nationaux et la formation des jeunes au cœur du projet du club.</p>
			<div class="boutons">
				<?php echo spt_adhesion( 'Nous rejoindre' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				<a class="btn btn-o" href="#rencontres">Prochaines rencontres</a>
			</div>
		</div>
		<div class="col" style="--base:420px">
			<div class="hero-accueil__photo">
				<?php
				echo spt_image( 'images/accueil-hero-mascotte.jpg', 'La mascotte des Spartiates, casque et cape dorée, entre sur le terrain devant les joueurs et les supporters, au milieu des fumigènes bleus et jaunes', array( 'class' => 'photo', 'loading' => false, 'fetchpriority' => 'high' ) ); // phpcs:ignore WordPress.Security.EscapeOutput
				echo spt_logo( 200, 'Logo des Spartiates d’Amiens', array( 'class' => 'hero-accueil__badge', 'loading' => false ) ); // phpcs:ignore WordPress.Security.EscapeOutput
				?>
			</div>
		</div>
	</div>
	<?php echo spt_frise( 'hero__frise' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
</section>

<!-- PROCHAINES RENCONTRES (source : calendrier) -->
<section id="rencontres" class="sombre-2 section" style="padding-top:72px;padding-bottom:88px" aria-labelledby="titre-rencontres">
	<div class="conteneur">
		<div class="entete-section">
			<h2 id="titre-rencontres" class="disp titre-section" style="margin:0">Prochaines <span class="jaune">rencontres</span></h2>
			<a class="tlink tlink--jaune" href="<?php echo esc_url( spt_url( 'calendrier' ) ); ?>">Calendrier complet →</a>
		</div>
		<div class="cartes-rencontres">
			<?php foreach ( array( 'foot', 'flag', 'cheer' ) as $spt_disc ) : ?>
				<div><?php spt_part( 'carte-rencontres', array( 'disc' => $spt_disc ) ); ?></div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<!-- ADHÉSION -->
<section id="adhesion" class="clair section" aria-labelledby="titre-adhesion">
	<div class="conteneur colonnes colonnes--haut">
		<div class="col" style="--base:460px">
			<p class="cond surtitre" style="margin:0">Adhésions</p>
			<h2 id="titre-adhesion" class="disp titre-section titre-section--l">Rejoignez la famille <span class="surligne">Spartiates</span></h2>
			<p class="texte" style="font-size:20px;line-height:1.55;max-width:520px;margin-bottom:36px">Que vous veniez pour le contact, la vitesse ou le spectacle, il y a une place pour vous dans l’un de nos trois sports. Un club associatif, une équipe, une ambition nationale.</p>

			<ol class="etapes">
				<li><span class="disp num" aria-hidden="true">1</span><span class="txt">Choisissez votre discipline</span></li>
				<li><span class="disp num" aria-hidden="true">2</span><span class="txt">Venez nous rencontrer à l’entraînement <span class="emplacement-texte">[modalités à confirmer]</span></span></li>
				<li><span class="disp num" aria-hidden="true">3</span><span class="txt">Inscrivez-vous et portez les couleurs</span></li>
			</ol>

			<div class="boutons">
				<?php echo spt_adhesion( 'Nous rejoindre', 'navy' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				<a class="btn btn-n" href="<?php echo esc_url( spt_url( 'contact' ) ); ?>">Contacter le bureau</a>
			</div>
		</div>

		<div class="col" style="--base:480px">
			<ul class="cartes-sport">
				<?php foreach ( $spt_sports as $spt_s ) : ?>
					<li>
						<a class="carte-sport" href="<?php echo esc_url( spt_url( $spt_s['slug'] ) ); ?>">
							<span class="carte-sport__icone"><?php echo spt_icone( $spt_s['disc'], 44, '#fcc409' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
							<div style="min-width:0">
								<h3 class="disp"><?php echo esc_html( $spt_s['name'] ); ?></h3>
								<p><?php echo esc_html( $spt_s['texte'] ); ?></p>
								<span class="cta"><?php echo esc_html( $spt_s['cta'] ); ?> →</span>
							</div>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
</section>

<!-- LE CLUB EN CHIFFRES -->
<section id="club" class="sombre" aria-labelledby="titre-club">
	<?php echo spt_frise(); // phpcs:ignore WordPress.Security.EscapeOutput ?>
	<div class="conteneur section" style="padding-top:72px;padding-bottom:80px">
		<div class="entete-section" style="margin-bottom:40px">
			<h2 id="titre-club" class="disp titre-section titre-section--m">Un club <span class="jaune">historique</span></h2>
			<a class="tlink tlink--jaune" href="<?php echo esc_url( spt_url( 'le-club' ) ); ?>">Découvrir l’histoire du club →</a>
		</div>
		<ul class="chiffres">
			<li><span class="disp n">1987</span><span class="l">Année de fondation à Amiens</span></li>
			<li><span class="disp n"><?php echo esc_html( $spt_titres ); ?></span><span class="l">Titres nationaux</span></li>
			<li><span class="disp n"><?php echo esc_html( spa_reglage( 'chiffres.finales_nationales' ) ); ?></span><span class="l">Finales nationales disputées</span></li>
			<li><span class="disp n">3</span><span class="l">Disciplines : football américain, flag, cheerleading</span></li>
		</ul>
	</div>
</section>

<!-- INSTAGRAM -->
<section class="blanc section" style="padding-top:96px;padding-bottom:96px" aria-labelledby="titre-insta">
	<div class="conteneur">
		<div class="entete-section" style="gap:20px 32px">
			<div>
				<p class="cond surtitre" style="margin:0">Instagram · <?php echo esc_html( $spt_compte ); ?></p>
				<h2 id="titre-insta" class="disp titre-section titre-section--m" style="margin-top:10px">Le club <span class="surligne">en direct</span></h2>
			</div>
			<a class="btn btn-n" href="<?php echo esc_url( $spt_insta ); ?>">Suivre <?php echo esc_html( $spt_compte ); ?></a>
		</div>
		<?php
		/*
		 * Emplacements des six dernières publications Instagram, comme dans la maquette.
		 * Le flux reste à brancher : l'outil n'est pas encore choisi (docs/05-a-completer.md).
		 */
		?>
		<ul class="insta">
			<?php for ( $spt_i = 1; $spt_i <= 6; $spt_i++ ) : ?>
				<li>
					<a href="<?php echo esc_url( $spt_insta ); ?>" aria-label="Voir la publication sur Instagram">
						<span class="cond">[Publication Instagram <?php echo (int) $spt_i; ?>]</span>
						<?php echo spt_icone( 'instagram', 24, '#060640' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					</a>
				</li>
			<?php endfor; ?>
		</ul>
	</div>
</section>

<?php
get_footer();
