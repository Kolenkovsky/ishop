<td class="content">
<!проверим по входящим данным что нам необходимо делать,доьавлять или редактировать>

<? if($option == 'add') : ?>				
						<h1>
							Добавление страницы 
						</h1>	
                        <!Выведем сообщение о добавлении страницы>
                        <? if($mes) :?>
                            <p style="color: red;"><?=$mes; ?></p>
                        <?endif; ?>
                        <form method="POST" action="<?=SITE_URL;?>admin">						
                        <p><span>Заголовок страницы: &nbsp;
						</span><input class="txt-zag" type="text" name="title" value="<?=$data['title'];?>"></p>
						<p><span>Текст страницы:</span></p>
						<textarea rows="15" cols="60" name="text" id="text">
                            <?=$data['text'];?>
                        </textarea>
						<br /><br />
						<p><span>Ключевые слова: &nbsp;
						</span><input class="txt-zag" type="text" name="keywords" value="<?=$data['keywords'];?>"></p>
						<p><span>Описание: &nbsp;
						</span><input class="txt-zag" type="text" name="description" value="<?=$data['description'];?>"></p>
						<p><span>Позиция страницы: &nbsp;
						</span><input class="txt-num" type="text" name="position" value="<?=$data['position'];?>"></p>
						<input type="image" src="<?=SITE_URL.VIEW.'admin/';?>images/save_btn.jpg" name="add">
						
						</form>
<!иначе если надо делать другое действие то опять же надо вывести туже таблицу-это чтобы не делать отдельный шаблон для редактирования>
<? elseif($option == 'edit') : ?>
        <? if($page_text) : ?>        
                        <h1>
							Редактирование страницы - <?=$page_text['title'];?>
						</h1>	
                        <!Выведем сообщение о добавлении страницы>
                        <? if($mes) :?>
                            <p style="color: red;"><?=$mes; ?></p>
                        <?endif; ?>
               <form method="POST" action="<?=SITE_URL;?>admin">
						<input type="hidden" name="id" value="<?=$page_text['pages_id'];?>"/>
                        <p><span>Заголовок страницы: &nbsp;
						</span><input class="txt-zag" type="text" name="title" value="<?=$page_text['title'];?>"></p>
						<p><span>Текст страницы:</span></p>
						<textarea rows="15" cols="60" name="text" id="text">
                            <?=$page_text['text'];?>
                        </textarea>
						<br /><br />
						<p><span>Ключевые слова: &nbsp;
						</span><input class="txt-zag" type="text" name="keywords" value="<?=$page_text['keywords'];?>"></p>
						<p><span>Описание: &nbsp;
						</span><input class="txt-zag" type="text" name="description" value="<?=$page_text['description'];?>"></p>
						<p><span>Позиция страницы: &nbsp;
						</span><input class="txt-num" type="text" name="position" value="<?=$page_text['position'];?>"></p>
						<input type="image" src="<?=SITE_URL.VIEW;?>admin/images/update_btn.jpg" name="edit">
						<a href="<?=SITE_URL;?>admin/id/<?=$page_text['pages_id'];?>/option/delete">
							<img src="<?=SITE_URL.VIEW;?>admin/images/delete_btn.jpg" alt="Удалить страницу">
						</a>
               </form>
        <? else : ?>
            <h4 style="color: red;">Такой страницы нет!</h4>
        <?endif;?>

<? endif; ?>						
				</td>
				<td class="rightbar-adm">
					<h1>
						Список страниц
					</h1>
                   <div>
                   <!выводим список страниц>
                        <? if($pages) : ?>
                            <? foreach($pages as $item) : ?>
                                <p>
                                    <a href="<?=SITE_URL;?>admin/id/<?=$item['pages_id'];?>"><?=$item['title'];?></a>
                                </p>
                            <?endforeach; ?>
                        <? else : ?>
                            <p>Страниц нет</p>
                        <? endif; ?>
						<br />							 
						<p><a href="<?=SITE_URL;?>admin"><img src="<?=SITE_URL.VIEW.'admin/';?>images/add_btn.jpg" alt="добавить страницу" /></a></p>
																			
                            <? if($pages) : ?>
                            
                            <p>Главная страница:</p>
					    	<form method="POST" action="<?=SITE_URL;?>admin">
                                
                                <select name="home_page">
                                
                                <? foreach($pages as $item) : ?>
                                    <!для подсветки главн стр. сравним индеф-ор текущей с индеф.главной>
                                    <? if($item['pages_id'] == $home) : ?>
                                        <option selected value="<?=$item['pages_id'];?>">
                                            <?=$item['title'];?>
                                        </option>
                                        
                                    <? else : ?>
                                        <option value="<?=$item['pages_id'];?>">
                                            <?=$item['title'];?>
                                        </option>
                                        
                                    <? endif; ?>
                                
                                   
                                <?endforeach; ?>
                                
                                </select>
                               	<br>
							    <input type="image" src="<?=SITE_URL.VIEW.'admin/';?>images/update_btn.jpg" name="home">
			                </form>	
                            <? endif; ?>												
						
						<br>
                        
                        <? if($pages) : ?>
						<p>Страница контактов:</p>
						<form method="POST" action="<?=SITE_URL;?>admin">
							<select name="contacts">
							 <? foreach($pages as $item) : ?>
                                    <!для подсветки главн стр. сравним индеф-ор текущей с индеф.главной>
                                    <? if($item['pages_id'] == $contacts) : ?>
                                        <option selected value="<?=$item['pages_id'];?>">
                                            <?=$item['title'];?>
                                        </option>
                                        
                                    <? else : ?>
                                        <option value="<?=$item['pages_id'];?>">
                                            <?=$item['title'];?>
                                        </option>
                                        
                                    <? endif; ?>
                                
                                   
                             <?endforeach; ?>																		
							</select>
							<br>
							<input type="image" src="<?=SITE_URL.VIEW.'admin/';?>images/update_btn.jpg" name="contacts_submit">
						</form>
                        <? endif; ?>	
					</div> 
                 </td>