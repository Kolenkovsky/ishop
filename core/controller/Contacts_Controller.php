<?php
defined('SHOP') or exit('Access denied');
/*Созздадим отдельно класс для для вывода ссылки в левой части вверху картинка домик,что бы она от-
дельно выводилась не зависимо от меню "Контакты"что бы можно было что нибудь поменять в админке для контактов
а ссылка -картинка домик оставалась рабочей*/
class Contacts_Controller extends Base {
    
    protected $contacts;//Будет храниться массив по контактам
    
    protected function input($params = array()) {
        parent::input();
        
        $this->title .= "Контакты";
        
        //Обрвтимся к модели
        $this->contacts = $this->ob_m->get_contacts();
        
        $this->keywords = $this->contacts['keywords'];
        
        $this->description = $this->contacts['description']; 
    }
    
    protected function output() {
        
        $this->content = $this->render(VIEW.'contacts_page',array(
                                                                  'contacts' => $this->contacts 
                                                                 ));
        
        $this->page = parent::output();
        
        return $this->page;    
    }    
}

?>