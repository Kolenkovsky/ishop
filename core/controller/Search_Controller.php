<?php
defined('SHOP') or exit('Access denied');
/*Опишем контроллер для полнотекстового поиска*/
class Search_Controller extends Base {
    /*В этом контроллере нам необходимо принять POST данные и дальше с помощью постраничной навигации
    выводить данные,не через Модель,так как найденных данных может быть не на одну страницу*/

    protected $str;//Здесь будет храниться строка запроса
    
    protected $navigation;//Тут будем хранить массив ссылок для постран.навигации
    
    protected $search;//Тут будет хранить массив данных для вывода на экран
        
    protected function input($param = array()) {
    
        parent::input();
        
        /*Опишем параметры которые будем передаваться этому контролеру,так как мы будем выводить данные
        через постраничную навигацию-опишем параметр page так как нам надо передавать через адресную
        строку номер страницы которую небходимо отобразить в данный момент поэтому мы должны его принять ,очистить от вредноносного кода 
        и сохранить в какуюто переменную*/
        if(isset($param['page'])) {
            $page = $this->clear_int($param['page']);
            
            if($page == 0) {
                $page = 1;//По умолчанию присваивю=1,т.к нуль вернет если ввели не число а строку
            }
        }
        else {
            /*Если $param['page'] не пришел т.е. не ввели в адр.стр. то тоже присваиваем =1*/
            $page = 1;//Первая страница
        }
        
        /*еще один параметр который будет передаваться формой поиска контроллеру-это сама строка запроса так как мы будем выводить
        с постраничной навига. то после того как мы перейдем на вторую страницу найденого,наши пост данные очистятся,
        поэтому нам прейдется вручную вводить поисковый запрос*/
        if(isset($param['str'])) {
            /*очистим эту строку и разшифруем ,так как она у нас будет зашифрована с помощью функции rawurl*/
            $this->str = rawurldecode($this->clear_str($param['str']));    
        }
        /*Теперь если даных нет в адресной строке,то мы ждем что они должны прийти,поэтому мы проверяем 
        Теперь принимаем данные который пользователь ввел в поисковый запро(не понятно а выше,что
        было)и так же проверяем и ичистим т.е. тут проверим данные пришли методом пост или нет
        в классе Base_Controller у нас есть метод is_post, который возвращает TRUE если массив передан
        методом POST и сохраняем в свойстве $str*/
        elseif($this->is_post()) {
            $this->str = $this->clear_str($_POST['txt1']);//txt1 -это из шаблона header в форме name        
        }
        
        //Теперь заголовок пристыкуем
        $this->title .= "Результаты поиска по запросу -".$this->str;
        
        //Теперь пристыкуем ключевые слова и описание
        $this->keywords .= "Поиск ishop";
        $this->description .= "Результаты поиска по запросу -".$this->str;
        
        /*Теперь когда мы получили параметры, необходимо создать обьект класса Peger и получить два массива,
        первый масив -это массив данных которые необходимо вывести на экран и второй массив - это массив ссылок 
        для вывода постраничной навигации*/
        $pager = new Pager(
                           $page,//это номер страницы которую необходимо открыть в данный момент
                           'tovar',//название таблицы в которой будем искать
                           array('publish'=> 1),//Фильтрация по полю publish его значение должно быть 1
                           'tovar_id',
                           'ASC',
                           QUANTITY,
                           QUANTITY_LINKS,
                           array("="),
                           array('title,text' => $this->str)//Это для полнотекстового поиска массив параметров                          
                           );
                           
        /*Проверим хранится ли в переменной $pager обьект,так как вдруг мы ошиблись при его создании и пе-
        редали не те параметры и конструктор не сработает и вернет нам не обьект,а что то другое*/
        if(is_object($pager)) {
            /*если да то обратимся к нашему свойству $navigation и присвоим отработку метода get_navigation()
            класса Pager*/
            $this->navigation = $pager->get_navigation();
            //Теперь сохраним массив данных которые необходимо вывести
            $this->search = $pager->get_posts();
            //Теперь зашифруем поисковый запрос что бы вывести в адресную строку
            $this->str = rawurlencode($this->str);
        }
        
        $this->need_right_side = FALSE;//Что бы не показывался правый блок, для этого шаблона он нам не нужен
                    
    }
    
    protected function output() {
        
        /*Сгенирируем центральную часть(шаблон) для вывода на экран*/
        $this->content = $this->render(VIEW.'search_page',array(
                                                                'search' => $this->search,
                                                                'navigation' => $this->navigation,
                                                                'str' => $this->str
                                                               ));
        
        $this->page = parent::output();
        return $this->page;    
    }    
}


?>