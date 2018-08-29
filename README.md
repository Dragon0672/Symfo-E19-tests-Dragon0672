# Evénements Symfony, Doctrine

## Evénement(s) du Kernel

Au choix :

- Afficher dans le footer un film aléatoire avec lien vers le film.
- Injecter du code HTML en haut des pages (par ex. une bannière informative pour une prochaine maintenance du site).

### Ressources

- http://symfony.com/doc/3.4/event_dispatcher.html
- http://symfony.com/doc/3.4/event_dispatcher/before_after_filters.html

## Evénement(s) de formulaire

- Modifier le formulaire UserType pour :
    - password = NotBlank au create seulement
    - password, placeholder = 'Laissez vide si inchangé' uniquement à l'edit

### Ressources

- http://symfony.com/doc/3.4/form/events.html

## Evénement(s) Doctrine

- Slugifier le title lorsque l'entité Movie est créée ou modifiée.

### Ressources

- http://symfony.com/doc/3.4/doctrine/lifecycle_callbacks.html
- http://symfony.com/doc/3.4/doctrine/event_listeners_subscribers.html
