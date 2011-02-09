<h3>Blog posts</h3>
<?php if (is_array($this->posts)): ?>
	<ul>
	<?php foreach ($this->posts as $post): ?>
		<li><h3><?php echo \Sys\Helpers\Html::link('cms/blog/id/'.$post->getId(), $post->getTitle()); ?></h3>
		<p><?php echo $post->getText(); ?></p>
		</li>
	<?php endforeach; ?>
	</ul>
<?php endif; ?>