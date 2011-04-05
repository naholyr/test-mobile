<?php

require __DIR__ . '/config.php';
require __DIR__ . '/lib.php';

$conn = new PDO($dsn, $user, $pass);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if (!isset($_GET['start']) || !isset($_GET['nb'])) {
	throw404();
}

$start = intval($_GET['start']);
$nb = intval($_GET['nb']);
$statement = $conn->query('SELECT id, titre, intro, auteur, datepub FROM articles ORDER BY datepub DESC LIMIT ' . $start . ', ' . $nb);

$articles = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($articles as &$article) {
	$article['datepub'] = date('d/m/Y H:i', $article['datepub']);
}

header('Content-Type: application/json');
echo json_encode($articles);
