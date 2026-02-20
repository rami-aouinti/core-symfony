# Proposition d’architecture cible (Symfony)

## 1) Objectif
Passer d’une structure orientée *framework-first* (Controllers/Entity/Repository) à une architecture **modulaire, testable et évolutive** en restant sur un **monolithe modulaire**.

Objectifs concrets :
- isoler la logique métier du framework Symfony et d’Doctrine,
- réduire le couplage entre couches,
- améliorer la testabilité (tests unitaires métier sans kernel Symfony),
- permettre une évolution progressive vers CQRS/Event-driven si nécessaire.

---

## 2) Architecture recommandée

### Choix : **Hexagonale légère + Monolithe modulaire**

On conserve un seul déploiement, mais on structure par domaines métier (ex: `Platform`, `User`, `Catalog`).

Chaque module suit les couches :
- **Domain**: règles métier pures (entités métier, VOs, services métier, événements),
- **Application**: cas d’usage (commands/queries + handlers),
- **Infrastructure**: implémentations techniques (Doctrine, API externes, cache),
- **UI**: contrôleurs HTTP, forms, presenters/transformers.

---

## 3) Arborescence cible

```text
src/
  Shared/
    Domain/
    Application/
    Infrastructure/
    UI/

  Platform/
    Domain/
      Model/
      ValueObject/
      Event/
      Repository/           # interfaces (ports)
      Service/
    Application/
      Command/
      Query/
      DTO/
      Handler/
    Infrastructure/
      Persistence/Doctrine/
        Entity/
        Repository/
        Mapper/
      Symfony/
        EventSubscriber/
    UI/
      Http/
        Controller/
        Request/
        Response/
      Form/

  Kernel.php
```

---

## 4) Règles de dépendance (importantes)

- `UI -> Application -> Domain`
- `Infrastructure -> Domain` (implémente des interfaces Domain/Application)
- `Domain` ne dépend de **rien** de Symfony/Doctrine.
- Les contrôleurs n’accèdent jamais directement à Doctrine EntityManager.
- Les handlers applicatifs orchestrent les cas d’usage.

---

## 5) Exemple concret sur le module `Platform`

### État actuel (typique)
- Controller appelle Repository Doctrine,
- logique métier mélangée avec validation HTTP,
- entités Doctrine utilisées partout.

### Cible
- `CreatePlatformCommand` + `CreatePlatformHandler`,
- `PlatformRepository` (interface côté Domain),
- `DoctrinePlatformRepository` (implémentation Infrastructure),
- `Platform` métier (Domain) séparé de l’entité Doctrine si nécessaire,
- Controller = adaptation HTTP (input/output), sans logique métier.

---

## 6) Plan de migration pragmatique (sans big-bang)

### Phase 1 — Stabilisation (rapide)
1. Créer la structure modulaire `src/Platform/{Domain,Application,Infrastructure,UI}`.
2. Introduire un premier use case (`CreatePlatform`).
3. Déplacer la logique métier depuis le controller vers le handler.
4. Ajouter tests unitaires Domain/Application.

### Phase 2 — Consolidation
1. Introduire queries dédiées (`ListPlatformsQuery`).
2. Mettre un mapper UI <-> Application DTO.
3. Centraliser règles métier dans Domain services / Value Objects.
4. Réduire progressivement l’exposition directe des entités Doctrine.

### Phase 3 — Industrialisation
1. Standardiser les modules (même layout partout).
2. Ajouter conventions de logs/events.
3. Optionnel: bus de messages (Symfony Messenger) pour traitements async.
4. Optionnel: split lecture/écriture (CQRS léger) sur modules critiques.

---

## 7) Conventions d’équipe recommandées

- 1 use case = 1 handler = 1 responsabilité.
- DTO d’entrée/sortie au niveau Application (pas dans Domain).
- Validation syntaxique en UI, validation métier en Domain.
- Repositories en interface côté Domain, implémentations côté Infrastructure.
- Tests :
  - Domain/Application en unitaires,
  - UI/Infrastructure en tests d’intégration.

---

## 8) Bénéfices attendus

- lisibilité du code par contexte métier,
- meilleure vitesse de changement,
- tests plus rapides et plus fiables,
- dette technique réduite à moyen terme,
- montée en charge fonctionnelle sans explosion de complexité.

---

## 9) Démarrage immédiat (prochaine itération)

1. Migrer uniquement le flux `CreatePlatform` selon ce modèle.
2. Conserver le reste en l’état (approche incrémentale).
3. Mesurer : couverture tests, temps de review, bugs post-release.
4. Étendre ensuite à `UpdatePlatform` puis `ListPlatforms`.

Cette trajectoire permet d’obtenir des gains rapidement sans bloquer les livraisons.
