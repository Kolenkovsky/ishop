<?php
defined('SHOP') or exit('Access denied');
/*создадим класс который будет обрабатывать исключения связанные с авторизацией*/
class AuthException extends Exception {
    
    //опишем конструктор класса
    public function __construct($text) {
        
        /*передадим свойству messege класса Exception наш текст об ошибке*/
        $this->message = $text;
        
        /*сохраним в масиве SESSION текст который передается при генерации исключений сюда, а в 
        Login_Cotrollere мы шаблону передаем переменную error со значением этой ячейки масива SESSION*/
        $_SESSION['auth'] = $text;    
    }    
}


?>