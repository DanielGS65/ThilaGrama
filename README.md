# Instrucciones de Descarga

Una vez se ha clonado el repositorio hay que hacer dos acciones para que funcione:

    composer install

    composer update

Copiar '.env.example' y borrarle el '.example', este fichero ya deberia estar configurado.

Para ejecutar por primera vez:

  php artisan migrate:frssh --seed
  php artisan serve
