# BackOffice Installation

Après avoir démarré les containers Docker, importé la DB et lu le readme général suivez les instructions.

## Accéder au container du backoffice

Prendre connaissance de l'ID du container :

    docker ps -a

Connexion au container à l'aide de son ID (reunionou_back2_1) :

    docker exec -it <CONTAINER_ID> bash

Installation des librairies nécessaires au fonctionnement du backoffice :

    composer install

Le backoffice est désormais disponible à cette adresse : http://docketu.iutnc.univ-lorraine.fr:62641/

**Identifiants** :

Adresse email : admin@reunionou.com
Mot de passe : admin