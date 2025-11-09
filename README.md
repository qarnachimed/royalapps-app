# Royal Apps - PHP Test Assignment
***Installation du Projet***
***Prérequis***

    PHP 8.3+

    Composer

    SQLite

1. Cloner le repository

        git@github.com:qarnachimed/royalapps-app.git
        cd royalapps-app

2. Installer les dépendances

       composer install

3. Démarrer l'application

       php artisan serve

4. Créer un auteur via CLI

       php artisan author:create "John" "Doe" --birthday=1990-01-01 --gender=male --place_of_birth="Paris"

5. Tester la connexion API

       php artisan api:test

6. Vider le cache

        php artisan config:clear
        php artisan cache:clear

7. Accéder à l'application

        http://localhost:8000

8. Identifiants de connexion

        Email: ahsoka.tano@royal-apps.io
        Mot de passe: Kryze4President