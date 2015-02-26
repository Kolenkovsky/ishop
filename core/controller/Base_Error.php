<?php
defined('SHOP') or exit('Access denied');
/*Cоздадим абсктрактный класс Base_Error по аналогии с Base_Controller,который будет выводить шаблон и 
получать какие то данные из error контролера которые будут выводиться в шаблоне*/
abstract class Base_Error extends Base_Controller {
    
    protected $message_err;//тут будет храниться массив данных для вывода на экран
    
    protected $title;// здесь будем хранить заголовок страницы
    
    //дальше по анналогии с классом Base
    protected function input() {
        $this->title = 'Страница ERROR';    
    }
    
    protected function output() {
    
        /*тут по анлогии с классом Base_Controller сформируем переменную $page и сохраним в ней сгенерированый
        шаблон и затем вернем эту переменную*/
        $page = $this->render(VIEW.'error_page',array(
                                                     'title' => $this->title,
                                                     'error' => $this->message_err
                                                     ));
        return $page;          
    }    
}


?>