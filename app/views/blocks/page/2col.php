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
							<h3 id="test">2 columns template :)</h3>
						</div>
						<?php echo $this->getChildHtml('root.content'); ?>
						<?php echo $this->getChildHtml('root.left'); ?>
						<div class="message attention">
							<?php print_r(Sys\ZeroG::getParams());?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>