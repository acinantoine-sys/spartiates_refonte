# Consignes pour le développement du site des Spartiates d'Amiens

Tu développes le nouveau site du club **Les Spartiates d'Amiens** (football américain, flag, cheerleading ; association fondée en 1987). Le design a été validé par le comité directeur : il faut le reproduire fidèlement, pas le réinventer.

## Avant de coder

- Lis `docs/` en entier, dans l'ordre des numéros.
- Les maquettes de `design/maquettes/*.dc.html` font foi pour les textes, l'ordre des sections et la mise en page. Les captures de `design/captures/` servent de repère visuel rapide (attention : elles utilisent une police de remplacement, la vraie police est Barlow).
- Le choix entre WordPress et site statique est décrit dans `docs/04-choix-technique.md`. S'il n'est pas encore tranché dans ce fichier, demande-le à l'utilisateur avant d'installer quoi que ce soit.

## Règles non négociables

1. **Sources uniques.** Calendrier, horaires, lien d'adhésion HelloAsso, partenaires et palmarès doivent chacun être modifiables à **un seul endroit**, qui met à jour tout le site. Les données de départ sont dans `data/*.json`. Ne jamais recopier une date, un horaire, un lien HelloAsso ou un logo partenaire en dur dans un gabarit.
2. **Tous les boutons d'adhésion** (« Nous rejoindre », « Inscrire mon enfant », « Rejoindre l'équipe », « Devenir bénévole », « Voir les tarifs et adhérer ») pointent vers `adhesion_url`.
3. **Horaires d'ouverture du stade** : ils ne se saisissent pas, ils se calculent (bureau + entraînements au stade, fusionnés par jour). Règle détaillée dans `docs/03-sources-uniques.md`.
4. **Bandeau partenaires** en bas de **toutes** les pages, logos à taille identique.
5. **Accessibilité** : contraste suffisant (texte normal 4,5:1), cibles tactiles d'au moins 44 px, vrais `<a>` et `<button>`, textes alternatifs des photos repris des maquettes, navigation au clavier, site utilisable sur téléphone.
6. **Ne rien inventer.** Les éléments entre crochets dans les maquettes (`[Heure]`, `[Lieu à définir]`, `[Âges de chaque catégorie à préciser]`…) restent des emplacements à compléter : garde-les visibles et liste-les, ne les remplace pas par du contenu imaginé. Liste à jour : `docs/05-a-completer.md`.

## Vocabulaire du club

- « Les Spartiates d'Amiens » en toutes lettres ; « Spartiates » seul dans le calendrier et les cartes de match.
- « Nos Spartenaires » pour le bandeau partenaires.
- Le site est entièrement en français.
