<?php

namespace App\Code\Core\ZeroG\Core\Block
{
	use \Sys\Layout\Block as Block;
	
	class Text extends Block
	{
		public function render()
		{
			// render all children of this block
			$content = '';
			foreach ($this->children as $child)
			{
				$content .= $child->render();
			}
			return $content;
		}
	}
}