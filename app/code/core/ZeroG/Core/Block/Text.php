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
			// If the html code is set for this block then it means it has no
			// children...
			if ($this->_code != null)
				return $this->_code;
			// otherwise we go through all its children
			if ($this->_children)
				foreach ($this->_children as $child)
				{
					$content .= $child->render();
				}
			return $content;
		}
	}
}