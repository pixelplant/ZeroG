<div class="message">
	<?php echo \Sys\Helper\Html::link('cms', 'CMS index'); ?>
</div>
<div class="message">
	<p>
		<?php echo \Sys\ZeroG::__('Click this link:', 'cms'); ?>
		<?php echo Sys\Helper\Html::link('cms/test/id/5/page/32/search/fast', 'CMS Controller test action!')?>
	</p>
	<p>
		<strong><?php echo \Sys\ZeroG::__('Ajax call test', 'cms'); ?></strong>
		<?php echo Sys\Helper\Html::ajaxlink('cms/ajax', \Sys\ZeroG::__('run first Ajax call', 'cms'), array("success" => "$('#test').fadeOut().html(data).fadeIn();")); ?>
	</p>
	<p>
		<strong><?php echo \Sys\ZeroG::__('Ajax call test', 'cms'); ?></strong>
		<?php echo Sys\Helper\Html::ajaxlink('cms/ajax2', \Sys\ZeroG::__('run second Ajax call', 'cms'), array("success" => "$('#test').fadeOut().html(data).fadeIn();")); ?>
	</p>
</div>