# 1. Pages et contenus

Le site compte **7 pages** et **4 éléments communs**. Les textes exacts sont dans les maquettes (`design/maquettes/`) ; ce document décrit la structure et le rôle de chaque section.

## Arborescence

| Page | Maquette | Capture |
|---|---|---|
| Accueil | `Main.dc.html` | `01-accueil.jpg` |
| Football américain | `Football.dc.html` | `02-football-americain.jpg` |
| Flag | `Flag.dc.html` | `03-flag.jpg` |
| Cheerleading | `Cheerleading.dc.html` | `04-cheerleading.jpg` |
| Calendrier | `PageCalendrier.dc.html` | `05-calendrier.jpg` |
| Le club | `Club.dc.html` | `06-le-club.jpg` |
| Contact | `Contact.dc.html` | `07-contact.jpg` |

Il n'y a **pas de page « Nous rejoindre »** : l'adhésion se fait directement sur HelloAsso.

## Éléments communs à toutes les pages

- **En-tête** (fond navy, filet jaune en bas) : logo + « Les Spartiates d'Amiens » à gauche ; menu au centre (Football américain, Flag, Cheerleading, Calendrier, Le club, Contact ; la page courante est en jaune et soulignée, avec `aria-current="page"`) ; bouton jaune « Nous rejoindre » **à droite, sur la même ligne** (lien HelloAsso). Sur mobile : menu repliable.
- **Bandeau partenaires « Nos Spartenaires »** : partenaires publics puis privés, logos à taille identique, lien « Devenir partenaire → » vers Contact.
- **Pied de page** (fond navy, frise grecque jaune en haut) : logo, devise « Force · Passion · Volonté », adresse et contacts du bureau, horaires du bureau regroupés (source Horaires), liens vers les disciplines + « Nous rejoindre », réseaux sociaux (Instagram @spartiatesfoot, Facebook, YouTube, TikTok), mentions légales, lien FFFA.
- **Bouton d'adhésion** : toujours le même lien HelloAsso, avec des libellés selon le contexte.

## Accueil

1. **Hero** : sur-titre « Football américain · Flag · Cheerleading », titre « Force. Passion. Volonté. », texte d'accroche (depuis 1987, 10 titres nationaux, formation des jeunes), boutons « Nous rejoindre » et « Prochaines rencontres ». Photo de la mascotte dans un cadre à bord jaune, avec le badge rond du logo en bas à gauche.
2. **Prochaines rencontres** : trois cartes (Football américain, Flag, Cheerleading). Chacune montre la prochaine date en grand, puis les deux suivantes, avec un lien « Tout le calendrier → ». Elles sont alimentées automatiquement par le calendrier, et les dates passées disparaissent. Lien « Calendrier complet → ».
3. **Rejoignez la famille Spartiates** : trois étapes (choisir sa discipline, venir à l'entraînement, s'inscrire), bouton « Contacter le bureau », puis trois cartes sport qui mènent chacune à sa page.
4. **Un club historique** : chiffres clés (1987, 10 titres nationaux, 6 finales nationales, 3 disciplines) et lien vers Le club.
5. **Le club en direct** : six dernières publications Instagram de @spartiatesfoot (à brancher sur un flux Instagram) et bouton « Suivre ».
6. Bandeau partenaires, pied de page.

## Football américain

1. **Hero** : titre, texte (deux équipes seniors D1 et D2, équipes jeunes, formation depuis 1987), boutons « Voir le calendrier » et « Les entraînements », photo du joueur n°4 en cadre parallélogramme.
2. **Calendrier 2027** : une liste par catégorie (Seniors D1, Seniors D2…) générée depuis le calendrier. Les dates passées sont grisées. Bouton « Voir tout le calendrier du club ».
3. **Venez supporter les Spartiates** : accueil des supporters au stade, avec une photo.
4. **Ils vivent le jeu** : deux photos d'action en cadres inclinés en miroir.
5. **Un palmarès qui compte** : 4 cartes (D1 2004 · 2010 · 2012 ; D2 1997 · 2018 · 2026 ; U19 1999 · 2010 · 2011 ; U17 champion territorial Île-de-France 2025).
6. **Les entraînements** : horaires des équipes de football, issus de la source Horaires.

## Flag

Page pensée pour **les parents** (mixte, dès 8 ans, sans contact).

1. **Hero** avec bouton « Inscrire mon enfant ».
2. **Ce que le flag lui apporte** : agilité et motricité, tactique et physique, sans contact, filles et garçons.
3. **Le flag entre aux Jeux olympiques** (Los Angeles 2028).
4. **Un accueil chaleureux pour toute la famille**.
5. **Inscrire votre enfant en 3 étapes** (modalités de la séance d'essai à confirmer).
6. **Infos pratiques** : horaires Flag jeunes et Flag seniors (source Horaires), équipement (tenue de sport, crampons recommandés), licence et tarifs sur HelloAsso.

## Cheerleading

1. **Hero** « Danse, acrobaties, esprit d'équipe », badge « 4 catégories ».
2. **Bien plus que des pompons** : les cinq éléments d'une routine (stunts, pyramides, tumbling, sauts, chorégraphie).
3. **Ce que le cheerleading apporte** : quatre bienfaits.
4. **Quatre catégories** : minimes, cadets, juniors, seniors (âges à préciser).
5. **Les entraînements** : horaires (source Horaires, dont les lieux hors stade), encadré « Pour débuter ».
6. **Nos résultats** : palmarès par saison (classement surligné en jaune pour les podiums) et carte « Prochaine compétition » issue du calendrier.

## Calendrier

1. **Titre** « Le calendrier ».
2. **Filtres** : discipline (Toutes, Football américain, Flag, Cheerleading) ; catégorie (affichée une fois une discipline choisie) ; type d'événement (Tous, Matchs, Tournois, Compétitions, Détections). Compteur « N dates » annoncé aux lecteurs d'écran (`aria-live`).
3. **Liste groupée par mois** : bloc date (jour + jour de semaine), discipline · catégorie, titre (« Spartiates – Adversaire » pour un match, nom de l'événement sinon), heure · lieu, étiquette de type (jaune pour tournoi, compétition et détection), « Domicile » ou « Déplacement » pour les matchs. Les dates passées sont grisées. Si aucune date ne correspond, un message s'affiche.
4. Bandeau « Envie de les rejoindre ? ».

## Le club

1. **Hero** « Le club historique ».
2. **Chronologie** depuis 1987 (fondateurs Marc Gambetti et Xavier Thuilot, Casque d'Or 1997, titres, Europe, 2023 nouvelle présidence…). Données : `data/historique.json`.
3. **Des titres, dans chaque discipline** : carte seniors (D1/D2) et cartes Football jeunes, Flag, Cheerleading. Données : `data/palmares.json`.
4. **Le cœur du club : ses bénévoles** : mosaïque photo, chiffres (environ 40 bénévoles, environ 300 licenciés, jours de match à préciser), bouton « Devenir bénévole » (lien HelloAsso).

## Contact

1. **Nous joindre** : 03 22 52 99 24 (lien `tel:`), secretariat@lesspartiates.com (lien `mailto:`), réseaux sociaux.
2. **Le bureau** : Stade du Grand Marais, 37 Rue du Grand Marais, 80080 Amiens ; tableau des horaires d'ouverture ; lien itinéraire.
3. **Le stade** : même adresse ; horaires d'ouverture **calculés** ; liste des entraînements ; accès et stationnement (à compléter).
4. **Nous trouver** : carte interactive (à intégrer) et bouton « Itinéraire ».
5. Bandeau « Envie de rejoindre l'aventure ? ».
