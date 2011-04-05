<?php

require __DIR__ . '/config.php';
require __DIR__ . '/lib.php';

$conn = new PDO($dsn, $user, $pass);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if (!isset($_GET['id'])) {
	throw404();
}

$statement = $conn->query('SELECT id, titre, intro, auteur, datepub, texte FROM articles WHERE id = ' . $conn->quote($_GET['id'], PDO::PARAM_INT));
$article = $statement->fetch(PDO::FETCH_ASSOC);

if (!$article) {
	throw404();
}

?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, minimum-scale=1, maximum-scale=1">
	<title>Article - Titre</title>
	<link rel="stylesheet"  href="http://code.jquery.com/mobile/1.0a4/jquery.mobile-1.0a4.min.css" />
	<script type="text/javascript" src="http://code.jquery.com/jquery-1.5.min.js"></script>
	<script type="text/javascript" src="http://code.jquery.com/mobile/1.0a4/jquery.mobile-1.0a4.min.js"></script>
</head>

<body>
	<div data-role="page" data-theme="b">
		<div data-role="header">
			<a data-icon="back" data-rel="back" href="#">Back</a>
			<h1><?php echo htmlspecialchars($article['titre']) ?></h1>
		</div>
		<div data-role="content">
			<h1><?php echo $article['titre'] ?></h1>
			<p>Par <?php echo htmlspecialchars($article['auteur']) ?>, le <?php echo date('d/m/Y H:i:s', $article['datepub']) ?></p>
			<p><?php echo nl2br(htmlspecialchars($article['texte'])) ?></p>
		</div>
		<div data-role="footer"><h4>Footer ici...</h4></div>
	</div>
</body>

</html>
