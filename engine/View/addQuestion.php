<!DOCTYPE html>
<html lang="ru">
  <head>
    
  </head>
  <body>
    <?php if (!empty($errors)) : ?>
    <p style="color: red;"><?= array_shift($errors);?></p>
    <?php endif ?>
    <form method="POST" action="add">
      <p>
        <lable id="name">Ваше имя:</lable><br>
        <input name="name" type="text" placeholder="Укажите имя">
      </p>
      <p>
        <lable id="email">E-mail:</lable><br>
        <input name="email" type="email" placeholder="Укажите email">
      </p>
      <p>
        <lable id="question">Вопрос:</lable><br>
        <textarea name="question" rows="4" cols="20" placeholder="Напишите вопрос"></textarea>
      </p>
      <p>
        <lable id="category">Категория:</lable><br>
        <select name="category">
        <?php foreach ($categories as $category) :?>
          <option value="<?php echo $category['id']?>"><?php echo $category['category']?></option>
        <?php endforeach; ?>
        </select>
      </p>
      <p>
        <input name="submit" type="submit" value="Отправить">
      </p>
      <a href="/">На главную</a>
    </form>
  </body>
</html>  
