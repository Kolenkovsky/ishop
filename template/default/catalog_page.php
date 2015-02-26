<td class="content">
				
						<h1>
							Каталог продукции
						</h1>
                        <? if($krohi) : ?><!проверим пришли ли крохи и если да то ниже>
						<div class="kat_map">
                        <!посчитаем сколько ячеек выбрало в крохи,если одна то это родительская,две значит дочерняя >
                        <? if(count($krohi) > 1) : ?>
                         <a href="<?=SITE_URL;?>">Главная</a> /
                         <a href="<?=SITE_URL;?>catalog/parent/<?=$krohi[0]['brand_id'];?>"><?=$krohi[0]['brand_name'];?></a> /
                         <span><?=$krohi[1]['brand_name'];?></span>
                         
                       <!в другом случае проверяем есть ли в массиве $krohi[0] ячейка 'type_name' >                               
                        <? elseif(count($krohi) == 1 && array_key_exists('type_name',$krohi[0])) : ?>
                            <a href="<?=SITE_URL;?>">Главная</a> /
                            <span><?=$krohi[0]['type_name'];?></span>
                            
                        <!теперь то же для проверки ячейки 'brand_name'>
                        <? elseif(count($krohi) == 1 && array_key_exists('brand_name',$krohi[0])) : ?>
                            <a href="<?=SITE_URL;?>">Главная</a> /
                            <span><?=$krohi[0]['brand_name'];?></span>        
                          
                        <? endif; ?>
							 
						</div>
                        
                        <? endif; ?>
                        
                         <!Как обычно проверяем пришли ли данные в шаблон>
                        <? if($catalog) : ?>
                        
                        <!Что бы у каждого третьего товара при выводе убрать справа вертикальную черту
                        т.е. поменять блок  div class="product-cat" на div class="product-cat-third"
                        будем считать итерацию цикла и каждый раз при третьей иттерации менять его>
                        <?
                        $i = 1;
                        ?>
                            <? foreach($catalog as $key => $item) : ?>
                           	    <div class="product-cat-main">
                                <? if($i == 3) : ?><!Проверяем -это третий товар или нет>
                                     <div class="product-cat-third">
                                     <? $i = 0 ;?> <!После вывода третьего товара обнуляем счетчик чтобы опять шло с чертой с права>  
                                <? else : ?>
                                     <div class="product-cat"><!Если не третий то выводим этот блок div>
                                <? endif; ?>	                               
								        <a href="<?=SITE_URL;?>tovar/id/<?=$item['tovar_id'];?>"><img src="<?=SITE_URL.UPLOAD_DIR.$item['img'];?>" alt="<?=$item['title']?>" /></a>
								        <a href="<?=SITE_URL;?>tovar/id/<?=$item['tovar_id'];?>"><?=$item['title']?></a>
                                   </div>
							       <div class="bord-bot"></div>
						        </div>
                                <? $i++ ;?><!Просле прохода увеличиваем нашу переменную $i на 1>
                            <? endforeach; ?>
                            <div class="clr"></div>
                            
                            <!Навигацию копируем из шаблона archive_page только поменяем пути для ссылок и добавим параметр str>
                                     <? if($navigation) : ?>
                                     <br /> <!Это можно в стилях прописать чтобы навигация отступала от товара >                                  
                                <ul class="pager">
  <!Теперь будем выводить проверяя а есть ли эти ячейки в массиве $navigation тут для кнопки "Начало">
                                    <? if($navigation['first']) : ?>
                                        <li class="first">
                                            <a href="<?=SITE_URL;?>catalog/page/1<?=$previous;?>">Начало</a>
                                        </li>
                                    <? endif; ?>
                                    <!Теперь для стрелочки>
                                    <? if($navigation['last_page']) : ?>
                                        <li>
                                            <a href="<?=SITE_URL;?>catalog/page/<?=$navigation['last_page'];?><?=$previous;?>">&lt;</a>
                                        </li>
                                    <? endif; ?>
                                    <!Теперь для предыдущих трех страниц ссылки,поэтому тут надо циклом>
                                    <? if($navigation['previous']) : ?>
                                        <? foreach($navigation['previous'] as $val) :?>
                                            <li>
                                               <a href="<?=SITE_URL;?>catalog/page/<?=$val;?><?=$previous;?>"><?=$val;?></a>
                                            </li>
                                        <? endforeach; ?>
                                        
                                    <? endif; ?>
                                    
                                    <!Теперь для текущей страницы,она ячейкой не будет у нас>
                                     <? if($navigation['current']) : ?>
                                        <li>
                                            <span><?=$navigation['current'];?></span>
                                        </li>
                                    <? endif; ?>
                                    
                                    <!Теперь все то же для правой стороны от текущей>
                                      <? if($navigation['next']) : ?>
                                        <? foreach($navigation['next'] as $v) :?>
                                            <li>
                                               <a href="<?=SITE_URL;?>catalog/page/<?=$v;?><?=$previous;?>"><?=$v;?></a>
                                            </li>
                                        <? endforeach; ?>
                                        
                                    <? endif; ?>
                                    
                                    <!Для стрелочки вправо>
                                    <? if($navigation['next_pages']) : ?>
                                        <li>
                                            <a href="<?=SITE_URL;?>catalog/page/<?=$navigation['next_pages'];?><?=$previous;?>">&gt;</a>
                                        </li>
                                    <? endif; ?>
                                    
                                    <!Для кнопки "Последняя">
                                     <? if($navigation['end']) : ?>
                                        <li class="last">
                                            <a href="<?=SITE_URL;?>catalog/page/<?=$navigation['end'];?><?=$previous;?>">Конец</a>
                                        </li>
                                    <? endif; ?>
                                                                  
                                </ul>
                            <? endif; ?> 
                        <? else : ?>
                            <p>Введите запрос</p>
                        <? endif; ?>		
						
					    
</td>