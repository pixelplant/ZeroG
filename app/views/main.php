<html>
	<head>
		<title>Sample website</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<base href="<?php echo App\Config\System::BASE_URL; ?>"/>
		<script type="text/javascript" src="<?php echo App\Config\System::MEDIA_URL; ?>public/js/jquery-1.4.3.min.js"></script>
		<?php echo Sys\Helper\Html::getAjaxCalls(); ?>
		<link rel="stylesheet" type="text/css" href="<?php echo App\Config\System::MEDIA_URL; ?>public/css/style.css" />
	</head>
	<body>
		<div class="main">
			<div class="modal">
				<div class="window">
					<h1><?php echo \Sys\ZeroG::__('ZeroG version')?></h1>
					<h2 id="test">Sample title</h2>
					<div class="window_content">
						<h3><?php echo \Sys\ZeroG::L()->getDate('D  => l, d/m (F)/Y M | H:i:s'); ?></h3>
						<p><?php echo \Sys\ZeroG::L()->getCurrency('USD')->format(2987); ?></p>
						<p><?php echo Sys\Helper\Html::link('cms/index', \Sys\ZeroG::__('Back to main page', 'cms'))?></p>
						<?php echo $this->content; ?>
						<?php echo \Sys\ZeroG::callController('blog/blog/listView'); ?>
						<?php print_r(Sys\ZeroG::getParams());?>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>