<td class="content">
	
	<h1>
		Авторизация
	</h1>
	<p style="color: red;">
    <!вывоодим сообщение об ошибке,если она была>
	<? if($error):?>
		<?=$error;?>
	<? endif;?>
	</p>
	<form action='<?=SITE_URL?>login' method='post'>
		<span>Логин:</span><br/>
		<input type='text' name = 'name'><br/>
		<span>Пароль:</span><br/>
		<input type='password' name ='password'><br/>
		
		<input class="submit_login" type='submit' name='submit' value ='Войти'><br/>
	</form>
	
</td>