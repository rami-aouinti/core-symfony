# Infrastructure

La couche Infrastructure implémente les détails techniques (ex. persistence Doctrine).

Règles :
- dépend de l'Application et du Domain pour brancher les interfaces ;
- n'est jamais dépendue par le Domain.
