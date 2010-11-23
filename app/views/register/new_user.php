<!--<?php echo $this->block; ?>
<form method="post" action="">
	<label for="username">Username</label>
	<?php echo Sys\Helpers\Html::input('username', $this->username);?>
	<label for="password">Password</label>
	<?php echo Sys\Helpers\Html::input('password', $this->password);?>
	<input type="submit" value="Register">
</form>-->
<p>Click this link: <?php echo Sys\Helpers\Html::link('cms/test/id/5/page/32/search/fast', 'CMS Controller test action!')?></p>
<p>Asta ar trebui sa fie <?php echo Sys\Helpers\Html::ajaxlink('cms/ajax', 'un apel ajax', array("success" => "$('#test').fadeOut().html(data).fadeIn();")); ?></p>
<p>Asta ar trebui sa fie <?php echo Sys\Helpers\Html::ajaxlink('cms/ajax2', 'al doilea apel ajax', array("success" => "$('#mood').fadeOut().html(data).fadeIn();")); ?></p>