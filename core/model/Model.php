<?php
defined('SHOP') or exit('Access denied');
/*Создадим основную модель нашего сайта,модель это тот же класс мы его выполним по шаблону
сингл тон это хороший тон когда все основные классы делаются по этому шаблону*/
class Model {
    static $instance;
    
    public $ins_driver;/*Здесь будем хранить обьект класса Model_Driver его делаем публичным
    что бы можно было обратиться к методу в классе Model_Driver так как все классы моделей
    у нас будут самостоятельными и наследовать не будут*/
    
    //Опишем главный метод сингл тона
    static function get_instance() {
        /*Проверяем в свойстве $instance записан ли обьект класса Model*/
        if(self::$instance instanceof self) {
            return self::$instance;    
        }
        //Если условие не выполняется то создаем обьект
        return self::$instance = new self;
    }
    
    //Теперь опишем конструктор этого класса его сделаем закрытым,чтобы напрямую нельзя было создать обьект
    private function __construct() {
        /*В классе Base в методе input нам надо было создать обькт класса Model,
        логично создавая обьект сразу подключиться к базе данных,поэтому мы тут создадим обьект
        класса Model_Driver и он будет подключаться к нашей базе*/
        /*Обратимся к свойству $ins_driver и сохраним туда обьект класса Model_Driver только
        все это будем делать с проверкой на случай ошибки с помощью блока try*/
        try {
            $this->ins_driver = Model_Driver::get_instance();//Пытаемся выполнить это соединение    
        }
        //Если что то не так перехватим эту ощибку с помощью блока catch
        catch(DbException $e) {
            /*Создаем класс DbException и его обьект $e этот класс занимается обработкой 
            ощибок,с ним мы познакомимся позже*/
            exit();    
        }
        
    }
    
    /*Теперь займемся выводом наших блоков на экран,пока весь текст у нас прописан в шаблонах наших
    в html тегах,а нам надо что бы все выбиралось из базы данных.Поэтому пропишем метод который будет
    аваодить блок новостей,т.е. правый блок*/
    public function get_news() {
        //Создадим промежуточноу переменую $result и обратимся к методу select
        $result = $this->ins_driver->select(
                                            array('news_id','title','anons','date'),//передаем методу select параметры какие поля надо выбрать
                                            'news',//от куда выбрать- таблица
                                            array(),//Фильтрация не нужна-поэтому указываем просто пустой массив
                                            'date',//Сортировка по дате 
                                            'DESC',//в обратном порядке
                                            3//Лимит три                                            
                                           );
        /*Теперь чтобы анонс отображался не полностью аограниченое количество символов,например 255
        пройдемся по полученому массиву и обратимся к строке анонс и с помощью функции substr обрежим
        ее с 0 позиции и до 255*/
        $row = array();//Обьявим пустой массив,который будем формировать ниже в цикле
        foreach($result as $value) {
            $value['anons'] = substr($value['anons'],0,255);
            /*Теперь нам надо еще отрезать эту строку по последнему пробелу,чтобы слово не обрывалось на пол слове*/
            $value['anons'] = substr($value['anons'],0,strrpos($value['anons'],' '));//Ф-я ищет последний,в даном случае пробел
            
            $row[] = $value;        
        }
        
        return $row;//И возвращаем наш массив
    }
    
    /*Теперь займемся нашим левым блоком, сначала там идет список страниц,затем категории и
    затем бренды. Сначала опишем метод для вывода страниц-Левое меню*/
    /*Изменим наш метод для вывода страниц немного,чтобы ним можно было пользоваться и в админ части для вывода правой
    колонки,где выводятся все страницы сайта,т.к.в пользовательской части в левом меню не выводится главная страница
    то мы дадим нашему методу параметр $all и по умолчанию сделаем его FALSE,т.е. если false то будет для пользователь-
    ской части без главной страницы,а сли прийдет TRUE то будет для админки все страницы, это все мы делаем,чтобы не 
    писать еще один метод для вывода в правою колонку для админки,т.к. они очень похожи*/
    public function get_pages($all = FALSE) {
        
        /*теперь сделаем проверочку,если параметром пришло TRUE то будет выбирать для правой колонки админки,а если
        ничего не прищло или FALSE то для левой пользовательской*/
        if($all) {
              //если пришло TRUE то для админки
              $result = $this->ins_driver->select(
                                             array('pages_id','title','type'),
                                             'pages',
                                             array(),
                                             'position',//Сортируем по полю position
                                             'ASC'                            
                                           );     
        }
        else {
        /*Создаем промежуточную переменую,обращаемся к обьекту класса Model_Driver а это
        свойство ins_driber*/
        $result = $this->ins_driver->select(
                                             array('pages_id','title'),
                                             'pages',
                                             array('type' => "'post','contacts'"),/*Здесь фильтрация по полу type кроме 
                                             первго с типом home,т.к. Главная нам выводить не надо*/
                                             'position',//Сортируем по полю position
                                             'ASC',
                                             FALSE,//Ограничивать количество выборки не надо
                                             array("IN")                             
                                           );    
        }
       
        return $result;    
    }
    
    /*ТЕперь то же напишем для вывода типов товара */
    public function get_catalog_type() {
        $result = $this->ins_driver->select(
                                             array('type_id','type_name'),
                                             'type'
                                           );
        return $result;
    }
    
    //Теперь выведем бренды в левый сайт-бар
    public function get_catalog_brands() {
        $result = $this->ins_driver->select(
                                             array('brand_id','brand_name','parent_id'),
                                             'brands'
                                           );
        /*Тут надо наш многомерный массив преобразовать в нужный нам,т.к. тут еще есть и подкатегории,поэтому
        мы преобразуем в массив,где ключом будет номер родительской категории, а в поле массива добавим еще next_level*/
        //Создаем пустой массив в который будем сохранять преобразованый массив
        $arr = array();
        //Пройдемся в цикле по исходному массиву с проверкой что у него в ячейке parent_id
        foreach($result as $item) {
            if($item['parent_id'] == 0) {
                //parent. то мы берем наш массив $arr и создаем в нем ячейку с индефикатором нашей родит.категории
                //т.е. мы создаем новый массив у которого ключи это индефикаторы родительских категорий
                $arr[$item['brand_id']][] = $item['brand_name'];//Знак [] что это массив и даем значение этой ячейки
            }
            else {
                //child!!!
                /*Если нет то мы ищем по parent_id родительскую ячейку,попадаем туда,создаем там ячейку
                next_level указываем что эта ячейка является массивом и укзываем что ключом этого массива является
                его индефикатор его и указываем его значение brand_name*/
                $arr[$item['parent_id']]['next_level'][$item['brand_id']] = $item['brand_name'];
            }    
        }
        
        return $arr;
    }
    
    /*опишем метод который будет вытаскивать только родительские категории- это для выпадающего списка в админке*/
    public function get_parent_brands() {
        $result = $this->ins_driver->select(
                                            array('brand_id','brand_name'),
                                            'brands',
                                            array('parent_id' => 0)
                                            );
        return $result;    
    }
    
   //Теперь напишем метод для вывода индексной страницы для Index_Controllera
   public function get_home_page() {
        $result = $this->ins_driver->select(
                                            array('pages_id','title','text','keywords','description'),
                                            'pages',
                                            array('type'=>'home'),
                                            FALSE,
                                            FALSE,
                                            1
                                            );
         return $result[0];      
   }
   
   /*Напишем метод для выбора типов товара для меню шапки,там где они с картинками этот метод будет
   вызываться из класса Base а не Index_Controler,так как он общий для всех*/
   public function get_header_menu() {
        $result = $this->ins_driver->select(
                                            array('type_id','type_name'),
                                            'type',
                                            array('in_header' => "'1','2','3','4'"),
                                            'in_header',
                                            'ASC',
                                            4,
                                            array('IN')
                                           );
 /*Теперь нам надо чтобы надписи в меню шапки были все с большой буквы и каждое слово с новой строки
 поэтому пройдем циклом по нашему массиву и переведем текст в верхний регистр*/
        $row = array();
        foreach($result as $item) {
            $item['type_name'] = str_replace(" ","<br/>",$item['type_name']);//Это для перевода строки ф-я str_replace
            $item['type_name'] = mb_convert_case($item['type_name'],MB_CASE_UPPER,"UTF-8");
            $row[] = $item;    
        }       
        return $row;     
   }
   
   //Пропишем метод который выбирает одну контретную новость и вызывается в News_Controller
   public function get_news_text($id) {
        $result = $this->ins_driver->select(
                                            array('title','text','date','keywords','description'),
                                            'news',
                                            array('news_id' => $id)//Т.е.фильтруем по полу news_id и оно должно быть равно $id которую мы передали
                                           );
        return $result[0];//Нулевую ячейку масива резалт,просто что ы было красиву убрать ключ [0] 
   }
   
   /*Создадим метод get_page и он получит параметр $id из контроллера Page_Controller*/
   public function get_page($id) {
    //Создаем промежуточную переменную $result и обращаемся к обьекту класса Model_Draver и его методу select
    $result = $this->ins_driver->select(
                                         array('title','keywords','description','text'),
                                         'pages',
                                         array('pages_id' => $id)
                                       );
    return $result[0];
   }
   
   //Создадим метод для контроллера Contacts_Controller
   public function get_contacts() {
        $result = $this->ins_driver->select(
                                             array('pages_id','title','text','keywords','description'),
                                             'pages',
                                             array('type' => 'contacts')
                                           );
        return $result[0]; 
   }
   
   /*опишем метод для вывода всех индефикаторов дочерн.катег. это для Catalog_Controllera
   и в качестве параметра он будет получать $id родительск.категории передаваемой из Catalog_Controllera
   при обращении к этому методу*/
   public function get_child($id) {
        $result = $this->ins_driver->select(
                                            array('brand_id'),
                                            'brands',
                                            array('parent_id' => $id)
                                           );
        /*С проверкой если в $result есть true ,т.е. товар есть в базе то выполняем */
        if($result) {
             /*преобразуем получиный массив в более удобный для нас,для этого пройдемся по нему циклом
             и добавим в него ячейку и id родителя категории*/
             $row = array();
             foreach($result as $item) {
                $row[] = $item['brand_id'];    
             }
            //и добавим ячейку id родител
            $row[] = $id;//т.е.мы преобразовали массив $result в массив $row
            /*но это еще не все преобразуем наш массив в строку с помощью ф-ии implode,создадим
            промежуточную переменную и ф-ии передадим наш массив $row*/
            $res = implode(",",$row);
         
            return $res;  
        }
        /*если там false т.е.пользовать набрал например в адресной строке не существующие id,то*/
        else {
            return FALSE;
        }
   }
   
   /*опишем метод get_krohi - этот метод у нас будет не стандартны,он не будет обращаться к
   классу Model_Driver а будет работать с базой на прямую,мы в методе опишем sql запрос*/
   public function get_krohi($type,$id) {
        
        if($type == 'type') {
            $sql = "SELECT type_id, type_name
                    FROM type
                    WHERE type_id = $id";    
        }
        /*для брэнда запрос сложнеее так как там есть дочернии категории тут мы обьеденим два запроса
        сначала вытащим дочернии а потом у них будем искать родительское id первая часть запроса
        вернет нам родительскую id категории ,если конечно мы находимся на дочерней, а вторая часть
        уже вернет  либо родительскую категории если мы в ней находимся либо уже id по дочерней категории*/
        if($type == 'brand') {
            $sql = "(SELECT brand_id, brand_name
                    FROM brands
                    WHERE brand_id = (SELECT parent_id FROM brands WHERE brand_id = $id))
                    UNION
                    (SELECT brand_id, brand_name FROM brands WHERE brand_id = $id)";    
        }
        
        /*теперь выполним sql запрос,мы обратимся к обьекту Model_Driver к его свойству
        ins_db - это свойство является обьектом класса mysql и в нем есть метод query кото-
        рый и выполнит запрос*/
        $result = $this->ins_driver->ins_db->query($sql);
        
        //теперь как обычно проверим
        if(!$result) {
            //генерируем ошибку
            throw new DbException("Ошибка базы данных".$this->ins_driver->ins_db->errno."|".$this->ins_driver->ins_db->error);    
        }
        //теперь проверим количество полей выбоанное из базы данных
        if($result->num_rows == 0) {
            return false;        
        }
        
        /*теперь как и в классе Model_Drivers вытащим данные в цикле*/
        $row = array(); 
        for($i=0; $i < $result->num_rows; $i++) {
            $row[] = $result->fetch_assoc();//вытаскиваем как ассоциативный массив    
        }
        
        return $row; 
   }
   
   //Опишем метод для вывода полной информации о одном товаре
   public function get_tovar($id) {
        
        $result = $this->ins_driver->select(
                                             array('title','keywords','description','text','img','price'),
                                             'tovar',
                                             array('tovar_id' => $id, 'publish' => 1)
                                           );
        return $result[0];//ячейка [0] -это чтобы выбрать только одномерный массив 
   }
   
   /*опишем метод который будет выбирать данные для нашего прайс листа.Этот метод будет работат с базой данных
   не через класс Model_Driver а на прямую,так как тут будет сложный запрос,который класс Model_Driver
   сформировать не сможет в силу того как мы его описали*/
   public function get_pricelist() {
        $sql = "SELECT brands.brand_id,
                       brands.brand_name,
                       brands.parent_id,
                       tovar.title,
                       tovar.anons,
                       tovar.price
                       FROM brands
             LEFT JOIN tovar
                    ON tovar.brand_id=brands.brand_id
                 WHERE brands.brand_id
                    IN(
                       SELECT brands.parent_id FROM tovar
                       LEFT JOIN brands ON tovar.brand_id=brands.brand_id
                       WHERE tovar.publish='1')
                    OR brands.brand_id
                    IN
                       (SELECT brands.brand_id FROM tovar LEFT JOIN brands 
                         ON tovar.brand_id=brands.brand_id
                          WHERE tovar.publish='1')
                   AND tovar.publish='1'";
        /*теперь выполним этот запрос,обратившись к обьекту Model_Driver затем к обьекту MySql и его
        методу query*/
        $result = $this->ins_driver->ins_db->query($sql);
        
        //теперь проверим есть ли выборка и если нет сгенерируем исключение
        if(!$result) {
            throw new DbException("Ошибка подключения к базе : ".$this->ins_driver->ins_db->errno."|".
            $this->ins_driver->ins_db->error);
        }
        /*теперь проверим на количество выбранных полей имея ввиду,что переменная $result является
        теперь обьектом,*/
        if($result->num_rows == 0) {
            return FALSE;    
        }
        
        /*теперь нам осталось из нашей переменной $result в цикле выбрать данные*/
        $myrow = array();
        for($i = 0; $i < $result->num_rows; $i++) {
            $row = $result->fetch_assoc();
            
            /*проверим в цикле является категория родительской или дочерней т.е проверим если в ячейки
            выбраной первой строки в ячейки parent_id хранится записано 0 то значит это род*/
            if($row['parent_id'] === '0') {
                //и проверим есть ли товары в род.катег,т.е есть ли запись в ячейки 'tytle'
                if(!empty($row['title'])) {
                    //если товар есть то создадим массив
                    $myrow[$row['brand_id']][$row['brand_name']][] = array(
                                                                            'title' => $row['title'],
                                                                            'anons' => $row['anons'],
                                                                            'price' => $row['price']
                                                                          );    
                }
                /*если товара нет то мы создадим массив $myrow  ячейку с ключами значениям род.кат и в этой 
                ячейке создаем ячейку с еще одним массивов и в этом массиве ключем будет уже имя даной категории
                и значение этой ячейки - это пустой массив так как товара у нас нет,т.е. = array()*/
                else {
                    $myrow[$row['brand_id']][$row['brand_name']] = array();        
                }                       
            }
            
            else {
                //теперь разберемся с дочерними категориями
                $myrow[$row['parent_id']]['sub'][$row['brand_name']][] = array(
                                                                            'title' => $row['title'],
                                                                            'anons' => $row['anons'],
                                                                            'price' => $row['price']
                                                                          );       
            }              
        }         
        return $myrow; 
   }
   //////////////////////// АДМИНКА       АДМИНКА       АДМИНКА ////////////////////
   //опишем метод для админки,который будет добавлять страницы на сайт
   public function add_page($title,$text,$position,$keywords,$description) {
        
        /*вызываем обьект класса Moder_Driver и у него метод insert*/
        $result = $this->ins_driver->insert(
                                           'pages',//таблица куда добавляем
                                            array('title','text','position','keywords','description'),//поля куда будем доьавлять
                                            array($title,$text,$position,$keywords,$description)//данные которые вставляем                                 
                                            );
        return $result; 
   }
   
   //создадим метод который будет выбирать все данные по странице,по аналогии с методом get_page, только для админки
   public function get_page_admin($id) {
    //Создаем промежуточную переменную $result и обращаемся к обьекту класса Model_Draver и его методу select
   $result = $this->ins_driver->select(
                                         array('pages_id','title','keywords','description','text','position'),
                                         'pages',
                                         array('pages_id' => $id)
                                       );
   return $result[0];
   }
   
   //опишем метод который будет обновлять страницу в админке в Admin_Controler вызывается
   public function edit_page($id,$title,$text,$position,$keywords,$description) {
        /*вызываем обьект класса Moder_Driver и у него метод update*/
        $result = $this->ins_driver->update(
                                            'pages',
                                            array('title','text','position','keywords','description'),
                                            array($title,$text,$position,$keywords,$description),
                                            array('pages_id' => $id)
                                           );
        return $result;     
   }
   
   //опишем метод для админки,которая будет удалять страницу при нажатии на кнопку Удалить
   public function delete_page($id) {
        $result = $this->ins_driver->delete(
                                            'pages',
                                            array('pages_id' => $id)
                                           );
        return $result;
   }
   
   //опишем метод для добавления новостей в админке
   public function add_news($title,$anons,$text,$keywords,$description) {
        //и создаем переменную и обращаемся к методу класса Model_Draver
        $result = $this->ins_driver->insert(
                                           'news',
                                           array('title','anons','text','date','keywords','description'),
                                           array($title,$anons,$text,time(),$keywords,$description)
                                           );
        return $result;     
   }
   
   //Пропишем метод который выбирает одну контретную новость и вызывается в Editnews_Controller
   public function get_news_admin($id) {
        $result = $this->ins_driver->select(
                                            array('news_id','title','anons','text','date','keywords','description'),
                                            'news',
                                            array('news_id' => $id)//Т.е.фильтруем по полу news_id и оно должно быть равно $id которую мы передали
                                           );
        return $result[0];//Нулевую ячейку масива резалт,просто что ы было красиву убрать ключ [0] 
   }
   
   //опишем метод который изменяет новость и вызывается в Editnews_Controller
   public function edit_news($id,$title,$anons,$text,$keywords,$description) {
        
        $result = $this->ins_driver->update(
                                            'news',
                                            array('title','anons','text','date','keywords','description'),
                                            array($title,$anons,$text,time(),$keywords,$description),
                                            array('news_id' => $id)
                                           );
        return $result;      
   }
   
   //Опишем метод для удаления новостей
   public function delete_news($id) {
    $result = $this->ins_driver->delete(
                                        'news',
                                        array('news_id' => $id)
                                       );
    return $result;
   }
   
   //Опишем метод который добавляет категории из админки
   public function add_category($title,$parent) {
        $result = $this->ins_driver->insert(
                                            'brands',
                                            array('brand_name','parent_id'),
                                            array($title,$parent)
                                           );
        return $result; 
   }
   
   //метод который добавляет новый тип товаров,вызывается при отправке формы из Editcatalog_Controller
   public function add_new_type($new_type) {
        
        //нам нужно всавить одно поле в таблицу type и получить индефикатор созданого типа,поэтому
        $result = $this->ins_driver->insert(
                                            'type',//таблица
                                            array('type_name'),//поле в которое вставляем
                                            array($new_type),//что вставляем в это поле
                                            TRUE //для возврата индефикатора с. inssert в Model_Driver
                                            );
        return $result; 
   }
   
   //опишем метод который добавляет товар в базу
   public function add_goods($id,$title,$anons,$text,$img,$type,$publish,$price,$keywords,$description) {
        
        $result = $this->ins_driver->insert(
                                           'tovar',
                                           array('title','anons','text','img',
                                                 'brand_id','type_id','publish',
                                                 'price','keywords','description'),
                                           array($title,$anons,$text,$img,$id,
                                                 $type,$publish,$price,$keywords,$description)
                                           );
        return $result; 
   }
   
   //опишем метод который изменяет товар в админке
   public function edit_goods($id,$title,$anons,$text,$img,$type,$publish,$price,$category,$keywords,$description) {
    
        //сначала проверим менял ли пользователь изображение
        if($img) {
            $result = $this->ins_driver->update(
                                               'tovar',
                                               array('title','anons','text','img','type_id','publish',
                                                     'price','brand_id','keywords','description'),
                                               array($title,$anons,$text,$img,$type,$publish,$price,
                                                     $category,$keywords,$description),
                                               array('tovar_id' => $id)
                                                );
        }
        else {
            //т.е. если в $img пришло FALSE то без обновления ячейки img
            $result = $this->ins_driver->update(
                                               'tovar',
                                               array('title','anons','text','type_id','publish',
                                                     'price','brand_id','keywords','description'),
                                               array($title,$anons,$text,$type,$publish,$price,
                                                     $category,$keywords,$description),
                                               array('tovar_id' => $id)
                                               );             
        }
        //и возвращаем
        return $result;        
   }
                                                    
   //опишем метод который удаляет товар из базы в админке
   public function delete_tovar($id) {
        $result = $this->ins_driver->delete(
                                            'tovar',
                                            array('tovar_id' => $id)
                                           );
        return $result; 
   }                                                  
                                                        
   //опишем метод который вытаскивает товар для изменения в админке в Editcatalog
   public function get_tovar_admin($id) {
        $result = $this->ins_driver->select(
                                             array('tovar_id','title','keywords','description',
                                             'text','img','anons','brand_id','type_id','publish','price'),
                                             'tovar',
                                             array('tovar_id' => $id)
                                           );
        return $result[0];//ячейка [0] -это чтобы выбрать только одномерный массив      
   }
   
   //опишем метод который выбирает конкретнуюю категорию для редактирования
   public function get_category($id) {
        
        $result = $this->ins_driver->select(
                                            array('brand_id','brand_name','parent_id'),
                                            'brands',
                                            array('brand_id' => $id)
                                           );
        return $result[0]; 
   }
   
   //метод изменения категории в админке
   public function edit_category($title,$parent,$id) {
      
            $result = $this->ins_driver->update(
                                               'brands',
                                               array('brand_name','parent_id'),
                                               array($title,$parent),
                                               array('brand_id' => $id)
                                               );            
        return $result;
   }
   
   //метод удаления категории в админке
   public function delete_category($id) {
        $result = $this->ins_driver->delete(
                                            'brands',
                                            array('brand_id' => $id)
                                           );
        /*теперь нам надо у всех товаров этой категории изменить поле brand_id на значение "0"т.е. что товар без
        категории это для того чтобы он выводился в правом сайт-баре ,так как категорию мы удаляем а товар остается,
        поэтому чтобы в базе данных товар не ссылался на не существующую категорию сделаем так*/
        $result2 = $this->ins_driver->update(
                                             'tovar',
                                             array('brand_id'),
                                             array(0),
                                             array('brand_id' => $id)
                                            );        
        if($result) {
            if($result2) {
                return TRUE;
            }
            else {
                return $result2;
            }
        }
        else {
            return $result;
        } 
    }
    
    //щпишем метод который получает данные по конкретному типу товара для изменения типа
    public function get_type_admin($id) {
        $result = $this->ins_driver->select(
                                            array('type_id','type_name','in_header'),
                                            'type',
                                            array('type_id' => $id)
                                            );
        return $result[0];
    }
    
    //метод обновления типов товаров в админке
    public function edit_types($type_name,$in_header,$id) {
        $result = $this->ins_driver->update(
                                            'type',
                                            array('type_name','in_header'),
                                            array($type_name,$in_header),
                                            array('type_id' => $id) 
                                           );
        return $result;
    }
    
    //метод который удаляет типы товаров
    public function delete_types($id) {
        $result = $this->ins_driver->delete(
                                            'type',
                                            array('type_id' => $id)
                                           );
        return $result;
    }
    
    //Создадим метод,который будет обновлять сразу две записи - это для замены главной  страницы и страницы контактов
    /*этот метод будет уневерсальным для Главной страницы и для страницы Контактов.Параметрами сюда идет: $option- это
    тот параметр,который мы установим в поле type таблицы pages,т.е. если хотим изменить главную страницу то в $option
    прийдет home, вторым параметром прийдет новый индефикатор новой страницы -главнолй или страницы контактов,и третим параметром
    индефикатор старой страницы -главной или контактов */
    public function update_page_option($option,$new_id,$old = FALSE) {
        //сначала проверим установлена у нас вообще главная страница или страница контактов,т.е.проверим $old
        if(!$old) {
            //то сформируем sql запрос и установим
            $sql = "UPDATE pages SET type='$option' WHERE pages_id='$new_id'";
        }
        /*для случая когда была уже установлена Главная страница и страница Контактов пишем так, обновим две записи
        используя один sql запрос*/
        else {
            $sql = "UPDATE pages SET type = CASE
                    WHEN pages_id='$new_id' THEN '$option'
                    WHEN pages_id='$old' THEN 'post' END
                    WHERE pages_id IN('".$new_id."','".$old."')";    
        }
        //echo $sql;
        //exit();
        //и обращаемся к базе и выполняем запрос и сохраняем его в переменной
        $result = $this->ins_driver->ins_db->query($sql);
        //проверим успешно ли выполнен запрос и если нет сгенерируем исключение
        if(!$result) {
            throw new DbException("Ощибка базы данных: ".$this->ins_db->errno." | ".$this->ins_db->error);
            
        return FALSE;    
        }         
        return TRUE;    
    }                                                            
}

?>