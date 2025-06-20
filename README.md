# 💕 Pawfect Match 🐾

## Description
**Pawfect Match** est une application de rencontre intuitive conçue pour faciliter la mise en relation entre propriétaires de chiens. Elle propose une interface fluide, une gestion sécurisée des profils et une expérience utilisateur optimisée.

### Fonctionnalités principales
* 🔍 Matchmaking intelligent basé sur des critères personnalisés (genre, âge, distance, loisirs, caractéristiques canines)
* 👤 Création et gestion de profils avec photos et préférences
* 📨 Système de messagerie pour communiquer après un match (ou avant si l’utilisateur est abonné)
* 🚩 Modération & signalement (harcèlement, spam, faux profil, comportement inapproprié)
* 💳 Système d’abonnement pour accéder à des fonctionnalités premium
* 🌐 Responsive design (mobile / desktop)

---

### 🛠️ Backend (PHP)
* PHP 8
* API RESTful
* Architecture MVC
* Gestion de sessions via JWT
* Base de données MySQL
* PHPMailer
* Pusher Channels
* Pusher Beams

#### Schémas de base de données

![Sans titre](https://github.com/user-attachments/assets/9eaf772e-4651-4163-ab67-de6d84ea02f1)


---

### 💻 Frontend (Angular)
* Angular 15+ (vérifie la version exacte)
* Tailwind CSS (ou autre framework CSS utilisé)
* Angular Material
* Angular Router
* Pusher Channels
* Pusher Beams
* Intégration PayPal

---

### 📦 Prérequis
* Node.js & npm
* Angular CLI
* PHP 8+
* Composer
* Serveur local (MAMP / XAMPP ou équivalent)
* MySQL
* Compte Pusher
* Compte PayPal Developer

---

### 🚀 Installation

#### 1. Cloner le projet
```
git clone https://github.com/votre-user/pawfect-match.git
cd pawfect-match
```

2. Lancer le frontend Angular
```
cd client
npm install
ng serve
```

3. Lancer l’API Backend
```
cd api
composer install
```

4. Importation de la base de donnée
   ici
  
6. Configuration des environnements
Backend :
Copier le fichier .env.dist en .env
puis remplir les informations nécessaires (connexion DB, clés API, etc.).

Frontend :
Dans client/src/environments/, copier environment.dist.ts en environment.ts
et renseigner les bonnes valeurs (URL API, clés Pusher, PayPal, etc.).

6. Lancer le serveur PHP
```
php -S localhost:8000 -t public
```

# 📡 API Endpoints

## 🔐 Auth

| Méthode | Endpoint            | Description                 | Auth | Admin |
|---------|---------------------|-----------------------------|------|-------|
| POST    | `/login`            | Connexion utilisateur       | ❌   | ❌    | 
| POST    | `/login-admin`      | Connexion admin             | ❌   | ❌    |
| POST    | `/auth-pusher`      | Auth Pusher Channels        | ✅   | ❌    |
| GET     | `/beams-token`      | Token Beams (notifications) | ✅   | ❌    |

## 👤 Users

| Méthode | Endpoint                          | Description                          | Auth | Admin |
|---------|-----------------------------------|--------------------------------------|------|-------|
| GET     | `/users/{quantity}/{page}`        | Liste utilisateurs paginée           | ✅   | ✅    |
| GET     | `/profil/{id}`                    | Profil utilisateur                   | ✅   | ❌    |
| GET     | `/profils`                        | Tous les profils avec les filtre     | ✅   | ❌    |
| GET     | `/matchs`                         | Matchs de l'utilisateur              | ✅   | ❌    |
| GET     | `/profil-admin/{id}`              | Profil version admin                 | ✅   | ✅    |
| GET     | `/user-count`                     | Nombre d'utilisateurs                | ✅   | ✅    |
| GET     | `/email/{email}`                  | Vérification si mail est utilisé     | ❌   | ❌    |
| GET     | `/user-notifications`             | Renvoie les options de notifications | ✅   | ❌    |
| POST    | `/create-user`                    | Création utilisateur                 | ❌   | ❌    |
| POST    | `/update`                         | Mise à jour à jour utilisateur       | ✅   | ❌    |
| POST    | `/update-user`                    | Mise à jour profil utilisateur       | ✅   | ❌    |
| POST    | `/update-location`                | Mise à jour localisation             | ✅   | ❌    |
| POST    | `/update-photo`                   | Mise à jour photo                    | ✅   | ❌    |
| POST    | `/update-user-admin`              | Mise à jour admin                    | ✅   | ❌    |
| POST    | `/update-verify`                  | Vérification utilisateur/token       | ✅   | ❌    |
| POST    | `/update-password`                | Mise à jour mot de passe             | ✅   | ❌    |
| POST    | `/reset-password`                 | Réinitialisation mot de passe        | ❌   | ❌    |
| DELETE  | `/delete/{id}`                    | Suppression utilisateur              | ✅   | ❌    |

## 🧑‍🤝‍🧑 Gender

| Méthode | Endpoint                | Description                      | Auth | Admin |
|---------|-------------------------|----------------------------------|------|-------|
| GET     | `/genders`              | Liste genres                     | ❌   | ❌     |
| GET     | `/genders-user/{id}`    | Genres préférés de l'utilisateur | ✅   | ❌     |

## 🎯 Hobbies

| Méthode | Endpoint              | Description                  | Auth | Admin |
|---------|-----------------------|------------------------------|------|-------|
| GET     | `/hobbies`            | Liste hobbies                | ❌   | ❌     |
| GET     | `/user-hobbies/{id}`  | Hobbies d'un utilisateur     | ✅   | ❌     |

## 💬 Messages

| Méthode | Endpoint                    | Description                                     | Auth | Admin |
|---------|-----------------------------|-------------------------------------------------|------|-------|
| GET     | `/messages`                 | Tous les messages de l'utilisateurs avec profil | ✅   | ❌     |
| GET     | `/messages/{id0}/{id1}`     | Messages entre 2 utilisateurs                   | ✅   | ❌     |
| POST    | `/send-message`             | Envoyer un message                              | ✅   | ❌     |
| GET     | `/vue/{id}`                 | Marquer message comme vu                        | ✅   | ❌     |

## ❤️ Match

| Méthode | Endpoint      | Description                         | Auth | Admin |
|---------|---------------|-------------------------------------|------|-------|
| POST    | `/match`      | Créer un match entre 2 utilisateurs | ✅   | ❌     |

## 🚫 Report

| Méthode | Endpoint                             | Description                      | Auth | Admin |
|---------|--------------------------------------|----------------------------------|------|-------|
| GET     | `/report-reason`                     | Liste des raisons de signalement | ✅   | ❌     |
| PUT     | `/report`                            | Créer un signalement             | ✅   | ✅     |
| GET     | `/reports/{quantity}/{page}`         | Liste signalements paginés       | ✅   | ✅     |
| GET     | `/report/{id}`                       | Détail d'un signalement          | ✅   | ✅     |
| PUT     | `/report-update`                     | Mise à jour signalement          | ✅   | ✅     |
| GET     | `/report-count`                      | Compteur signalements            | ✅   | ✅     |

## 💳 Subscription & Prices

| Méthode | Endpoint               | Description                            | Auth | Admin |
|---------|------------------------|----------------------------------------|------|-------|
| GET     | `/is-subscribe`       | Vérifier si l'abonnement est actif      | ✅   | ❌     |
| GET     | `/subscription-info`  | Infos sur l'abonnement de l'utilisateur | ✅   | ❌     |
| POST    | `/subscription`       | Créer ou met a jours un abonnement      | ✅   | ❌     |
| GET     | `/prices`             | Liste prix                              | ✅   | ❌     |
| POST    | `/prices-update`      | Mise à jour des prix                    | ✅   | ✅     |

## 🐕 Dogs

| Méthode | Endpoint             | Description                    | Auth | Admin |
|---------|----------------------|--------------------------------|------|-------|
| GET     | `/dogs/{id}`         | Liste de chiens de l'utilisateur| ✅   | ❌    |
| POST    | `/add-dogs`          | Ajouter un chien                | ✅   | ❌    |
| GET     | `/dog-sizes`         | Listes tailles de chien         | ❌   | ❌    |
| GET     | `/dog-genders`       | Listes de genres de chien       | ❌   | ❌    |
| GET     | `/dog-temperaments`  | Liste de tempéraments de chien  | ❌   | ❌    |

## 📊 Statistiques

| Méthode | Endpoint         | Description          | Auth | Admin |
|---------|------------------|----------------------|------|-------|
| GET     | `/statistics`    | Données globales     | ✅   | ✅     |

## 🚷 Banned

| Méthode | Endpoint                  | Description            | Auth | Admin |
|---------|---------------------------|------------------------|------|-------|
| PUT     | `/banned-add`            | Bannir un utilisateur   | ✅   | ✅     |
| DELETE  | `/banned-delete/{id}`    | Débannir un utilisateur | ✅   | ✅     |

## 🧪 Filtres

| Méthode | Endpoint           | Description                               | Auth | Admin |
|---------|--------------------|-------------------------------------------|------|-------|
| GET     | `/get-filter`     | Récupérer les informations des filtres     | ✅   | ✅    |
| POST    | `/add-filter`     | Ajouter les informations des filtres       | ✅   | ✅    |
| GET     | `/reset-filter`   | Réinitialiser les informations des filtres | ✅   | ✅    |

## ✉️ Mail

| Méthode | Endpoint           | Description         | Auth | Admin |
|---------|--------------------|---------------------|------|-------|
| POST    | `/send-contact`   | Contact utilisateur  | ❌   | ❌     |
| POST    | `/send-warning`   | Avertissement        | ✅   | ✅     |

---

## Auteur

Cecile Fischer alias Cily
En tant qu'etudiante chez Coda-Orléans

[@CecileFischer](https://www.linkedin.com/in/fischercecile/)
