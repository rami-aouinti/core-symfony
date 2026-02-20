# Checklist PR — Migration architecture

À copier dans la description de PR pour homogénéiser les futures migrations.

## Definition of Done architecture

- [ ] Aucun contrôleur ne contient de logique métier.
- [ ] Le Domain ne dépend pas de Symfony/Doctrine.
- [ ] Chaque nouveau flux passe par un `Command` et un `Handler`.
- [ ] La persistance suit le pattern port + adapter (interface côté Domain/Application, implémentation côté Infrastructure).
