<?php
/**
 * Flag (maquette : design/maquettes/Flag.dc.html). Page pensée pour les parents.
 *
 * @package Spartiates
 */

defined( 'ABSPATH' ) || exit;

get_header();

$spt_h      = spa_horaires();
$spt_lieu   = spa_reglage( 'coordonnees.lieu' );
$spt_tel    = spa_reglage( 'coordonnees.telephone' );
$spt_email  = spa_reglage( 'coordonnees.email' );
$spt_ben    = array(
	array( 'courbe', 'Agilité et motricité', 'Courir, esquiver, changer de direction : la coordination se travaille en jouant.' ),
	array( 'eclair', 'Tactique et physique', 'De la vitesse, mais aussi de l’intelligence de jeu : lire le terrain et choisir la bonne action.' ),
	array( 'bouclier', 'Sans contact', 'Pas de plaquage : on arrête le porteur du ballon en attrapant le flag de sa ceinture.' ),
	array( 'groupe', 'Filles et garçons', 'Une équipe mixte, où chacun trouve sa place et son rôle.' ),
);
?>

<!-- HÉROS -->
<section class="sombre hero--disc debord" aria-labelledby="titre-flag">
	<div class="conteneur colonnes">
		<div class="col" style="--base:520px">
			<p class="cond surtitre surtitre--jaune" style="margin:0">Flag football · Filles et garçons</p>
			<h1 id="titre-flag" class="disp titre-disc">
				<span class="ligne">Le flag,</span>
				<span class="ligne">vos enfants</span>
				<span class="ligne jaune">vont adorer</span>
			</h1>
			<p class="chapo" style="color:rgba(255,255,255,.86)">Un sport d’équipe mixte, sans contact, accessible dès 8 ans : on court, on esquive, on réfléchit ensemble. L’énergie du football américain, sans les plaquages.</p>
			<div class="boutons">
				<?php echo spt_adhesion( 'Inscrire mon enfant' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				<a class="btn btn-o" href="#pourquoi">Pourquoi le flag ?</a>
			</div>
		</div>
		<div class="col" style="--base:420px">
			<?php
			echo spt_cadre( // phpcs:ignore WordPress.Security.EscapeOutput
				'flag-joueur-action.jpg',
				'Un joueur des Spartiates en action, le flag orange accroché à la ceinture',
				array(
					'hauteur'  => 580,
					'position' => '50% 35%',
					'marge'    => '0 22px 34px',
					'image'    => array( 'loading' => false, 'fetchpriority' => 'high' ),
					'apres'    => '<p class="badge-rond" style="margin:0"><span class="disp" style="font-size:28px;line-height:1">Dès</span><span class="disp" style="font-size:54px;line-height:.9">8 ans</span></p>',
				)
			);
			?>
		</div>
	</div>
</section>

<!-- L'ESSENTIEL EN UN COUP D'ŒIL -->
<section class="fond-jaune essentiel" aria-label="L’essentiel en un coup d’œil">
	<ul class="conteneur essentiel__liste">
		<li class="essentiel__item"><?php echo spt_icone( 'horloge', 44, '#060640' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><div><p class="disp essentiel__titre">Dès 8 ans</p><p class="essentiel__texte">Pour les enfants à partir de 8 ans</p></div></li>
		<li class="essentiel__item"><?php echo spt_icone( 'groupe', 44, '#060640' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><div><p class="disp essentiel__titre">Mixte</p><p class="essentiel__texte">Filles et garçons dans la même équipe</p></div></li>
		<li class="essentiel__item"><?php echo spt_icone( 'bouclier', 44, '#060640' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><div><p class="disp essentiel__titre">Sans contact</p><p class="essentiel__texte">On attrape un flag, on ne plaque pas</p></div></li>
		<li class="essentiel__item"><?php echo spt_icone( 'trophee', 44, '#060640' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><div><p class="disp essentiel__titre">Olympique en 2028</p><p class="essentiel__texte">Aux Jeux de Los Angeles</p></div></li>
	</ul>
</section>

<!-- POURQUOI LE FLAG -->
<section id="pourquoi" class="blanc section debord" aria-labelledby="titre-pourquoi">
	<div class="conteneur colonnes">
		<div class="col" style="--base:380px">
			<?php
			echo spt_cadre( // phpcs:ignore WordPress.Security.EscapeOutput
				'flag-sprint-nuit.jpg',
				'Un joueur de flag en plein sprint, de nuit, flags à la ceinture',
				array(
					'hauteur'  => 640,
					'miroir'   => true,
					'position' => '30% 60%',
				)
			);
			?>
		</div>
		<div class="col" style="--base:560px">
			<p class="cond surtitre" style="margin:0">Pour votre enfant</p>
			<h2 id="titre-pourquoi" class="disp titre-section titre-section--56" style="margin-bottom:18px">Ce que le flag <span class="surligne">lui apporte</span></h2>
			<p class="texte" style="max-width:600px;line-height:1.6;margin-bottom:32px">Un sport complet, où l’on se dépense autant avec les jambes qu’avec la tête, dans une ambiance d’équipe.</p>
			<ul class="grille-ben">
				<?php foreach ( $spt_ben as $spt_b ) : ?>
					<li class="ben">
						<span class="ben__icone"><?php echo spt_icone( $spt_b[0], 28, '#fcc409' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
						<h3 class="disp"><?php echo esc_html( $spt_b[1] ); ?></h3>
						<p><?php echo esc_html( $spt_b[2] ); ?></p>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
</section>

<!-- LES JEUX OLYMPIQUES -->
<section class="sombre section debord" style="padding-top:96px;padding-bottom:96px" aria-labelledby="titre-jo">
	<div class="conteneur colonnes" style="gap:32px 56px">
		<div class="col" style="--base:420px">
			<p class="cond surtitre surtitre--jaune" style="margin:0;font-size:28px;letter-spacing:.24em">Los Angeles</p>
			<p class="disp" aria-hidden="true" style="margin:0;font-size:clamp(120px,20vw,300px);line-height:.85;color:transparent;-webkit-text-stroke:3px #fcc409">2028</p>
		</div>
		<div class="col" style="--base:460px">
			<p class="cond surtitre surtitre--jaune" style="margin:0">Sport olympique</p>
			<h2 id="titre-jo" class="disp titre-section titre-section--54">Le flag entre aux <span class="jaune">Jeux olympiques</span></h2>
			<p class="texte" style="font-size:20px;line-height:1.6;margin-bottom:28px">Le flag football sera au programme des Jeux olympiques de Los Angeles en 2028. Pour vos enfants, c’est le bon moment de s’y mettre : les Spartiates les accueillent dès 8 ans.</p>
			<?php echo spt_adhesion( 'Inscrire mon enfant' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
		</div>
	</div>
</section>

<!-- UN ACCUEIL POUR TOUTE LA FAMILLE -->
<section class="clair section debord" aria-labelledby="titre-famille">
	<div class="conteneur colonnes">
		<div class="col" style="--base:440px">
			<p class="cond surtitre" style="margin:0">Au club</p>
			<h2 id="titre-famille" class="disp titre-section titre-section--54">Un accueil chaleureux pour <span class="surligne">toute la famille</span></h2>
			<p class="texte">Au <?php echo esc_html( $spt_lieu ); ?>, des bénévoles accueillent les familles et les supporters à chaque rencontre. Sur le terrain, des coachs accompagnent les joueurs.</p>
			<p class="texte" style="margin-bottom:28px">Le flag est ouvert à tous : les enfants en priorité, mais aussi les jeunes et les adultes.</p>
			<?php foreach ( spa_palmares()->titres_nationaux( 'flag' ) as $spt_titre ) : ?>
				<p class="cond" style="display:inline-block;background:#060640;color:#fcc409;font-size:20px;padding:10px 18px;margin:0 8px 8px 0"><?php echo esc_html( $spt_titre ); ?></p>
			<?php endforeach; ?>
		</div>
		<div class="col" style="--base:460px">
			<div class="famille-photos">
				<?php
				echo spt_image( 'images/supporters-bord-terrain.jpg', 'Supporters et bénévoles au bord du terrain, devant la bâche des partenaires du club', array( 'class' => 'famille-photos__a' ) ); // phpcs:ignore WordPress.Security.EscapeOutput
				?>
				<div class="famille-photos__fond" aria-hidden="true"></div>
				<?php
				echo spt_image( 'images/flag-coach-plan-de-jeu.jpg', 'Un joueur et son coach étudient un plan de jeu sur une planchette aux couleurs des Spartiates', array( 'class' => 'famille-photos__b' ) ); // phpcs:ignore WordPress.Security.EscapeOutput
				?>
			</div>
		</div>
	</div>
</section>

<!-- INSCRIPTION -->
<section id="inscription" class="sombre-2 section" aria-labelledby="titre-inscription">
	<div class="conteneur colonnes colonnes--haut">
		<div class="col" style="--base:440px">
			<p class="cond surtitre surtitre--jaune" style="margin:0">Inscriptions</p>
			<h2 id="titre-inscription" class="disp titre-section titre-section--56" style="margin-bottom:32px">Inscrire votre enfant en <span class="jaune">3 étapes</span></h2>
			<ol class="etapes-s">
				<li><span class="disp num" aria-hidden="true">1</span><div><p class="t">Contactez le bureau</p><p class="d">Par téléphone ou par e-mail, nous répondons à toutes vos questions.</p></div></li>
				<li><span class="disp num" aria-hidden="true">2</span><div><p class="t">Venez rencontrer l’équipe</p><p class="d">Au <?php echo esc_html( $spt_lieu ); ?>, pendant un entraînement des jeunes (horaires dans « Infos pratiques »). <span class="emplacement-texte">[Modalités de la séance d’essai à confirmer]</span></p></div></li>
				<li><span class="disp num" aria-hidden="true">3</span><div><p class="t">Inscrivez votre enfant</p><p class="d">L’adhésion de la saison se fait en ligne sur HelloAsso. Il rejoint ensuite son équipe, sa ceinture de flags à la taille.</p></div></li>
			</ol>
			<div class="boutons">
				<?php echo spt_adhesion( 'Inscrire mon enfant' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				<a class="btn btn-o" href="<?php echo esc_url( spt_url( 'contact' ) ); ?>">Poser une question</a>
			</div>
		</div>

		<div class="col" style="--base:400px">
			<div class="infos-pratiques">
				<h3 class="disp">Infos pratiques</h3>
				<dl>
					<div><dt class="cond">Âge</dt><dd>À partir de 8 ans</dd></div>
					<div>
						<dt class="cond">Entraînements des jeunes</dt>
						<dd><ul class="lignes lignes--fort"><?php foreach ( $spt_h->lignes_entrainements( 'Flag jeunes' ) as $spt_l ) : ?><li><?php echo esc_html( $spt_l ); ?></li><?php endforeach; ?></ul></dd>
					</div>
					<div>
						<dt class="cond">Entraînements des seniors</dt>
						<dd><ul class="lignes lignes--fort"><?php foreach ( $spt_h->lignes_entrainements( 'Flag seniors' ) as $spt_l ) : ?><li><?php echo esc_html( $spt_l ); ?></li><?php endforeach; ?></ul></dd>
					</div>
					<div><dt class="cond">Lieu</dt><dd><?php echo esc_html( $spt_lieu . ', ' . spt_adresse_ligne() ); ?></dd></div>
					<div>
						<dt class="cond">Licence et tarifs</dt>
						<dd>Adhésion de la saison : tarifs consultables sur notre page HelloAsso.<br><?php echo spt_adhesion( 'Voir les tarifs et adhérer sur HelloAsso →', 'lien-souligne' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></dd>
					</div>
					<div><dt class="cond">Équipement</dt><dd>Une tenue de sport et une paire de crampons (recommandé)</dd></div>
					<div><dt class="cond">Contact</dt><dd><a href="<?php echo esc_attr( spt_tel( $spt_tel ) ); ?>"><?php echo esc_html( $spt_tel ); ?></a> · <a href="mailto:<?php echo esc_attr( $spt_email ); ?>"><?php echo esc_html( $spt_email ); ?></a></dd></div>
				</dl>
			</div>
		</div>
	</div>
</section>

<?php
get_footer();
