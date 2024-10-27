# Playtest Manager

Cet API est un gestionnaire d'événement de Session de Test de Jeux Vidéos. Un Utilisateur peut s'inscrire et se connecter afin de pouvoir rejoindre une session de test gratuitement. Les Studios de jeux vidéos pourront elles aussi s'inscrire et se connecter afin de pouvoir poster des sessions.

## Lien du dépot Git

https://gitlabinfo.iutmontp.univ-montp2.fr/groupe-projet-web-s5/playtest-manager

## Utilisation de l'API

Pour utiliser pleinenement l'API, il faut d'abord s'authentifier. Pour cela, il faudra s'inscrire en tant que Joueur ou Studio puis se connecter. Une fois connecté, l'utilisateur pourra modifier ses informations ansi que supprimer son compte.
<p>
Un joueur connecté peut s'inscrire à une session de jeu ainsi que se désinscrire de celle-ci. Il ne peut s'inscrire à une session seulement si elle n'est pas déjà pleine ou que ses horaires ne chevauchent pas avec les horaires d'une autre session auquel le joueur est inscrit
</p>
<p>
Un studio connecté peut créer un Jeu Vidéo, le modifier ainsi que le supprimer. Il peut également créer une Session de Jeu, le modifier ainsi que le supprimer.
</p>
<p>
Un utilisateur peut avoir des droits d'administrateur. Il peut ainsi supprimer d'autres comptes utilisateurs qui ne sont pas admins. Il peut également suprrimer des Jeux Vidéos ou bien des Sessions de jeu
</p>

### Les routes

- Authentification
    - **/api/auth**
        - POST : Connecter un Utilisateur (Studio ou Joueur)
    - **/api/token/refresh**
        - POST : Rafraichit le refresh token
    - **/api/token/invalidate**
        - POST : Invalide le refresh token
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
    - **/api/playtests/{idPlaytest}/players**
        - GET : Récupérer les joueurs inscrit à une Session de jeu
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
    - **/api/players/{idPlayer}/playtests**
        - GET : Récupérer les Sessions auquelles participe un joueur
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
    - **/api/playtests/participate/{id}**
        - DELETE : Supprime une participation

### Les commandes

- **makeAdmin [login]**
    - Rend l'utilisateur de login: login admin
- **removeAdmin [login])**
    - Enlève le rôle admin de l'utilisateur de login : login

L'argument login dans chacune des commandes n'est pas obligatoire car il est demandé après

## Investissement du groupe

- Clément HAMEL : CRUD VideoGames, Vérification des dates et du nombre max joueurs (quand inscription à un playtest)
- Maëlys BOISSEZON : CRUD Utilisateurs (Company et Player) avec héritage, Connexion des utilisateurs, Gestion du refresh Token, Inscription d'un utilisateur à un événement, Commandes Admin
- Romain TOUZÉ : CRUD Evenement (Playtest), Gestion du ROLE_ADMIN, normalizationsContext, Gestion de la securité via Voter

## Indications supplémentaires

