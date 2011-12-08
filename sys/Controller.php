<?php
/**
 * The base controller class for zerog
 * User: radu.mogos
 * Date: 23.10.2010
 * Time: 22:16:30
 */

namespace Sys
{
	class Controller
	{
		/**
		 * Current executed action
		 * 
		 * @var <string> 
		 */
		protected $_action;

		public function __construct() {}

		/**
		 * Redirect to another page 
		 * @param <string> $path router/controller/action
		 */
		public function redirect($path, $code = 302)
		{
			header('Location: '.$this->getUrl($path), TRUE, $code);
		}

		/**
		 * Return the absolute url + the path appended to it
		 * @param <string> $path
		 * @return <string>
		 */
		public function getUrl($path)
		{
			return \Z::getHelper('Sys\Helper\Html')->url($path);
		}

		public function getRequest()
		{
			return \Z::getRequest();
		}

		public function preDispatch()
		{
			\Z::dispatchEvent('controller_action_predispatch', 
					array('controller' => $this));
			\Z::dispatchEvent('controller_action_predispatch_'.$this->_action,
					array('controller' => $this));
		}

		public function postDispatch()
		{
			\Z::dispatchEvent('controller_action_postdispatch', 
					array('controller' => $this));
			\Z::dispatchEvent('controller_action_postdispatch_'.$this->_action,
					array('controller' => $this));
		}

		public function dispatch()
		{
			$method = $this->getRequest()->getParam('action').'Action';
			$this->_action = $method;
			if (!is_callable(array($this, $method)))
				$method = 'noRouteAction';
			\Z::getProfiler()->start('controller_preDispatch');
			$this->preDispatch();
			\Z::getProfiler()->stop('controller_preDispatch');

			\Z::getProfiler()->start('controller_dispatch');
			$this->$method();
			\Z::getProfiler()->stop('controller_dispatch');

			\Z::getProfiler()->start('controller_postDispatch');
			$this->postDispatch();
			\Z::getProfiler()->stop('controller_postDispatch');
		}

		public function noRouteAction()
		{
			$this->redirect('page/view/error');
		}
	}
}
