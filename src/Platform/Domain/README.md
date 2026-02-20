# Domain

Le Domain contient la logique métier pure (`Model`, `ValueObject`, `Service`, `Event`, interfaces de `Repository`).

Règles :
- aucune dépendance à Symfony, Doctrine ou à l'infrastructure ;
- dépend uniquement de PHP standard et d'autres éléments du Domain.
