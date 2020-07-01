## Films de science-fiction

### Installation 
```
cd /wamp64/www
git clone https://github.com/gsylvestre/eni-sfmovies.git 
cd eni-sfmovies
composer install
```

Pour créer la base de donnée, vous devez d'abord configurer correcter le fichier .env ou .env.local, puis exécuter :
```
php bin/console doctrine:database:create 
php bin/console doctrine:schema:update --force
```

Pour charger des films dans la base de données, se rendre dans un navigateur sur : 
http://localhost/eni-sfmovies/public/tmdb  
Pour charger encore plus de films, modifier la variable `$startAt` dans le TmdbController.php

#### Recherche en AJAX 
(assurez-vous de bien avoir des films dans la bdd) :  
http://localhost/eni-bucket-list/public/