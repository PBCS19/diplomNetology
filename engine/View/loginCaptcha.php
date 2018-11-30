<!DOCTYPE html>
<html lang="ru">  
  <head>
    <title>Авторизация</title>
    <script src='https://www.google.com/recaptcha/api.js'></script>
  </head>
  <body>
    <?php if (!empty($errors)) : ?>
    <p style="color: red;"><?= array_shift($errors);?></p>
    <?php endif ?>
    <form method="POST" action="auth">
      <p>
        <lable id="login">Логин:</lable><br>
        <input name="login" type="text">
      </p>
      <p>
        <lable id="password">Пароль:</lable><br>
        <input name="password" type="password">
      </p>
      <div class="g-recaptcha" data-sitekey="6LfASncUAAAAALxm8HOzYmtziGG5o72r2_OG1X1o"></div>
      <p>
        <input name="submit" type="submit" value="Войти">
      </p>
    </form>
    <a href="/">На главную</a>
  </body>
</html>

