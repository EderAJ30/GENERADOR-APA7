1 genera api app key
docker compose exec app composer install
docker compose exec app cp .env.example .env
docker compose exec app php artisan key:generate

2 migraciones
docker compose exec app php artisan migrate

3 url
http://localhost:8000

4 instalar node
docker compose exec app npm install

5 vite
docker compose exec app npm run dev

6