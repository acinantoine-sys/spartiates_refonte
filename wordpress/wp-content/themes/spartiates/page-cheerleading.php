<?php
/**
 * Cheerleading (maquette : design/maquettes/Cheerleading.dc.html).
 *
 * @package Spartiates
 */

defined( 'ABSPATH' ) || exit;

get_header();

$spt_elements = array(
	array( 'Stunts', 'Des portés réalisés en groupe, où la flyer est soutenue par ses bases.' ),
	array( 'Pyramides', 'Des enchaînements de stunts reliés entre eux.' ),
	array( 'Tumbling', 'Les enchaînements au sol hérités de la gymnastique.' ),
	array( 'Sauts', 'Des figures propres à la discipline, comme en gymnastique.' ),
	array( 'Chorégraphie', 'Une partie dansée, sur musique, qui donne tout son spectacle à la routine.' ),
);
$spt_bienfaits = array(
	array( 'eclair', 'Un sport complet', 'Acrobaties, gymnastique et danse : trois univers réunis, sans avoir à choisir.' ),
	array( 'groupe', 'Une deuxième famille', 'Sport d’équipe avant tout, il crée des liens solides entre les athlètes.' ),
	array( 'bouclier', 'Confiance et dépassement', 'Pour que chacun soit en sécurité, il faut se faire confiance et appliquer les règles avec rigueur.' ),
	array( 'cible', 'Concentration', 'Se produire devant un public et en compétition apprend à se concentrer et à gérer son stress.' ),
);
$spt_categories = array(
	array( 'Minimes', 'Les premiers pas : découvrir les bases, les sauts et le plaisir de pratiquer en équipe.' ),
	array( 'Cadets', 'On progresse sur les portés, le tumbling et la chorégraphie.' ),
	array( 'Juniors', 'Des routines plus techniques et l’esprit de compétition.' ),
	array( 'Seniors', 'L’équipe senior porte haut les couleurs du club en compétition.' ),
);
?>

<!-- HÉROS -->
<section class="sombre hero--disc debord" aria-labelledby="titre-cheer">
	<div class="conteneur colonnes">
		<div class="col" style="--base:520px">
			<?php echo spt_fil_ariane( 'Cheerleading' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			<h1 id="titre-cheer" class="disp titre-disc" style="margin-top:18px;margin-bottom:26px">
				<span class="ligne">Danse,</span>
				<span class="ligne">acrobaties,</span>
				<span class="ligne jaune">esprit d’équipe</span>
			</h1>
			<p class="chapo" style="max-width:580px;color:rgba(255,255,255,.86)">Le cheerleading mêle gymnastique, danse et portés spectaculaires. Un vrai sport d’équipe, où l’on se dépasse ensemble et où l’on prend confiance en soi.</p>
			<div class="boutons">
				<?php echo spt_adhesion( 'Rejoindre l’équipe' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				<a class="btn btn-o" href="#entrainements">Les entraînements</a>
			</div>
		</div>
		<div class="col" style="--base:420px">
			<?php
			echo spt_cadre( // phpcs:ignore WordPress.Security.EscapeOutput
				'cheer-dos-fumee.jpg',
				'Une cheerleader des Spartiates de dos, pompons en main, dans la fumée jaune et bleue',
				array(
					'hauteur'  => 600,
					'position' => '50% 40%',
					'marge'    => '0 22px 34px',
					'image'    => array( 'loading' => false, 'fetchpriority' => 'high' ),
					'apres'    => '<p class="badge-rond" style="margin:0"><span class="disp" style="font-size:54px;line-height:.9">' . count( $spt_categories ) . '</span><span class="disp" style="font-size:20px;line-height:1">catégories</span></p>',
				)
			);
			?>
		</div>
	</div>
</section>

<!-- L'ESSENTIEL EN UN COUP D'ŒIL -->
<section class="fond-jaune essentiel" aria-label="L’essentiel en un coup d’œil">
	<ul class="conteneur essentiel__liste">
		<li class="essentiel__item"><div><p class="disp essentiel__titre">Acrobaties</p><p class="essentiel__texte">Portés, pyramides et figures en groupe</p></div></li>
		<li class="essentiel__item"><div><p class="disp essentiel__titre">Gymnastique</p><p class="essentiel__texte">Tumbling et sauts spectaculaires</p></div></li>
		<li class="essentiel__item"><div><p class="disp essentiel__titre">Danse</p><p class="essentiel__texte">Une chorégraphie sur musique</p></div></li>
		<li class="essentiel__item"><div><p class="disp essentiel__titre">Esprit d’équipe</p><p class="essentiel__texte">Se faire confiance pour avancer ensemble</p></div></li>
	</ul>
</section>

<!-- UN SPORT COMPLET -->
<section class="sombre section debord" aria-labelledby="titre-sport">
	<div class="conteneur">
		<div class="colonnes" style="margin-bottom:56px">
			<div class="col" style="--base:440px">
				<p class="cond surtitre surtitre--jaune" style="margin:0">Le sport</p>
				<h2 id="titre-sport" class="disp titre-section titre-section--56">Bien plus que des <span class="jaune">pompons</span></h2>
				<p class="texte" style="max-width:580px;color:rgba(255,255,255,.9)">Né aux États-Unis à la fin du XIX<sup>e</sup> siècle pour encourager les équipes sportives, le cheerleading est devenu un sport à part entière, avec ses compétitions et ses championnats.</p>
				<p class="texte" style="max-width:580px;color:rgba(255,255,255,.9)">En compétition, chaque équipe présente une routine d’environ trois minutes, jugée selon une grille d’évaluation précise. Elle réunit des éléments bien distincts :</p>
			</div>
			<div class="col" style="--base:380px">
				<?php
				echo spt_cadre( // phpcs:ignore WordPress.Security.EscapeOutput
					'cheer-pose-equilibre.jpg',
					'Une cheerleader des Spartiates en pose d’équilibre, la jambe tenue haut, devant de la fumée jaune et bleue',
					array(
						'hauteur'  => 560,
						'miroir'   => true,
						'position' => '50% 30%',
					)
				);
				?>
			</div>
		</div>
		<ul class="grille-el">
			<?php foreach ( $spt_elements as $spt_e ) : ?>
				<li class="el"><h3 class="disp"><?php echo esc_html( $spt_e[0] ); ?></h3><p><?php echo esc_html( $spt_e[1] ); ?></p></li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>

<!-- LES BIENFAITS -->
<section id="bienfaits" class="blanc section debord" aria-labelledby="titre-bienfaits">
	<div class="conteneur colonnes">
		<div class="col" style="--base:380px">
			<?php
			echo spt_cadre( // phpcs:ignore WordPress.Security.EscapeOutput
				'cheer-sourire-pompons.jpg',
				'Une cheerleader des Spartiates souriante, un pompon dans chaque main',
				array(
					'hauteur'  => 640,
					'position' => '50% 22%',
				)
			);
			?>
		</div>
		<div class="col" style="--base:560px">
			<p class="cond surtitre" style="margin:0">Les bienfaits</p>
			<h2 id="titre-bienfaits" class="disp titre-section titre-section--56" style="margin-bottom:18px">Ce que le cheerleading <span class="surligne">apporte</span></h2>
			<p class="texte" style="max-width:600px;line-height:1.6;margin-bottom:32px">Un sport athlétique et spectaculaire, qui développe le corps comme le mental.</p>
			<ul class="grille-ben">
				<?php foreach ( $spt_bienfaits as $spt_b ) : ?>
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

<!-- LES CATÉGORIES -->
<section id="categories" class="clair section" aria-labelledby="titre-categories">
	<div class="conteneur">
		<p class="cond surtitre" style="margin:0">Pour tous les âges</p>
		<h2 id="titre-categories" class="disp titre-section titre-section--xl" style="margin-bottom:18px">Quatre <span class="surligne">catégories</span></h2>
		<p class="texte" style="max-width:700px;line-height:1.6;margin-bottom:40px">Le cheerleading n’impose pas de contre-indication d’âge ou de niveau. Une bonne condition physique et un peu de souplesse sont un plus, et le reste s’apprend à l’entraînement.</p>
		<ul class="cartes-categories">
			<?php foreach ( $spt_categories as $spt_c ) : ?>
				<li><h3 class="disp"><?php echo esc_html( $spt_c[0] ); ?></h3><p><?php echo esc_html( $spt_c[1] ); ?></p></li>
			<?php endforeach; ?>
		</ul>
		<p style="margin:28px 0 0;font-size:16px;color:#3b3b6b">[Âges de chaque catégorie à préciser]</p>
	</div>
</section>

<!-- ENTRAÎNEMENTS (source : horaires) -->
<section id="entrainements" class="sombre section" aria-labelledby="titre-entrainements">
	<div class="conteneur colonnes colonnes--haut" style="gap:48px 64px">
		<div class="col" style="--base:440px">
			<p class="cond surtitre surtitre--jaune" style="margin:0">Rejoindre une équipe</p>
			<h2 id="titre-entrainements" class="disp titre-section titre-section--56">Les <span class="jaune">entraînements</span></h2>
			<p class="texte" style="max-width:520px;margin-bottom:28px">Les séances ont lieu au <?php echo esc_html( spa_reglage( 'coordonnees.lieu' ) ); ?>, ainsi qu’au complexe sportif Dupontreue et dans plusieurs équipements d’Étouvie, selon les catégories.</p>
			<div class="encadre">
				<p class="cond surtitre-petit jaune" style="margin:0 0 8px;color:#fcc409">Pour débuter</p>
				<p>Pas de tenue imposée à l’entraînement : on choisit des vêtements près du corps, qui ne gênent pas les figures, et des baskets d’intérieur souples, avec un bon maintien. L’uniforme est de rigueur en compétition. <span class="emplacement-texte">[Modalités de l’uniforme à confirmer]</span></p>
			</div>
			<div class="boutons">
				<?php echo spt_adhesion( 'Nous rejoindre' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				<a class="btn btn-o" href="<?php echo esc_url( spt_url( 'contact' ) ); ?>">Poser une question</a>
			</div>
		</div>
		<div class="col" style="--base:440px">
			<h3 class="cond surtitre-petit" style="margin:0 0 6px">Horaires par équipe</h3>
			<ul class="lignes lignes--groupe">
				<?php foreach ( spa_horaires()->lignes_entrainements( 'Cheerleading', true ) as $spt_ligne ) : ?>
					<li><?php echo esc_html( $spt_ligne ); ?></li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
</section>

<!-- PALMARÈS ET CALENDRIER (sources : palmarès, calendrier) -->
<section id="calendrier" class="blanc section" aria-labelledby="titre-resultats">
	<div class="conteneur colonnes colonnes--haut">
		<div class="col" style="--base:520px">
			<p class="cond surtitre" style="margin:0">Palmarès</p>
			<h2 id="titre-resultats" class="disp titre-section titre-section--54" style="margin-bottom:30px">Nos <span class="surligne">résultats</span></h2>
			<div class="saisons">
				<?php foreach ( spa_palmares()->saisons_cheerleading() as $spt_s ) : ?>
					<section class="saison" aria-label="<?php echo esc_attr( 'Saison ' . $spt_s['season'] ); ?>">
						<h3 class="disp">Saison <?php echo esc_html( $spt_s['season'] ); ?></h3>
						<ul>
							<?php foreach ( $spt_s['items'] as $spt_r ) : ?>
								<li>
									<div class="infos">
										<span class="cond comp"><?php echo esc_html( $spt_r['comp'] ); ?></span>
										<span class="cat"><?php echo esc_html( $spt_r['cat'] ); ?></span>
										<?php if ( $spt_r['note'] ) : ?>
											<span class="note"><?php echo esc_html( $spt_r['note'] ); ?></span>
										<?php endif; ?>
									</div>
									<?php if ( $spt_r['rank'] ) : ?>
										<span class="disp rang<?php echo $spt_r['podium'] ? ' rang--podium' : ''; ?>"><span class="visuellement-cache">Classement : </span><?php echo esc_html( $spt_r['rank'] ); ?></span>
									<?php endif; ?>
								</li>
							<?php endforeach; ?>
						</ul>
					</section>
				<?php endforeach; ?>
			</div>
			<div style="margin-top:20px"><a class="tlink tlink--souligne" href="<?php echo esc_url( spt_url( 'le-club', 'palmares' ) ); ?>">Tout le palmarès du club →</a></div>
		</div>
		<div class="col" style="--base:420px">
			<p class="cond surtitre" style="margin:0">Compétitions</p>
			<h2 class="disp titre-section titre-section--54" style="margin-bottom:30px">Prochaine <span class="surligne">compétition</span></h2>
			<div class="carte-rdv--encadree">
				<?php spt_part( 'carte-rencontres', array( 'disc' => 'cheer' ) ); ?>
			</div>
		</div>
	</div>
</section>

<?php
get_footer();
