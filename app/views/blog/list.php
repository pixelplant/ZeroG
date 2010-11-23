<h2>Blog posts</h2>
<?php if (is_array($this->posts)): ?>
	<ul>
	<?php foreach ($this->posts as $post): ?>
		<li><h3><?php echo \Sys\Helpers\Html::link('cms/blog/'.$post->getId(), $post->getTitle()); ?></h3>
		<p><?php echo $post->getText(); ?></p>
		</li>
	<?php endforeach; ?>
	</ul>
<?php endif; ?>