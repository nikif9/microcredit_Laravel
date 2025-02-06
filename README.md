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
Выполните миграции и сидеры:
```sh
docker-compose exec app php artisan migrate:fresh --seed
```
Проверка работы приложения:
После запуска контейнеров приложение будет доступно по адресу http://localhost
Для доступа к API каждый запрос должен содержать заголовок **X-API-KEY** со значением вашего статического API-ключа.  
Например, если ваш API-ключ равен `secret_api_key`, заголовок должен выглядеть так:
## Основные API эндпоинты
/api/organizations/{id} - Возвращает детальную информацию об организации
/api/buildings/{building}/organizations - Возвращает список организаций, находящихся в указанном здании
/api/activities/{activity}/organizations - Возвращает список организаций, напрямую связанных с указанным видом деятельности
/api/organizations/nearby?lat=55.7558&lng=37.6176&radius=5 - Возвращает список организаций, находящихся указанном радиусе 
/api/organizations/nearby?lat_min=55.7&lat_max=55.8&lng_min=37.6&lng_max=37.7 - Возвращает список организаций, находящихся указанной прямоугольной области
/api/organizations/search/activity?activity_name=Еда - Выполняет поиск организаций по виду деятельности с учётом вложенных категорий до 3-го уровня 
/api/organizations/search/name?name=Рога - Возвращает список организаций, название которых содержит указанный текст.
/api/buildings - Возвращает список всех зданий.
