<?php
/**
 * Contact (maquette : design/maquettes/Contact.dc.html).
 *
 * @package Spartiates
 */

defined( 'ABSPATH' ) || exit;

get_header();

$spt_h     = spa_horaires();
$spt_tel   = spa_reglage( 'coordonnees.telephone' );
$spt_email = spa_reglage( 'coordonnees.email' );
$spt_lieu  = spa_reglage( 'coordonnees.lieu' );
?>

<!-- TITRE DE PAGE -->
<section class="sombre hero hero--page">
	<div class="conteneur">
		<?php echo spt_fil_ariane( 'Contact' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
		<h1 class="disp titre-page">Nous <span class="jaune">contacter</span></h1>
		<p class="chapo" style="max-width:640px">Une question sur nos trois sports, une adhésion, un partenariat ? Le bureau des Spartiates vous répond, ou vous accueille directement à Amiens.</p>
	</div>
	<?php echo spt_frise( 'hero__frise' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
</section>

<!-- COORDONNÉES -->
<section class="clair section" style="padding-top:80px;padding-bottom:56px">
	<div class="conteneur cartes-contact">

		<article class="carte-contact carte-contact--joindre" aria-labelledby="titre-joindre">
			<div class="carte-contact__tete">
				<span class="icone-carre icone-carre--56"><?php echo spt_icone( 'telephone', 30, '#fcc409' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
				<h2 id="titre-joindre" class="disp">Nous joindre</h2>
			</div>
			<div>
				<h3 class="cond sous-libelle" style="margin:0 0 6px">Téléphone</h3>
				<a class="bigl" href="<?php echo esc_attr( spt_tel( $spt_tel ) ); ?>"><?php echo esc_html( $spt_tel ); ?></a>
			</div>
			<div>
				<h3 class="cond sous-libelle" style="margin:0 0 6px">E-mail</h3>
				<a class="bigl" href="mailto:<?php echo esc_attr( $spt_email ); ?>"><?php echo esc_html( $spt_email ); ?></a>
			</div>
			<div class="bas" style="padding-top:20px;border-top:1px solid #d9daea">
				<h3 class="cond sous-libelle" style="margin:0 0 4px">Suivez-nous</h3>
				<ul class="reseaux">
					<?php foreach ( spt_reseaux() as $spt_nom => $spt_url ) : ?>
						<li><a class="tlink" href="<?php echo esc_url( $spt_url ); ?>"><?php echo esc_html( $spt_nom ); ?></a></li>
					<?php endforeach; ?>
				</ul>
			</div>
		</article>

		<article class="carte-contact" aria-labelledby="titre-bureau">
			<div class="carte-contact__tete">
				<span class="icone-carre icone-carre--56"><?php echo spt_icone( 'repere', 30, '#fcc409' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
				<h2 id="titre-bureau" class="disp">Le bureau</h2>
			</div>
			<address class="adresse">Bureau des Spartiates d’Amiens<br><?php echo esc_html( $spt_lieu ); ?><br><?php echo esc_html( spa_reglage( 'coordonnees.rue' ) ); ?><br><?php echo esc_html( spa_reglage( 'coordonnees.ville' ) ); ?></address>
			<div>
				<h3 class="cond sous-libelle" style="display:flex;align-items:center;gap:8px;margin:0 0 8px"><?php echo spt_icone( 'horloge', 18, '#3b3b6b' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>Horaires d’ouverture</h3>
				<dl class="horaires-table">
					<?php foreach ( $spt_h->tableau_bureau() as $spt_j ) : ?>
						<div><dt><?php echo esc_html( $spt_j['day'] ); ?></dt><dd><?php echo esc_html( $spt_j['hours'] ); ?></dd></div>
					<?php endforeach; ?>
				</dl>
			</div>
			<a class="tlink bas" href="<?php echo esc_url( spt_itineraire_url() ); ?>">Itinéraire vers le bureau →</a>
		</article>

		<article class="carte-contact" aria-labelledby="titre-stade">
			<div class="carte-contact__tete">
				<span class="icone-carre icone-carre--jaune icone-carre--56"><?php echo spt_icone( 'foot', 30, '#060640' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
				<h2 id="titre-stade" class="disp">Le stade</h2>
			</div>
			<p class="adresse"><?php echo esc_html( $spt_lieu ); ?><br>Même adresse que le bureau</p>
			<div class="bloc-lignes">
				<div>
					<h3 class="cond sous-libelle" style="margin:0">Horaires d’ouverture du stade</h3>
					<dl class="horaires-stade" style="margin-top:6px">
						<?php foreach ( $spt_h->ouverture_stade() as $spt_j ) : ?>
							<div><dt><?php echo esc_html( $spt_j['day'] ); ?></dt><dd><?php echo esc_html( $spt_j['hours'] ); ?></dd></div>
						<?php endforeach; ?>
					</dl>
				</div>
				<div>
					<h3 class="cond sous-libelle" style="margin:0">Entraînements</h3>
					<ul class="lignes lignes--simple">
						<?php foreach ( $spt_h->lignes_entrainements() as $spt_l ) : ?>
							<li><?php echo esc_html( $spt_l ); ?></li>
						<?php endforeach; ?>
					</ul>
				</div>
				<div>
					<h3 class="cond sous-libelle" style="margin:0">Accès et stationnement</h3>
					<p style="margin:0;font-size:17px;color:#2c2c5e">[Bus, parking, accessibilité à compléter]</p>
				</div>
			</div>
		</article>

	</div>
</section>

<!-- CARTE -->
<section class="clair section" style="padding-top:0;padding-bottom:96px" aria-labelledby="titre-trouver">
	<div class="conteneur colonnes colonnes--etire" style="gap:24px">
		<div class="col" style="--base:260px;flex-grow:1;display:flex;flex-direction:column;gap:16px">
			<h2 id="titre-trouver" class="disp titre-section" style="--taille:clamp(36px,4vw,52px);margin:0 0 4px">Nous <span class="surligne" style="padding:0 8px">trouver</span></h2>
			<div class="carte-trouver">
				<?php echo spt_icone( 'repere', 28, '#060640' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				<div>
					<p style="margin:0;font-weight:700;font-size:18px">Le bureau et le stade</p>
					<p style="margin:0;font-size:16px;color:#2c2c5e"><?php echo esc_html( spt_adresse_ligne() ); ?></p>
				</div>
			</div>
			<a class="btn btn-plein" href="<?php echo esc_url( spt_itineraire_url() ); ?>">Itinéraire</a>
		</div>
		<div class="col emplacement" style="--base:520px;flex-grow:3;min-height:420px">
			<p class="cond" style="margin:0;font-size:18px;max-width:360px;line-height:1.35">[Carte interactive à intégrer — bureau et stade du Grand Marais repérés]</p>
		</div>
	</div>
</section>

<?php
spt_part(
	'bandeau-rejoindre',
	array(
		'titre' => 'Envie de rejoindre <span class="jaune">l’aventure ?</span>',
		'texte' => 'Football américain, flag ou cheerleading : passez nous voir ou appelez le bureau.',
	)
);
get_footer();
