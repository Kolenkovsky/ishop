<td class="content">

<? if($option == 'view') : ?>				
						<h1>
							Типы товаров
						</h1>
                        <!Выведем системное сообщение в начале>
                        <p><?=$mes;?></p>
<? if($data_type) : ?>
<!Выводить будем в виде таблицы>
    <table class="edit_types" cellspacing="4px" border="2px" width="100%">
        <tbody>
            <?foreach($data_type as $item) : ?>
                <tr>
                    <td>
                        <?=$item['type_name'];?>
                    </td>
                        
                    <td>
                        <a href="<?=SITE_URL;?>edittypes/option/edit/id/<?=$item['type_id'];?>">Изменить тип</a>    
                    </td>
                    
                    <td>
                        <a href="<?=SITE_URL;?>edittypes/option/delete/id/<?=$item['type_id'];?>">Удалить тип</a>    
                    </td>    
                </tr>
            <?endforeach;?>
        </tbody>
    </table>
    
<?else : ?>
    <h3 style="color: red;">Типов товаров нет</h3>
    
<?endif;?>


<?elseif($option == 'edit' && $type) : ?>
						<h1>
							Редактирование типа товара - <?=$type['type_name'];?>
						</h1>
                        <!Выведем системное сообщение в начале>
                        <p><?=$mes;?></p>
                        
<form action="<?=SITE_URL;?>edittypes/option/edit" method="POST">
    <input type="hidden" name="id" value="<?=$type['type_id'];?>"/>
    
    <p><span>Название типа товара: &nbsp;</span>
        <input class="txt-zag" type="text" name="type_name" value="<?=$type['type_name'];?>"></p>
        
        <!теперь для отображения есть ли тип в хедере или нет выведем через радиокнопки>
        <? for($i = 0; $i < 5; $i++) : ?>
            
            <? if($type['in_header'] == $i) : ?>
                <? if($i == 0) : ?>
                    <input type="radio" checked name="in_header" value="<?=$i;?>"/>Тип товара не отображается в шапке сайта<br />
                <? else : ?>
                    <input type="radio" checked name="in_header" value="<?=$i;?>"/>Ячейка№<?=$i;?><br />
                <?endif;?>
            <?else : ?>
                <? if($i == 0) : ?>
                    <input type="radio" name="in_header" value="<?=$i;?>"/>Тип товара не отображается в шапке сайта<br />
                <? else : ?>
                    <input type="radio" name="in_header" value="<?=$i;?>"/>Ячейка№<?=$i;?><br />
                <?endif;?>
            <? endif;?>
                          
        <?endfor;?>
        <br />
    <input type="image" src="<?=SITE_URL.VIEW;?>admin/images/update_btn.jpg" name="submit_edit_types">
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
	<p><a href="<?=SITE_URL;?>edittypes"><strong>Редактирование типов</strong></a></p>
</td>