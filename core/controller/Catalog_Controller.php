<?php
defined('SHOP') or exit('Access denied');
class Catalog_Controller extends Base {
    
    protected $type = FALSE;//Здесь будем хранить тип товара который надо вывести
    
    protected $id = FALSE;//Здесь будем хранить индефикатор или типов товаров или категорий товара
    
    protected $parent = FALSE;//Тут будет если надо вывести все товары данной категории,т.е. если прийдет parent
    
    protected $navigation;//тут будет храниться массив ссылок для постранич. навига
    
    protected $catalog;//здесь массив данных (товаров) для вывода на экран
    
    protected $krohi;//массив хлебных крох
    
    protected function input($param = array()) {
        parent::input();
        /*Какие параметры будет принимать этот контроллер- очевидно параметр $page для постраничной еввигации
        дальше тип товара-brand,type либо parent т.е. какой тип товаров выводить. */
        
        $this->title .= "Каталог";
        
        //Закрываем правый блок
        $this->need_right_side = FALSE;
        
        /*Теперь проверим а есть ли в массиве $param ячейка brand, и если есть то мы присвоим строку "brand"*/
        if(isset($param['brand'])) {
            $this->type = "brand";
            
            //примем также id и очистим его,так как он приходит из адресной строки
            $this->id = $this->clear_int($param['brand']);    
        }
        /*теперь если пришел не brand а type, т.е. если счелкнул пользователь по категории товара*/
        elseif(isset($param['type'])) {
            $this->type = "type";//т.е тогда в свойство $type сохраним строку type
            
            //и для его параметра id запишем
            $this->id = $this->clear_int($param['type']);         
        }        
        //если не брэнд и не тип то возможно парент т.е.
        elseif(isset($param['parent'])) {
            /*Т.К по умолчанию свойство $parent у нас FALSE а тут мы проверили и он прищел то присвоим ему TRUE*/
            $this->parent = TRUE;//так как тут нам надо будет получать индеф. всех дочерних катег.,поэтому 
            $this->id = $this->clear_int($param['parent']);                    
        }
        
        /*теперь примем параметр $page т.е номер страницы для отображения,и как обычно*/
        if(isset($param['page'])) {
            $page = $this->clear_int($param['page']);//так же с очисчением
            if($page == 0) {
                $page = 1;
            }
        }
        else {
            $page = 1;//т.е если не пришел то по умолчанию присваиваем 1        
        }                
        
         /*так как в свойстве $type у нас хранится либо строка brand, либо type и по умолчанию оно FALSE
        то если пользователь что то выбрал то туда запишется или то или другое и оно уже не будет FALSE
        поэтому проверим и создадим обьект класса Pager и передадим ему параметры для конструктора класса Pager*/
        if($this->type) {
            /*опять же проверим,вдруг пользаваль вместо id ввел какуюто строку и после очистки в параметр
            id запишется ноль и выскочит ошибка,поэтому проверим и если ноль то выйдем*/
            if(!$this->id) {
                return;    
            }
            
            $pager = new Pager(
                                $page,//номер страницы которую необходимо отобразить
                                'tovar',//таблица из которой выбираем данные
                                array($this->type.'_id'=>$this->id,'publish'=>1),
                                'tovar_id',//сортировка по индефикатору
                                'ASC', //в прямом направлении 
                                QUANTITY,
                                QUANTITY_LINKS                                                                               
                               );
            /*для вывода хлебных крох обратимся к модели и передадим параметры,т.е. либо brand
            либо type и вторым параметром индефикатор категории*/                
            $this->krohi = $this->ob_m->get_krohi($this->type,$this->id);
            
            //сформируем кл.слова и описание из нашего массива $this->krohi
            $this->keywords = $this->krohi[0][$this->type.'_name'].','.$this->krohi[1]['brand_name'];
            $this->description = $this->krohi[0][$this->type.'_name'].','.$this->krohi[1]['brand_name'];         
        }
        /*теперь если не type и не brand то возможно parent поэтому проверим и запишем*/
        elseif($this->parent) {
            /*сначала проверим какое значение сейчас в id если там не id а какаято строка то return*/
            if(!$this->id) {
                return;
            }
            /*если есть то нам надо выбрать все индефикаторы дочерних категорий,поэтому создадим
            промеж.перем.$ids и обратимся к обьекту модели и его методу get_child,который возвращает
            эти индефикаторы*/
            $ids = $this->ob_m->get_child($this->id);//параметром передаем индефикат родител.категории    
        
        /*теперь получив в переменную $ids строку с дочерними индефикаторами,опять же создаем
        обьект класса Pager и передаем ему параметры,но так же с проверкой на случай если $ids false см.выше*/
        if(!$ids) {
            return;//т.е.выходим из данного метода input  
        }
        $pager = new Pager(
				           $page,
				           'tovar',
				           array('brand_id' => $ids,'publish'=>1),
				           'tovar_id',
				           'ASC',
			               QUANTITY,
			               QUANTITY_LINKS,
				           array("IN","=")					
				           );                      
        /*теперь сохраним в свойстве $this->type строку parent что бы она в конце добавлялась в
        адресной строке для постраничной навигации после $previous*/
        $this->type = "parent";
        
         /*для вывода хлебных крох обратимся к модели и передадим параметры,т.е. либо brand
         либо type и вторым параметром индефикатор категории*/                
        $this->krohi = $this->ob_m->get_krohi('brand',$this->id);
            
         //сформируем кл.слова и описание из нашего массива $this->krohi
         $this->keywords = $this->krohi[0]['brand_name'];
         $this->description = $this->krohi[0]['brand_name'];         
        }
               
       /*теперь займемся выводом всех товаров,т.е.сделаем рабочей нашу ссылку Каталог товаров
       т.к.когда мы выводим весь список товаров мы контроллеру Catalog ничего не передаем,то есть
       будем проверять чтобы не было не $this->type не $this->parent*/
       elseif(!$this->type && !$this->parent) {
        //то мы просто создаем обьект    
             $pager = new Pager(
                                $page,//номер страницы которую необходимо отобразить
                                'tovar',//таблица из которой выбираем данные
                                array('publish'=>1),
                                'tovar_id',//сортировка по индефикатору
                                'ASC', //в прямом направлении 
                                QUANTITY,
                                QUANTITY_LINKS                                                                               
                               );
             //опишем хлебюкрошки для ссылки Каталог товаров,т.е. для всех.Тут пропишем стационарно
             $this->krohi[0]['brand_name'] = "Каталог";
             
             //сформируем кл.слова и описание для ссылки Каталог товаров
             $this->keywords = "ishop, Каталог товаров";
             $this->description = "ishop, Каталог товаров";                    
        } 
       
       
        /*теперь нам надо для этого обьекта вызвать два метода класса Pager на получение массива данных
        и массива ссылок по тем параметрам что мы передали этому обьекту,сделаем это с проверкой является 
        ли $pager обьектом*/
        if(is_object($pager)) {
            $this->navigation = $pager->get_navigation();
            $this->catalog = $pager->get_posts();        
        }        
      }     
   
    
    protected function output() {
        
        /*Теперь что бы наши ссылки в постраничной навигации передавали тип товара или parent или 
        бренд,по аналогии со строкой запроса в Search_Controllere($str), мы создадим переменную и проверим
        есть ли эти параметры,и если есть то запишем в переменную строкой эти параметры т.е. 
        формируем дополнительную строку запроса'/type/2' и передадим нашим ссылкам в шаблон*/
        //т.е.мы сохраняем параметры которые уже были переданы контроллеру,для перехода на след.стр.
        $previous = FALSE;
        if($this->type && $this->id) {
            $previous = "/".$this->type."/".$this->id;    
        }
        
        
        $this->content = $this->render(VIEW.'catalog_page',array(
                                                                 'catalog' => $this->catalog,
                                                                 'navigation' => $this->navigation,
                                                                 'previous' => $previous,
                                                                 'krohi' => $this->krohi
                                                                ));    
        
        $this->page = parent::output();
        return $this->page;    
    }    
}



?>