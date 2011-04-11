<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, minimum-scale=1, maximum-scale=1">
	<title>Articles</title>
	<script type="text/javascript" src="http://code.jquery.com/jquery-1.5.min.js"></script>
<!--
	<script type="text/javascript" src="http://code.jquery.com/mobile/1.0a4/jquery.mobile-1.0a4.min.js"></script>
	<link rel="stylesheet"  href="http://code.jquery.com/mobile/1.0a4/jquery.mobile-1.0a4.min.css" />
-->
	<script type="text/javascript" src="jquery-mobile/jquery.mobile-1.0beta-pre.min.js"></script>
	<link rel="stylesheet"  href="jquery-mobile/jquery.mobile-1.0beta-pre.min.css" />
	<style type="text/css">
		#article-template { display: none; }
		#articles-loader .message-finished { display: none; }
	</style>
</head>

<body>

	<script>
	var articles_loaded = 0;
	var loading_lock = false;
	var loaded_everything = false;

	function onLoaderVisible(e, visible) {
		if (visible) {
			load_next_articles();
		}
	}

	function load_next_articles() {
		if (loaded_everything) {
			alert('plus rien à charger :)');
			return;
		}
		if (loading_lock) {
			alert('déjà en cours de chargement');
			return;
		}
		loading_lock = true;
		load_articles(articles_loaded, 10, function() {
			$('#articles-loader').data('inview', false);
			loading_lock = false;
		});
	}

	function load_articles(i, nb, callback) {
		$.ajax({
			"url": "articles.js.php?start="+i+"&nb="+nb,
			"dataType": "json",
			"error": function(xhr, status, err) {
				alert('Erreur chargement');
				callback();
			},
			"success": function(articles) {
				if (articles.length == 0) {
					loaded_everything = true;
					var loader = $('#articles-loader');
					loader.unbind('inview', onLoaderVisible);
					$('.message-loading', loader).hide();
					$('.message-finished', loader).show();
				} else {
					for (var n=0; n<articles.length; n++) {
						add_article(articles[n]);
					}
				}
				callback();
			}
		});
	}
	function add_article(article) {
		articles_loaded ++;
		$('.nb-articles', '#articles-loader').html(articles_loaded);
		var node = $('#article-template').clone(false);
		node.attr('id', '');
		$('.article-link', node).attr('href', 'article.php?id=' + article.id);
		$('.article-title', node).html(article.titre);
		$('.article-intro', node).html(article.intro);
		$('.article-date', node).html(article.datepub);
		node.insertBefore('#articles-loader');
	}
	</script>

	<div data-role="page" data-theme="b">
		<div data-role="header">
			<h1>Articles</h1>
		</div>
		<div data-role="content">
			<ul data-role="listview" data-inset="true">
				<li data-role="list-divider">Articles</li>
				<li id="article-template">
					<a href="{{ url }}" class="article-link">
						<h3><span class="article-title">{{ titre }}</span> (<span class="article-date">{{ datepub }}</span>)</h3>
						<p class="article-intro">{{ intro }}</p>
					</a>
					<span data-icon="arrow-r" />
				</li>
				<li id="articles-loader">
					<span class="message-loading">Chargement...</span>
					<span class="message-finished"><span class="nb-articles">{{ nb }}</span> articles chargés</span>
				</li>
			</ul>
		</div>
		<div data-role="footer"><h4>Footer ici...</h4></div>
	</div>

	<script type="text/javascript">$('#articles-loader').bind('inview', onLoaderVisible);</script>
	<script type="text/javascript" src="jquery.inview.js"></script>

</body>

</html>
