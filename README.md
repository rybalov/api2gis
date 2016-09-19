Упрощенная версия справочника 2ГИС
====

В рамках выполнения тестового задания была реализована упрощенная версия справочника 2ГИС согласно [техническому заданию](web/doc/specification.md).

Серверная часть разработана на основе следующего стека технологий:

> PHP 7.0, Symfony 3.1, Doctrine 2, MySQL 5.7.

Клиентская часть:

> jQuery 3.0, Bootstrap 4.0, Angular 2.0.


## Развертывание справочника

Для развертывания необходимо выполнить следующие шаги:

#### 1) Склонировать проект из репозитория:

    git clone git@github.com:rybalov/api2gis.git

#### 2) Установить все зависимости (библиотеки) Symfony с помощью [composer](http://getcomposer.org):

> Необходим **PHP** версии не ниже **7.0**

    curl -s http://getcomposer.org/installer | php
    php composer.phar install -o

В процессе установки зависимостей composer предложит указать параметры подключения к БД (значения остальных параметров можно оставить по умолчанию).
Данные настройки можно изменить вручную позднее, отредактировав файл:

    app/config/parameters.yml
    
После изменения настроек необходимо выполнить очистку кэша Symfony:

    php bin/console cache:clear
    
#### 3) Установить все зависимости (библиотеки) Angular 2 с помощью npm:

> Для установки зависимостей и сборки Angular-приложения необходимы
> устновленные **Node.js** (желательно версии не ниже **4.4**) и **npm** (не ниже
> версии **3.0**).

    cd src/AppBundle/Resources/public/js
    npm install

#### 4) Настроить права доступа

Чтобы файлы кэша могли храниться и редактироваться не только под Вашим пользователем, но и под пользователем, под которым запущен веб-сервер, необходимо установить соответствующие права:

    rm -rf var/cache/*
    rm -rf var/logs/*

    HTTPDUSER=`ps aux | grep -E '[a]pache|[h]ttpd|[_]www|[w]ww-data|[n]ginx' | grep -v root | head -1 | cut -d\  -f1`
    sudo chmod -R a+w "$HTTPDUSER allow delete,write,append,file_inherit,directory_inherit" var/cache var/logs var/sessions
    sudo chmod -R a+w "`whoami` allow delete,write,append,file_inherit,directory_inherit" var/cache var/logs var/sessions

#### 5) Проверить системные требования

Чтобы убедиться, что проект будет работать нормально, необходимо проверить Вашу систему на соответствие системным требованиям. Сделать это можно, выполнив команду следующую из командной строки:

    php bin/symfony_requirements
    
Если для командной строки используется версия PHP отличная от PHP для Web (или различные php.ini), то откройте config.php с помощью браузера:

    http://127.0.0.1:8000/config.php
    
Если после проверки оказалось, что какие-то пункты не удовлетворены, желательно их исправить.

#### 6) Подготовка приложения

> Необходим **MySQL** версии не ниже **5.7**

Для того, чтобы увидеть работающий сайт, необходимо также пройти его настройку:

##### Создание базы данных

Чтобы создать базу данных необходимо выполнить следующую команду:

    php bin/console doctrine:database:create
    
##### Создание схемы и загрузка данных

Создать новую схему данных, сгенерировать модель:

    php bin/console doctrine:schema:create

Либо загрузить базу с тестовым набором данных (в этом случае уже созданная схема будет заменена данными из дампа):

    php bin/console dbal:import db/2gis.sql

##### Копирование ресурсов (js/css/images) в web root

Клиентские js-скрипты, стили, картинки необходимо пробросить в web root: 

    php bin/console assets:install --symlink

##### Проверка работоспособности API

Для того, чтобы проверить работоспособность API, необходим установленный [PhpUnit](https://phpunit.de/getting-started.html). В корневой директории выполните команду:

    phpunit
    
##### Запуск встроенного веб-сервера

Проверить работоспособность сайта можно используя возможности Symfony, он будет доступен по адресу [http://127.0.0.1:8000](http://127.0.0.1:8000).

Для запуска выполните команду:

    php bin/console server:run
    

## Документация по API

Документация по API доступна по ссылке: [https://2gis.rybalov.work/doc/api](https://2gis.rybalov.work/doc/api).

В формате Swagger: [https://2gis.rybalov.work/doc/api/swagger.json](https://2gis.rybalov.work/doc/api/swagger.json).

Сервис для тестирования API: http://petstore.swagger.io
> Подробнее о Swagger: http://swagger.io.
