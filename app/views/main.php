<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<title>Sample website</title>
		<base href="<?php echo App\Config\System::BASE_URL; ?>"/>
		<?php echo Sys\Helper\Html::addCss('public/css/reset.css', 'public/css/style.css','public/css/theme-cool.css'); ?>
		<script type="text/javascript" src="<?php echo App\Config\System::BASE_URL; ?>public/js/jquery-1.4.3.min.js"></script>
		<?php echo Sys\Helper\Html::getAjaxCalls(); ?>
	</head>
	<body>
		<div class="main">
			<div class="modal">
				<div class="window">
					<h2><?php echo \Sys\ZeroG::__('ZeroG version')?></h2>
					<div class="window_content">
						<div class="message notice">
							<h3 id="test"><?php echo \Sys\ZeroG::__('Locale sample. Current locale:'); ?> <em><?php echo \App\Config\System::LOCALE; ?></em></h3>
							<p><?php echo \Sys\ZeroG::L()->getDate('D  => l, d/m (F)/Y M | H:i:s'); ?></p>
						</div>
						<div class="message">
							<p>
								<?php echo \Sys\ZeroG::__('Currency format:'); ?><br/>
								- <?php echo \Sys\ZeroG::L()->getCurrency('USD')->format(1); ?><br/>
								- <?php echo \Sys\ZeroG::L()->getCurrency('USD')->format(2987.3982); ?>
							</p>
						</div>
						<?php echo $this->content; ?>
						<div class="message attention">
							<?php print_r(Sys\ZeroG::getParams());?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>