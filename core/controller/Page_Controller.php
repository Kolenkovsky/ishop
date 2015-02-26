<?php
defined('SHOP') or exit('Access denied');
//Создадим класс который будет выводить страницы "О компании","Контакты",все по анологии с News_Conroller
class Page_Controller extends Base {
    
    protected $page;//Тут будем хранить массив данных по странице(заголовок,текст ит.д.)
    
    protected function input($params) {
        
        parent::input();
        
        /*Как обычно проверяем пришли ли нам данные т.е. массив $param и очистим от злоумышлеников*/
        if(isset($params['id'])) {
            $id = $this->clear_int($params['id']);
            /*Обратимся к обьекту модели и его методу get_page*/
            $this->page = $this->ob_m->get_page($id);//Переаем индефикатор $id какую страницу нам надо
            //Так этот контролле для двух страниц то title добавляем здесь а не в начале
            $this->title .= $this->page['title'];
            $this->keywords = $this->page['keywords'];
            $this->description = $this->page['description'];         
        }    
    }
    
    protected function output() {
        
        //Генерируем центральную часть контента
        $this->content = $this->render(VIEW.'page_page',array(
                                                              'page' => $this->page
                                                             ));
        
        $this->page = parent::output();
        return $this->page;
    } 

}
?>