<html>
	<head>
		<title>Sample website</title>
		<base href="<?php echo App\Config\System::BASE_URL; ?>"/>
		<script type="text/javascript" src="<?php echo App\Config\System::MEDIA_URL; ?>public/js/jquery-1.4.3.min.js"></script>
		<?php echo Sys\Helpers\Html::getAjaxCalls(); ?>
		<link rel="stylesheet" type="text/css" href="public/css/style.css" />
	</head>
	<body>
		<div class="pagina">
			<h1 id="test">Sample title</h1>
			<p><?php echo Sys\Helpers\Html::link('cms/index', 'Inapoi la pagina principala!')?></p>
			<?php echo $this->content; ?>
			<?php echo \Sys\ZeroG::callController('blog/listView'); ?>
			<?php print_r(Sys\ZeroG::getParams());?>
		</div>
	</body>
</html>