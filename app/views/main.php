<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<title>Sample website</title>
		<base href="<?php echo App\Config\System::BASE_URL; ?>"/>
		<script type="text/javascript" src="<?php echo App\Config\System::MEDIA_URL; ?>public/js/jquery-1.4.3.min.js"></script>
		<?php echo Sys\Helper\Html::getAjaxCalls(); ?>
		<link rel="stylesheet" type="text/css" href="<?php echo App\Config\System::MEDIA_URL; ?>public/css/reset.css" />
		<link rel="stylesheet" type="text/css" href="<?php echo App\Config\System::MEDIA_URL; ?>public/css/style.css" />
		<link rel="stylesheet" type="text/css" href="<?php echo App\Config\System::MEDIA_URL; ?>public/css/theme-cool.css" />
	</head>
	<body>
		<div class="main">
			<div class="modal">
				<div class="window">
					<h2><?php echo \Sys\ZeroG::__('ZeroG version')?></h2>
					<div class="window_content">
						<div class="message notice">
							<p id="test">Sample title</p>
							<h3><?php echo \Sys\ZeroG::L()->getDate('D  => l, d/m (F)/Y M | H:i:s'); ?></h3>
						</div>
						<div class="message">
							<p><?php echo \Sys\ZeroG::L()->getCurrency('USD')->format(2987); ?></p>
							<p><?php echo Sys\Helper\Html::link('cms/index', \Sys\ZeroG::__('Back to main page', 'cms'))?></p>
						</div>
						<div class="message">
							<h3>Attention</h3>
							<p>For sensitive data input, such as passwords, the text field can also be set into secret mode where the input will not be echoed to display.</p>
						</div>
						<?php echo $this->content; ?>
						<?php echo \Sys\ZeroG::callController('blog/blog/listView'); ?>
						<div class="message attention">
							<?php print_r(Sys\ZeroG::getParams());?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>