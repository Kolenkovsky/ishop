<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?=$title;?></title>
</head>
<body>
      <div style="width: 500px; margin: 100px auto 0px auto; padding: 50px; border: 3px solid red;">
           <!Тперь проверим получили ли мы переменную error,которая передается из Base_Error>
           <?if(isset($error)) : ?>
                <?foreach($error as $item) : ?>
                    <!просто выведем на экран переменную $item>
                    <?=$item.'<br />';?>
                <?endforeach;?>
           <? endif; ?> 
      </div>
</body>
</html>