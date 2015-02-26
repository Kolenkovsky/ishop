

<td class="content">
				
						<h1>
							Результаты поиска
						</h1>
						<div class="kat_map">
							<a href="<?=SITE_URL;?>">Главная</a> / <span>Результаты поиска</span>
						</div>
                        
                        <!Как обычно проверяем пришли ли данные в шаблон>
                        <? if($search) : ?>
                        
                        <!Что бы у каждого третьего товара при выводе убрать справа вертикальную черту
                        т.е. поменять блок  div class="product-cat" на div class="product-cat-third"
                        будем считать итерацию цикла и каждый раз при третьей иттерации менять его>
                        <?
                        $i = 1;
                        ?>
                            <? foreach($search as $key => $item) : ?>
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
                                            <a href="<?=SITE_URL;?>search/page/1/str/<?=$str;?>">Начало</a>
                                        </li>
                                    <? endif; ?>
                                    <!Теперь для стрелочки>
                                    <? if($navigation['last_page']) : ?>
                                        <li>
                                            <a href="<?=SITE_URL;?>search/page/<?=$navigation['last_page'];?>/str/<?=$str;?>">&lt;</a>
                                        </li>
                                    <? endif; ?>
                                    <!Теперь для предыдущих трех страниц ссылки,поэтому тут надо циклом>
                                    <? if($navigation['previous']) : ?>
                                        <? foreach($navigation['previous'] as $val) :?>
                                            <li>
                                               <a href="<?=SITE_URL;?>search/page/<?=$val;?>/str/<?=$str;?>"><?=$val;?></a>
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
                                               <a href="<?=SITE_URL;?>search/page/<?=$v;?>/str/<?=$str;?>"><?=$v;?></a>
                                            </li>
                                        <? endforeach; ?>
                                        
                                    <? endif; ?>
                                    
                                    <!Для стрелочки вправо>
                                    <? if($navigation['next_pages']) : ?>
                                        <li>
                                            <a href="<?=SITE_URL;?>search/page/<?=$navigation['next_pages'];?>/str/<?=$str;?>">&gt;</a>
                                        </li>
                                    <? endif; ?>
                                    
                                    <!Для кнопки "Последняя">
                                     <? if($navigation['end']) : ?>
                                        <li class="last">
                                            <a href="<?=SITE_URL;?>search/page/<?=$navigation['end'];?>/str/<?=$str;?>">Конец</a>
                                        </li>
                                    <? endif; ?>
                                                                  
                                </ul>
                            <? endif; ?> 
                        <? else : ?>
                            <p>Введите запрос</p>
                        <? endif; ?>	
																
				</td>

