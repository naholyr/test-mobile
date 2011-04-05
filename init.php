<?php

require __DIR__ . '/config.php';

$conn = new PDO($dsn, $user, $pass);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$conn->query('DROP TABLE IF EXISTS articles');
$conn->query('CREATE TABLE articles (
	id INTEGER PRIMARY KEY ,
	titre VARCHAR( 255 ) NOT NULL ,
	section VARCHAR( 255 ) NOT NULL ,
	auteur VARCHAR( 255 ) NOT NULL ,
	intro TEXT NOT NULL ,
	texte LONGTEXT NOT NULL ,
	datepub TIMESTAMP NOT NULL
);');

$sections = array('Général', 'Humour', 'Actualités');
$auteurs = array('Bob', 'Bill', 'John');

$insert = $conn->prepare('INSERT INTO articles (titre, section, auteur, intro, texte, datepub) VALUES (?, ?, ?, ?, ?, ?);');

for ($i=0; $i<100; $i++) {
	$texte = `fortune -l`;

	$lines = explode("\n", $texte);
	while (count($lines) > 0 && !preg_match('/[a-zA-Z0-9]{3}/', $titre = trim(array_shift($lines)))) {
		continue;
	}
	$titre = substr($titre, 0, 50);

	if (strlen($texte) > 150) {
		$intro = substr($texte, 0, 147) . '...';
	} else {
		$intro = $texte;
	}

	$section = $sections[array_rand($sections)];
	$auteur = $auteurs[array_rand($auteurs)];
	$datepub = mktime(mt_rand(8, 17), mt_rand(0, 59), mt_rand(0, 59), mt_rand(3, 5), mt_rand(1, 30), 2011);

	if ($insert->execute(array($titre, $section, $auteur, $intro, $texte, $datepub))) {
		echo ($i+1), '. ', $titre, "\n";
	}
}

