<!DOCTYPE html>
<html lang="ru">
  <head>
    
  </head>
  <body>
    <?php foreach ($array['questions'] as $question) : ?>
    <form method="POST" action="edit">
      <p>
        <a>Имя:</a><br>
        <input type="text" name="changeName" value="<?php echo $question['name']?>"/><br>
      </p>
      <p>
        <a>Вопрос:</a><br>
        <input type="text" name="changeQuestion" value="<?php echo $question['question']?>"/><br>                   
      </p>
      <p>
        <a>Ответ:</a><br>
        <input type="text" name="changeAnswer" value="<?php echo $question['answer']?>"/><br>
      </p>
      <p>
        <lable id="updateCategory">Категория:</lable><br>
        <select name="updateCategory">
        <?php foreach ($array['categories'] as $category) :?>
          <option <?php if ($category['id'] == $question['category_id']):?>
          selected<?php endif; ?> value="<?php echo $category['id']?>"><?php echo $category['category']?></option>
        <?php endforeach; ?>
        </select>
      </p>
      <p>
        <lable id="status">Статус:</lable><br>
        <select name="status">
          <option <?php if ($question['status'] == 0) : ?>selected<?php endif; ?> value="0">Открыт</option>
          <option <?php if ($question['status'] == 1) : ?>selected<?php endif; ?> value="1">Скрыт</option>
        </select>
      </p>
      <p>
        <button name="updateQuestion" type="submit" value="<?php echo $question['id']; ?>">Изменить/Ответить</button>
      </p>
    </form>
    <?php endforeach; ?>
  </body>
</html>

