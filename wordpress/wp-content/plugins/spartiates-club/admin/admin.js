/* Administration des Spartiates. Les formulaires fonctionnent aussi sans ce script. */
(function () {
	'use strict';

	// Événement : « Adversaire » pour un match, « Nom de l'événement » sinon.
	var type = document.querySelector('[data-champ="type"] select');
	if (type) {
		var maj = function () {
			var match = type.value === 'match';
			document.querySelectorAll('.spa-si-match').forEach(function (el) { el.classList.toggle('spa-cache', !match); });
			document.querySelectorAll('.spa-si-autre').forEach(function (el) { el.classList.toggle('spa-cache', match); });
		};
		type.addEventListener('change', maj);
		maj();
	}

	// Horaires : vider une ligne, ajouter un créneau.
	var corps = document.querySelector('[data-spa-repeteur]');
	if (!corps) {
		return;
	}
	corps.addEventListener('click', function (ev) {
		var bouton = ev.target.closest('[data-spa-vider]');
		if (!bouton) {
			return;
		}
		bouton.closest('tr').querySelectorAll('input').forEach(function (input) { input.value = ''; });
	});
	var ajouter = document.querySelector('[data-spa-ajouter]');
	ajouter.hidden = false;
	ajouter.addEventListener('click', function () {
		var lignes = corps.querySelectorAll('tr');
		var modele = lignes[lignes.length - 1];
		var copie = modele.cloneNode(true);
		var index = lignes.length;
		copie.querySelectorAll('[name], [id], [for]').forEach(function (el) {
			['name', 'id', 'for'].forEach(function (attr) {
				var v = el.getAttribute(attr);
				if (v) {
					el.setAttribute(attr, v.replace(/\[\d+\]/, '[' + index + ']').replace(/spa-e-\d+-/, 'spa-e-' + index + '-'));
				}
			});
			if (el.tagName === 'INPUT') {
				el.value = '';
			}
		});
		corps.appendChild(copie);
		copie.querySelector('select').focus();
	});
})();
