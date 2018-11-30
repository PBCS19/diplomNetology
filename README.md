<a href="https://drive.google.com/file/d/1I8rf3_aQ2VtCQeAGVQ2m-5fx0kP1Aj6b/view?usp=sharing">Документация</a><br>
<a href="https://pbcs-shop.com/">Рабочая версия</a><br>

#Установка
1. Скачать
2. Установить в директорию сайта
3. Через терминал войти в дерикторию сайта и ввести команду пример для (ubuntu):
```cd /path/to/site
composer update```
4. Создать базу данных для сайта
5. В файле ./engine/Config/Database.php прописать данные для подключения к СУБД
```return [
    'host'         => 'localhost',
    'charset'      => 'utf8',
    'dbname'       => 'faq',
    'username'     => 'root',
    'password'     => ''
];```
6. Загрузать дамп 'faq.sql' в базу данных
7. Настроить хостинг что бы грузил с дериктории path/to/site/public/, если не получится то приложил файл .htaccess
8. Войти на сайт через браузер
9. Войти в административную часть сайта: ./admin
>логин: admin
>пароль: admin