# Refonte du site des Spartiates d'Amiens

Dossier de transmission entre la phase de design (validée en comité directeur le 17 novembre 2026) et la phase de développement du nouveau site **lesspartiates.com**.

Le club : association de sports américains fondée à Amiens en 1987. Trois disciplines : **football américain**, **flag** et **cheerleading**.

## Contenu du dépôt

| Dossier | Contenu |
|---|---|
| `docs/` | Le cahier de transmission : pages et contenus, charte graphique, sources uniques de données, choix technique, contenus à compléter |
| `data/` | Les données du site extraites des maquettes, au format JSON : calendrier, horaires, lien d'adhésion, partenaires, palmarès, historique |
| `design/maquettes/` | Les maquettes validées (fichiers `.dc.html` issus de l'outil de design). Elles font foi pour les textes et la mise en page |
| `design/captures/` | Une capture pleine page de chaque page, pour référence visuelle rapide |
| `design/video-demo-codir.mp4` | La vidéo de démonstration présentée au comité directeur |
| `assets/` | Logo du club (SVG et PNG), photos optimisées pour le web, logos des partenaires |
| `CLAUDE.md` | Les consignes lues automatiquement par Claude au début de chaque session de développement |

## Par où commencer

1. Lire `docs/01-pages-et-contenus.md` puis `docs/03-sources-uniques.md`.
2. Trancher le choix technique décrit dans `docs/04-choix-technique.md`.
3. Développer page par page en suivant les maquettes et la charte (`docs/02-charte-graphique.md`).

## Note sur les captures

Les captures et la vidéo ont été produites hors ligne, sans la police Barlow : une police condensée de remplacement a été utilisée. Les vraies proportions des titres sont celles des maquettes, en Barlow et Barlow Condensed (Google Fonts).
