# Symfony Admin - Gestion des utilisateurs

Symfony Admin est un projet basé sur le framework Symfony qui vise à fournir une interface d'administration pour la gestion des utilisateurs. Il offre des fonctionnalités avancées pour créer, modifier et supprimer des utilisateurs, ainsi que gérer leurs rôles et leurs permissions.

## Fonctionnalités clés :

- Authentification et autorisation : Symfony Admin intègre un système d'authentification sécurisé pour permettre l'accès à l'interface d'administration uniquement aux utilisateurs autorisés. Les rôles et les permissions sont utilisés pour contrôler les actions disponibles pour chaque utilisateur.

- Gestion des utilisateurs : L'application offre des fonctionnalités complètes pour gérer les utilisateurs. Les administrateurs peuvent créer de nouveaux utilisateurs, modifier leurs informations, réinitialiser les mots de passe et les supprimer si nécessaire.

- Profils d'utilisateur : Symfony Admin permet de définir des profils d'utilisateur avec des attributs personnalisés. Les administrateurs peuvent attribuer des profils à chaque utilisateur pour mieux organiser les données et les autorisations associées.

- Fonctionnalités de recherche et de filtrage : Une recherche avancée est disponible pour trouver rapidement des utilisateurs en fonction de critères spécifiques. Des filtres peuvent être appliqués pour affiner les résultats et faciliter la navigation dans une grande quantité d'utilisateurs.

- Journal d'activité : Symfony Admin enregistre les actions effectuées par les utilisateurs, telles que la création ou la modification de comptes. Ce journal d'activité permet de suivre les modifications effectuées et de détecter d'éventuelles actions suspectes.

## Prérequis :

- PHP 8
- Composer
- Symfony 6
- MySQL ou un autre système de gestion de base de données compatible

## Installation :

1. Clonez le dépôt du projet : `git clone https://github.com/votre-repo/symfony-admin.git`
2. Accédez au répertoire du projet : `cd symfony-admin`
3. Installez les dépendances : `composer install`
4. Configurez les paramètres de base de données dans le fichier `.env`
5. Créez la base de données : `php bin/console doctrine:database:create`
6. Effectuez les migrations : `php bin/console doctrine:migrations:migrate`
7. Lancez le serveur de développement : `symfony serve`

Pour accéder à l'interface d'administration, ouvrez votre navigateur et rendez-vous sur `http://localhost:8000/admin`.

N'oubliez pas de personnaliser le projet selon vos besoins spécifiques en adaptant les vues, les entités et les contrôleurs.
# Symfony Admin - User Management

Symfony Admin is a project based on the Symfony framework that aims to provide an administration interface for user management. It offers advanced features for creating, editing, and deleting users, as well as managing their roles and permissions.

## Key Features:

- Authentication and Authorization: Symfony Admin integrates a secure authentication system to allow access to the administration interface only for authorized users. Roles and permissions are used to control the available actions for each user.

- User Management: The application provides comprehensive functionality for managing users. Administrators can create new users, modify their information, reset passwords, and delete users if necessary.

- User Profiles: Symfony Admin allows defining user profiles with custom attributes. Administrators can assign profiles to each user to better organize data and associated permissions.

- Search and Filtering Functionality: Advanced search is available to quickly find users based on specific criteria. Filters can be applied to refine the results and facilitate navigation in a large number of users.

- Activity Log: Symfony Admin records user actions, such as account creation or modification. This activity log allows tracking the changes made and detecting any suspicious actions.

## Requirements:

- PHP 8
- Composer
- Symfony 6
- MySQL or another compatible database management system

## Installation:

1. Clone the project repository: `git clone https://github.com/your-repo/symfony-admin.git`
2. Navigate to the project directory: `cd symfony-admin`
3. Install the dependencies: `composer install`
4. Configure the database parameters in the `.env` file
5. Create the database: `php bin/console doctrine:database:create`
6. Run the migrations: `php bin/console doctrine:migrations:migrate`
7. Start the development server: `symfony serve`

To access the administration interface, open your browser and go to `http://localhost:8000/admin`.

Don't forget to customize the project according to your specific needs by adapting the views, entities, and controllers.

