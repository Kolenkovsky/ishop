<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="description" content="<?=$description;?>" />
<meta name="keywords" content="<?=$keywords;?>" />


<!Для подключения стилей>
<? if($styles) : ?>
    <? foreach($styles as $style) : ?>
        <link rel="stylesheet" type="text/css" href="<?=$style;?>" />
    <? endforeach; ?>
<? endif; ?>

<!Для подключения скриптов>
<? if($scripts) : ?>
    <? foreach($scripts as $script) : ?>
        <script type="text/javascript" src="<?=$script;?>"></script>
    <? endforeach; ?>
<? endif; ?>

<title><?=$title;?></title>
</head>

<body>
<div class="karkas-main">
<div class="karkas">
	<div class="main">
		<div class="header">
			<a href="/"><img src="<?=SITE_URL.VIEW;?>/images/logo.png" class="logo" alt="Промстрой энерго" /></a>
			<ul class="menu">
            <!Ниже мы сделаем без прохождения циклом а будем напрямую указывать ноиер ячейки и значение надписи>
				<li><a href="<?=SITE_URL;?>catalog/type/<?=$header_menu[0]['type_id']?>" class="step1"><span class="menu_links_text"><?=$header_menu[0]['type_name']?></span></a></li>
				<li><a href="<?=SITE_URL;?>catalog/type/<?=$header_menu[1]['type_id']?>" class="step2"><span class="menu_links_text1"><?=$header_menu[1]['type_name']?></span></a></li>
				<li><a href="<?=SITE_URL;?>catalog/type/<?=$header_menu[2]['type_id']?>" class="step3"><span class="menu_links_text2"><?=$header_menu[2]['type_name']?></span></a></li>
				<li><a href="<?=SITE_URL;?>catalog/type/<?=$header_menu[3]['type_id']?>" class="step4"><span class="menu_links_text3"><?=$header_menu[3]['type_name']?></span></a></li>
			</ul>
			<div class="search">
				<p>поиск по сайту:</p>
				<form action="<?=SITE_URL;?>search" method="POST">
					<img src="<?=SITE_URL.VIEW;?>/images/loopa.gif" alt="Отправить запрос"/>
					<input class="inpt" type="text" name="txt1"  />
					<input type="image" src="<?=SITE_URL.VIEW;?>/images/search.gif" name="go" />
				</form>
			</div>
		</div>
 	</div>
