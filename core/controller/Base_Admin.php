<?php
defined('SHOP') or exit('Access denied');
abstract class Base_Admin extends Base_Controller {
    
    protected $ob_m;//по анологии с Base здесь будем хранить обьект модели сайта
    
    protected $ob_us;//здесь будет обьект класса Model_User
    
    protected $title;//здесь храниться заголовок страницы
    
    protected $style;//здесь будут стили для админ панели
    
    protected $script;//здесь скрипты
    
    protected $content;/*здесь будет храниться сгенерированый щаблон центральной части вместе с правой
    это свойство будет формироваться в дочерних контролерах, а футер, шапка и левая часть будет в этом*/
    protected $user = TRUE;/*здесь будет храниться либо true либо false, если true -то значит авторизация
    пользователя нужна ,если false то нет*/
    //теперь как обычно два главных метода input и output
    //метод input который подготовливает все входные данные
    protected function input() {
        /*сначала проверим нужна ли авторизация пользователя или нет*/
        if($this->user == TRUE) {
            /*если нет то вызовем метод который описан в Base_Controleer check_auth,который проверяет 
            авторетизировался пользователь или нет*/
            $this->check_auth();    
        }
        
        //формируем заголовок
        $this->title = "ISHOP |";//дальше в дочерних классах будет добавлять заголовок
        
        /*теперь сформируем свойства $style и $script, для этого пройдемся циклом по массиву styles и
        scripts записаных в свойствах Base_cONTROLLER и сформируем массив style и script*/
        foreach($this->styles_admin as $style) {
            $this->style[] = SITE_URL.VIEW.'admin/'.$style;    
        }
        
        //точно также для скриптов формируем массив с полными путями к скриптам
         foreach($this->scripts_admin as $script) {
            $this->script[] = SITE_URL.VIEW.'admin/'.$script;    
        }
        
        //теперь получим обьект нашей модели обратившись к классу Model и его методу get_instans
        $this->ob_m = Model::get_instance();
        //теперь получим и сохраним в нашем свойстве,обьект класса Model_User
        $this->ob_us = Model_User::get_instance();     
    }
    
    //и методо который уже генерирует шаблоны для вывода на экран и возвращает готовую страницу 
    protected function output() {
        
        //теперь сгенерируем шаблоны для вывода статических частей сайта\левый ,шапка,футер
        //для шапки -создадим переменную header и
        $header = $this->render(VIEW.'admin/header',array(
                                                          'title' => $this->title,
                                                          'styles' => $this->style,
                                                          'scripts' => $this->script
                                                         ));
        /*теперь для левого блока,туда параметры передавать не будем,т.к. там обычное статическое
        меню,которое будет просто в шаблоне описано*/
        $left_bar = $this->render(VIEW.'admin/left_bar');
        
        //то же для футера
        $footer = $this->render(VIEW.'admin/footer');
        
        /*теперь собирем все вместе этим у нас займется индексный файл из папки admin*/
        $page = $this->render(VIEW.'admin/index',array(
                                                      'header' => $header,
                                                      'left_bar' => $left_bar,
                                                      'content' => $this->content,//это сформируется в дочернем классе
                                                      'footer' => $footer
                                                      ));
         
        //и возвращаем нашу страницу
        return $page;   
    }
    
}
?>