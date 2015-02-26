<?php
defined('SHOP') or exit('Access denied');
/*создадим контролер,который будет заниматься  новостями в админке- добавлять, редактировать, и удалять новости*/
class Editnews_Controller extends Base_Admin {
    
    protected $news;//здесь будет масссив всех новостей которые надо для одной страницы,
    
    protected $navigation;//здесь массив новостей постраничной навигации
    
    protected $option = 'add';//Здесь будет оператор  который будет указывать какое действие необходимо,добавление или изменение новости
    
    protected $news_text;//Здесь будет массив контректной новости по принятому id
    
    protected $data;//Здесь будет храниться массив данных которые пользователь ввел в форму,т.е.сессия
    
    //переопределяем как всегда методы input output
    protected function input($param = array()) {
        
        parent::input();
       
       $this->title .= "Добавление новостей";
       ////////////////////////////////
       /*теперь проверим данные пришли методом POST или нет и если да то создадим переменные и будем им присваивать
       значения соответствующих ячеек массива $_POST*/
       if($this->is_post()) {
            $id = $this->clear_int($_POST['id']);
            $title = $_POST['title'];
            $anons = $_POST['anons'];
            $text = $_POST['text'];
            $keywords = $_POST['keywords'];
            $description = $_POST['description'];
            
            //Проверим на заполнение обьязательных полей 
            if(!empty($title) && !empty($anons) && !empty($text)) {
                //тогда проверим нажата ли кнопка добавить,т.е. есть ячейка в массиве POST['add_news_x']
                if($_POST['add_news_x']) {
                    //то мы создаем переменную и сохраняем там отработку метода модели
                    $result = $this->ob_m->add_news($title,$anons,$text,$keywords,$description);
                    //и теперь проверим если метод вернул TRUE или FALSE то сохраним соответствующие сообщения
                    if($result === TRUE) {
                        $_SESSION['message'] = "Новости успешно добавлены!";
                        
                          
                    }
                    else {
                        $_SESSION['message'] = "Ошибка: новость не добавлена!";    
                    }
                    //теперь когда все отработало и новость добавлена сделаем редирект и обнулить данные POST
                    header("Location:".SITE_URL.'editnews');
                    exit();    
                }
                
                /*теперь для случая когда нажата кнопка Обновить, т.е. для редактирования новости*/
                if($_POST['edit_news_x']) {
                    //то мы создаем переменную и сохраняем там отработку метода модели
                    $result = $this->ob_m->edit_news($id,$title,$anons,$text,$keywords,$description);
              
                    //теперь проверим что нам вернула $result и запишем сообщение
                    if($result === TRUE) {
                        $_SESSION['message'] = "Новость успешно обновлена";    
                    }
                    else {
                        $_SESSION['message'] = "Ошибка: новость не обновилась!";
                    }
                    //теперь когда все отработало и новость добавлена сделаем редирект и обнулить данные POST
                    header("Location:".SITE_URL.'editnews/id/'.$id);
                    exit();       
                }   
            }
            //Если не все поля заполнены так же дадим сообщение
            else {
                $_SESSION['message'] = "Заполните все поля!<br />";
                //проверим по полям отдельно каждое,и если поле не пустое то сохраним в сессии то что было в поле
               	if(empty($title))  {
					$_SESSION['message'] .= "Заполните заголовок<br />";//если пустое
				}
				else {
					/*если не пустое то массиве SESSION сохраним ячейку data и в ней ячейку title и там сохраним то,что
                    было введено в поле что бы поле не пропало при обновлении и заново не вносить*/
                    $_SESSION['data']['title'] = $title;
				}
				if(empty($anons))  { 
					$_SESSION['message'] .= "Заполните анонс<br />";//тоже для анонса
				}
				else {
					$_SESSION['data']['anons'] = $anons;//если не пустое то сохраним в сессии
				}	
				if(empty($text))  { 
					$_SESSION['message'] .= "Заполните text<br />";
				}	
				else {
					$_SESSION['data']['text'] = $text;//и для текста так же
				}
                //поля keywords и discription не обьязательные,поэтому без проверки,прросто сохраним в сессии
				$_SESSION['data']['keywords'] = $keywords;
				$_SESSION['data']['description'] = $description;
                /*и теперь в свойстве data сохраним масссив всех данных которые были внесены в поля и теперь при перезагрузке
                страницы если какое то поле было пустое,то заполненные поля останутся и мы их выведем в шаблоне,поэтому
                мы данное свойство передадим шаблону*/
				$this->data = $_SESSION['data'];
                
                if($_POST['add_news_x']) {
                    header("Location:".SITE_URL.'editnews');
                    exit();    
                }
                elseif($_POST['edit_news_x']) {
                    header("Location:".SITE_URL.'editnews/id/'.$id);
                    exit();
                }			
            }
        }
        ////////////////////////
        
       /////////////////   ОПИШЕМ КОД КОТОРЫЙ БУДЕТ ИЗМЕНЯТЬ НОВОСТИ //////////////////////////////
       /*ОПИШЕМ КОД КОТОРЫЙ БУДЕТ ПРИНИМАТЬ ПАРАМЕТР ID ВЫБРАННОЙ НОВОСТИ*/
       if(isset($param['id'])) {
            //очистим и сохраним в переменную
            $id = $this->clear_int($param['id']);
            
            //то запишем в наше свойство данные по данной новости
            $this->news_text = $this->ob_m->get_news_admin($id);
            
            /*теперь перезададим  какое действие необходимо делать по параметру option*/
            $this->option = 'edit';
           
            /*Для удаления стр.проверим существует ли параметр option со значением delete*/
            if($param['option'] == 'delete') {
                $result = $this->ob_m->delete_news($id);
                
                if($result === TRUE) {
                    $_SESSION['message'] = "Новость успешно удалена!";                        
                }
                else {
                    $_SESSION['message'] = "Ошибка: новость не удалена!";    
                }
                //теперь когда все отработало и новость добавлена сделаем редирект и обнулить данные POST
                header("Location:".SITE_URL.'editnews');
                exit();    
            }                     
       }       
      
            /////////////// П Р А В Ы Й     Б Л О К ///////////////////////////////
        
        /*Мы должнв принять параметр page, который указывает какую имено страничку надо отобразить.Т.к.мы правый блок с 
        новостями будем выводить с помощью постраничной навигации, поэтому проверим*/
        if(isset($param['page'])) {
            //то мы присвоим созданой переменой page значение этой ячейки -очищенное
            $page = $this->clear_int($param['page']);
            //теперь после очистки проверим условие и если нет то по умолчанию зададим 1 страницу
            if(!$page) {
                $page = 1;//т.е.если нет ячейки page,то мы ей зададим первую страницу
            }    
        }
        //если такой параметр не пришел то мы так же зададим по умолчанию значение первой страницы
        else {
            $page = 1;    
        }
        
        /*теперь создадим переменную pager и в нее сохраним обьект класса Pager*/
        $pager = new Pager($page,'news',array(),'date','DESC',3,QUANTITY_LINKS);
        /*теперь обратимся к созданому обьекту и вызовем у него метод класса Peger get_posts и сохраним его отработку
        в свойстве $this->news*/
        $this->news = $pager->get_posts();
        
        /*теперь уже заполним свойство $navigation так же обратимся к свойству класса Pager get_navigation,который
        уже будет формировать ссылки для самой навигации стрелок и кнопок*/
        $this->navigation = $pager->get_navigation();
         ///////////////////// К О Н Е Ц      П Р А В О Г О      Б Л О К А //////////////////////
        
        //сформируем свойство $message
        $this->message = $_SESSION['message'];        
   }      

    protected function output() {
    
        $this->content = $this->render(VIEW.'admin/edit_news',array(
                                                                   'mes' => $this->message,
                                                                   'news' => $this->news,
                                                                   'navigation' => $this->navigation,
                                                                   'news_text' => $this->news_text,
                                                                   'option' => $this->option,
                                                                   'data' => $this->data
                                                                   ));    
        
        
        $this->page = parent::output();        
        //обнолим сессию ячейку меседж
        unset($_SESSION['message']);
        //и теперь когда массив data успешно передан в шаблон мы очистим сессию
        unset($_SESSION['data']);        
        
        return $this->page;    
    }
}
?>