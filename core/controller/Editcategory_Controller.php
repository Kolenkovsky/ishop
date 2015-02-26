<?php
defined('SHOP') or exit('Access denied');
//создадим контролер который будет заниматься категориями,добавлять удалять и изменять
class Editcategory_Controller extends Base_Admin {
    
    protected $brands;//здесь будет массив наших категорий
    
    protected $parents_cat;//здесь будем хранить массив родительских категорий-это для выпадающего списка
    
    protected $message;
    
    protected $option = 'add';
    
    protected $id;//здесь будет индефикатор выбранной категории
    
    protected $category;//массив данных по выбранной категории
    
    protected function input($param = array()) {
    
        parent::input();
        
        $this->title .= "Админка - категории";
                
        //Проверим какое действие необходимо делать
        if($param['option'] == 'edit') {
            $this->option = 'edit';
            //теперь проверим пришел ли индефикатор категории,т.е.выбрал ли категорию
            if($param['id']) {
                $this->id = $this->clear_int($param['id']);
                //тогда обратимся к методу который выберет данные по данной категории
                $this->category = $this->ob_m->get_category($this->id);
            }    
        }
        
        //для случая удаления
        if($param['option'] == 'delete') {
            $this->option = 'delete';
            //теперь проверим пришел ли индефикатор категории,т.е.выбрал ли категорию
            if($param['id']) {
                $this->id = $this->clear_int($param['id']);
                //проверим осталось что то в $id,т.е. если там была строка а не число то будет '0'
                if($this->id) {
                    //тогда обратимся к методу который выберет данные по данной категории
                    $result = $this->ob_m->delete_category($this->id);
                    
                    //и теперь в зависимости от результата отработки метода выведем сообщение
                    if($result === TRUE) {
                        $_SESSION['message'] = "Категория успешно удалена";    
                    }
                    else {
                        $_SESSION['message'] = "Ошибка: Категория не удалена";
                    }
                    //и перенаправляем
                    header("Location:".SITE_URL.'editcatalog');
                    exit();    
                }                
            }    
        }
        
        /////////теперь опишем логику уже непосрдственно добавления категорий
        if($this->is_post()) {
            
            //то мы создадим переменные и сохраним в них ячейки массива POST
            $title = $_POST['title'];//т.е.то что внесено в поле под именем title в форме
            $parent = $_POST['parent'];//т.е.то что записано в ячейке
            $id = $this->clear_int($_POST['id']);
            //print_r($_POST);
            /*теперь проверим нажата ли кнопка отправить,а лучше проверим свойство option,чтобы можно было отправлять
            и по нажатию ENTER а не долько кнопки формы ДОБАВИТЬ*/
            if($param['option'] == 'add') {                
                //теперь проверим на заполненость полей
                if(empty($title)) {
                    $_SESSION['message'] = "Заполните название категории";
                    //и перенаправляем
                    header("Location:".SITE_URL.'editcategory/option/add');
                    exit();    
                }
                else {
                     //и теперь обратимся к методу модели,который и добавит категорию
                     $result = $this->ob_m->add_category($title,$parent);    
                }                
                //и теперь в зависимости от результата отработки метода выведем сообщение
                if($result === TRUE) {
                    $_SESSION['message'] = "Категория успешно добавлена";    
                }
                else {
                    $_SESSION['message'] = "Ошибка: Категория не добавлена";
                }
                //и перенаправляем
                header("Location:".SITE_URL.'editcatalog');
                exit();    
            }
            
            //теперь для случая редактирования категории
            if($param['option'] == 'edit') {               
                /*теперь проверим заполняемость полей(эту проверку можно было вынести сзазу после проверки на POST)
                тогда она была бы общая и для add  и для edit*/
                if(empty($title)) {
                    $_SESSION['message'] = "Заполните название категории";
                    //и перенаправляем
                    header("Location:".SITE_URL.'editcategory/option/edit/id/'.$id);
                    exit();    
                }
                else {
                     $result = $this->ob_m->edit_category($title,$parent,$id);
                    
                     //и теперь в зависимости от результата отработки метода выведем сообщение
                     if($result === TRUE) {
                        $_SESSION['message'] = "Категория успешно обновлена";    
                     }
                     else {
                        $_SESSION['message'] = "Ошибка: Категория не обновлена";
                     }
                     //и перенаправляем
                     header("Location:".SITE_URL.'editcategory/option/edit/id/'.$id);
                     exit();        
                }    
            }    
        }
        /*сохраним в свойстве обратившись к методу модели наши категории их массив, это чтобы вывести в списке в 
        правой части наши категории*/
        $this->brands = $this->ob_m->get_catalog_brands();
        
        /*получим список родительских категорий и сохраним в свойстве*/
        $this->parents_cat = $this->ob_m->get_parent_brands();
        
        //запишем в свойство значение сообщения
        $this->message = $_SESSION['message'];    
    }
    
    protected function output() {
        
        //сгенерируем центральную часть
        $this->content = $this->render(VIEW.'admin/edit_category',array(
                                                                       'brands' => $this->brands,
                                                                       'parents_cat' => $this->parents_cat,
                                                                       'mes' => $this->message,
                                                                       'option' => $this->option,
                                                                       'category' => $this->category
                                                                       ));
        
        $this->page = parent::output();
        unset($_SESSION['message']);//очистяем в сиссии  ячейку message
        return $this->page;
    }    
}

?>