# Système de Matching Étudiant-Tuteur

Application Laravel permettant de faire correspondre des étudiants avec des tuteurs en fonction de critères de compatibilité.

## L'approche choisie

Le système utilise une **architecture modulaire basée sur des règles** (Rule-Based Architecture) avec un algorithme de scoring pondéré. Cette approche offre plusieurs avantages :

- **Séparation des responsabilités** : Chaque critère de matching (matière, niveau, disponibilité) est géré par une classe dédiée (`SubjectMatchingRule`, `LevelMatchingRule`, `AvailabilityMatchingRule`)
- **Maintenabilité** : Les règles peuvent être modifiées indépendamment sans affecter le reste du système
- **Testabilité** : Chaque règle peut être testée de manière isolée
- **Extensibilité** : De nouvelles règles peuvent être ajoutées facilement sans modifier le code existant

L'architecture suit le pattern **Repository** pour l'accès aux données et utilise des **DTOs** (Data Transfer Objects) pour structurer les résultats, garantissant une séparation claire entre la logique métier et la couche de présentation.

Le processus de matching suit ces étapes :
1. Récupération de l'étudiant et de tous les tuteurs avec leurs relations
2. Calcul du score de compatibilité pour chaque paire étudiant-tuteur
3. Filtrage des matches avec un score > 0
4. Tri des résultats par score de compatibilité décroissant

## Les critères de scoring

Le système évalue trois critères principaux :

### 1. Correspondance des matières (Subject Matching)
- **Calcul** : `(nombre de matières communes / nombre total de matières de l'étudiant) × 100`
- **Exemple** : Si un étudiant a 3 matières et le tuteur enseigne 2 de ces matières, le score est de 66.67%
- **Score minimum** : 0% (aucune matière commune)
- **Score maximum** : 100% (toutes les matières de l'étudiant sont couvertes)

### 2. Correspondance du niveau (Level Matching)
- **Calcul** : Score binaire (100% si match, 0% sinon)
- **Logique** : Le niveau de l'étudiant doit correspondre à l'un des niveaux enseignés par le tuteur
- **Score** : 100% si le niveau correspond, 0% sinon

### 3. Correspondance des disponibilités (Availability Matching)
- **Calcul** : `(minutes de chevauchement / minutes totales de disponibilité de l'étudiant) × 100`
- **Détails** :
  - Compare les créneaux horaires jour par jour
  - Calcule le chevauchement temporel pour chaque jour correspondant
  - Additionne tous les chevauchements et les compare au total des disponibilités de l'étudiant
  - Déduplique les créneaux communs pour éviter les doublons
- **Score minimum** : 0% (aucun chevauchement)
- **Score maximum** : 100% (toutes les disponibilités de l'étudiant sont couvertes)

## Les pondérations

Le score de compatibilité final est calculé avec les pondérations suivantes :

```
Score final = (Score Matière × 40%) + (Score Niveau × 30%) + (Score Disponibilité × 30%)
```

### Justification des pondérations

- **Matière (40%)** : Critère le plus important car un tuteur doit pouvoir enseigner les matières demandées par l'étudiant
- **Niveau (30%)** : Important pour garantir que le tuteur est adapté au niveau académique de l'étudiant
- **Disponibilité (30%)** : Essentiel pour que les cours puissent effectivement avoir lieu, mais peut être négocié plus facilement que les matières ou le niveau

Ces pondérations peuvent être ajustées selon les besoins métier. Elles sont définies comme constantes dans `MatchmakingService` pour faciliter leur modification.

## Les limites de la solution

### Limitations techniques

1. **Performance** : Le système calcule les matches pour tous les tuteurs à chaque requête. Avec un grand nombre de tuteurs (>1000), cela peut devenir lent. Pas de mise en cache des résultats.

2. **Scalabilité** : 
   - Pas de pagination des résultats
   - Chargement en mémoire de tous les tuteurs et étudiants
   - Pas d'optimisation des requêtes N+1 pour les relations complexes

3. **Disponibilités** :
   - Ne prend pas en compte les fuseaux horaires
   - Ne gère pas les disponibilités récurrentes complexes (ex: "tous les lundis pairs")
   - Ne considère pas les créneaux déjà réservés par d'autres étudiants

4. **Scoring** :
   - Le score de niveau est binaire (0 ou 100), ce qui peut être trop strict
   - Pas de prise en compte de la qualité/expérience du tuteur
   - Pas de considération du prix ou de la localisation géographique

5. **Filtrage** :
   - Seuls les matches avec score > 0 sont retournés, mais il n'y a pas de seuil minimum configurable
   - Pas de filtrage par critères additionnels (ex: prix maximum, distance)

6. **Données** :
   - Pas de validation stricte des chevauchements de créneaux horaires
   - Pas de gestion des cas limites (ex: créneaux qui se chevauchent partiellement)

### Limitations fonctionnelles

1. **Pas de système de réservation** : Le matching ne réserve pas les créneaux, il les identifie seulement
2. **Pas de feedback** : Aucun système de notation ou de retour d'expérience pour améliorer les matches futurs
3. **Pas de préférences utilisateur** : Ne prend pas en compte les préférences personnelles de l'étudiant ou du tuteur

## Ce que j'améliorerais avec plus de temps

### Optimisations de performance

1. **Mise en cache** :
   - Implémenter un système de cache Redis pour les résultats de matching
   - Invalider le cache uniquement quand les données pertinentes changent
   - Cache par étudiant avec TTL approprié

2. **Optimisation des requêtes** :
   - Utiliser `with()` pour le chargement eager des relations
   - Implémenter la pagination pour les résultats
   - Ajouter des index sur les colonnes fréquemment utilisées (level_id, day_of_week)

3. **Traitement asynchrone** :
   - Utiliser des queues Laravel pour le calcul des matches en arrière-plan
   - Notifier l'utilisateur une fois le calcul terminé

### Améliorations du scoring

1. **Score de niveau plus nuancé** :
   - Permettre des scores partiels (ex: 50% si le tuteur enseigne un niveau proche)
   - Prendre en compte la hiérarchie des niveaux (ex: un tuteur de niveau "Master" peut enseigner à un étudiant de niveau "Licence")

2. **Critères additionnels** :
   - Ajouter un score basé sur l'expérience du tuteur
   - Prendre en compte les avis/ratings des tuteurs
   - Considérer la distance géographique si disponible
   - Intégrer le prix dans le calcul de compatibilité

3. **Pondérations dynamiques** :
   - Permettre à l'utilisateur de personnaliser les pondérations
   - A/B testing pour optimiser les pondérations selon les taux de conversion

### Fonctionnalités métier

1. **Système de réservation** :
   - Permettre de réserver des créneaux spécifiques
   - Gérer les conflits de réservation
   - Système de confirmation/annulation

2. **Machine Learning** :
   - Analyser les matches réussis pour améliorer l'algorithme
   - Prédire la satisfaction des étudiants basée sur l'historique
   - Recommandations personnalisées

3. **Interface utilisateur** :
   - Dashboard pour visualiser les matches
   - Filtres avancés (prix, distance, disponibilité minimale)
   - Comparaison côte à côte des tuteurs

4. **Notifications** :
   - Notifier les étudiants des nouveaux tuteurs correspondants
   - Alertes pour les créneaux disponibles

### Améliorations techniques

1. **Tests** :
   - Ajouter des tests unitaires pour chaque règle de matching
   - Tests d'intégration pour le service complet
   - Tests de performance avec de grandes quantités de données

2. **Documentation API** :
   - Intégrer Swagger/OpenAPI pour documenter les endpoints
   - Exemples de requêtes/réponses

3. **Monitoring** :
   - Ajouter des métriques de performance (temps de réponse, nombre de matches)
   - Logging structuré pour le debugging
   - Alertes en cas de performance dégradée

4. **Gestion des erreurs** :
   - Gestion plus robuste des cas limites
   - Messages d'erreur plus explicites
   - Retry logic pour les opérations critiques

### Architecture

1. **Microservices** :
   - Séparer le service de matching en microservice indépendant
   - API Gateway pour gérer les différentes versions

2. **Base de données** :
   - Optimiser le schéma pour les requêtes fréquentes
   - Considérer une base de données spécialisée pour les recherches géographiques si nécessaire
   - Implémenter des vues matérialisées pour les agrégations complexes

## Structure du projet

```
app/
├── DTOs/                    # Data Transfer Objects
├── Http/
│   ├── Controllers/        # Contrôleurs
│   └── Requests/          # Form Requests pour la validation
├── Models/                 # Modèles Eloquent
├── Repositories/           # Pattern Repository
│   ├── Contracts/         # Interfaces des repositories
│   └── ...                # Implémentations
├── Rules/
│   └── Matchmaking/       # Règles de matching
└── Services/
    └── Matchmaking/       # Services de matching
```

## Installation

```bash
composer install
npm install
php artisan migrate
php artisan db:seed
npm run build
```

## Utilisation

### API Endpoints

- `GET /api/matchmaking` - Retourne tous les matches pour tous les étudiants
- `GET /matchmaking/{student}` - Retourne les matches pour un étudiant spécifique (JSON ou HTML)

### Exemple de réponse

```json
{
  "student": {
    "id": 1,
    "full_name": "Jean Dupont",
    "level": "Licence",
    "subjects": ["Mathématiques", "Physique"]
  },
  "total_matches": 3,
  "matches": [
    {
      "tutor": {
        "id": 1,
        "full_name": "Marie Martin",
        "subjects": ["Mathématiques", "Physique", "Chimie"],
        "levels": ["Licence", "Master"]
      },
      "compatibility_score": 85,
      "matched_subjects": ["Mathématiques", "Physique"],
      "level_match": true,
      "common_availabilities": [
        {
          "day": "Monday",
          "start": "14:00",
          "end": "16:00"
        }
      ],
      "availability_score": 75
    }
  ]
}
```

## Technologies utilisées

- **Laravel 12** - Framework PHP
- **PHP 8.4** - Langage de programmation
- **SQLite** - Base de données

## Licence

MIT
