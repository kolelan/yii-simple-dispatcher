# Yii2 Console Application with Docker

Простое консольное приложение на фреймворке [Yii2](https://www.yiiframework.com/), работающее в контейнере Docker. Реализует простой диспетчер событий.

## 🧰 Требования

- [Docker](https://www.docker.com/)
- [Docker Compose](https://docs.docker.com/compose/)

## 📁 Структура проекта

```
/app/
├── bin/
│   └── app.php         # Точка входа (исполняемый файл)
├── vendor/             # Зависимости через Composer
├── app/
│   └── controllers/
│       └── HelloController.php  # Пример контроллера
├── composer.json       # Конфигурация Composer
├── docker-compose.yml  # Конфигурация Docker
└── .env                # (опционально) переменные окружения
```

## 🚀 Как запустить

1. **Скопируйте репозиторий:**

   ```bash
   git clone https://github.com/kolelan/yii-simple-dispatcher.git
   cd yii-simple-dispatcher
   ```

2. **Запустите контейнер:**

   ```bash
   docker compose up -d --build
   ```

3. **Установите зависимости:**

   ```bash
   docker compose exec php-cli composer install
   ```

4. **Дайте права на исполнение (если нужно):**

   ```bash
   docker compose exec php-cli chmod +x /app/bin/app
   ```

5. **Запустите команду:**

   ```bash
   docker compose exec php-cli /app/bin/app hello/index
   ```

Вы должны увидеть:

```
Привет из консольного приложения на Yii!
```

## ✅ Дополнительно

- Вы можете добавлять новые контроллеры в папку `app/controllers/`.
- Все изменения сохраняются локально, благодаря монтированию директории через `volumes`.

## 📌 Лицензия

MIT License

---

Автор: [Kolelan]  

GitHub: [https://github.com/kolelan/yii-simple-dispatcher.git]  

GitVerse: [https://gitverse.ru/Kolelan/yii-simple-dispatcher.git]  
```

