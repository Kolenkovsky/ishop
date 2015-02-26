<?php
defined('SHOP') or exit('Access denied');
class Index_Controller extends Base {
    /*Создадим свойство $text где будем хранить текст полученный из Model методо gen_home_page*/
    protected $text;
    
    protected function input() {
        /*Чтобы выполнялся сначала не этот метод input а input из родительского класса Base,
        обратимся сначала к нему с помощью ключевого слова parent*/
        parent::input();
        
        /*Теперь,когда выполнился родительский метод input, мы можем обратиться к родительскому свойству 
        title и выполнить еще какой то код, в даном случае пристоковать к тому что там ище строку*/
        $this->title .= "Главная";
        
        /*Обращаемся к обьекту модели созданому в классе Base и методу */
        $this->text = $this->ob_m->get_home_page();
        
        /*Теперь сформируем наши свойства protected $keywords,$discription обращась к свойству $this->text 
        где у нас массив и к ячейкам keywords и discription */
        $this->keywords = $this->text['keywords'];
        $this->description = $this->text['description'];
                           
    }
    
    protected function output() {
        
        //Теперь контент вторым параметром берем то что получили во входных данных 
        $this->content = $this->render(VIEW.'content',array(
                                                            'text' => $this->text 
                                                           ));
                
        /*Теперь обращаемся к родительскому методу output класса Base и он дальше будет формировать блоки
        и отработку этого метода сохраним в свойстве $page класса Base_Controller. то есть сохраним уже
        полностью сгенирированную страницу*/        
        $this->page = parent::output();
        
        return $this->page;
        
    }
    
        
}

?>