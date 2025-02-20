# Ymmersions S2 - Symfony

## Description du Projet

Ce projet consiste en la mise en place d'un tracker d'habitudes gamifié inspiré du site Habitica en utilisant le framework Symfony. Il permet aux utilisateurs de suivre et compléter des habitudes quotidiennes ou hebdomadaires, en solo ou en groupe, tout en gagnant des points.

## Objectifs Pédagogiques

Approfondir les connaissances en PHP et Symfony.

Appliquer les bonnes pratiques de programmation avec Symfony.

Travailler en équipe sur un projet collaboratif.

Concevoir et exploiter un modèle de base de données relationnel.

Fonctionnalités Principales

Gestion des utilisateurs

Création de compte avec pseudo, email, mot de passe sécurisé (hashé).

Connexion/déconnexion.

Photo de profil téléchargeable (max 1 Mo).

## Gestion des groupes

Un utilisateur peut créer un groupe, quitter un groupe ou inviter d'autres utilisateurs.

Chaque utilisateur ne peut appartenir qu'à un seul groupe.

Acceptation/refus d'invitations.

## Gestion des habitudes

Un utilisateur peut ajouter une habitude par jour.

Une habitude peut être quotidienne ou hebdomadaire.

Chaque habitude a une difficulté et une couleur d'affichage.

Seul le créateur du groupe peut ajouter une habitude pour l'équipe.

## Système de score

Les tâches accomplies augmentent le score selon la difficulté.

Les tâches non accomplies réduisent le score du groupe.

Si le score devient négatif, le groupe est dissout et les utilisateurs doivent recréer leurs habitudes.

## Installation et Lancement

### Prérequis

PHP 8+
Compose
Symfony CLI
Base de données (SQLite / MySQL)

### Installation

Cloner le projet :

git clone https://github.com/star0062/Symfony.git
cd Symfony

Installer les dépendances :
composer install

Configurer l'environnement :

Copier le fichier .env.example en .env :

cp .env.example .env

Modifier DATABASE_URL dans .env selon votre base de données.

Créer la base de données et exécuter les migrations :

php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate

Lancer le serveur Symfony avec PHP:
php -S 127.0.0.1:8000 -t public


## Modèle SQLite

User : id, team_id, email, mot_de_passe, photo_profil, level, hp, date_created, last_update

Team : id, user_add_id, team_name, date_create, date_update, point

Score : id, user_id, team_id, points, date

Sqlite_sequence : name, seq

Team_task : task_id, team_id, user_id, date_create, title, description, difficult, point, color, periodicity, target