## Installation
```
# On clone le dépot !
git clone https://github.com/9ou9ou/recette.git

# On se déplace dans le dossier
cd recette

# On installe les dépendances !
composer install

# On créé la base de données:  Verifier votre chaine de connexion.
php bin/console doctrine:database:create

# On exécute les migrations
php bin/console doctrine:migrations:migrate

# On lance le serveur
php bin/console server:run
```