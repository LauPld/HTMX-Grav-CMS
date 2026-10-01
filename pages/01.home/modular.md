---
title: HTMX Demo 
subtitle: Twig Edition
body_classes: title-center title-h1h2
markdown:
    extra: true
hero_image: hero.webp
accept:
  - 'image/*' 
content:
    items: '@self.modules'
    order:
        by: default
        dir: asc
        custom:
            - _messages
            - _mouse_entered
            - _trigger_delay
            - _account

---
This page is a practical use case of the [HTMX](#htmx-docs) documentation page using a `PHP` backend and `twig` templating on [Grav](https://getgrav.org/) cms. I build this project in order to stay as much as possible in a php environment for front-end interactions and avoid JavaScript fatigue. 
