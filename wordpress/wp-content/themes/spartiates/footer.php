<?php
/**
 * Bandeau partenaires (toutes les pages) et pied de page.
 *
 * @package Spartiates
 */

defined( 'ABSPATH' ) || exit;

$spt_tel   = spa_reglage( 'coordonnees.telephone' );
$spt_email = spa_reglage( 'coordonnees.email' );
?>
</main>

<?php spt_part( 'partenaires' ); ?>

<footer class="pied">
	<?php echo spt_frise(); // phpcs:ignore WordPress.Security.EscapeOutput ?>
	<div class="pied__corps">
		<div class="pied__colonnes">
			<div class="pied__col pied__col--large">
				<?php echo spt_logo( 120, 'Logo des Spartiates d’Amiens', array( 'class' => 'pied__logo' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				<p class="disp pied__devise">Force · Passion · <span class="jaune">Volonté</span></p>
				<p class="pied__texte">Club associatif de football américain, flag et cheerleading, fondé à Amiens en 1987.</p>
			</div>

			<div class="pied__col pied__col--moyen">
				<h2 class="cond pied__titre pied__titre--espace">Le bureau</h2>
				<address>
					<?php echo esc_html( spa_reglage( 'coordonnees.lieu' ) ); ?><br>
					<?php echo esc_html( spa_reglage( 'coordonnees.rue' ) ); ?><br>
					<?php echo esc_html( spa_reglage( 'coordonnees.ville' ) ); ?><br>
					<a href="<?php echo esc_attr( spt_tel( $spt_tel ) ); ?>"><?php echo esc_html( $spt_tel ); ?></a><br>
					<a href="mailto:<?php echo esc_attr( $spt_email ); ?>"><?php echo esc_html( $spt_email ); ?></a>
				</address>
				<ul class="pied__horaires" aria-label="Horaires d’ouverture du bureau">
					<?php foreach ( spa_horaires()->lignes_pied_de_page() as $spt_ligne ) : ?>
						<li><?php echo esc_html( $spt_ligne ); ?></li>
					<?php endforeach; ?>
				</ul>
			</div>

			<nav class="pied__col" aria-labelledby="pied-club">
				<h2 class="cond pied__titre" id="pied-club">Le club</h2>
				<ul class="pied__liens">
					<li><a class="tlink" href="<?php echo esc_url( spt_url( 'football-americain' ) ); ?>">Football américain</a></li>
					<li><a class="tlink" href="<?php echo esc_url( spt_url( 'flag' ) ); ?>">Flag</a></li>
					<li><a class="tlink" href="<?php echo esc_url( spt_url( 'cheerleading' ) ); ?>">Cheerleading</a></li>
					<li><?php echo spt_adhesion( 'Nous rejoindre', 'lien' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></li>
				</ul>
			</nav>

			<nav class="pied__col" aria-labelledby="pied-reseaux">
				<h2 class="cond pied__titre" id="pied-reseaux">Suivez-nous</h2>
				<ul class="pied__liens">
					<?php foreach ( spt_reseaux() as $spt_nom => $spt_url ) : ?>
						<li><a class="tlink" href="<?php echo esc_url( $spt_url ); ?>"><?php echo esc_html( $spt_nom ); ?></a></li>
					<?php endforeach; ?>
				</ul>
			</nav>
		</div>

		<div class="pied__bas">
			<span>© Les Spartiates d’Amiens <?php echo esc_html( wp_date( 'Y' ) ); ?></span>
			<ul>
				<li><a class="tlink" href="<?php echo esc_url( spt_url( 'mentions-legales' ) ); ?>">Mentions légales</a></li>
				<li><a class="tlink" href="https://www.fffa.org/actualites/">Fédération Française de Football Américain</a></li>
			</ul>
		</div>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
