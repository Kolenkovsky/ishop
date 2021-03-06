<?php
defined('SHOP') or exit('Access denied');
/*Этот класс будет получать данные от класса Model что надо выбрать ,как отсортировать
 формировать запрос к базе данных, подключаться к ней и отдавать уже выбранные данные 
 обратно в класс Model, класс Model_Driver как бы промежуточный один раз тут пропишем 
 те функции которые повторяются при запросах чтобы не повторять их,т.к. запросов у нас
 будет много, он будет формировать основные запросы,а если надо будет какой то сложный 
 не стандартный запрос то мы его пропишем прямо в классе Model минуя этот класс*/
 class Model_Driver {
    
    static $instance;
    
    public $ins_db;/*Здесь будем хранить обьект класса mysqui, т.к. мы будем пользоваться
    расширением mysqui*/
        
    //Опишем главный метод сингл тона
    static function get_instance() {
        /*Проверяем в свойстве $instance записан ли обьект класса Model*/
        if(self::$instance instanceof self) {
            return self::$instance;    
        }
        //Если условие не выполняется то создаем обьект
        return self::$instance = new self;
    }
    
    //Опишем конструктор,так же логично присоздании обьекта подключиться к базе
    public function __construct() {
    
    /*Подключимся к базе данных с помощью расширения mysquli,т.е. создадим обьект этого 
    класса и передадим его конструктору mysquli класса где в качесвте параметров наши 
    константы из config файла для подключения*/
        $this->ins_db = new mysqli(HOST,USER,PASSWORD,DB_NAME);
        /*Если произошла какая то ошибка то она попадает в спициальное
        обьекта $this->ins_db  свойство connect_error,поэтому сделаем проверку*/
        if($this->ins_db->connect_error) {
            /*Формируем исключение если есть ошибка,пристыковываем номер ошибки и сообщение обращаясь к свойствам обьекта $this->ins_db
            но сервер может отдавать сообщение об ошибке в кодеровке СР1251 , а у нас все в UTF-8, поэтому нам надо
            преобразовать строку об ошибке в UTF-8,для этого воспользуемся ф-ей iconv(),где первым параметром передается
            из какой кодировки,вторым в какую кодировку и третьим саму строку сообщения*/            
            throw new DbException("Ощибка соединения : ".$this->ins_db->connect_errno."|".iconv("CP1251","UTF-8",$this->ins_db->connect_error));    
        }
        
        //Установим кодировку запроса с помощью метода query(запрос)
        $this->ins_db->query("SET NAMES 'UTF8'");            
    }
    
    //Теперь перейдем к описанию методов,которые будут формировать запросы у базе
    //Сначала мы себе где выписали все запросы какие могут быть у нас
    
    /*$param это параметр который мы передаем нашему методу,тут будет массив какие поля
    надо выбрать,этот массив будет передаваться из класса model когда ьы будем вызывать
    метод select, параметр $table- это от куда будем выбирать,массив $where-это массив 
    фильтрации и по умолчанию мы его прировняли к пустому массиву. Параметр $order- это
    сортировка, переменная $napr- направление сортировки и по умолчанию ASC,параметр $limit
    это если необходимо лимитировать количество выборки-изначельно FALSE.В $operand будет
    храниться оператор для фильтрации(либо равно,либо >)по умолчанию он '=', и оператор
    $match будет нас информировать нужно ли формировать запрос для полнотекстового поиска*/
    public function select(
                           $param,
                           $table,
                           $where=array(),
                           $order=FALSE,
                           $napr='ASC',
                           $limit=FALSE,
                           $operand=array('='),
                           $match=array()
                           ) {
        /*Теперь нам надо сформировать запрос,который будет выполнен на сервере базы даных,
        с помощью цикла пройтись по этому запросу и сформировать массив данных который будет
        возвращаться в класс Model*/
        $sql = "SELECT";//Первое слово в строке запроса
        //массив $param вытаскиваем как переменую $item
        foreach($param as $item) {
            //Теперь будем делать строку пристыковывая
            $sql .= ' '.$item.',';
        }
        //Теперь уберем не нужную нам запитую в конце строки запроса используя функцию rtrim
        $sql = rtrim($sql,',');
        
        //Теперь добавим от куда надо делать выборку
        $sql .= ' '.'FROM'.' '.$table;//В переменной $table хранится имя таблицы 
        
        //Проверим нужно ли нам фильтровать данные
        //Теперь добавим сортировку с проверкой, есть ли в массиве $where какие то ячейки
        if(count($where) > 0) {
            $ii = 0;//Вспомогательная перемен.чтобы знать иттерацию цикла и на втором шаге цикла Where не вызывать
            foreach($where as $key=>$val) {
                if($ii == 0) {//Т.е.на первом шаге операции(strtolower($key)-переводит в нижний регистр)
                    /*Тут проверим существует ли оператор IN,т.е.если массив $operand с индексом $ii
                    равен 'IN' то сформируем запрос немного другого вида*/                    
                    if($operand[$ii] == 'IN') {
                        $sql.= ' WHERE '.strtolower($key)." ".$operand[$ii]."(".$val.")";        
                    }
                    else {
                        $sql .= ' '.' WHERE '.strtolower($key).' '.$operand[$ii].' '."'".$this->ins_db->real_escape_string($val)."'";
                    //а real_escape_string- делает защиту от sql инекций    
                    }
                   
                }
                /*Для второго и последующих проходов цикла у нас вместо WHERE добавится AND*/
                if($ii > 0) {
                    
                    if($operand[$ii] == 'IN') {//По аналогии как выше                        
                        $sql.= ' AND '.strtolower($key)." ".$operand[$ii]."(".$val.")";    
                    }
                    else {
                         $sql .= ' '.' AND '.strtolower($key).' '.$operand[$ii].' '."'".$this->ins_db->real_escape_string($val)."'";    
                    }
                       
                }
                
                $ii++;//Увеличиваем нашу переменную на один 
                
                /*Теперь проверим на случай если количество оперантов  меньше чем количество
                иттерации, то мы присвоим ему предыдущее значение,какое было при придыдущей иттерации*/
                if((count($operand)- 1) < $ii) {
                    $operand[$ii] = $operand[$ii-1];   
                }  
            }
            /*Теперь займемся добавлением если нужен полнотекстовый поиск,сначала проверим есть ли
            у нас ячейки в масиве $match по аналогии с тем что выше*/
            }
            if(count($match) > 0) {
                //То проходимся циклом по массиву
                foreach($match as $k=>$v) {
                    //Опять же проверим есть ли ячейки $Where,т.е.нужно WHERE или AND и
                    if(count($where) > 0) {
                        $sql.= " AND MATCH (".$k.") AGAINST('".$this->ins_db->real_escape_string($v)."')";        
                    }
                    elseif(count($where) == 0) {
                        $sql.= " WHERE MATCH (".$k.") AGAINST('".$this->ins_db->real_escape_string($v)."')";    
                    }        
                }                
            }
            
            /*Теперь осталось еще LIMIT и сортировка.сначала проверим есть ли ORDER так как по
            умолчанию этот параметр FALSE*/
            if($order) {
                $sql.= " ORDER BY ".$order." ".$napr." ";    
            }
            
            /*То же для LIMIT по умолчанию она тоже FALSE*/
            if($limit) {
                $sql .= " LIMIT ".$limit; 
            }
            
            /*Запрос мы сформировали,теперь его необходимо выполнить вызвав функцию quary.
            которая выполняет запрос,поэтому формируем переменную $rezult*/
            $result = $this->ins_db->query($sql);
            
            /*Опять же проверим на случай если что то не пошло и если нет то сгенерируем исключение*/
            if(!$result) {
                throw new DbException("Ошибка запроса".$this->ins_db->connect_errno."|".$this->ins_db->connect_error);       
            }
            
            /*Теперь еще сделаем такую проверочку,посчитаем сколько полей нам вернула база данных*/
            if($result->num_rows == 0) {
                return false;//Т.е если выбрала ноль поле то возвращаем FALSE    
            }
            
            /*И последнее, пройдемся циклом.И что бы последовательно выбрать все поля выбраной записи воспользуемся
            методом fetch_assos который сформирует нам ассотиативный массив*/
            for($i = 0; $i < $result->num_rows; $i++) {
                $row[] = $result->fetch_assoc();
            }    
                   
            //И возвращаем наш массив
            return $row;
    }
    
    /*Теперь займемся описанием SQL запроса для админки, и первым опишем метод Delet для удаления
    каких то данных из базы данных, параметрами необходимыми будет название таблицы,вторым будет
    массив условие, WHERE id=3, например ,и третьим массив операнд-по умолчанию он =*/
    public function delete($table,$where = array(),$operand = array('=')) {
        // DELETE FROM brands WHERE brands_id<=3 для нвглядности(такой будет sql запрос)
        /*создадим переменую sql где будет храниться начало строки запроса, и дальше будем ей пристыковывать
        продолжение*/
        $sql = "DELETE FROM ".$table;
        
        //теперь проверим ,действительно ли у нас в переменой where хранится массив используя ф-ю is_array
        if(is_array($where)) {
            
            $i = 0;//создадим начало для счетчика чтобы пройтись по массиву циклом
            
            foreach($where as $k => $v) {
                //и пристыковываем дальше строку запроса
                $sql .= ' WHERE '.$k.$operand[$i]."'".$v."'";
                
                $i++;//увеличиваем на 1
                
                /*теперь проверим сколько ячеек у нас хранится в масиве operand чтобы,если не хватает знаков
                количеству выводимых данных то чтобы брался последний операнд какой был на предыдущей иттерации цикла*/
                if((count($operand)- 1) < $i) {
                    $operand[$i] = $operand[$i-1];   
                }   
            }
                
        }
         //echo $sql;
         //exit();
        /*теперь формируем переменкю резалт и обращаемся к обьекту класса mysqli и его методу query,
        который и выполняет запрос по аналогии все с методом stlect*/
        $result = $this->ins_db->query($sql);
        
        /*теперь сделаем проверку успешно ли выполнился запрос к базе данных и если нет то сгенерируем исключение*/
        if(!$result) {
            throw new DbException("Ощибка базы данных: ".$this->ins_db->errno." | ".$this->ins_db->error);
            
            return FALSE;    
        }
        
        return TRUE;   
    }
    
    //теперь опишем метод для добавления информации
    public function insert($table,$data =array(),$values =array(),$id=FALSE) {
        //$sql = "INSERT INTO brands (,brand_name,parent_id) VALUES ('TEST','0')"- это для наглядности такой будет запрос
        //по анологии с delete создаем строку запроса поэтапно пристыковывая
        $sql = "INSERT INTO ".$table." (";
        
        /*чтобы не проходится циклом по масиву data мы воспоспользуемся ф-ей implode, которая разбивает
        наш массив в  строку с разделителем,который ей укажем*/
        $sql .= implode(",",$data).")";
        
        //теперь дальше
        $sql .= ' VALUES (';
        
        //а тут пройдемся циклом т.к. нам надо добавить кавычки а с ф-ей implode добавлять не получится
        foreach($values as $val) {
            $sql .="'".$val."'".",";           
        }
        /*т.к. у нас появится лищняя запятая в конце,чтобы ее убрать воспользуемся ф-ей rtrim,
        которая убирает последний пробел в строке или последний символ который ей укажем*/
        $sql = rtrim($sql,',').")";
        
        //выполним запрос
        $result = $this->ins_db->query($sql);
        
        //проверим успешно ли выполнен запрос и если нет сгенерируем исключение
        if(!$result) {
            throw new DbException("Ощибка базы данных: ".$this->ins_db->errno." | ".$this->ins_db->error);
            
            return FALSE;    
        }
        /*теперь проверим указывал ли пользователь третим параметром в метод insert индефикатор и если
        да то выведем его,т.е. если true, с помощью свойства insert_id класса mysqli*/
        if($id) {
            return $this->ins_db->insert_id;    
        }       
                
        return TRUE;
    }
    
    //теперь создадим метод который обновляет данные в базе
    public function update($table,$data = array(),$values = array(),$where = array()) {
    //для наглядности напишем какого вида должен быть sql запрос
    //$sql ="UPDATE brands SET brand_name = 'TEST1',parent_id = 1 WHERE brand_id = 28";
    /*так как у нас массив $data и массив $values равные по количеству элементов,то мы можем воспользоваться
    ф-ей array_combain и слить их,где ключами будут из массива data а значения из массива values*/
    $data_res = array_combine($data,$values);
    
    //формируем sql запрос
    $sql = "UPDATE ".$table." SET ";
    
    //пройдемся циклом по слитому массиву
    foreach($data_res as $key => $val) {
        $sql .=$key." = '".$val."',";
    }
    //избавимся от последней запятой
    $sql = rtrim($sql,',');
    
    //теперь опишем условие WHERE т.к.это массив то циклом
    foreach($where as $k => $v) {
        $sql .= " WHERE ".$k." ="." '".$v."'";    
    }
    //echo $sql;
    //exit();
    //выполним запрос
    $result = $this->ins_db->query($sql);
        
    //проверим успешно ли выполнен запрос и если нет сгенерируем исключение
    if(!$result) {
        throw new DbException("Ощибка базы данных: ".$this->ins_db->errno." | ".$this->ins_db->error);
            
        return FALSE;    
    }                         
    return TRUE;       
    }
    
 } 

?>