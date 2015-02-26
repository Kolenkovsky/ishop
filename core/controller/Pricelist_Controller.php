<?php
defined('SHOP') or exit('Access denied');
//Создадим класс для генерации прайс-листа
class Pricelist_Controller extends Base {
 /*скачаем и установим библотеку phpexcel для работы с Excel*/   
    protected $objPHPExcel;//в этом свойстве будем хранить обьект главного класса библиотеки PHPExcel
    
    protected $catalog;//здесь будет массив данных полцченных из модели после выборки данных
    
    protected function input() {//параметры сюда передавать не будем 
        parent::input();
        
        /*подключим вручную классы PHPExcel, т.к.в автозагр.мы прописали,что если есть в названии класса
        строка PHPExcel то мы выходим из автозагрузки*/
        include(LIB."/PHPExcel.php");
        
        //создаем обьект гл.класса библиотеки
        $this->objPHPExcel = new PHPExcel();
        
        /*сначала укажем активный лист нашей таблицы Excel*/
        $this->objPHPExcel->setActiveSheetIndex(0);//номерация листов начинается с 0
        
        /*теперь получим обьект этого активного листа для доступа всех свойств и методов для работы,
        создав пропежуточную переменную и в неее сохраним обьект активного листа*/
        $active_sheet = $this->objPHPExcel->getActiveSheet();
        
        //$this->objPHPExcel->createSheet();
        
        /*зададим ориентацию листа для печати,для этого обратимся к обьекту активного листа ему вызовем
        метод getPageSetup - этот метод нам вернет обьект у которого мы можем работать снастройками нашего листа,
        и у него вызовем метод setOrientation*/
        $active_sheet->getPageSetup()->setOrientation(PHPExcel_WorkSheet_PageSetup::ORIENTATION_PORTRAIT);
        
        //теперь зададим размер нашей страницы
        $active_sheet->getPageSetup()->setPaperSize(PHPExcel_WorkSheet_PageSetup::PAPERSIZE_A4);
        
        /*теперь зададим отступы, метод getPageMargins возвращает нам обьект который отвечает за отступы*/
        $active_sheet->getPageMargins()->setTop(0.5);
        $active_sheet->getPageMargins()->setRight(0.5);
        $active_sheet->getPageMargins()->setLeft(0.75);
        $active_sheet->getPageMargins()->setBottom(1);
        //сделаем фиксированную высоту строк
        $active_sheet->getDefaultRowDimension()->setRowHeight(22);
        //создадим заголовок
        $active_sheet->setTitle("ishop - Прайс лист");
        
        //выведем футер нашего листа
        $active_sheet->getHeaderFooter()->setOddFooter('&L&B'.$active_sheet->getTitle()."&RСтраница &P из &N");
        
        /*Установим шрифты по умолчанию, тут обращаемя к обьекту библиотеки и обратимся к методу
        getDefaultStyle() который возвращает обьект для настроек по умолчанию*/
        $this->objPHPExcel->getDefaultStyle()->getFont()->setName('Arial');
        //теперь размер шрифта
        $this->objPHPExcel->getDefaultStyle()->getFont()->setSize(8);
        
        /*теперь зададим ширину наших столбцов,для вывода нам необходимо три столбца,тут также обратимся
        к обьекту листа-дальше к методу который ворвращает обьект столбца который мы передадим параметром*/
        $active_sheet->getColumnDimension('A')->setWidth(30);
        $active_sheet->getColumnDimension('B')->setWidth(70);
        $active_sheet->getColumnDimension('C')->setWidth(10);
        
        //обьеденим верхние ячейки для шапки нашей страницы
        $active_sheet->mergeCells('A1:C1');//т.е диапозон от ячейки А до С в строке 1
        /*теперь расширим т.е увеличим высоту этой строки,тут сначала вызываем метод который возвращает
        обьект для строки уже а не столбца,мы ему в параметрах указываем какую строку*/
        $active_sheet->getRowDimension('1')->setRowHeight(60);
        /////////////////////////////////////////////////////////////////////////////////////////////
        /*теперь уже начнем добавлять непосредственно данные в таблицу*/
        $active_sheet->setCellValue('A1',"ISHOP-ИНТЕРНЕТ МАГАЗИН");
                       
        /*Теперь для расширения PHPExcel опишем стили,они задаются как массив, сначала для шапки*/
        $style_header = array(
                              'font' => array(
                                              'bold' => true,
                                              'name' => 'Times New Roman',
                                              'size' => 20,
                                              'color' => array('rgb' => 'ffffff')
                                             ),
                              'alignment' => array(
                                                   'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                                                   'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
                                                  ),
                              'fill' => array(
                                              'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                              'color' => array('rgb' => '2e778f')
                                             ),
        );
        //Применим массив стилей описаных выше к нашей ячейке шапки листа
        $active_sheet->getStyle('A1:C1')->applyFromArray($style_header);
        
        /*теперь вставим логотип сайта на прайс,сначала создадим обьект который позволяет всавлять всякие 
        изображения в Exel документ*/
       	$objDrawing = new PHPExcel_Worksheet_Drawing();
	    $objDrawing->setName('Logo');//указываем имя изображения
	    $objDrawing->setPath('images/price_logo.png');//указываем путь к изображ.
	    
	    $objDrawing->setWorksheet($this->objPHPExcel->getActiveSheet());//укажем рабочий лист куда всталяем изображение
	    $objDrawing->setCoordinates('A1');//указываем ячейку куда надо поместить рисунок
	    $objDrawing->setOffsetX(5);//делаем отступы по оси X
	    $objDrawing->setOffsetY(3);//и по оси Y
         
        ///////////////////////////////////////////////////////////////////////////////////////////////
        //обьеденим ниже еще ячейки для какого то слогана сайта
        $active_sheet->mergeCells('A2:C2');
        $active_sheet->setCellValue('A2',"Лучшие товары на рынке Украины!");
        //теперь опишем стили для строки слогана
        $style_slogan = array(
                              'font' => array(                                             
                                              'size' => 11,
                                              'color' => array('rgb' => 'ffffff'),
                                              'italic' => true
                                             ),
                              'alignment' => array(
                                                   'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                                                   'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
                                                  ),
                              'fill' => array(
                                              'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                              'color' => array('rgb' => '2e778f'),                                             
                                             ),
                              'borders' => array(
                                                 'bottom' => array(
                                                                   'style'=>PHPExcel_Style_Border:: BORDER_THICK
                                                                  )
                                                )
        );
        //применим стили для слогана
        $active_sheet->getStyle('A2:C2')->applyFromArray($style_slogan);                 

        //////////////////////////////////////////////////////////////////////////////////////////////////////        
        /*теперь на четвертой строке среднего столбца выведем дату генерации прайс листа*/
        $active_sheet->mergeCells('A4:B4');
        $active_sheet->setCellValue('A4',"Дата создания прайс листа:");
        //теперь опишем стили для строки дата создания прайс листа
        $style_tdate = array(                            
                              'alignment' => array(
                                                   'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT
                                                  ),
                              'fill' => array(
                                              'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                              'color' => array('rgb' => 'CFCFCF'),                                             
                                             ),
                              'borders' => array(
                                                 'right' => array(
                                                                   'style'=>PHPExcel_Style_Border:: BORDER_NONE
                                                                  )
                                                )
        );
        //применим эти стили для нашей ячейки
        $active_sheet->getStyle('A4:B4')->applyFromArray($style_tdate);
        
        //////////////////////////////////////////////////////////////////////////////////////////////////////
        $date = date("d-m-Y");
        $active_sheet->setCellValue('C4',$date);
                
        /*для ячейки в которую генерируется дата зададим формат данных*/
        $active_sheet->getStyle('C4')->
                       getNumberFormat()->
                       setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX14);
        $style_date = array(                                                        
                              'fill' => array(
                                              'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                              'color' => array('rgb' => 'CFCFCF'),                                             
                                             ),
                              'borders' => array(
                                                 'left' => array(
                                                                   'style'=>PHPExcel_Style_Border:: BORDER_NONE
                                                                  )
                                                )
        );
        //применим эти стили для нашей ячейки
        $active_sheet->getStyle('C4')->applyFromArray($style_date);              
                       
         ///////////////////////////////////////////////////////////////////////////////////////////              
        //теперь ниже сделаем как бы шапочку нашего прайс листа
        $active_sheet->setCellValue('A6',"Название");
        $active_sheet->setCellValue('B6',"Описание");
        $active_sheet->setCellValue('C6',"Цена");
        //опишем стили для названий столбцов наше таблицы
        $style_hprice = array(                            
                              'alignment' => array(
                                                   'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                                                   'vertical' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
                                                  ),
                              'fill' => array(
                                              'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                              'color' => array('rgb' => '2e778f'),                                             
                                             ),
                              'font' => array(
                                              'bold' => true,
                                              'italic' => true,
                                              'name' => 'Times New Roman',
                                              'size' => 10,
                                              'color' => array('rgb' => 'ffffff')  
                                             )
        );
        //применим эти стили для нашей ячейки
        $active_sheet->getStyle('A6:C6')->applyFromArray($style_hprice);
        ///////////////////////////////////////////////////////////////////////////////////////////
        /*Здесь опишем стили для родительских,дочерних категорий и для просто товара, а подключать их
        уже будем ниже в цикле,т.к. там выводятся данные,что бы не было громоздким циклы мы опишем здесь*/
        $style_parent = array(                            
                              'alignment' => array(
                                                   'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                                                   'vertical' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
                                                  ),
                              'fill' => array(
                                              'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                              'color' => array('rgb' => 'CFCFCF'),                                             
                                             ),
                              'font' => array(
                                              'bold' => true,
                                              'italic' => false,
                                              'name' => 'Times New Roman',
                                              'size' => 14,
                                              'color' => array('rgb' => '000000')  
                                             )
        );
        
        //Теперь для дочерних категорий
        $style_category = array(                            
                              'alignment' => array(
                                                   'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                                                   'vertical' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
                                                  ),
                              'fill' => array(
                                              'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                              'color' => array('rgb' => 'CFCFCF'),                                             
                                             ),
                              'font' => array(
                                              'bold' => true,
                                              'italic' => true,
                                              'name' => 'Times New Roman',
                                              'size' => 11,
                                              'color' => array('rgb' => '432332')  
                                             )
        );
        
        //теперь для отдельной ячейки товара
          $style_cell = array(                            
                              'alignment' => array(
                                                   'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                                                   'vertical' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                                                   'wrap' => true
                                                  ),                             
                              'font' => array(                                             
                                              'color' => array('rgb' => '432332')  
                                             )
        );
        //стили для всего прайс листа - это будет рамка подключим ее в самом конце цикла в низу
        $style_wrap = array(                                                                                  
                           'borders' => array(
                                             'allborders' => array(
                                                            'style'=>PHPExcel_Style_Border:: BORDER_THIN,
                                                            'color'=>array('rgb' => '696969')
                                                            ),
                                             'outline' => array(
                                                                'style' => PHPExcel_Style_Border:: BORDER_THICK 
                                                               )
                                             ),
                           
        );
        
        ///////////////////////////////////////////////////////////////////////////////////////////
        /*теперь вызовем наш метод модели get_pricelist*/
        $this->catalog = $this->ob_m->get_pricelist();
        
        /*Теперь когда модель отработала у нас в $this->catalog массив с товарами, теперь пройдемся по
        нему циклом и сначала проверим есть ли в нем подмасив sub,т.е. есть ли дочернии категории и если
        есть то и по нему пройдемся циклом и вытащим так что бы ключ попадал в переменную$parent а значение
        в переменную $goods*/
        $row_start = 6; /*это число будет хранить строку с которой будем начинать заносить данные в таблицу
        это начало отсчета для вывода данных в цикле*/
        $curent_row = $row_start;//Здесь будем хранить текущий ряд для вывода данных
        foreach($this->catalog as $val) {
            
            if($val['sub']) {
                
                foreach($val as $parent => $goods) {
                    
                    /*теперь проверим что хранится в $parent,т.е. что попало в ключ,если там sub то
                    значит мы в дочерней категории если нет то в родительской*/
                    if($parent != 'sub') {
                        //теперь выведем название род.кат.,обьекденим столбцы
                        $curent_row++;//перешли на следующую строку
                        $active_sheet->mergeCells('A'.$curent_row.':C'.$curent_row);//обьеденили столбцы
                        $active_sheet->setCellValue('A'.$curent_row, $parent);//вставляем значение в ячейку этту
                        $active_sheet->getStyle('A'.$curent_row.':C'.$curent_row)->applyFromArray($style_parent);//применяем стили  
                       
                        //теперь проверим а нет ли у нас в родит.катег каких то товаров
                        if(count($goods) > 0) {
                            /*тогда опять пройдемся по ячейке goods  и выведем наш товар*/
                            foreach($goods as $tovar) {
                                $curent_row++;
                                $active_sheet->setCellValue('A'.$curent_row, $tovar['title']);
                                $active_sheet->setCellValue('B'.$curent_row, $tovar['anons']);
                                $active_sheet->setCellValue('C'.$curent_row, $tovar['price']);
                                
                                $active_sheet->getStyle('A'.$curent_row.':C'.$curent_row)->applyFromArray($style_cell);//применяем стили      
                            }    
                        }
                                
                    }
                    // тогда для дочерней категории
                    else {
                        foreach($goods as $category => $tovars) {
                             $curent_row++;
                             $active_sheet->mergeCells('A'.$curent_row.':C'.$curent_row);
                             $active_sheet->setCellValue('A'.$curent_row, $category);
                             //для стилей подклю,прописаны выше
                             $active_sheet->getStyle('A'.$curent_row.':C'.$curent_row)->applyFromArray($style_category);
                             
                             //заголовок вывели ,теперь сами товары то же в цикле т.к.в $tovars тоже массив
                             foreach($tovars as $item) {
                                $curent_row++;
                                $active_sheet->setCellValue('A'.$curent_row, $item['title']);
                                $active_sheet->setCellValue('B'.$curent_row, $item['anons']);
                                $active_sheet->setCellValue('C'.$curent_row, $item['price']);
                                //подключим стили
                                $active_sheet->getStyle('A'.$curent_row.':C'.$curent_row)->applyFromArray($style_cell);   
                             }    
                        }
                           
                    }    
                }    
            }
            //этот случай когда нет дочерних катег.,но в родительской обьязательно есть товар какой то
            else {
                 foreach($val as $parent1 => $goods1) {
                    $curent_row++;
                    $active_sheet->mergeCells('A'.$curent_row.':C'.$curent_row);
                    $active_sheet->setCellValue('A'.$curent_row, $parent1);
                    
                    $active_sheet->getStyle('A'.$curent_row.':C'.$curent_row)->applyFromArray($style_parent);//применяем стили  
                    
                    //по аналогии как выше,если там что то есть то в цикле пройдемся и выведем товар
                    if(count($goods1) > 0) {
                         foreach($goods1 as $tovar1) {
                            $curent_row++;
                            $active_sheet->setCellValue('A'.$curent_row, $tovar1['title']);
                            $active_sheet->setCellValue('B'.$curent_row, $tovar1['anons']);
                            $active_sheet->setCellValue('C'.$curent_row, $tovar1['price']);
                            //подключим стили
                            $active_sheet->getStyle('A'.$curent_row.':C'.$curent_row)->applyFromArray($style_cell);//применяем стили    
                         }        
                    }   
                 }    
            }    
        }
        //подключим нашу рамку
        $active_sheet->getStyle('A1:C'.$curent_row)->applyFromArray($style_wrap);    
    }
    
    protected function output() {
        /*родительский метод вызывать не будем,т.к.при клике на ссылку у нас не будет выводиться ни
        какая страница,а просто выскочит предложение скачать прайс-лист*/
        
        /*нам надо отослать два заголовка с помощью метода header, первый укажет, что ьы отправляем в браузер
        докуметн excel  а второй,что нам не надо его показывать а надо дать на скачивание*/
        header("Content-Type:application/vnd.ms-excel");
        header("Content-Disposition:attachment;filename='priselist.xls'");
        
        /*теперь создадим промежуточную переменную и в неее сохраним обьект класса,который сохраняет документ
        в формате который иы ему укажем.В клссе PHPExcel есть класс шаблон-фабрика, который занимается созданием обьектов
        других классов PHPExcel_IOFactory и у него есть метод который и создаст обьект того клсса который 
        мы ему укажем в параметрах*/
        $objWriter = PHPExcel_IOFactory::createWriter($this->objPHPExcel,'Excel5');
        
        /*теперь уже непосредственно сгенирируем наш документ с помощуью метода save() класса PHPExcel*/
        $objWriter->save("php://output");
        
        exit();    
    }
}
/*Чтобы прайс лист не генерировать каждый раз,если товар и цены меняются редко,можно сделать так что при клике 
на ссылку Скачать прайс лист, прайс лист будет автоматически сохраняться в файл, это все сделать в админской части,
а в пользовательской просто указать путь к файлу на ссылке Скачать прайслист. В таком случае метод Output
надо написать так:
 protected function output() {
               
        $objWriter = PHPExcel_IOFactory::createWriter($this->objPHPExcel,'Excel5');
        
       
        $objWriter->save("price.xls"); т.е. мохранить под именем price.xls
        
        header("Location:".SITE_URL); т.е обратно перенаправляем на главную страницу после клика
        
        exit();    
    }
*/

?>