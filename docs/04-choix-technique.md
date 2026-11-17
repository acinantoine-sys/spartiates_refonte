# 4. Choix technique

**Décision : WordPress** (17 novembre 2026). Hébergement encore à choisir.

## Les deux options

### WordPress (recommandée)

- **Pour qui** : des bénévoles qui mettent à jour le site sans connaissances techniques.
- **Principe** : un thème sur mesure qui reprend la charte. Chaque source unique devient un écran d'administration :
  - un type de contenu « Événement » pour le calendrier ;
  - une page d'options pour les horaires et le lien HelloAsso ;
  - un type de contenu « Partenaire » avec son logo ;
  - des entrées de palmarès.
- **Avantages** : interface d'administration familière, gestion des comptes des bénévoles, ajout de photos sans aide.
- **Points d'attention** : un hébergement PHP/MySQL est nécessaire, ainsi que des mises à jour de sécurité régulières (cœur, extensions). Limiter le nombre d'extensions.

### Site statique

- **Pour qui** : un club avec au moins une personne à l'aise pour modifier un fichier ou utiliser un petit éditeur en ligne.
- **Principe** : générateur de site statique (par exemple Astro ou Eleventy) qui lit les fichiers `data/*.json`. Un éditeur en ligne (par exemple Decap CMS) peut s'ajouter pour les bénévoles. Hébergement sur un service statique gratuit ou très peu cher.
- **Avantages** : rapide, très sûr (pas de base de données), presque aucune maintenance.
- **Points d'attention** : chaque modification reconstruit le site (en général en moins d'une minute). Les fonctions dynamiques (flux Instagram) passent par des services tiers.

## Dans les deux cas

- Reprendre les données de `data/` comme contenu initial.
- Garder les mêmes adresses de pages si possible, ou prévoir des redirections depuis l'ancien site lesspartiates.com.
- Mesurer la performance (photos en `srcset`, chargement différé sous la ligne de flottaison) et l'accessibilité avant la mise en ligne.

## Décision

- [x] WordPress
- [ ] Site statique
- Hébergement : à choisir (PHP 7.4 ou plus, MySQL ou MariaDB, HTTPS)
- Décidé le : 17 novembre 2026

La mise en œuvre est décrite dans `docs/06-developpement-wordpress.md`.
