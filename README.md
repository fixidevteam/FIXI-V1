🛠️ README.md – Plateforme Fixi
📌 Présentation Générale
Fixi est une plateforme complète de gestion et de réservation de services automobiles, organisée autour de trois interfaces principales selon le type d’utilisateur :

Module	Rôle utilisateur	Description
FixiCore	Administrateur	Interface pour gérer les utilisateurs, garages, opérations, calendriers...
FixiPro	Garage / Mécanicien	Tableau de bord pour gérer les rendez-vous, clients, véhicules et promotions
FixiPlus	Client Particulier	Espace personnel pour gérer ses véhicules, rendez-vous et documents

🧩 Détail des Modules
🔴 FixiCore (anciennement FixiAdmin)
Interface réservée aux administrateurs de la plateforme.

Fonctionnalités principales :

Gestion des utilisateurs (clients, garages, mécaniciens)

Validation des documents et promotions

Gestion des marques, modèles, domaines et opérations

Gestion des calendriers globaux

Suivi global des réservations et statistiques

🔵 FixiPro
Tableau de bord destiné aux garages et mécaniciens partenaires.

Fonctionnalités principales :

Confirmation et historique des RDV

Suivi des opérations effectuées

Gestion des clients et véhicules

Synchronisation avec les données FIXI.MA

Gestion des promotions et disponibilités

🟢 FixiPlus
Espace utilisateur pour les clients particuliers.

Fonctionnalités principales :

Gestion de ses véhicules et documents personnels (carte grise, vignette, permis…)

Prise de rendez-vous en ligne avec les garages partenaires

Suivi des opérations réalisées

Accès aux promotions disponibles

Notifications en temps réel

🌐 Intégration Web
Le site Fixi.ma est la vitrine publique (WordPress).

Il est connecté via API avec FixiPro et FixiPlus pour permettre :

Réservation directe d’un garage depuis le site

Synchronisation des données entre les plateformes

📎 Technologies Utilisées
Frontend : Vue.js / Blade / Bootstrap (selon module)


Backend : Laravel (API RESTful, Auth, Jobs, Events, WebSockets…)

Base de données : MySQL

Notifications : Pusher ou Laravel WebSockets

Infrastructure : Nginx / Apache, Certbot SSL



📬 Contact
Pour toute suggestion ou contribution :

📧 contact@fixi.ma
💻 Développé par l’équipe BLC & FIXI
