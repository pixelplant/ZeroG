<div class="message">
	<!--<?php echo $this->block; ?>
	<form method="post" action="">
		<label for="username">Username</label>
		<?php echo Sys\Helper\Html::input('username', $this->username);?>
		<label for="password">Password</label>
		<?php echo Sys\Helper\Html::input('password', $this->password);?>
		<input type="submit" value="Register">
	</form>-->
	<p>
		<?php echo \Sys\ZeroG::__('Click this link:', 'cms'); ?>
		<?php echo Sys\Helper\Html::link('cms/test/id/5/page/32/search/fast', 'CMS Controller test action!')?>
	</p>
	<p>
		<?php echo \Sys\ZeroG::__('This should be', 'cms'); ?>
		<?php echo Sys\Helper\Html::ajaxlink('cms/ajax', \Sys\ZeroG::__('an AJAX call', 'cms'), array("success" => "$('#test').fadeOut().html(data).fadeIn();")); ?>
	</p>
	<p>Asta ar trebui sa fie <?php echo Sys\Helper\Html::ajaxlink('cms/ajax2', 'al doilea apel ajax', array("success" => "$('#mood').fadeOut().html(data).fadeIn();")); ?></p>
</div>