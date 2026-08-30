# Reglas para el agente de código

Este fichero lo lee opencode al empezar en este repositorio. Cópialo a la raíz
de cada proyecto y ajústalo al stack real.

## Tu papel

Implementas especificaciones. Otro agente (el orquestador) decide el qué y el
por qué; tú resuelves el cómo. Si la spec es ambigua, elige la opción más
conservadora y **anótalo en tu resumen final** en vez de parar a preguntar:
esta sesión es no interactiva y nadie va a contestarte.

## Límites duros

- No hagas `git commit`, `git push` ni crees ramas. Deja los cambios en el
  working tree; de integrarlos se encarga el orquestador.
- No toques ficheros fuera de la lista de la spec. Si crees que hace falta,
  dilo en el resumen en lugar de hacerlo.
- No toques `.env`, credenciales, ni `docker-compose.yml` de producción.
- No añadas dependencias nuevas sin que la spec las mencione.

## Estilo

- Nombres de fichero y de rama en inglés, kebab-case.
- Comentarios y textos de interfaz en español.
- Nada de código muerto, ficheros de ejemplo ni `TODO` sueltos.
- Si el proyecto tiene linter o formateador configurado, pásalo antes de
  terminar.

## Resumen final

Termina siempre con un bloque de 5 líneas como máximo:

- Ficheros tocados y qué hace cada cambio.
- Decisiones que has tomado por ambigüedad de la spec.
- Lo que has dejado sin hacer y por qué.

Ese resumen es lo único que llega al orquestador. Si te extiendes, le cuesta
dinero; si te quedas corto, se lo tiene que preguntar al repositorio y también
le cuesta dinero.

## Stack de este proyecto

- Lenguaje: PHP >= 8.1 (imagen Docker en 8.2)
- Framework: ninguno con nombre — MVC propio (app/core, app/models, app/controllers, autoload por classmap), enrutamiento en router.php, servido con Apache
- Instalar: `composer install`
- Test: `vendor/bin/phpunit`
- Build: no hay paso de build aparte de `composer install`; el Dockerfile ejecuta `composer install --no-dev --optimize-autoloader` para producción
