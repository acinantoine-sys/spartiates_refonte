/* Spartiates d'Amiens : menu mobile et filtres du calendrier. Le site reste utilisable sans ce script. */
(function () {
	'use strict';

	/* ---------- Menu mobile ---------- */
	var entete = document.querySelector('[data-entete]');
	var bouton = document.querySelector('[data-menu]');
	if (entete && bouton) {
		var basculer = function (ouvrir) {
			entete.classList.toggle('ouvert', ouvrir);
			bouton.setAttribute('aria-expanded', ouvrir ? 'true' : 'false');
		};
		bouton.addEventListener('click', function () {
			basculer(bouton.getAttribute('aria-expanded') !== 'true');
		});
		document.addEventListener('keydown', function (ev) {
			if (ev.key === 'Escape' && bouton.getAttribute('aria-expanded') === 'true') {
				basculer(false);
				bouton.focus();
			}
		});
	}

	/* ---------- Filtres du calendrier ---------- */
	var cal = document.querySelector('[data-calendrier]');
	if (!cal) {
		return;
	}
	var etat = { disc: 'all', cat: 'all', type: 'all' };
	var filtres = cal.querySelector('[data-filtres]');
	var compteur = cal.querySelector('[data-compteur]');
	var vide = cal.querySelector('[data-vide]');
	var mois = cal.querySelectorAll('[data-mois]');
	var lignes = cal.querySelectorAll('[data-date]');
	var groupesCat = cal.querySelectorAll('[data-categories]');
	var blocCat = cal.querySelector('[data-bloc-categories]');
	var nomsDisc = JSON.parse(cal.getAttribute('data-disciplines') || '{}');
	filtres.hidden = false;

	var maj = function () {
		var total = 0;
		var discAvecDates = false;
		lignes.forEach(function (li) {
			var ok = (etat.disc === 'all' || li.dataset.disc === etat.disc) &&
				(etat.cat === 'all' || li.dataset.cat === etat.cat) &&
				(etat.type === 'all' || li.dataset.type === etat.type);
			li.hidden = !ok;
			if (ok) { total++; }
			if (etat.disc === 'all' || li.dataset.disc === etat.disc) { discAvecDates = true; }
		});
		mois.forEach(function (m) {
			m.hidden = !m.querySelector('[data-date]:not([hidden])');
		});
		compteur.textContent = total + ' ' + (total > 1 ? 'dates' : 'date');
		vide.hidden = total > 0;
		vide.textContent = discAvecDates
			? 'Aucune date ne correspond à ces filtres.'
			: '[Calendrier ' + (nomsDisc[etat.disc] || '') + ' à venir : dates à communiquer]';

		blocCat.hidden = etat.disc === 'all';
		groupesCat.forEach(function (g) {
			g.hidden = g.dataset.categories !== etat.disc;
		});
		cal.querySelectorAll('[data-filtre]').forEach(function (b) {
			b.setAttribute('aria-pressed', etat[b.dataset.filtre] === b.dataset.valeur ? 'true' : 'false');
		});
	};

	cal.addEventListener('click', function (ev) {
		var b = ev.target.closest('[data-filtre]');
		if (!b) {
			return;
		}
		etat[b.dataset.filtre] = b.dataset.valeur;
		if (b.dataset.filtre === 'disc') {
			etat.cat = 'all'; // Choisir une discipline remet la catégorie à « Toutes ».
		}
		maj();
	});
	maj();
})();
