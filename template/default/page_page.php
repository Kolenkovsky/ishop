	<td class="content">

        <? if($page) : ?>
            <h1>
        	   <?=$page['title'];?>					
            </h1>
            
            <p><?=$page['text'];?></p>
        
        <? else : ?>
        <p>Такой страницы не существует</p>
        
        <? endif; ?>				
						
    </td>