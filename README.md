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

6 entrar a la bash de msql
docker exec -it referenciasico_db mysql -u eder -p1234 referenciasico

7 volver a contruir el docker si le cambian algo
docker compose up -d --build

8 iniciar docker
docker compose up -d

9 cualquier comando en bash
docker compose exec app