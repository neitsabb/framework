# NeitsabKit

[Développement en cours]

Ce projet est un petit framework MVC qui fonctionne avec des "modules" facilement interchangeable

## Installation

1. Cloner le repository :

   ```bash
   git clone https://github.com/BastienVdp/framework.git
   ```

2. Installer les dépendances via Composer :

   ```bash
   composer install
   ```

## Configuration

1. Copier le fichier `.env.example` et le renommer en `.env`.
2. Modifier les variables d'environnement dans le fichier `.env` selon vos besoins.

## Créer un module

1. Créer un dossier dans le répertoire `modules`.
2. Dans ce dossier, créer plusieurs sous-dossiers nécessaires : controllers, models, services 
3. Vous pouvez maintenant créer votre logique dans vos controllers et leur ajouter des routes si nécessaire.

## Structure du Projet

- `app/`: Contient les classes principales au framework.
- `config/`: Contient les fichiers de configuration.
- `modules/`: Contient les différents modules (fonctionnalités) de l'application.
- `public/`: Répertoire racine accessible publiquement.
- `themes/`: Contient les thèmes.

## Architecture du Projet

```
|—— app
|    |—— core
|        |—— Application.php
|        |—— Config.php
|        |—— Container.php
|        |—— Controller.php
|        |—— Exception.php
|        |—— Modules.php
|        |—— Request.php
|        |—— Response.php
|        |—— Router.php
|        |—— Template.php
|        |—— Theme.php
|    |—— exceptions
|        |—— container
|            |—— ContainerException.php
|            |—— NotFoundException.php
|    |—— helpers
|        |—— utils.php
|    |—— libs
|—— config
|    |—— app.php
|    |—— database.php
|    |—— modules.php
|    |—— themes.php
|—— modules
|    |—— ExampleTest
|        |—— Controllers
|            |—— TestController.php
|        |—— Models
|        |—— Services
|            |—— TestRepository.php
|        |—— Views
|—— themes
|    |—— default
|        |—— assets
|        |—— index.php
|        |—— layouts
|            |—— main.php
|        |—— pages
|            |—— home.php
|        |—— partials
|        |—— theme.json
|—— public
|    |—— .htaccess
|    |—— index.php
```

## Contributions

Les contributions sont les bienvenues ! Si vous souhaitez contribuer à ce projet, veuillez ouvrir une issue pour discuter des modifications que vous souhaitez apporter.

## Licence

Ce projet est sous licence [MIT](LICENSE).
