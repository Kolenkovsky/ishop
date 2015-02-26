<?php
defined('SHOP') or exit('Access denied');
//класс для вывода полного описания товара
class Tovar_Controller extends Base {
    
    protected $tovar;//здесь будет масив с полным описанием товара конкретного
    
    protected $krohi;
    
    protected function input($param = array()) {//параметр id приходит из адресн. строки при клике на товар
        parent::input();
                
        /*обратимся к обьекту модели и ее методу get_tovar только сначала проверим есть ли $id т.е
        счелкнул по товару пользователь или нет если да-то очистим ее и передадим методу get_tovar */
        if(isset($param['id'])) {
            $id = $this->clear_int($param['id']);
            //после очистки, если $id не ноль,то
            if($id) {
                //если есть то в свойстве $tovar сохраним отработку метода get_tovar
                $this->tovar = $this->ob_m->get_tovar($id);
                
                /*оформим заголов,только сделаем его не статичным ,а конкретто для каждого товара*/
                $this->title.=$this->tovar['title'];
                $this->keywords = $this->tovar['keywords'];
                $this->description = $this->tovar['description'];
                
                //теперь для хлебных крох создадим ячейку tovar_name и поставим туда название товара
                $this->krohi[0]['tovar_name'] = $this->tovar['title'];                        
            }    
        }
                   
    }
    
    protected function output() {
        
        //генерируем среднюю часть
        $this->content = $this->render(VIEW.'tovar_page', array(
                                                                 'tovar' => $this->tovar,
                                                                 'krohi' => $this->krohi
                                                                ));
        
        $this->page = parent::output(); 
        
        return $this->page;            
    }
}

?>