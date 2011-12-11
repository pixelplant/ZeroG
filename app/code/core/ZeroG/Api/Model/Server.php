<?php

namespace App\Code\Core\ZeroG\Api\Model
{
	/**
	 * Api Server
	 *
	 * @author radu.mogos
	 */
	class Server {

		protected $_adapter;

		protected $_adapterModels = array();

		public function __construct()
		{
			// so far we only have the xmlrpc adapter defined, while the
			// soap one is empty
			$this->_adapterModels = array(
				'xmlrpc' => 'api/server/adapter/xmlrpc',
				'soap'   => 'api/server/adapter/soap'
				);
		}

		/**
		 * Initialize the desired api server adapter
		 *
		 * @param <string> $adapter Adapter name
		 * @return Server
		 */
		public function init($adapter = '')
		{
			if ($adapter == '')
				throw new \Sys\Exception('You must specify a valid web service adapter');
			$this->_adapter = \Z::getSingleton($this->_adapterModels[$adapter]);
			if (!($this->_adapter instanceof \App\Code\Core\ZeroG\Api\Model\Server\Adapter\ApiAdapter))
				throw new \Sys\Exception('The adapter class for => %s must be declared as a webservice',
					$adapter);
			return $this;
		}

		/**
		 * Return the current webservice adapter
		 *
		 * @return <\App\Code\Core\ZeroG\Api\Model\Server\Adapter\ApiAdapter>
		 */
		public function getAdapter()
		{
			return $this->_adapter;
		}

		/**
		 * Execute the current adapter
		 */
		public function run()
		{
			$this->getAdapter()->run();
		}
	}
}
