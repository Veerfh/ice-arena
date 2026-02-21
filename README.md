## Настройка проекта

1. Клонирование проекта

```
git clone https://github.com/Veerfh/ice-arena.git
```

2. Установка зависимостей

```
composer install
// ИЛИ
composer update
```

3. Настройка env(скопировать файл .env.example)

```
cp .env.example .env
```

4. Создание базы данных

```
mysql -u root -p -e "CREATE DATABASE ice_arena"
```

5. Запуск миграций и сидов

```
php artisan migrate --seed
```

6. Запуск сервера

```
php artisan serve
```

## Админ-панель

Доступна по адресу http://localhost:8000/admin

Данные для входа:

```
Логин: admin@mail.ru
Пароль: 123456
```
