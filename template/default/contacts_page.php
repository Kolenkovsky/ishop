<!Шаблон для вывода страницы контактов на ссылку домик в левом меню для Contacts_Controllera>
	<td class="content">

        <? if($contacts) : ?>
            <h1>
        	   <?=$contacts['title'];?>					
            </h1>
            
            <p><?=$contacts['text'];?></p>
        
        <? else : ?>
        <p>Такой страницы не существует</p>
        
        <? endif; ?>				
						
    </td>

