# Nos routes

## FrontOffice

| url          | controller | fonction | methode | remarque                      |
| ------------ | ---------- | -------- | ------- | ----------------------------- |
| /            | Main       | homepage | GET     | Affiche la page d'accueil     |
| /tvshow      | TvShow     | list     | GET     | Affiche la liste des séries   |
| /tvshow/{id} | TvShow     | read     | GET     | Affiche le détail de la série |

## BackOffice

| url                            | controller          | fonction | methode   | remarque                                        |
| ------------------------------ | ------------------- | -------- | --------- | ----------------------------------------------- |
| /backoffice/category/browse    | BackOffice/Category | browse   | GET       | Affiche la liste                                |
| /backoffice/category/read/{id} | BackOffice/Category | read     | GET       | Affiche une catégorie                           |
| /backoffice/category/edit/{id} | BackOffice/Category | edit     | GET, POST | Affiche le formulaire d'édition d'une catégorie |
| /backoffice/category/add       | BackOffice/Category | add      | GET, POST | Affiche le formulaire de création               |
| /backoffice/category/delete    | BackOffice/Category | delete   | GET       | Supprime la catégorie demandée                  |

## Api

| url                       | controller                   | fonction | methode  | remarque                         |
| ------------------------- | ---------------------------- | -------- | -------- | -------------------------------- |
| `/api/v1/tvshows`         | `Api\V1\TvshowController`    | `browse` | `GET`    | `Affiche la liste des tvshow`    |
| `/api/v1/tvshows/{id}`    | `Api\V1\TvshowController`    | `read`   | `GET`    | `Affiche le détail de tvshow`    |
| `/api/v1/tvshows/{id}`    | `Api\V1\TvshowController`    | `edit`   | `PATCH`  | `Modifie un TVShow`              |
| `/api/v1/tvshows/`        | `Api\V1\TvshowController`    | `add`    | `POST`   | `Insère un TVShow`               |
| `/api/v1/tvshows/{id}`    | `Api\V1\TvshowController`    | `delete` | `DELETE` | `Supprime un TVShow`             |
| ----------------------    | -------------------------    | -------- | -------- | -----------------------------    |
| `/api/v1/categories`      | `Api\V1\CategoryController`  | `browse` | `GET`    | `Affiche la liste des category`  |
| `/api/v1/categories/{id}` | `Api\V1\CategoryController`  | `read`   | `GET`    | `Affiche le détail de category`  |
| `/api/v1/categories/{id}` | `Api\V1\CategoryController`  | `edit`   | `PATCH`  | `Modifie un Category`            |
| `/api/v1/categories/`     | `Api\V1\CategoryController`  | `add`    | `POST`   | `Insère un Category`             |
| `/api/v1/categories/{id}` | `Api\V1\CategoryController`  | `delete` | `DELETE` | `Supprime un Category`           |
| ----------------------    | -------------------------    | -------- | -------- | -----------------------------    |
| `/api/v1/characters`      | `Api\V1\CharacterController` | `browse` | `GET`    | `Affiche la liste des character` |
| `/api/v1/characters/{id}` | `Api\V1\CharacterController` | `read`   | `GET`    | `Affiche le détail de character` |
| `/api/v1/characters/{id}` | `Api\V1\CharacterController` | `edit`   | `PATCH`  | `Modifie un Character`           |
| `/api/v1/characters/`     | `Api\V1\CharacterController` | `add`    | `POST`   | `Insère un Character`            |
| `/api/v1/characters/{id}` | `Api\V1\CharacterController` | `delete` | `DELETE` | `Supprime un Character`          |
