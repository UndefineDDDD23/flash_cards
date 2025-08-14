# Flash Cards

Интерактивное веб-приложение для изучения слов с помощью карточек, реализованное на Laravel с Livewire и поддержкой AI-переводов.

## Основные возможности
- Создание, изучение и управление карточками слов
- Поддержка spaced repetition (интервалы повторения)
- Переводы с помощью AI (OpenRouter)
- Аутентификация и восстановление пароля
- Многоязычность (английский, русский)

## Быстрый старт
1. Установите зависимости:
   ```bash
   composer install
   npm install
   ```
2. Соберите фронтенд:
   ```bash
   npm run build
   ```
3. Запустите миграции:
   ```bash
   php artisan migrate
   ```
4. Запустите сервер:
   ```bash
   php artisan serve
   ```

## Интеграции и зависимости
- Laravel, Livewire, Vite
- AI-переводы через OpenRouter
- Docker (docker-compose.yml, Dockerfile)
