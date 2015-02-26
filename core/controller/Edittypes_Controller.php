<?php
defined('SHOP') or exit('Access denied');
//создадим контролер который будет заниматься  удалять и изменять типы товаров
class Edittypes_Controller extends Base_Admin {
    
    protected $brands;//здесь будет массив наших категорий
    
    protected $message;
    
    protected $option = 'view';//по умолчанию просмотр,т.е.вывести все типы товаров 
    
    protected $id;//здесь будет индефикатор выбранного типа товаров
    
    protected $data_type;//здесь будет массив всех типов товаров
    
    protected $type;//здесь массив данных по конкретному типу
    
    
    protected function input($param = array()) {
    
        parent::input();
        
        $this->title .= "Админка - типы";
       
        //Проверим какое действие необходимо делать
        if($param['option'] == 'edit') {
            $this->option = 'edit';
            //теперь проверим пришел ли индефикатор категории,т.е.выбрал ли категорию
            if($param['id']) {
                $this->id = $this->clear_int($param['id']);
                //тогда обратимся к методу который выберет данные по данной категории
                $this->type = $this->ob_m->get_type_admin($this->id);
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
                    $result = $this->ob_m->delete_types($this->id);
                    
                    //и теперь в зависимости от результата отработки метода выведем сообщение
                    if($result === TRUE) {
                        $_SESSION['message'] = "Тип товара успешно удален";    
                    }
                    else {
                        $_SESSION['message'] = "Ошибка: Тип товара не удален";
                    }
                    //и перенаправляем
                    header("Location:".SITE_URL.'edittypes');
                    exit();    
                }                
            }    
        }
        
        /////////теперь опишем логику уже непосрдственно изменения типов
        if($this->is_post()) {            
            //то мы создадим переменные и сохраним в них ячейки массива POST
            $type_name = $_POST['type_name'];//т.е.то что внесено в поле под именем title в форме
            $in_header = $_POST['in_header'];//т.е.то что записано в ячейке
            $id = $this->clear_int($_POST['id']);
            
            /*теперь проверим нажата ли кнопка отправить,а лучше проверим свойство option,чтобы можно было отправлять
            и по нажатию ENTER а не долько кнопки формы ДОБАВИТЬ*/            
            //теперь для случая редактирования категории
            if($param['option'] == 'edit') {               
                /*теперь проверим заполняемость полей(эту проверку можно было вынести сзазу после проверки на POST)
                тогда она была бы общая и для add  и для edit*/
                if(empty($type_name)) {
                    $_SESSION['message'] = "Заполните название типа товаров";
                    //и перенаправляем
                    header("Location:".SITE_URL.'edittypes/option/edit/id/'.$id);
                    exit();    
                }
                else {
                     $result = $this->ob_m->edit_types($type_name,$in_header,$id);
                    
                     //и теперь в зависимости от результата отработки метода выведем сообщение
                     if($result === TRUE) {
                        $_SESSION['message'] = "Тип товара успешно обновлен";    
                     }
                     else {
                        $_SESSION['message'] = "Ошибка: Тип товара не обновлен";
                     }
                     //и перенаправляем
                     header("Location:".SITE_URL.'edittypes');
                     exit();        
                }    
            }    
        }
        
        /*сохраним в свойстве обратившись к методу модели наши категории их массив, это чтобы вывести в списке в 
        правой части наши категории*/
        $this->brands = $this->ob_m->get_catalog_brands();
        //получим типы товаров обратившись к методу модели
        $this->data_type = $this->ob_m->get_catalog_type();
       
        //запишем в свойство значение сообщения
        $this->message = $_SESSION['message'];    
    }
    
    protected function output() {
        
        //сгенерируем центральную часть
        $this->content = $this->render(VIEW.'admin/edit_types',array(
                                                                       'brands' => $this->brands, 
                                                                       'mes' => $this->message,
                                                                       'option' => $this->option,
                                                                       'data_type' => $this->data_type,
                                                                       'type' => $this->type
                                                                       ));
        
        $this->page = parent::output();
        unset($_SESSION['message']);//очистяем в сиссии  ячейку message
        return $this->page;
    }    
}

?>