# 6. Développement WordPress

Mise en œuvre du choix technique (`docs/04-choix-technique.md`). Le site se compose de **deux éléments**, sans extension tierce :

| Dossier | Rôle |
|---|---|
| `wordpress/wp-content/plugins/spartiates-club/` | Extension **« Spartiates – Données du club »** : les sources uniques (calendrier, horaires, lien d'adhésion, partenaires, palmarès, histoire), leurs écrans d'administration et l'import des données de départ. |
| `wordpress/wp-content/themes/spartiates/` | Thème **« Spartiates d'Amiens »** : la mise en forme des 7 pages d'après les maquettes. Il ne contient aucune donnée : il lit tout dans l'extension. |

Séparer données et thème évite de perdre le calendrier ou les partenaires si le thème est un jour remplacé.

## Installation

1. Installer WordPress (6.4 ou plus récent, PHP 7.4 ou plus) chez l'hébergeur, en français.
2. Copier les deux dossiers ci-dessus dans `wp-content/plugins/` et `wp-content/themes/`.
3. Activer l'extension **Spartiates – Données du club**. À l'activation, elle importe les données de départ (copie de `data/`) et :
   - crée les pages Accueil, Football américain, Flag, Cheerleading, Calendrier, Le club, Contact et Mentions légales ;
   - règle la page d'accueil, les permaliens (`/nom-de-la-page/`) et le fuseau horaire Europe/Paris (nécessaire pour griser les dates passées au bon moment).
4. Activer le thème **Spartiates d'Amiens**.
5. Créer un compte **Éditeur** pour chaque bénévole : ce rôle suffit pour tout mettre à jour.

L'import peut être relancé depuis **Spartiates > Données de départ**, ou avec WP-CLI :

```bash
wp spartiates importer
```

Les adresses des pages sont `/football-americain/`, `/flag/`, `/cheerleading/`, `/calendrier/`, `/le-club/`, `/contact/` et `/mentions-legales/`. Les redirections depuis les adresses de l'ancien site restent à prévoir au moment de la bascule.

## Où modifier quoi (pour les bénévoles)

Tout est dans le menu **Spartiates** de l'administration. Une modification met à jour toutes les pages concernées.

| Information | Écran | Pages mises à jour |
|---|---|---|
| Dates (matchs, tournois, compétitions, détections) | Spartiates > Calendrier | Accueil (cartes), Football américain, Cheerleading (prochaine compétition), Calendrier |
| Horaires du bureau et des entraînements | Spartiates > Horaires | Pied de page, Contact (bureau, **ouverture du stade calculée**, entraînements), Football américain, Flag, Cheerleading |
| Lien HelloAsso de la saison | Spartiates > Adhésion et réglages | Tous les boutons d'adhésion du site |
| Partenaires et logos | Spartiates > Partenaires | Bandeau « Nos Spartenaires » de toutes les pages |
| Titres et résultats | Spartiates > Palmarès | Football américain, Cheerleading, Flag (badge), Le club, et le nombre de **titres nationaux, calculé** (accueil, Le club) |
| Chronologie | Spartiates > Histoire du club | Le club |
| Chiffres clés, coordonnées, réseaux sociaux, catégories du calendrier | Spartiates > Adhésion et réglages | Accueil, Le club, Contact, pied de page, filtres du calendrier |

Les 7 pages principales sont mises en forme par le thème. Leur éditeur WordPress est désactivé et remplacé par un message qui renvoie vers le menu Spartiates. Seule la page **Mentions légales** se rédige dans l'éditeur.

## Règles de calcul

Elles sont écrites en PHP sans dépendance à WordPress, et testées :

- `includes/class-spa-horaires.php` : tableau du bureau, regroupement des jours du pied de page, **ouverture du stade** (bureau + entraînements sans lieu, fusion des plages qui se chevauchent ou se touchent), lignes d'entraînement par équipe ou par discipline.
- `includes/class-spa-calendrier.php` : dates passées, tri, libellés (« 20-21 », « Sam. – Dim. », « Févr. », « Février 2027 »), cartes de l'accueil, listes par catégorie, regroupement par mois, catégories des filtres, années du titre « Calendrier 2027 ».
- `includes/class-spa-palmares.php` : cartes du palmarès, résultats du cheerleading par saison (podium = 1er, 2e ou 3e, surligné en jaune), cartes de la page Le club, **nombre de titres nationaux** (résultats cochés « Titre de champion de France »).

### Le palmarès : une seule liste

`data/palmares.json` décrivait le palmarès en trois blocs, dont certains se répétaient (les résultats du cheerleading figuraient deux fois). L'extension les remplace par **une seule liste de résultats**. Chaque résultat indique sa discipline, sa catégorie, son année, son titre, son éventuel classement, s'il s'agit d'un titre national et s'il doit apparaître sur la page Le club. Toutes les vues en sont déduites. Le fichier de départ est `donnees-initiales/resultats.json`. Les tests vérifient qu'il redonne exactement le contenu de `data/palmares.json` et des maquettes.

## Tests et vérifications

```bash
php tests/logique.php
```

Ce script vérifie les règles de calcul (48 vérifications), avec les valeurs attendues tirées des maquettes et de `data/`.

```bash
php outils/apercu/generer.php 2027-03-01
```

```bash
php -S localhost:8080 -t outils/apercu/site
```

Ces deux commandes produisent puis servent un **aperçu statique sans WordPress**. L'outil simule les quelques fonctions WordPress utilisées par le thème et alimente les gabarits avec les données de départ. La date passée en argument est celle du jour simulé, pour voir les dates passées grisées. Il sert à contrôler la mise en page et à repérer les erreurs des gabarits. Il **ne remplace pas** un essai dans un vrai WordPress.

Contrôles faits sur cet aperçu :

- les 7 pages, à 1440 px et 390 px de large, sans débordement horizontal ;
- audit axe-core (WCAG 2.2 AA) sans erreur sur toutes les pages, dates passées comprises ;
- cibles tactiles d'au moins 44 px ;
- menu mobile (ouverture, fermeture avec Échap, retour du focus) ;
- filtres du calendrier (combinaisons, remise à « Toutes » de la catégorie, message quand aucune date ne correspond) ;
- pages utilisables sans JavaScript (liste complète des dates, menu déplié).

**Pas encore testé dans un vrai WordPress.** Le développement a été fait dans un environnement qui ne pouvait pas télécharger WordPress. Avant la mise en ligne, il faut installer le site sur un WordPress de test et vérifier les écrans d'administration : saisie d'une date, des horaires, d'un partenaire avec son logo, droits du rôle Éditeur, import.

## Écarts avec les maquettes, à valider

1. **Dates passées.** La charte demande une opacité de 0,5. Elle fait tomber le contraste du texte à 2,7:1, sous le minimum de 4,5:1 exigé par les consignes d'accessibilité. Les dates passées restent grisées, mais avec des gris lisibles (au moins 4,5:1).
2. **Titres calculés.** « 10 titres nationaux », « 6 titres de champion de France » (seniors), « Champion de France Senior en 2009 » (Flag) et « Calendrier 2027 » sont calculés à partir du palmarès et du calendrier, au lieu d'être écrits en dur.
3. **Polices hébergées sur le site.** Barlow et Barlow Condensed sont servies par le site lui-même, et non par Google Fonts, pour ne pas transmettre l'adresse IP des visiteurs à Google (RGPD).
4. **Logo allégé.** Le logo PNG d'origine (684 Ko) est décliné en 128, 240 et 400 px (7 à 29 Ko).
5. **Lien « Mentions légales ».** Il pointe vers la page Mentions légales du nouveau site, créée avec un emplacement `[Mentions légales et politique de confidentialité à rédiger]`.
6. **Textes ajoutés.** Les descriptions pour les moteurs de recherche (`functions.php`) et le texte de la page 404 ont été rédigés à partir des maquettes : à relire par le club.

## Reste à faire

- Essai complet dans un WordPress de test, puis choix de l'hébergeur et mise en ligne.
- Flux Instagram : choisir l'outil, puis remplacer les 6 emplacements de l'accueil (`front-page.php`).
- Carte interactive de la page Contact (emplacement prévu).
- Redirections depuis les adresses de l'ancien site.
- Contenus entre crochets : voir `docs/05-a-completer.md`. Ils sont visibles sur le site tant qu'ils ne sont pas fournis.
