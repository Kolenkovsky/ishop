<?php
defined('SHOP') or exit('Access denied');
class News_Controller extends Base {
    /*Мы помним,что когда мы создаем контроллер который выводит страницу, мы должны переопределить два метода
    input и output*/
    protected $news_text;// Тут будем хранить наш текст новости
   
    protected function input($params) {//Масив параметров $params прийут сюда из метода request класса Base
        //Вызываем родит.метод
        parent::input();
        //Добавляем к свойству $title хранящемучя в классе Base стыкуем Новости
        $this->title .= "Новости";
        
        /*Принятый массив параметров $params нам надо очистить(на случай если злоумышленик ввел в адресной
        строке какойто ява код или еще что,поэтому условимся что в этом массиве должны приходить только чис-
        ловые данные то есть значение id новости)*/
        //Сначала проверим а есть ли &params и ячейка id в нем
        if(isset($params['id'])) {
            /*Если есть,то создаем промежуточную перпеменую id и ей присваиваем строку переведеную в 
            числовой вид- ф-я clear_int*/
            $id = $this->clear_int($params['id']);    
        }
        //ТеперьЭесли после очистки проверим там есть какие то даненые не нуль
        if($id) {
            //Обращаемся к модели и методу который выберет нам текст этот метод прописан в модели
            //и передаем этому методу параметр $id так как нам надо выбрать одну новость
            $this->news_text = $this->ob_m->get_news_text($id);
            
            //Формируем ключевые слова и описание для этой страницы
            $this->keywords = $this->news_text['keywords'];
            $this->description = $this->news_text['description'];
        }
       
    }
    
    protected function output() {
        
        /*Теперь сформируем центральную часть нашей страницы т.е. обратимся к свойству $content т.к 
        в этом свойстве к нас должна быть центральная часть стр.-см. метод render в Base*/
        $this->content = $this->render(VIEW.'news_page',array(
                                                              'news_text' => $this->news_text  
                                                             ));
        
        /*Так же вызываем родителский мет.только мы вызывали свойство $this->$page и сохраняли там отработку
        этого метода*/
        $this->page = parent::output();
        
        //И возвращаем полностью сгенерированную страничку
        return $this->page;    
    }
}

?>