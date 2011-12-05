<?php

namespace App\Code\Local\Pixelplant\Blog\Model\Resource\Post
{
/**
 * Description of Collection
 *
 * @author radu.mogos
 */
	class Collection extends \Sys\Database\Model\Collection
	{
		protected function _construct()
		{
			$this->_init('blog/post');
		}
	}
}
