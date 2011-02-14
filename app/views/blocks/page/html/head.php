		<meta charset="utf-8" />
		<title>Sample website</title>
		<base href="<?php echo App\Config\System::BASE_URL; ?>"/>
		<?php echo Sys\Helper\Html::addCss('public/css/reset.css', 'public/css/style.css','public/css/theme-cool.css'); ?>
		<script type="text/javascript" src="<?php echo App\Config\System::BASE_URL; ?>public/js/jquery-1.4.3.min.js"></script>
		<?php echo Sys\Helper\Html::getAjaxCalls(); ?>