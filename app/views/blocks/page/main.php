<!DOCTYPE html>
<html>
	<head>
		<?php echo $this->getChildHtml('root.head'); ?>
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
						<?php echo $this->getChildHtml('root.left'); ?>
						<?php echo $this->getChildHtml('root.content'); ?>
						<div class="message attention">
							<?php print_r(Sys\ZeroG::getParams());?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>