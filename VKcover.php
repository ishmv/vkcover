<?php
// Перейти по адресу https://site.ru/VKcover.php?debug=1 для дебага если будут ошибки
if($_GET['debug'] == 1) {ini_set("display_errors",1);} else {ini_set("display_errors",0);}
error_reporting(E_ALL);

// API ключ https://rucaptcha.com для разгаднывания капчи, если поле пустое, то нужно будет 1 раз в час грузить обложку вручную.
$rucaptcha_key = '';

// Логини или телефон для входа на VK.com
$login = '';

// Пароль для входа на VK.com
$password = '';

// Для проверки по телефону. Например Ваш номер телефона 79201234567, то необходимо в переменную прописать промежуток от 7 до 67, то есть 92012345. Если телефона с Украины 380961234567, то необходимо вписать промежуток от 380 до 67, то есть 612345
$security_check_code = '98856145';

// ID Вашего паблика или группы
$public = '143063541';

// Сервер для загрузки в изображение. Как получить читать в readme.txt
$server = '637419';

// Подпись для загрузки фото. Как получить читать в readme.txt
$signature = 'cca816e387a1b22a41c780ee98adc6a2';

// Временная зона
date_default_timezone_set('Europe/Moscow');

// $show_time = 1; - выводить время, $show_time = 0; - не выводить время
$show_time = 1;

// Домашняя дериктория где лежит данный скрипт. Если в корне, то
// Если в корне:
// $DROOT = ''.$_SERVER['DOCUMENT_ROOT'].'';
//
// Если в папке, то:
// $DROOT = ''.$_SERVER['DOCUMENT_ROOT'].'/papka';
$DROOT = ''.$_SERVER['DOCUMENT_ROOT'].'';

// Шрифт которым пишет на изображение (важно, только ttf формат)
$font = ''.$DROOT.'/Roboto-Bold.ttf';

// Цвет имен '255, 255, 255' - белый, '0, 0, 0' - черный. Остальные можете посмотреть в Photoshop, RGB схема
$color = array(255, 255, 255);

// Цвет времени '255, 255, 255' - белый, '0, 0, 0' - черный. Остальные можете посмотреть в Photoshop, RGB схема
$color_time = array(255, 255, 255);

// Путь к исоходному изображению на которое будет наложены последние вступившие
$bg = ''.$DROOT.'/cover.jpg';

// Положение изображений зависит от того какой из шаблоновы Вы выбрали: left - слева, center - по центру, right - справа
$position = 'center';

// Путь к изображению которое будет загружено
$imagelast = ''.$DROOT.'/tmp/result.jpg';

include 'VKfunction.php';
?>