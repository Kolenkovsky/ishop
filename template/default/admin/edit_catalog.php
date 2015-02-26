<td class="content">
<!проверим действие которое нам необходимо,то есть что показать в шаблоне>
<? if($option == 'view') : ?>				
						<h1>
							Редактирование каталога 
						</h1>
<!Выводим системные сообщения>
<p style="color: red;"><?=$mes;?></p>
<!Для вывода кнопок вверху сделаем проверку выбранна ли категория товара>
<?if($category) : ?>
 <div class="button-catalog-adm">
    <a href="<?=SITE_URL;?>editcatalog/option/add/id/<?=$category;?>"><img src="<?=SITE_URL.VIEW;?>admin/images/add_produkt.jpg" alt="Добавить продукт в категорию" /></a>
    <a href="<?=SITE_URL;?>editcategory/option/edit/id/<?=$category;?>"><img src="<?=SITE_URL.VIEW;?>admin/images/change_cat.jpg" alt="Изменить категорию" /></a>
    <a href="<?=SITE_URL;?>editcategory/option/delete/id/<?=$category;?>"><img src="<?=SITE_URL.VIEW;?>admin/images/del_cat.jpg" alt="Удалить категорию" /></a>
 </div>
<?endif;?>

<!теперь проверим надо ли показывать товары,т.е. выбраны ли они >
<? if($goods) : ?>
    <? foreach($goods as $item) : ?>
        <div class="adm-product-cat-main">
            <div class="adm-product-cat">
                <p><?=$item['title'];?></p>
				<img src="<?=SITE_URL.UPLOAD_DIR.$item['img'];?>" alt="<?=$item['title'];?>" />
				<p>
                   <a href="<?=SITE_URL;?>editcatalog/option/edit/tovar/<?=$item['tovar_id']?>">Изменить</a>  | 
                   <a href="<?=SITE_URL;?>editcatalog/option/delete/tovar/<?=$item['tovar_id']?><?=$previous;?>">Удалить</a>
                </p>
        	</div>
            <div class="adm-bord-bot"></div>
        </div>
    <? endforeach;?>
    <!тут очистим обтекание чтобы навигация не обтекала блоки с товаром>
    <div style="clear: both;"></div>
    
                                      <!Н А В И Г А Ц И Я>
  <!Если переменная $archive усть то нам надо выводить навигацию,поэтому проверим а пришли ли данные $navigation>
                            <? if($navigation) : ?>
                                <ul class="pager">
  <!Теперь будем выводить проверяя а есть ли эти ячейки в массиве $navigation тут для кнопки "Начало">
                                    <? if($navigation['first']) : ?>
                                        <li class="first">
                                            <a href="<?=SITE_URL;?>editcatalog/page/1<?=$previous;?>">Начало</a>
                                        </li>
                                    <? endif; ?>
                                    <!Теперь для стрелочки>
                                    <? if($navigation['last_page']) : ?>
                                        <li>
                                            <a href="<?=SITE_URL;?>editcatalog/page/<?=$navigation['last_page'];?><?=$previous;?>">&lt;</a>
                                        </li>
                                    <? endif; ?>
                                    <!Теперь для предыдущих трех страниц ссылки,поэтому тут надо циклом>
                                    <? if($navigation['previous']) : ?>
                                        <? foreach($navigation['previous'] as $val) :?>
                                            <li>
                                               <a href="<?=SITE_URL;?>editcatalog/page/<?=$val;?><?=$previous;?>"><?=$val;?></a>
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
                                               <a href="<?=SITE_URL;?>editcatalog/page/<?=$v;?><?=$previous;?>"><?=$v;?></a>
                                            </li>
                                        <? endforeach; ?>
                                        
                                    <? endif; ?>
                                    
                                    <!Для стрелочки вправо>
                                    <? if($navigation['next_pages']) : ?>
                                        <li>
                                            <a href="<?=SITE_URL;?>editcatalog/page/<?=$navigation['next_pages'];?><?=$previous;?>">&gt;</a>
                                        </li>
                                    <? endif; ?>
                                    
                                    <!Для кнопки "Последняя">
                                     <? if($navigation['end']) : ?>
                                        <li class="last">
                                            <a href="<?=SITE_URL;?>editcatalog/page/<?=$navigation['end'];?><?=$previous;?>">Конец</a>
                                        </li>
                                    <? endif; ?>
                                                                  
                                </ul>
                            <? endif; ?> 									
<!Если категория есть но в ней товара нет то выведем сообщение>
<? elseif($category && !$goods) : ?>
    <h3 style="color: red;">Товара нет в данной категории</h3>
					
<!усли товар не выбран т.е. категория не выбрана то нам надо показать строку "Выберети категорию товара">
<? else : ?>
    <h3 style="color: gray;">Выберети категорию товара</h3>
						
<? endif;?>

<? elseif($option == 'add') : ?>
    <h1>
        Добавление нового товара
    </h1>
    <p style="color: red;"><?=$mes;?></p>
        
        <!--FORM ADD-->
	<form enctype="multipart/form-data" action="<?=SITE_URL;?>editcatalog/option/add/id/<?=$category?>" method="POST">
			<p><span>Название: &nbsp;
			</span><input class="txt-zag" type="text" name="title" value="<?=$data['title'];?>"></p>
			<input type="hidden" name="MAX_FILE_SIZE" value="2097152">
			<p><span>картинка анонса: 
			</span><input class="txt-zag" type="file" value="" name="img">
			<p><span>Краткое описание:</span></p>
			<textarea name="anons" cols="60" rows="15"><?=$data['anons'];?></textarea><br /><br />
						
			<p><span>Полное описание:</span></p>
			<textarea name="text" cols="60" rows="15"><?=$data['text'];?></textarea><br /><br />
			
			<p><span>Ключевые слова: &nbsp;
						</span><input class="txt-zag" type="text" name="keywords" value="<?=$data['keywords'];?>"></p>
						<p><span>Описание: &nbsp;
						</span><input class="txt-zag" type="text" name="discription" value="<?=$data['description'];?>"></p>
			<p><span>Выберите тип товара:</span></p>
			<select name="type">
			<? if($type_cat) :?>
				<? foreach($type_cat as $item) :?>
					<option value="<?=$item['type_id']?>"><?=$item['type_name']?></option>	
				<? endforeach;?>	
			</select>
			<? else :?>
				<p>типов пока нет</p>
			<? endif; ?>
			
			<p><span>Или создайте новый: &nbsp;</span>
			<input class="txt-zag" type="text" name="new_type"></p>
			<p>Публиковать товар:<br />
			<input type="radio" name="publish" value="1" checked>Да
			<input type="radio" name="publish" value="0">Нет</p>
			
			<p><span>Цена: &nbsp;
			</span><input class="txt-zag" type="text" name="price" value="<?=$data['price'];?>"></p>
						
			<input type="image" src="<?=SITE_URL.VIEW;?>admin/images/save_btn.jpg" name="submit_add_cat">
						
		</form>
	<!--FORM ADD-->
    
<?elseif($option == 'edit') : ?>
    <h1>
        Редактирование товара - <?=$tovar['title'];?>
    </h1>
    <p style="color: red;"><?=$mes;?></p>
        
        <!--FORM ADID-->
	<form enctype="multipart/form-data" action="<?=SITE_URL;?>editcatalog/option/edit" method="POST">
			<p><span>Название: &nbsp;
			</span><input class="txt-zag" type="text" name="title" value="<?=$tovar['title'];?>" ></p>
			<input type="hidden" name="id" value="<?=$tovar['tovar_id'];?>">
            <input type="hidden" name="MAX_FILE_SIZE" value="2097152">
			<p><span>картинка анонса: 
			</span><input class="txt-zag" type="file" value="" name="img">
			<p><span>Краткое описание:</span></p>
			<textarea name="anons" cols="60" rows="15">
                <?=$tovar['anons'];?>
            </textarea><br /><br />
						
			<p><span>Полное описание:</span></p>
			<textarea name="text" cols="60" rows="15">
                <?=$tovar['text'];?>
            </textarea><br /><br />
			
			<p><span>Ключевые слова: &nbsp;
						</span><input class="txt-zag" type="text" name="keywords" value="<?=$tovar['keywords'];?>"></p>
						<p><span>Описание: &nbsp;
						</span><input class="txt-zag" type="text" name="description" value="<?=$tovar['description'];?>"></p>
			<p><span>Выберите тип товара:</span></p>
			<select name="type">
			<? if($type_cat) :?>
				<? foreach($type_cat as $item) :?>
                    <!Для подстведки того типа которуму принадлежит выбранный товар добавим такой код>
                    <? if($item['type_id'] == $tovar['type_id']) : ?>
                        <option selected value="<?=$item['type_id']?>"><?=$item['type_name']?></option>
                    <? else : ?>
                        <option value="<?=$item['type_id']?>"><?=$item['type_name']?></option>
                    <? endif;?>						
				<? endforeach;?>	
			</select>
			<? else :?>
				<p>типов пока нет</p>
			<? endif; ?>
			
			<p><span>Или создайте новый: &nbsp;</span>
			<input class="txt-zag" type="text" name="new_type"></p>
            
            <p><span>Выберите категорию:</span></p>
			<? if($brands) : ?>
                <select name="category">
                    <? if($tovar['brand_id'] == 0) : ?>
                        <option selected value="0">Без категории</option>
                    <? endif;?>
                    
                    <? foreach($brands as $key => $item) : ?>
                        <? if($key == $tovar['brand_id']) : ?>
                            <option selected  value="<?=$key;?>"><?=$item[0];?></option>
                        <? else :?>
                            <option  value="<?=$key;?>"><?=$item[0];?></option>
                        <?endif;?>
                        
                        <? if($item['next_level']) : ?>
                            <? foreach($item['next_level'] as $k => $val) : ?>
                                 <? if($k == $tovar['brand_id']) : ?>
                                    <option selected value="<?=$k;?>">--<?=$val;?></option>
                                 <?else : ?>
                                    <option  value="<?=$k;?>">--<?=$val;?></option>
                                 <?endif;?>
                            <? endforeach;?>
                        <?endif;?>
                        
                    <?endforeach;?>
                </select>
            <?else : ?>
                <p>Категорий нет</p>
            <?endif;?>
			<p>Публиковать товар:<br />
            <? if($tovar['publish'] === '1') : ?>
                <input type="radio" name="publish" value="1" checked>Да
			    <input type="radio" name="publish" value="0">Нет</p>
            <?else : ?>
                <input type="radio" name="publish" value="1">Да
			    <input type="radio" name="publish" value="0" checked>Нет</p>
            <?endif;?>
			
			
			<p><span>Цена: &nbsp;
			</span><input class="txt-zag" type="text" name="price" value="<?=$tovar['price'];?>"></p>
						
			<input type="image" src="<?=SITE_URL.VIEW;?>admin/images/update_btn.jpg" name="submit_edit_tovar">
						
		</form>
	<!--FORM ADIT-->

<?endif;?>									
				</td>
				
				<td class="rightbar-adm">
					<h1>
						Категории 
					</h1>
                    
                    <? if($brands) : ?>
                    <ul>
                        <? foreach($brands as $key => $item) : ?>
                            <!теперь проверим есть ли дочерняя категория,т.у. есть ячейка next_level в $item>
                            <? if($item['next_level']) : ?>
                                <!сначала выведем родительскую категорию>
                                <li>
                                    <a href="<?=SITE_URL;?>editcatalog/parent/<?=$key;?>"><?=$item[0];?></a>
                                    <!теперь циклом пройдемся по ячейке next_level т.к. это массив и выведем дочер.категории>
                                    <ul>
                                    <? foreach($item['next_level'] as $k => $val) : ?>
                                        <li>
                                            <a href="<?=SITE_URL;?>editcatalog/brand/<?=$k;?>"><?=$val;?></a>
                                        </li>
                                    <?endforeach;?>
                                    </ul>
                                </li>
                            <!для случая если нет дочерних категорий а товар в родительской есть>
                            <? else : ?>
                                <li>
                                    <a href="<?-SITE_URL;?>editcatalog/brand/<?=$key;?>"><?=$item[0];?></a>
                                </li>    
                            <?endif;?>
                        <?endforeach;?>
                        <!для случая когда мы удаляем категорию,для товара этой категории установим brand_id = 0>
                        <li>
                            <a href="<?=SITE_URL;?>editcatalog/brand/0">Без категории</a>
                        </li>
                    </ul>
                    <? else : ?>
                        <p style="color: green;">Категорий нет</p>
                    <? endif; ?>
						
	<br />
	<p><a href="<?=SITE_URL;?>editcategory"><strong>Новая категория</strong></a></p>
	<p><a href="<?=SITE_URL;?>edittypes"><strong>Редактирование типов</strong></a></p>
</td>