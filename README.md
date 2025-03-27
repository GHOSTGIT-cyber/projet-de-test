# Projet Emargement Symfony

Ce projet est une application d'émargement développée avec Symfony. Elle comprend l'authentification, la gestion des utilisateurs (admin, formateurs, apprenants), la réinitialisation de mot de passe, et bien plus encore.

## ✅ Fonctionnalités actuelles

- Authentification complète (connexion, déconnexion)
- Gestion des rôles utilisateurs (ROLE_ADMIN, ROLE_FORMATEUR, ROLE_APPRENANT)
- Système de réinitialisation de mot de passe par email
- Redirection automatique selon les rôles après connexion
- Gestion des erreurs (pages 403/404 customisées)

---

## 🔧 Prérequis

- PHP 8.x
- Composer
- Symfony CLI
- MariaDB ou MySQL
- Un serveur SMTP (Gmail dans ce projet)

---

## 🛠️ Installation

```bash
git clone https://github.com/Ostronger/emargement-symfony.git
cd emargement-symfony
composer install
cp .env .env.local
```

Configurer dans `.env.local` :

```dotenv
DATABASE_URL="mysql://root:root@127.0.0.1:3306/emargement_symfony"
MAILER_DSN="smtp://monprojetppefinal@gmail.com:mon-mot-de-passe@default"
APP_URL="http://127.0.0.1:8000"
```

Créer la base de données :

```bash
php bin/console doctrine:database:create
php bin/console make:migration
php bin/console doctrine:migrations:migrate
```

---

## 🚀 Lancer le serveur

```bash
symfony server:start
```

## 🔀 Gestion des branches Git

### 🟢 Branches principales :

- `main` : Branche **production**. Stable. Elle contient uniquement les fonctionnalités prêtes à être déployées en production.
- `develop` : Branche **intégration**. Elle regroupe toutes les fonctionnalités terminées mais pas encore prêtes pour la production.

### 🌿 Branches de fonctionnalités :

- `feature/<nom-de-la-feature>` : Branche pour **chaque nouvelle fonctionnalité** (exemple : `feature/authentication`, `feature/reset-password`).

### ✅ Workflow recommandé :

1. Créer une branche `feature/<nom>` à partir de `develop` :

```bash
git checkout develop
git pull
git checkout -b feature/ma-nouvelle-fonction
```

2. Travailler dessus et **committer souvent** :

```bash
git add .
git commit -m "Description claire de ce que tu as fait"
```

3. Quand la fonctionnalité est **testée et fonctionnelle**, merger dans `develop` :

```bash
git checkout develop
git pull
git merge feature/ma-nouvelle-fonction
git push origin develop
```

4. En phase de préparation pour la **production**, merger `develop` dans `main` :

```bash
git checkout main
git pull
git merge develop
git push origin main
```

---

## ✅ Tests à effectuer avant tout merge sur `develop` et `main`

### 🔹 Tests de fonctionnalité :
- Connexion utilisateur (tous les rôles)
- Réinitialisation de mot de passe (envoi et utilisation du token)
- Redirections selon les rôles
- Pages d'accès refusé (403) et page non trouvée (404)

### 🔹 Tests de sécurité :
- Protection CSRF sur les formulaires sensibles (connexion, reset password)
- Hashage correct des mots de passe
- Vérification des rôles sur les routes sensibles

### 🔹 Tests UI/UX :
- Responsive design
- Accessibilité minimale (labels, contrastes)

---

## 📨 Envoi de mail

On utilise le **Symfony Mailer** avec Gmail. Vérifie toujours que :

- Tu as un mot de passe d'application Gmail
- Tu as renseigné correctement `MAILER_DSN` dans `.env.local`

Exemple de `MAILER_DSN` :

```
smtp://ton.email@gmail.com:mot-de-passe-app@smtp.gmail.com:587
```

---

## 🗂️ Organisation du projet

```
├── src/
│   ├── Controller/
│   ├── Entity/
│   ├── Repository/
│   ├── Security/
│   └── Service/
├── templates/
├── public/
├── var/
└── migrations/
```

---

## ✅ Prochaines étapes

- Implémentation des dashboards pour les rôles (Admin, Formateur, Apprenant)
- Intégration des logs plus poussés
- Tests automatisés (unitaires et fonctionnels)

---

## 👨‍💻 Auteurs

Projet réalisé par Aristote Lukamba Kasa, Abidina  et Selim (PPE - BTS SIO SLAM)

---

## 📜 Licence

Ce projet est en open source (licence à définir).

