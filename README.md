# microcredit_Laravel
## устанвока
Скопируйте файл окружения и настройте его:
```sh
cp .env.example .env
```
Постройте и запустите контейнеры:
```sh
docker-compose up -d --build
```
настроить файл .env
```sh
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=laravel
DB_PASSWORD=laravel
API_KEY=secret_api_key
```
установка зависимостей:
```sh
composer install
```
Выполните миграции и сидеры:
```sh
docker-compose exec app php artisan migrate:fresh --seed
```
сгенерировать документацию 
```sh
php artisan l5-swagger:generate
```

Проверка работы приложения:
После запуска контейнеров приложение будет доступно по адресу http://localhost
А документацию по адресу http://localhost/api/documentation
Для доступа к API каждый запрос должен содержать заголовок X-API-KEY со значением вашего статического API-ключа.
