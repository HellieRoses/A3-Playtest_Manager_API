# Playtest Manager

Cet API est un gestionnaire d'événement de Session de Test de Jeux Vidéos. Un Utilisateur peut s'inscrire et se connecter afin de pouvoir rejoindre une session de test gratuitement. Les Studios de jeux vidéos pourront elles aussi s'inscrire et se connecter afin de pouvoir poster des sessions.

## Lien du dépot Git

https://gitlabinfo.iutmontp.univ-montp2.fr/groupe-projet-web-s5/playtest-manager

## Utilisatton de l'API

Pour utiliser pleinenement l'API, il faut d'abord s'authentifier. Pour cela, il faudra s'inscrire en tant que Joueur ou Studio puis se connecter. Une fois connecté, l'utilisateur pourra modifier ses informations ansi que supprimer son compte.
<p>
Un joueur connecté peut s'inscrire à une session de jeu ainsi que se désinscrire de celle-ci.
</p>
<p>
Un studio connecté peut créer un Jeu Vidéo, le modifier ainsi que le supprimer. Il peut également créer une Session de Jeu, le modifier ainsi que le supprimer.
</p>


### Les routes

- Authentification
    - **/api/auth**
        - POST : Connecter un Utilisateur (Studio ou Joueur)
- Studio (Company)
    - **/api/companies**
        - GET : Récupérer les informations de tous les Studios
        - POST : Créer un Studio
    - **/api/companies/{id}**
        - GET : Récupérer les informations d'un Studio
        - PATCH : Mettre à jour un Studio
        - DELETE : Supprimer un Studio
- Joueur (Player)
    - **/api/players**
        - POST : Créer un Joueur
        - GET : Récupérer les informations de tous les Joueurs
    - **/api/players/{id}**
        - GET : Récupérer les informations d'un Joueur
        - PATCH : Mettre à jour un Joueur
        - DELETE : Supprimer un Joueur
- Session de jeu (PlayTest)
    - **/api/playtests**
        - GET : Récupérer toutes les Sessions
        - POST : Créer une Session
    - **/api/playtests/{id}**
        - GET : Récupérer les informations d'une Session
        - PATCH : Mettre à jour une Session
        - DELETE : Supprimer une Session
    - **/api/companies/{idCompany}/playtests**
        - GET : Récupérer les Sessions d'un Studio
- Jeu Vidéo (VideoGame)
    - **/api/video_games**
        - GET : Récupérer tout les jeux vidéos
        - POST : Créer un Jeu Vidéo
    - **/api/video_games/{id}**
        - GET : Récupérer les informations d'un Jeu Vidéo
        - PATCH : Mettre à jour un Jeu Vidéo
        - DELETE : Supprimer un Jeu Vidéo
- Participation
    - **/api/playtests/participate**
        - POST : Inscrire le joueur connecté à une Session de jeu
## Investissement du groupe

- Clément HAMEL : CRUD VideoGames 
- Maëlys BOISSEZON : CRUD Utilisateurs (Company et Player), connexion utilisateur, Inscription d'un utilisateur à un événement
- Romain TOUZÉ : CRUD Evenement (Playtest), ROLE_ADMIN, normalizationsContext, 

## Indications supplémentaires

