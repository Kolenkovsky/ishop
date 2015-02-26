<td class="content">

<? if($option == 'add') : ?>				
						<h1>
							Добавление категорий
						</h1>
                        <!Выведем системное сообщение в начале>
                        <p><?=$mes;?></p>
<form action="<?-SITE_URL;?>editcategory/option/add" method="POST">
    <p><span>Название категории:</span>
    <input class="txt-zag" type="text" name="title"/>
    </p>
    
    <p><span>Родительская категория:</span>
        <select name="parent">
            <option value="0">Родительская</option>
            <? if($parents_cat) : ?>
                <? foreach($parents_cat as $item) : ?>
                    <option value="<?=$item['brand_id'];?>"><?=$item['brand_name'];?></option>
                <?endforeach;?>    
            <?endif;?>
        </select>
    </p>
    
    <input type="image" src="<?=SITE_URL.VIEW;?>admin/images/save_btn.jpg" name="submit_add_cat"/>
    
</form>
<?elseif($option == 'edit' && $category) : ?>
						<h1>
							Редактирование категории - <?=$category['brand_name'];?>
						</h1>
                        <!Выведем системное сообщение в начале>
                        <p><?=$mes;?></p>
<form action="<?-SITE_URL;?>editcategory/option/edit" method="POST">
    <p><span>Название категории:</span>
    <input class="txt-zag" type="text" name="title" value="<?=$category['brand_name'];?>"/>
    </p>
    
    <input  type="hidden" name="id" value="<?=$category['brand_id'];?>"/>
    
    <p><span>Родительская категория:</span>
        <select name="parent">
            <option value="0">Родительская</option>
            <? if($parents_cat) : ?>
                <? foreach($parents_cat as $item) : ?>
                    <? if($item['brand_id'] == $category['parent_id']) : ?>
                        <option selected value="<?=$item['brand_id'];?>"><?=$item['brand_name'];?></option>
                    <? else : ?>
                        <option value="<?=$item['brand_id'];?>"><?=$item['brand_name'];?></option>
                    <?endif;?>  
                    
                <?endforeach;?>    
            <?endif;?>
        </select>
    </p>
    
    <input type="image" src="<?=SITE_URL.VIEW;?>admin/images/update_btn.jpg" name="submit_edit_cat"/>
    
</form>
<? endif; ?>
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
	<p><a href="/visitka/edittypes"><strong>Редактирование типов</strong></a></p>
</td>