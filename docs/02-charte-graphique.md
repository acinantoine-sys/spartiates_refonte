# 2. Charte graphique

## Couleurs

| Rôle | Valeur |
|---|---|
| **Bleu navy** (couleur officielle du club) | `#060640` |
| **Jaune** (couleur officielle du club) | `#fcc409` |
| Jaune au survol des boutons | `#ffd84d` |
| Navy des sections secondaires (calendrier Football) | `#0a0a4d` |
| Navy des fonds de photo | `#0e0e6a` |
| Fond clair des sections | `#f3f4f9` |
| Texte secondaire | `#2c2c5e` |
| Libellés et sur-titres | `#3b3b6b` |
| Filets et séparateurs | `#d9daea` |
| Bordures des cases | `#c9cadf` |
| Fond des emplacements à compléter | `#e6e7f2` |
| Blanc | `#ffffff` |

Texte blanc sur navy et texte navy sur jaune : contrastes conformes. Ne pas mettre de texte jaune sur fond blanc.

## Typographies (Google Fonts)

- **Barlow Condensed** : titres en **italique 800, en capitales** (classe `.disp` des maquettes) ; libellés, menus, boutons et sur-titres en **700, capitales, espacement de 0,06 à 0,22 em** (classe `.cond`).
- **Barlow** : texte courant en 400 à 700. Taille du corps de 16 à 20 px, interligne de 1,55 à 1,65.
- Grandes tailles fluides avec `clamp()`, par exemple les titres de page `clamp(56px, 9vw, 128px)` et les titres de section `clamp(44px, 6vw, 76px)`.

## Mise en page

- Conteneur `max-width: 1320px`, marges latérales de 32 px (16 px sur mobile).
- Sections aérées : 88 à 104 px de marge verticale.
- Mise en page en flex ou en grille, avec retour à la ligne : tout passe en une colonne sur téléphone.

## Signatures visuelles

- **Frise grecque** (méandre) jaune en séparateur de 18 px de haut, sous les hero et en haut du pied de page. Motif SVG répété, tracé `M0 15H6V3H24V15H12V9H18` dans une tuile de 24 × 18, trait de 2,5 px.
- **Cadres de photo en parallélogramme** : `clip-path: polygon(16% 0, 100% 0, 84% 100%, 0 100%)` (ou son miroir), avec un **bloc jaune décalé** de 22 px derrière la photo.
- **Mot surligné** : un mot clé du titre sur fond jaune (`padding: 0 10px`) ou en jaune sur fond navy.
- **Badge rond** du logo en surimpression sur la photo du hero de l'accueil.
- Pas d'angles arrondis, pas de dégradés.

## Composants

- **Bouton jaune** `.btn-y` : fond `#fcc409`, texte navy, 52 px de haut, Barlow Condensed 700 en capitales, 20 px.
- **Bouton contour blanc** `.btn-o` (sur fond navy) et **bouton contour navy** `.btn-n` / `.btn-n2` (sur fond clair).
- **Liens de menu** `.navlink` : 44 px de haut minimum, capitales ; la page active est en jaune et soulignée de 3 px.
- **Étiquettes** : « Domicile » sur fond navy, « Déplacement » en contour navy, types d'événement non-match sur fond jaune.
- **Cases partenaires** : 128 px de haut, logo contenu (`object-fit: contain`) à 92 px de hauteur maximum.

## Logo

- `assets/logo/logo-spartiates.svg` : version vectorielle fournie par le club, à privilégier.
- `assets/logo/logo-spartiates.png` : version utilisée dans les maquettes.

## Photos

Elles sont dans `assets/images/`, déjà recadrées et allégées pour le web (environ 1000 px de large, JPEG qualité 84). Les textes alternatifs à utiliser sont dans les attributs `alt` des maquettes.
