# Playtest Manager

Cet API est un gestionnaire d'événement de Session de Test de Jeux Vidéos. Un Utilisateur peut s'inscrire et se connecter afin de pouvoir rejoindre une session de test gratuitement. Les Studios de jeux vidéos pourront elles aussi s'inscrire et se connecter afin de pouvoir poster des sessions.

## Lien du dépot Git

https://gitlabinfo.iutmontp.univ-montp2.fr/groupe-projet-web-s5/playtest-manager

## Utilisatton de l'API

Pour utiliser pleinenement l'API, il faut d'abord s'authentifier. Pour cela, il faudra s'inscrire en tant que Joueur ou Studio puis se connecter. Une fois connecté, l'utilisateur pourra modifier ses informations ansi que supprimer son compte.
<p>
Un joueur connecté, 
</p>
<p>
Un studio connecté, 
</p>

### Les routes

- Authentification
    - **/api/auth**
        - POST : Connecter un Utilisateur (Studio ou Joueur)
- Studio (Company)
    - **/api/companies**
        - POST : Créer un Studio
    - **/api/companies/{id}**
        - GET : Récupérer les informations d'un Studio
        - PATCH : Mettre à jour un Studio
        - DELETE : Supprimer un Studio
- Joueur (Player)
    - **/api/players**
        - POST : Créer un Joueur
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

## Investissement du groupe

- Clément HAMEL : 
- Maëlys BOISSEZON : 
- Romain TOUZÉ : 

## Indications supplémentaires

