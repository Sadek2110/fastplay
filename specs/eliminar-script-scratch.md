# Eliminar script de depuración scratch_dump_teams.php

## Objetivo

Sacar del repositorio un script de depuración de un solo uso que actualmente se
copia a la imagen de producción.

## Contexto

`scratch_dump_teams.php` está en la raíz del repositorio. Vuelca por pantalla
todas las filas de la tabla `teams`. No lo referencia ningún otro fichero del
proyecto, no está cubierto por tests y no aparece en `.gitignore` ni en
`.dockerignore`, de modo que el `COPY . .` del `Dockerfile` lo incluye en la
imagen que se despliega.

## Ficheros que se pueden tocar

- `scratch_dump_teams.php` (eliminar)

## Comportamiento esperado

- El fichero `scratch_dump_teams.php` deja de existir en el repositorio.
- No se modifica ningún otro fichero.

## Criterios de aceptación

- `ls scratch_dump_teams.php` devuelve error de fichero inexistente.
- `grep -rn "scratch_dump_teams" . --exclude-dir=.git --exclude-dir=vendor` no
  devuelve ninguna coincidencia.
- `vendor/bin/phpunit` sigue pasando igual que antes del cambio.

## Fuera de alcance

- No añadir reglas a `.gitignore` ni a `.dockerignore`.
- No tocar el `Dockerfile`.
- No revisar ni limpiar otros ficheros de la raíz, aunque parezcan sobrantes.
