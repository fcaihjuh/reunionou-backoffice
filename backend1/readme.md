# API Installation

Après avoir démarré les containers Docker et importé la DB, vous pouvez ajouter dans le fichier "hosts" le nom de domaine : 
"127.0.0.1 docketu.iutnc.univ-lorraine.fr"

## Accéder au container de l'API

Prendre connaissance de l'ID du container :

    docker ps -a


Connexion au container à l'aide de son ID (reunionou_back1_1) :

    docker exec -it <CONTAINER_ID> bash

Installation des librairies nécessaires au fonctionnement de l'API :

    composer install

L'API est désormais disponible à cette adresse : http://docketu.iutnc.univ-lorraine.fr:62640/