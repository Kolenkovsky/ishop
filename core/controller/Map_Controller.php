<?php
defined('SHOP') or exit('Access denied');
class Map_Controller extends Base {

    protected $pages;//Тут будем хранить массив страниц полученых методом get_pages описанного в Model
    
    protected $catalog;//Тут массив категорий и брендов получю методом get_catalog_brands() из Model

    /*Переопределим два метода output и input ,в метод input ни каких параметров передавать не будем
    так как у нас здесь не будет никаких параметров в адрессной строке*/
    protected function input() {
    
        parent::input();
        
        $this->title .= "Карта сайта";
        
        //Получим масив страниц и сохраним в нашем свойстве
        $this->pages = $this->ob_m->get_pages();
        //Также для каталога,через обьект Модели и сохраним
        $this->catalog = $this->ob_m->get_catalog_brands();
        
        $this->keywords = "Карта сайта";
        $this->description = "ishop|Карта сайта";               
    }
    
    protected function output() {

        /*Теперь сгенирируем центральную часть, вызвав свойство content и присвоим ему*/
        $this->content = $this->render(VIEW.'site_map',array(
                                                              'pages' => $this->pages,
                                                              'catalog' => $this->catalog
                                                             ));        
        
        
        $this->page = parent::output();
        return $this->page;    
    }
}

?>