<?php
defined('SHOP') or exit('Access denied');
/*Cоздадим класс Route_Controller который будет заниматься разбором адрессной строки
и формированием двух свойств класса Base_Controller таких как $controller и $params*/
class Route_Controller extends Base_Controller {
    /*Этот класс будем строить по шаблону Сингл Тон т.е. этот шаблон позволяет создавать
    только один обьект данного класса.Сначала создатим статическое свойство в котором 
    будет храниться обьект класса*/
    static $_instance;//Статические свойства и методы общие для всех обьектов класа
    /*Теперь создадим метод,который будет создавать обьект нашего класса*/
    static function get_instance() {
        //Сначала проверим что у нас хранится в свойстве  $_instance
        //К статическому свойству обращаются с помощью ключевого слова self:: а не $this 
        if(self::$_instance instanceof self) {
        /*конструкция instanceof проверяет хранится ли в свойстве $_instance обьект класса
        а ключевое слово self указывает на текущий класс*/
        //если вернет истина то мы просто вернем его
        return self::$_instance;    
        }
        //если вернет false,то создадим и вернем обьект
        return self::$_instance = new Route_Controller;// можно написать new self   
    }
    
    //Создадим конструктор класса с доступом приват иначе в классе созданом по шаблону
    //сингл тон он работать не будет
    private function __construct() {
       
       //Нам надо разобрать адресную строку, создадим вспомогательную переменную
       //и обратимся к суперглобальному массиву $_SERVER там есть ячека с ключем
       //REQUEST_URI где и хранится адрес без домена
       $zapros = $_SERVER['REQUEST_URI'];
       /*Теперь нам надо проверить правильно ли пользователь ввел адресс сайта,т.е. что то что хранится в 
       ячейки PHP_SELF совпадало с нашей константой define('SITE_URL','/ishop/ из файла config.php
       но сначала нам надо отрезать правую часть из того что хранится в ячейки PHP_SELF чтобы
       остался только папка сайта без следующего пути /index.php/id=3 и т.д.*/
       //Используя метод substr отрезания строки
       $path = substr($_SERVER['PHP_SELF'],0,strpos($_SERVER['PHP_SELF'],'index.php'));
       /*Функция strpos ищет позицию вхождения строки в строкут.е. первым параметром мы ей
       передаем в какой строке искать, а вторым параметром до какого слова в данном случае index.php
       так как нам надо оставить только левую часть*/
       
       //Теперь проверим равно ли значение $path со значением хранящимся в константе SITE_URL
       if($path===SITE_URL) {
       //Если совпадает,то заполним свойство $request_url из Base_Conrjller т.е. еще отрежим в адрессе и папку с сайто ishop
            $this->request_url = substr($zapros,strlen(SITE_URL));
       //Теперь полученную строку рабобьем на массив для этого используем функцию explode,которая
       //используя какойто разделитель,в нашем случае '/' трансформирует строку в массив а вторым параметром 
       //передаем саму строку $this->request_url
       $url = explode('/',rtrim($this->request_url,'/'));//rtrim-функция которая убирает последний пробел в строке а также то что ьы дадим ей в третем параметре'/'
       /*В ячейке "0" массива $url у нас будет храниться имя контролера, в нечетных ячейках параметры а в четных их значения
       Сначала проверим не пустая ли у нас нулевая ячейка,если нет то так как у нас все имена контролеров начинаются с большой буквы
       то нам надо переписать название ячейки ,0, чтобы начиналась с большой буквы с помощью функции ucfirst и сохранить в нашем свойстве
       $controller из класса Base_Controller было записано уже с большой*/
       if(!empty($url['0'])) { 
          $this->controller = ucfirst($url['0'].'_Controller'); 
          }
          /*Если же ячейка пустая то нам надо подгрузить индексный контролер,чтобы открылась тогда индексная страница
          поэтому мы опять обращаемся к свойству controller*/
          else {
            $this->controller = "Index_Controller";
          }
          /*Теперь займемся заполнением свойства $params из класса Base_Controller,так как они теперь хранятся в массиве $url,
          поэтолму нам надо пройтись циклом по массиву $url*/
          //Сначала с помощью функции count посчитаем количество элементов в массиве $url и сохраним их в переменой какой то
          $count = count($url);
          /*Теперь проверим есть ли у нас параметры,т.е. существует ли в массиве $url ячейка с номером "1"*/
          if(!empty($url[1] )) {
            //Если не пуста то циклом пройдем
            $key = array();//Обьявим массив где будем хранить параметры после цикла ниже
            $value = array();//Обьявим массив,где будем хранить значения этих параметров после прохождения цикла for ниже
            for($i=1;$i < $count;$i++) {
                //Теперь нам надо отделить четные ячейки от не четных, в PHP есть такая озможность которая проверяет
                //остаток от деления
                if($i%2 !=0) {
                  //если остаток от деления не равен нулю то есть не четная
                  $key[] = $url[$i];  
                }
                else {
                  $value[] = $url[$i];
                }
            }
            /*У нас поллучилось два массива, но былобы удобно еслибы был один результирующий -где ключ это
            имя параметра а значение -его значение.Для это воспользуемся функцией array_combine*/
            if(!$this->params = array_combine($key,$value)) {
            /*Но это все сделаем спроверкой на случай если пользователь не ввел значение параметра и у нас
            не получился бы массив с пустой ячейкой*/                 
                throw new ContrException("Не правильный адресс",$zapros);//Сгенерируем исключение для класса ContrException который пропишем когда будем писать для обработки ошибок                   
            }            
          }            
       }
       //если нет то мы сгенерируем исключение 
       else {
        try {
            throw new Exception('<p style="color:red">Не правильный адресс сайта.</p>');
        }
        //Опишем блок catch который перехватит это сообщение Exception
            catch(Exception $e) {
                echo $e->getMessage();//В переменой $e хранится обьект класса Exception,а в методе getMessage наше сообщение
                exit();
            }
       }
         
    }
}

?>