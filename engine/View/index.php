<!doctype html>
<html lang="ru" class="no-js">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700' rel='stylesheet' type='text/css'>

	<link rel="stylesheet" href="css/reset.css"> <!-- CSS reset -->
	<link rel="stylesheet" href="css/style.css"> <!-- Resource style -->
	<script src="js/modernizr.js"></script> <!-- Modernizr -->
        <style>
          a.button {
          font-weight: 700;
          color: white;
          text-decoration: none;
          padding: .8em 1em calc(.8em + 3px);
          border-radius: 3px;
          background: #a9c056;
          box-shadow: 0 -3px #778b43 inset;
          transition: 0.2s;
          }
          a.button:hover { 
              background: #778b43; 
          }
          a.button:active {
            background: #667a3d;
            box-shadow: 0 3px #a9c056 inset;
          }
          div.button {
            margin: 2% 1%;
          }
        </style>
	<title>FAQ</title>
</head>
<body>
<header>
	<h1>FAQ</h1>
</header>
<div class="button"><a class="button" href="/main/add">Задать вопрос</a></div>
<section class="cd-faq">
	<ul class="cd-faq-categories">
          <?php foreach ($array['categories'] as $key=>$category) : ?>
            <?php if ($key == 0) :?>
		<li><a class="selected" href="#<?php echo $category['id']?>"><?php echo $category['category']?></a></li>
            <?php else: ?>
                <li><a href="#<?php echo $category['id']?>"><?php echo $category['category']?></a></li>
            <?php endif; ?>
          <?php endforeach; ?>
	</ul> <!-- cd-faq-categories -->

	<div class="cd-faq-items">
          <?php foreach ($array['categories'] as $category) : ?>
		<ul id="<?php echo $category['id']?>" class="cd-faq-group">
			<li class="cd-faq-title"><h2><?php echo $category['category']?></h2></li>
                        <?php foreach ($array['questions'] as $question) : ?>
                          <?php if($category['id'] == $question['category_id']) : ?>
			    <li>
				<a class="cd-faq-trigger" href="#0"><?php echo $question['question']?></a>
				<div class="cd-faq-content">
					<p><?php echo $question['answer']?></p>
				</div> <!-- cd-faq-content -->
			    </li>
                          <?php endif; ?> 
                        <?php endforeach; ?>
		</ul> <!-- cd-faq-group -->
          <?php endforeach; ?>  
	</div> <!-- cd-faq-items -->
	<a href="#0" class="cd-close-panel">Close</a>
</section> <!-- cd-faq -->
<script src="js/jquery-2.1.1.js"></script>
<script src="js/jquery.mobile.custom.min.js"></script>
<script src="js/main.js"></script> <!-- Resource jQuery -->
</body>
</html>