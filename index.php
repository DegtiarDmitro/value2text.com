<?php
    /*
        Написать решение c использованием ООП, которое позволяет переводить целые числа 
        в каком-нибудь диапазоне (c миллионами включительно) в текст (разговорный) на трех языках (рус., англ., укр.)
    */
?>
<?php
    header("Content-type: text/html; charset=utf-8");
    /* Пути по-умолчанию для поиска файлов */
    set_include_path(get_include_path()
                        .PATH_SEPARATOR.'mvc/models'
                        .PATH_SEPARATOR.'mvc/controllers'
                        .PATH_SEPARATOR.'mvc/views');

    /* Имена файлов: views */
    define('INDEX_FILE', 'indexView.php');
    define('HEAD', 'head.php');

    /* Автозагрузчик классов */
    function __autoload($class){
    	require_once($class.'.php');
    }

    /* Инициализация и запуск FrontController */
    $front = FrontController::getInstance();
    $front->route();
    
    /* Вывод данных */
    echo $front->getBody();
?>

