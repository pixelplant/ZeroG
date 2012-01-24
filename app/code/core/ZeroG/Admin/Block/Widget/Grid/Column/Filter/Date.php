<?php

namespace App\Code\Core\ZeroG\Admin\Block\Widget\Grid\Column\Filter
{

	/**
	 * Description of Date Filter
	 *
	 * @author radu.mogos
	 */
	class Date extends \App\Code\Core\ZeroG\Admin\Block\Widget\Grid\Column\Filter\Base
	{

		/**
		 * _init is called after the parent column was attached to this filter
		 */
		/*protected function _init()
		{
			$this->getColumn()->setWidth('160px');
			//$this->setTemplate('widget/grid/column/filter/date.phtml');
		}*/

		public function getContent()
		{
			//return $this->render();
			
			$startId =  $this->getHtmlId().'_from';
			$endId   =  $this->getHtmlId().'_to';

			return '
				<div class="grid-input-medium">
					<div class="range-line">
						<label for="'.$startId.'">'.$this->__('From').'</label>
						<input class="input-text" type="text" name="'.$this->getFieldName().'[from]" id="'.$startId.'" value="'.$this->getEscapedValue('from').'" />
					</div>
				</div>
				<div class="grid-input-medium">
					<div class="range-line">
						<label for="'.$endId.'">'.$this->__('To').'</label>
						<input class="input-text" type="text" name="'.$this->getFieldName().'[to]" id="'.$endId.'" value="'.$this->getEscapedValue('to').'" />
					</div>
				</div>
				<script>
				$(function()
				{
					$("#'.$startId.'").datepicker({defaultDate: null});
					$("#'.$endId.'").datepicker({defaultDate: null});
				});
				</script>';
		}

		public function getValue($index = null)
		{
			if ($index)
			{
				if ($data = $this->getData('value', 'orig_'.$index))
				{
					return $data;//date('Y-m-d', strtotime($data));
				}
				return null;
			}

			return $this->getData('value');
		}

		public function setValue($value)
		{
			// add locale check
			if (!empty($value['from']))
			{
				$value['orig_from'] = $value['from'];
			}
			if (!empty($value['to']))
			{
				$value['orig_to'] = $value['to'];
			}
			if (empty($value['from']) && empty($value['to']))
				$value = null;
			$this->setData('value', $value);
			return $this;
		}

		public function getCondition()
		{
			$value = $this->getValue();

			return $value;
		}
	}
}
