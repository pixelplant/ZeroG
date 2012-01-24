<?php

namespace App\Code\Local\Pixelplant\Blog\Model\Resource\Category
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
			$this->_init('blog/category', 'cat_id');
		}
	}
}
