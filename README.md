# Projet Oflix

## Supports de cours associés

- [Doctrine Associations](https://symfony.com/doc/current/doctrine/associations.html)
- [Lien vers la fiche récap Doctrine Associations](https://kourou.oclock.io/ressources/fiche-recap/symfo-s2-j2-associations-avec-doctrine/)

## Challenge 

D'après le MCD suivant :

```
CHARACTER : firstname, lastname, gender, bio, age
HAS, 0N TVSHOW, 11 SEASON
SEASON : season number, published at

PLAY, 0N TVSHOW, 1N CHARACTER: creditOrder
TVSHOW : title, synopsis, image, nbLikes, published at
CONTAINS, 0N SEASON , 11 EPISODE

CATEGORY : name
LINKED, 0N TVSHOW, 0N CATEGORY
EPISODE : episode number, title
```

![MCD Oflix](mcd_mld_oflix.png)

### Démarrage 

- On part d'un nouveau Projet en Website skeleton : `composer create-project symfony/website-skeleton oflix`
- Ensuite on configure le fichier `.env.local` (Vous pouvez nommer la DB `oflix`)
- Puis création de ta DB : `php bin/console doctrine:database:create`

### Création des entités

- Créez toutes les entités du MCD à l'aide de la commande `php bin/console make:entity`

#### TvShow

- `title`
- `synopsis`
- `image` (url de l'image)
- `nbLikes`
- `publishedAt`
- `createdAt`
- `updatedAt`

#### Season

- `seasonNumber`
- `publishedAt`
- `createdAt`
- `updatedAt`

#### Episode

- `episodeNumber`
- `title`
- `publishedAt`
- `createdAt`
- `updatedAt`

etc...

Puis créez une migration et appliquez la : 
```bash
php bin/console make:migration
php bin/console do:mi:mi
```

### Relations OneToMany / ManyToOne

Créez les relations entre : 

- `TvShow` et `Season` : Une série peut avoir plusieurs saisons.
- `Season` et `Episode` : Une saison peut avoir plusieurs épisodes.


Puis créez une migration et appliquez la : 
```bash
php bin/console make:migration
php bin/console do:mi:mi
```

### Relations ManyToMany

Créez les relations entre `TvShow`, `Character` et `Category`

- `TvShow` et `Character` : Une série peut avoir plusieurs personnages, et 1 personnage peut joueur dans plusieurs séries.
- `TvShow` et `Category` :  Une série peut avoir plusieurs catégories, et 1 catégorie peut être associée à plusieurs séries.

Puis créez une migration et appliquez la : 
```bash
php bin/console make:migration
php bin/console do:mi:mi
```

### Lecture et affichage

Si vous voulez démarrer avec quelques données, créez-en vous-même depuis Adminer.

- Afficher la liste des 3 dernières séries depuis la page d'accueil (regarder du côté de `findBy`). URL = `/`.
- Afficher la liste des séries (TvShow) depuis la page des séries. URL = `/tvshow/`.
- Afficher les détails d'une série. URL = `/tvshow/{id}`.
  - Pour chaque série, affichez les saison`S`, les personnage`S` et les catégorie`S` associés... Ca sent la boucle `for` on dirait :wink:

## Bonus intégration

Dans le dossier [`docs`](docs) se trouve l'intégration du projet `oFlix` :tada:.
On y trouve
- Une page d'accueil qui servira de point d'entrée : `/`
- Une page `Séries` qui affichera les dernières séries publiées : `/tvshow/`
- Une page affichant les détails d'une série selon son ID : `/tvshow/{id}`
- Une page `Ma liste` qui affichera vos séries préférées : `/tvshow/favorite`
- Une page de `Login` pour accéder aux contenus réservées aux personnes connectées (A ne surtout pas coder pour le moment ^^)

Votre mission : l'inclure pour apporter un peu de couleur au projet actuel.

P.S. : on inclurera d'autres pages au fur et à mesure
