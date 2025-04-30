### Prerequisites:
- [Docker](https://www.docker.com/)
- [Install Laravel](https://laravel.com/docs/11.x/installation#creating-a-laravel-project)
- [NodeJS](https://nodejs.org/en)

---

### Local Development
When you want to develop locally you will run the Laravel app and connect to your Dockerized database and redis.

1. Clone the repository
2. In the root of the project copy `.env.example` to `.env`
> cp .env.example .env
3. Set the variables you wish for DB and REDIS (check the .env.example file)
4. Go to the `backend` directory
> cd backend
5. Install PHP dependencies
> composer install
6. Install Node.js dependencies
> npm install
7. Copy `.env.example` to `.env`
> cp .env.example .env
8. Change the DB and Redis ENV variables to match the ones from the root of the project (same username, database and password)
9. Change the `DB_HOST` and `REDIS_HOST` env variable to `127.0.0.1` when developing locally
10. Run the migrations
> php artisan migrate
11. Run the Laravel app
> php artisan serve
12. Run vite (You can skip this step if you are only using this for an API)
> npm run dev

---

### Docker environment
When you're finished with the development to run the app with Docker to the following
1. Change the `DB_HOST` env variable to `db` (db is the container service name)
2. Change the `REDIS_HOST` env variable to `redis` (redis is the container service name)
3. Build the containers
> docker compose up --build --detach
4. Go to `http://localhost`

### Going to production!
There are minimal changes needed when going to production.

You should change the nginx.conf to match your website URL and add SSL so that you can have an encrypted connection (HTTPS). Always USE PORT 443.

If you plan on using Laravel only for the API you don't need to bother with Vite so you can remove everything related to that. That means removing node, npm and running `npm run build` in the `php-fpm-entrypoint` file.

Some minimal changes might be required for larger file uploads.
