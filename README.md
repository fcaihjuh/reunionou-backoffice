
## LP1 - Atelier 2 : Reunionou

**Membres du groupe :**
- Alice Cai
- Florence Cai
- Thomas Dautecourt 
- Alexis Debard

**Lien vers le tableau de bord (Trello)** : https://trello.com/b/D76h5imU/reunionou

**Lien vers la documentation de l'API** : https://docs.google.com/document/d/1hik810ywbcwW9FDRsxDtMYHvaw2xQzEj9QpOE16XSdQ/edit?usp=sharing

## Installation générale du projet

**Prérequis** :

- Git
- Docker (docker-compose)
- NodeJS (npm)


**Installation** :

Clonage du projet :

    git clone https://github.com/fcaihjuh/reunionou-backoffice.git

Lancement des containers Docker :

    cd reunionou-backoffice/reunionou-backoffice
    docker-compose up -d

Import de la base de données MySQL :

- Se rendre sur http://localhost:62643
- Identifiants : root:root123
- Importer le fichier SQL : ./reunionou/backend1/sql/com.sql
