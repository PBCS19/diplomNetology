<!DOCTYPE html>
<html lang="ru">
  <head>
    
  </head>
  <body>
    <details>
      <summary>Администраторы</summary><br>
      <div class="button"><a href="/admin/addAdmin">Добавить администратора</a></div><br>
      <table border="1">
        <tr>
          <td>Логин</td>
          <td>Пароль</td>
          <td>Изменить пароль</td>
        </tr>
        <?php foreach ($listAdmins as $admin) : ?>
        <tr>
          <td><?php echo $admin['login'] ?></td>
          <td><?php echo $admin['password'] ?></td>
          <td>
            <form method="POST" action="changePassword">
            <input type="text" name="editPas" value="<?php echo $admin['password']?>" />
            <button type="submit" name="goEditPas" value="<?php echo $admin['id']?>">Изменить</button>
            </form>
          </td>
          <td>
            <form method="POST" action="admin/delAdmin">
            <p><button name="deladmin" type="submit" value="<?php echo $admin['id']; ?>">Удалить</button></p>
            </form>
          </td>
        </tr>
        <?php endforeach; ?>
      </table>
    </details>
    <details>
      <summary>Категории и вопросы</summary><br>
      <form method="POST" action="admin/addCategory">
        <input type="text" name="addCategory" placeholder="Введите название категории"/>
        <button name="goAddCategory" type="submit" value="">Создать новую категорию</button> * Пустая категория не добавится!
      </form>
      <?php foreach ($categories as $category) : ?>
      <h2><?php echo $category['category']?>
            <form method="POST" action="admin/delCategory">
              <button name="delCategory" type="submit" value="<?php echo $category['id']; ?>">Удалить категорию и все вопросы в ней</button>
            </form></h2>
		<table id="<?php echo $category['id']?>" border="1">
                  <tr>
                    <td><b>Дата</b></td>
                    <td><b>Имя</b></td>
                    <td><b>E-mail</b></td>
                    <td><b>Вопрос</b></td>
                    <td><b>Ответ</b></td>
                    <td><b>Статус</b></td>
                    <td><b>Удалить</b></td>
                    <td><b>Изменить</b></td>
                  </tr>
                        <?php foreach ($questions as $question) : ?>
                          <?php if($category['id'] == $question['category_id']) : ?>
                          <tr>
                            <td>
                              <a><?php echo $question['date']?></a>
                            </td>
                            <td>
                              <a><?php echo $question['name']?></a>
                            </td>
                            <td>
                              <a><?php echo $question['email']?></a>    
                            </td>
			    <td>
		              <a><?php echo $question['question']?></a>
			    </td>
                            <td>
                              <p><?php echo $question['answer']?></p>
                            </td>
                            <?php if ($question['answer_id'] == null) : ?>
                            <td>
                              <p>Ожидает ответа</p>
                            </td>
                            <?php else : ?>
                                <?php if ($question['status'] == 0) : ?>
                                <td>
                                  <a>Открыт</a>
                                  <form method="POST" action="admin/statusQuestion">
                                    <button name="questionHide" type="submit" value="<?php echo $question['id']; ?>">Скрыть</button>
                                  </form>
                                </td>
                                <?php else : ?>
                                <td>
                                  <a>Скрыт</a>
                                  <form method="POST" action="admin/statusQuestion">
                                    <button name="questionOpen" type="submit" value="<?php echo $question['id']; ?>">Опубликовать</button>
                                  </form>
                                </td>
                                <?php endif; ?>
                            <?php endif; ?>
                                <td>
                                  <form method="POST" action="admin/delQuestion">
                                    <button name="delQuestion" type="submit" value="<?php echo $question['id']; ?>">Удалить вопрос</button>
                                  </form>
                                </td>
                                <td>
                                  <form method="POST" action="admin/edit">
                                    <button name="goEdit" type="submit" value="<?php echo $question['id']; ?>">Изменить/Ответить</button>
                                  </form>
                                </td>
                          </tr> 
                          <?php endif; ?> 
                        <?php endforeach; ?>
		</table> <!-- cd-faq-group -->
      <?php endforeach; ?> 
    </details> 
    <details>
      <summary>Вопросы без ответа</summary>
      <table border="1">
        <?php foreach ($questionsNoAnswer as $questionNoAnswer) : ?>
            <tr>
              <td>
                <a><?php echo $questionNoAnswer['date'] ?></a>
              </td>
              <td>
                <a><?php echo $questionNoAnswer['name'] ?></a>
              </td>
              <td>
                <a><?php echo $questionNoAnswer['email'] ?></a>    
              </td>
              <td>
                <a><?php echo $questionNoAnswer['question'] ?></a>
              </td>
              <td>
                <form method="POST" action="admin/delQuestion">
                  <button name="delQuestion" type="submit" value="<?php echo $questionNoAnswer['id']; ?>">Удалить вопрос</button>
                </form>
              </td>
              <td>
                <form method="POST" action="admin/edit">
                  <button name="goEdit" type="submit" value="<?php echo $questionNoAnswer['id']; ?>">Изменить/Ответить</button>
                </form>
              </td>
            </tr>
        <?php endforeach; ?>
      </table>
    </details>
  </body>
</html>

