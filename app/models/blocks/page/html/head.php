<?php

namespace App\Models\Blocks\Page\Html
{
	class Head extends \Sys\Layout\Block
	{
		public function addJs()
		{
			echo 'added JS';
		}

		public function addCss()
		{
			echo 'added CSS';
		}
	}
}