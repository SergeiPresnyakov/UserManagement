# Управление пользователями
## версия Laravel Framework 8.29.0


### Установка проекта
    git clone
    composer update

Создайте 2 базы данных с параметром collation = utf8mb4_unicode_ci, например:

    user_management
    user_management_testing


Прописать правила подключения к основной и тестовой БД в вайле .env
```code
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=user_management
DB_USERNAME=ваш_логин
DB_PASSWORD=ваш_пароль

TEST_DB_HOST=127.0.0.1
TEST_DB_PORT=3306
TEST_DB_DATABASE=user_management_testing
TEST_DB_USERNAME=ваш_логин
TEST_DB_PASSWORD=ваш_пароль
```

Конфиг для БД в файле app/config/database.php
```php
'mysql' => [
    'driver' => 'mysql',
    'url' => env('DATABASE_URL'),
    'host' => env('DB_HOST', '127.0.0.1'),
    'port' => env('DB_PORT', '3306'),
    'database' => env('DB_DATABASE', 'forge'),
    'username' => env('DB_USERNAME', 'forge'),
    'password' => env('DB_PASSWORD', ''),
    'unix_socket' => env('DB_SOCKET', ''),
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix' => '',
    'prefix_indexes' => true,
    'strict' => true,
    'engine' => null,
    'options' => extension_loaded('pdo_mysql') ? array_filter([
        PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
    ]) : [],
],

'mysql_testing' => [
    'driver' => 'mysql',
    'url' => env('DATABASE_URL'),
    'host' => env('TEST_DB_HOST', '127.0.0.1'),
    'port' => env('TEST_DB_PORT', '3306'),
    'database' => env('TEST_DB_DATABASE', 'forge'),
    'username' => env('TEST_DB_USERNAME', 'forge'),
    'password' => env('TEST_DB_PASSWORD', ''),
    'unix_socket' => env('DB_SOCKET', ''),
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix' => '',
    'prefix_indexes' => true,
    'strict' => true,
    'engine' => null,
    'options' => extension_loaded('pdo_mysql') ? array_filter([
        PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
    ]) : [],
],
```
Конфиг для PHPUnit в файле phpunit.xml
```xml
    <php>
        <server name="APP_ENV" value="testing"/>
        <server name="BCRYPT_ROUNDS" value="4"/>
        <server name="CACHE_DRIVER" value="array"/>
        <server name="DB_CONNECTION" value="mysql_testing"/>
        <server name="MAIL_MAILER" value="array"/>
        <server name="QUEUE_CONNECTION" value="sync"/>
        <server name="SESSION_DRIVER" value="array"/>
        <server name="TELESCOPE_ENABLED" value="false"/>
    </php>
```
Запустить миграции для создания таблиц
```code
php artisan migrate --database=mysql
php artisan migrate --database=mysql_testing
```
Уже можно прогнать тесты
```code
php artisan test
```
Заполним основную БД фейковыми данными 
```code
php artisan db:seed --database=mysql
```
Аккаунт админа

    email: admin@gmail.com
    password: 12345678

К остальным аккаунтам пароль: password


### Функционал сайта

##### Неавторизованный пользователь может:

    * заходить на главную и просматривать профили пользователей
    * логиниться через форму если зарегистрирован
    * регистрироваться через форму

##### Авторизованный рядовой пользователь может:

    * то же, что и неавторизованный
    * редактировать общую информацию в своём профиле
    * редактировать свои учётные данные (email и пароль)
    * редактировать ссылки на соцсети
    * менять статус (Онлайн, Отошёл, Не беспокоить)
    * менять свой аватар
    * удалить свой профиль

##### Авторизованный администратор может

    * то же, что и авторизованный рядовой пользователь
    * те же действия для профиля любого пользователя
    * создать нового пользователя через доступную только ему форму


### Некоторые технические детали

    * есть пагинация (по 6 пользователей на страницу)
    * за доступность форм и действий отвечают компоненты middleware
    * валидация всех, вводимых пользователем, данных только в компонентах request
    * на каждую форму свой request со своими правилами валидации
    * в целях производительности и экономии памяти для некоторых запросов используется компонент DBService, который представляет собой набор подготовленных запросов, которые подтягивают из БД только то, что нужно. (Например чтобы не загружать всю модель User ради одной строчки с email)
    * при смене аватарки, прежнее изображения удаляется, кроме случаев если это не дефолтный аватар-плейсхолдер
    * при удалении профиля файл аватарки удаляется по тем же правилам
