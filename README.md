# Hungry People — Restaurant Website

Веб-сайт ресторана "Hungry People" с адаптивной вёрсткой, PHP MVC backend и MySQL базой данных.

## Функциональность

- **Главное меню** — якорные ссылки на разделы, фиксация при скролле, уменьшение логотипа
- **Hero-секция** — кнопки-якоря "Book Table" и "Explore"
- **About Us / Our Team / Private Events** — статичный контент из БД
- **Book a Table** — форма бронирования с AJAX-отправкой и email-уведомлением
- **Specialties** — слайдер контента (Slick Carousel) с данными из БД
- **Delicious Menu** — сетка блюд из БД (до 21 элемента), фильтрация по категориям
- **Contact** — форма обратной связи с AJAX-отправкой
- **Yandex Карта** — расположение ресторана
- **Авторизация/Регистрация** — модальное окно, AJAX, сессии PHP
- **Социальные сети** — ссылки с `target="_blank"`
- **Адаптивность** — Bootstrap 4 + БЭМ

## Технологии

- **Frontend:** HTML5, CSS3, Bootstrap 4, jQuery, Slick Carousel, Yandex Maps API
- **Backend:** PHP 8.1, MVC (Model-View-Controller), PDO
- **База данных:** MySQL 8.0
- **Инфраструктура:** Docker, Composer

## Структура проекта

```
hungry-people/
├── app/
│   ├── Controllers/       # Контроллеры (Auth, Booking, Contact, Menu, etc.)
│   ├── Core/              # Ядро (Database, Router, BaseModel, BaseController)
│   └── Models/            # Модели (User, Booking, Contact, MenuItem, etc.)
├── config/
│   └── database.php       # Конфигурация БД
├── css/
│   └── style.css          # Стили (БЭМ)
├── images/                # Изображения
├── js/
│   └── main.js            # JavaScript (AJAX, слайдер, фильтрация)
├── .htaccess              # Rewrite rules для Apache
├── composer.json          # PSR-4 автозагрузка
├── database.sql.txt       # SQL-дамп БД
├── docker-compose.yml     # Docker Compose
├── Dockerfile             # Docker образ
├── index.html             # Главная страница
├── index.php              # Front Controller (MVC)
└── README.md              # Этот файл
```

## API Endpoints

| Метод | Путь | Описание |
|-------|------|----------|
| POST | `/api/auth/signin` | Авторизация |
| POST | `/api/auth/signup` | Регистрация |
| GET | `/api/auth/check` | Проверка сессии |
| GET | `/api/auth/logout` | Выход |
| POST | `/api/booking` | Бронирование столика |
| POST | `/api/contact` | Обратная связь |
| GET | `/api/static/{section}` | Статический контент (about, team, events) |
| GET | `/api/specialities` | Специальности (слайдер) |
| GET | `/api/menu` | Все блюда (on_main=1, до 21) |
| GET | `/api/menu/{category}` | Блюда по категории (pizza, beer, wine...) |


## Работа с базой данных

### Таблицы

| Таблица | Назначение |
|---------|-----------|
| `static` | Статический контент (about, team, events) |
| `specialities` | Слайдер специальностей |
| `menu` | Блюда (с категориями и флагом `on_main`) |
| `users` | Пользователи (регистрация/авторизация) |
| `bookings` | Бронирования столиков |
| `contacts` | Сообщения из формы обратной связи |

### Добавление блюд в меню

Подключиться к БД и выполнить:
```sql
INSERT INTO `menu` (`title`, `subtitle`, `price`, `category`, `on_main`) VALUES
('Название блюда', 'Описание', 25.00, 'pizza', 1);
```

Поле `on_main = 1` означает, что блюдо отображается на главной странице.
Поле `category` может быть: `pizza`, `beer`, `wine`, `desert`, `soupe`, `drinks`, `pasta`.

### Добавление специальностей (слайдер)

```sql
INSERT INTO `specialities` (`title`, `subtitle`, `text`, `image`) VALUES
('Название', 'Подзаголовок', 'Описание', 'image.jpg');
```

Изображение должно находиться в папке `images/`.

## Разработка

### Добавление нового API-маршрута

1. Добавить маршрут в `app/Core/Router.php`:
   ```php
   $this->addRoute('GET', 'api/example', 'ExampleController@index');
   ```

2. Создать контроллер `app/Controllers/ExampleController.php`

3. Создать модель (если нужно) в `app/Models/`

### Изменение стилей

Стили находятся в `css/style.css` и используют методологию БЭМ.
Префиксы блоков: `.header__`, `.hero__`, `.about__`, `.menu__`, `.booking__`, `.contact__`, `.footer__`, `.auth-modal__`.

## Лицензия

## запуск

приложение запускается через xampp:

Шаг 1. Откройте C:\xampp\php\php.ini и замените соответствующие строки например на:
SMTP = smtp.gmail.com
smtp_port = 587
sendmail_from = ваш_email@gmail.com
Шаг 2. Установите Sendmail для XAMPP:
smtp_server=smtp.gmail.com
smtp_port=587
auth_username=ваш_email@gmail.com
auth_password=ваш_пароль_приложения
Шаг 3. В php.ini укажите путь к sendmail:
sendmail_path = "C:\xampp\sendmail\sendmail.exe -t"
** ВАЖНО ** Пароль приложения - это не пароль от почты, о его настройке узнайте по ссылке https://support.google.com/mail/answer/185833
