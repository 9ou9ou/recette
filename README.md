## Installation
```
# We clone the code from the remote repository
git clone https://github.com/9ou9ou/recette.git

# We move in the folder recette
cd recette

# We installe the dependencies !
composer install

# We create the database:  Verify your connexion string (username, password ...) 
symfony console doctrine:database:create

# We execute the migrations into the DB
symfony console doctrine:migrations:migrate

# We start the server
symfony console server:run
```
