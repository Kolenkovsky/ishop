<td class="content">
<!проверим по входящим данным что нам необходимо делать,доьавлять или редактировать>
<? if($option == 'add') : ?>
				
						<h1>
							Добавление новостей
						</h1>	
                        <!Выведем сообщение о добавлении страницы>
                        <? if($mes) :?>
                            <p style="color: red;"><?=$mes; ?></p>
                        <?endif; ?>
                        <form method="POST" action="<?=SITE_URL;?>editnews">
						<p><span>Заголовок новости: &nbsp;
						</span><input class="txt-zag" type="text" name="title" value="<?=$data['title']?>" ></p>
						<p><span>Текст новости:</span></p>
						<textarea rows="15" cols="60" name="text" id="text">
                            <?=$data['text'];?>
                        </textarea>
						<br /><br />
                        <p><span>Анонс новости:</span></p>
						<textarea rows="15" cols="60" name="anons" id="anons">
                            <?=$data['anons'];?>
                        </textarea>
						<br /><br />
						<p><span>Ключевые слова: &nbsp;
						</span><input class="txt-zag" type="text" name="keywords" value="<?=$data['keywords']?>"></p>
						<p><span>Описание: &nbsp;
						</span><input class="txt-zag" type="text" name="description" value="<?=$data['description']?>"></p>
					
						<input type="image" src="<?=SITE_URL.VIEW;?>admin/images/save_btn.jpg" name="add_news">
						
						</form>
<!иначе - если $option не равна add а значит равна edit>
<? elseif($option == 'edit') : ?>

        <? if($news_text) : ?>
	                    <h1>
							Редактирование новости - <?=$news_text['title'];?>
						</h1>	
                        <!Выведем сообщение о добавлении страницы>
                        <? if($mes) :?>
                            <p style="color: red;"><?=$mes; ?></p>
                        <?endif; ?>
                        <form method="POST" action="<?=SITE_URL;?>editnews">
						<input type="hidden" name="id" value="<?=$news_text['news_id'];?>"/>
                        <p><span>Заголовок новости: &nbsp;
						</span><input value="<?=$news_text['title'];?>" class="txt-zag" type="text" name="title"></p>
						<p><span>Текст новости:</span></p>
						<textarea rows="15" cols="60" name="text" id="text">
                            <?=$news_text['text'];?>
                        </textarea>
						<br /><br />
                        <p><span>Анонс новости:</span></p>
						<textarea rows="15" cols="60" name="anons" id="anons">
                            <?=$news_text['anons'];?>
                        </textarea>
						<br /><br />
						<p><span>Ключевые слова: &nbsp;
						</span><input class="txt-zag" type="text" name="keywords" value="<?=$news_text['keywords'];?>"></p>
						<p><span>Описание: &nbsp;
						</span><input class="txt-zag" type="text" name="description" value="<?=$news_text['description'];?>"></p>
					
						<input type="image" src="<?=SITE_URL.VIEW;?>admin/images/update_btn.jpg" name="edit_news">
						<a href="<?=SITE_URL;?>editnews/id/<?=$news_text['news_id'];?>/option/delete">
							<img src="<?=SITE_URL.VIEW;?>admin/images/delete_btn.jpg" alt="Удалить новость">
						</a>
						</form>
        <? else : ?>
            <h4 style="color: red;">Такой новости нет!</h4>
        <? endif;?>
<? endif; ?>

</td>

	<td class="rightbar-adm">
					<h1>
						Список новостей
					</h1>
                   <div>
                   <!выводим список новостей>
                        <? if($news) : ?>
                            <? foreach($news as $item) : ?>
                                <p>
                                    <a href="<?=SITE_URL;?>editnews/id/<?=$item['news_id'];?>"><?=$item['title'];?></a>
                                </p>
                            <?endforeach; ?>
                        <? else : ?>
                            <p>Страниц нет</p>
                        <? endif; ?>
						<br />
                        <!навигацию везвем из шаблона archive_page только изменим немного>
                        <? if($navigation) : ?>
                            <ul class="pager" style="width: 130 px !important; margin-left: 5px !important">
                                <!Для стрелочки влево>
                                <? if($navigation['last_page']) : ?>
                                    <li>
                                        <a href="<?=SITE_URL;?>editnews/page/<?=$navigation['last_page'];?>">&lt;</a>
                                    </li>
                                <? endif; ?>
                                
                                <!Теперь для текущей страницы,она ячейкой не будет у нас>
                                <? if($navigation['current']) : ?>
                                    <li>
                                        <span><?=$navigation['current'];?></span>
                                    </li>
                                <? endif; ?>
                                
                                <!Для стрелочки вправо>
                                <? if($navigation['next_pages']) : ?>
                                    <li>
                                        <a href="<?=SITE_URL;?>editnews/page/<?=$navigation['next_pages'];?>">&gt;</a>
                                    </li>
                                <? endif; ?>
                            </ul>
                        <? endif; ?>							 
						<p><a href="<?=SITE_URL;?>editnews"><img src="<?=SITE_URL.VIEW.'admin/';?>images/add_btn.jpg" alt="добавить новость" /></a></p>                          
					</div> 
                 </td>