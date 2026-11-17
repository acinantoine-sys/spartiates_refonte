# 3. Sources uniques de données

Exigence du club : **une seule modification doit mettre à jour tout le site.** Chaque type d'information a donc une seule source, à laquelle toutes les pages se réfèrent. Les données de départ sont dans `data/`. Dans la maquette, chaque source est un composant (`design/maquettes/Calendrier.dc.html`, `Horaires.dc.html`, `LienAdhesion.dc.html`, `Partenaires.dc.html`) dont le code contient les règles d'affichage décrites ici.

Ces sources seront mises à jour par des **bénévoles non techniques**. L'interface de modification doit donc être simple : formulaire d'administration sous WordPress, ou fichier de données documenté et éditeur en ligne pour un site statique.

## Calendrier (`data/calendrier.json`)

Un événement par entrée :

| Champ | Valeurs |
|---|---|
| `disc` | `foot`, `flag` ou `cheer` |
| `cat` | catégorie : `Seniors D1`, `Seniors D2`, `U19`, `U13/U15`, `Jeunes`, `Minimes`… |
| `type` | `match`, `tournoi`, `competition` ou `detection` |
| `start` | `AAAA-MM-JJ` |
| `end` | `AAAA-MM-JJ`, seulement pour un événement sur plusieurs jours |
| `opp` | adversaire (matchs) |
| `name` | nom de l'événement (tournois, compétitions, détections) |
| `city` | ville ou lieu (`[Lieu à définir]` en attendant) |
| `time` | facultatif, `HHhMM` ; à défaut, la maquette affiche `[Heure]` |
| `home` | `true` = à domicile (le lieu affiché devient alors le Stade du Grand Marais), `false` = déplacement |

`categories` donne, par discipline, les catégories proposées dans les filtres ; une catégorie utilisée dans un événement mais absente de cette liste est ajoutée automatiquement.

**Où il est utilisé :**

| Endroit | Ce qui s'affiche |
|---|---|
| Accueil, carte par discipline | la prochaine date en grand, puis les deux suivantes |
| Page Football | une liste par catégorie |
| Page Cheerleading | la carte « Prochaine compétition » |
| Page Calendrier | tous les événements, filtrables, groupés par mois |

**Règles :**

- Un événement est **passé** quand sa date de fin (ou de début) est avant aujourd'hui.
- Passé, il disparaît des cartes de l'accueil et apparaît grisé (opacité 0,5) dans les listes.
- Tri par date de début, puis par discipline, puis par catégorie.
- Titre d'un match : « Spartiates – Adversaire ».
- Libellés des dates : « 20-21 » pour deux jours, jours de semaine abrégés (« Sam. – Dim. »), mois abrégés dans les cartes (« Févr. ») et complets dans les titres de mois (« Février 2027 »).
- Les filtres de la page Calendrier se combinent. Choisir une discipline remet la catégorie à « Toutes ».

## Horaires (`data/horaires.json`)

- `bureau` : pour chaque jour, une ou plusieurs plages d'ouverture. Un jour absent = fermé.
- `entrainements` : pour chaque créneau, le jour, l'heure de début (`from`), l'heure de fin (`to`), l'équipe (`label`) et éventuellement le lieu (`place`). **Sans `place`, l'entraînement a lieu au stade** ; avec `place`, c'est un autre lieu (par exemple la Halle des sports d'Étouvie).

**Affichages dérivés :**

| Affichage | Contenu |
|---|---|
| Tableau du bureau (Contact) | les 7 jours, « Fermé » si aucune plage |
| Pied de page | jours regroupés quand les plages sont identiques, par exemple « Lundi, jeudi, vendredi 9h–17h · Mardi 9h–13h » |
| **Ouverture du stade (Contact)** | **calculée, jamais saisie** : pour chaque jour, on réunit les plages du bureau et les entraînements sans `place`, puis on fusionne les plages qui se chevauchent ou se touchent. On affiche seulement les horaires, sans préciser bureau ou entraînement. |
| Liste des entraînements | tous les créneaux, avec le lieu quand il n'est pas au stade |
| Page d'une équipe | les créneaux dont `label` correspond exactement, par exemple « Flag jeunes » |
| Page d'une discipline | les créneaux dont `label` commence par la discipline, par exemple « Football », regroupés par équipe |

Les heures s'écrivent `9h`, `19h30`.

## Lien d'adhésion (`data/adhesion.json`)

`adhesion_url` : la page HelloAsso de la saison en cours, à changer **une fois par saison**. Tous les boutons d'adhésion du site l'utilisent : « Nous rejoindre », « Inscrire mon enfant », « Rejoindre l'équipe », « Devenir bénévole » et « Voir les tarifs et adhérer sur HelloAsso ».

## Partenaires (`data/partenaires.json`)

Pour chaque partenaire : `name` (sert aussi de texte alternatif), `type` (`public` ou `prive`) et `logo` (chemin du fichier ; vide tant que le logo n'est pas fourni). L'ordre de la liste est l'ordre d'affichage. Le bandeau affiche les partenaires publics puis les partenaires privés. Toutes les cases ont la même taille, le logo y est centré et contenu, à 92 px de hauteur maximum. Une entrée sans logo affiche une case en pointillés avec le nom. À prévoir : un lien facultatif vers le site du partenaire.

## Palmarès (`data/palmares.json`) et historique (`data/historique.json`)

- Seniors football (titres D1 et D2), cartes par discipline de la page Le club, résultats du cheerleading par saison.
- Pour le cheerleading, la saison la plus récente vient en premier. Champs : compétition, catégorie, classement, `podium` (classement surligné en jaune) et `note` (étiquette, par exemple « Qualification aux championnats d'Europe »).
- La chronologie de la page Le club : année, titre, texte.
- Le chiffre « 10 titres nationaux » de l'accueil et du club correspond aux titres de champion de France, toutes catégories confondues. Il faut le mettre à jour quand un titre est gagné, et il gagnerait à être calculé à partir du palmarès.
