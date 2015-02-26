<td class="content">
				
						<h1>
							Новости
						</h1>
                        
                        <? if($archive) : ?>
                        
                            <? foreach($archive as $item) : ?>
                                
                                	<div class="news-cat">
							           <span>
                                            <?=date('d.m.Y',$item['date']);?>
                                       </span>
                                       
							           <h2>
                                            <a href="<?=SITE_URL;?>news/id/<?=$item['news_id'];?>">
                                                <?=$item['title'];?>
                                            </a>
                                       </h2>
	
							           <p>
                                            <?=$item['anons'];?>
                                       </p>
							           <p class="more">
                                            <a href="<?=SITE_URL;?>news/id/<?=$item['news_id'];?>">
                                                Читать подробнее
                                            </a>
                                       </p>
						            </div>
                            
                            <? endforeach; ?>
                            
                            <!Н А В И Г А Ц И Я>
  <!Если переменная $archive усть то нам надо выводить навигацию,поэтому проверим а пришли ли данные $navigation>
                            <? if($navigation) : ?>
                                <ul class="pager">
  <!Теперь будем выводить проверяя а есть ли эти ячейки в массиве $navigation тут для кнопки "Начало">
                                    <? if($navigation['first']) : ?>
                                        <li class="first">
                                            <a href="<?=SITE_URL;?>archive/page/1">Начало</a>
                                        </li>
                                    <? endif; ?>
                                    <!Теперь для стрелочки>
                                    <? if($navigation['last_page']) : ?>
                                        <li>
                                            <a href="<?=SITE_URL;?>archive/page/<?=$navigation['last_page'];?>">&lt;</a>
                                        </li>
                                    <? endif; ?>
                                    <!Теперь для предыдущих трех страниц ссылки,поэтому тут надо циклом>
                                    <? if($navigation['previous']) : ?>
                                        <? foreach($navigation['previous'] as $val) :?>
                                            <li>
                                               <a href="<?=SITE_URL;?>archive/page/<?=$val;?>"><?=$val;?></a>
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
                                               <a href="<?=SITE_URL;?>archive/page/<?=$v;?>"><?=$v;?></a>
                                            </li>
                                        <? endforeach; ?>
                                        
                                    <? endif; ?>
                                    
                                    <!Для стрелочки вправо>
                                    <? if($navigation['next_pages']) : ?>
                                        <li>
                                            <a href="<?=SITE_URL;?>archive/page/<?=$navigation['next_pages'];?>">&gt;</a>
                                        </li>
                                    <? endif; ?>
                                    
                                    <!Для кнопки "Последняя">
                                     <? if($navigation['end']) : ?>
                                        <li class="last">
                                            <a href="<?=SITE_URL;?>archive/page/<?=$navigation['end'];?>">Конец</a>
                                        </li>
                                    <? endif; ?>
                                                                  
                                </ul>
                            <? endif; ?>                          
                        
                        <? else : ?>
                        
                            <p>Новостей нет</p>
                        
                        <? endif; ?>
																	
</td>