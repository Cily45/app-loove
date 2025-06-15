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

4. Configuration des environnements
Backend :
Copier le fichier .env.dist en .env
puis remplir les informations nécessaires (connexion DB, clés API, etc.).

Frontend :
Dans client/src/environments/, copier environment.dist.ts en environment.ts
et renseigner les bonnes valeurs (URL API, clés Pusher, PayPal, etc.).

5. Lancer le serveur PHP
```
php -S localhost:8000 -t public
```

---

## Auteur

Cecile Fischer alias Cily
En tant qu'etudiante chez Coda-Orléans

[@CecileFischer](https://www.linkedin.com/in/fischercecile/)
