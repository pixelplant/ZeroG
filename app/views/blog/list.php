<div class="message">
	<h3>Blog posts</h3>
	<?php if (is_array($this->posts)): ?>
		<ul>
		<?php foreach ($this->posts as $post): ?>
			<li><?php echo \Sys\Helper\Html::link('cms/blog/id/'.$post->getId(), $post->getTitle()); ?><br/>
			<?php echo $post->getText(); ?>
			</li>
		<?php endforeach; ?>
		</ul>
	<?php endif; ?>
</div>