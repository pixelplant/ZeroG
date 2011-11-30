<?php
/* 
 * Installer script for the tables in ZeroG_Core version 0.1.0
 */

$installer = \Z::getModel('core/installer');

$installer->newTable('core_resource')
		->addColumn('code', $installer::TYPE_TEXT, 50, array(
			'nullable' => FALSE,
			'default' => '',
			'primary' => TRUE,
			),'Extension identifier')
		->addColumn('version', $installer::TYPE_TEXT, 50, array(
			'nullable' => FALSE,
			'default' => '',
			),'The currently installed version of this extension')
		->setComment('List of ZeroG installed extensions and their version number');

$installer->newTable('core_cache_option')
		->addColumn('code', $installer::TYPE_TEXT, 50, array(
			'nullable' => FALSE,
			'primary' => TRUE,
			),'')
		->addColumn('value', $installer::TYPE_INTEGER, 3, array())
		->setComment('List of objects for which caching is enabled');

$installer->newTable('core_config_data')
		->addColumn('config_id', $installer::TYPE_INTEGER, null, array(
			'unsigned' => TRUE,
			'primary' => TRUE,
			'nullable' => FALSE,
			'identity' => TRUE,
			),'Configuration string id')
		->addColumn('path', $identity::TYPE_TEXT, 255, array(
			'nullable' => FALSE,
			'default' => ''
			), 'Configuration path, separated by slashes')
		->addColumn('website_id', $identity::TYPE_INTEGER, null, array(
			'nullable' => FALSE,
			'default' => 0,
		))
		->addColumn('value', $identity::TYPE_TEXT, 255, array(
			'nullable' => FALSE,
			'default' => ''
			))
		->setComment('ZeroG specific configuration data');

$installer->newTable('core_website')
		->addColumn('website_id', $identity::TYPE_INTEGER, null, array(
			'nullable' => FALSE,
			'primary' => TRUE,
			'identity' => TRUE,
			'unsigned' => TRUE,
			),'')
		->addColumn('code', $identity::TYPE_TEXT, 40, array(
			'default' => '',
			'nullable' => FALSE,
		),'')
		->addColumn('name', $identity::TYPE_TEXT, 60, array(
			'default' => '',
			'nullable' => FALSE,
			),'')
		->addColumn('is_default', $identity::TYPE_SMALLINT, 1, array(
			'nullable' => FALSE,
			'default' => 0,
		),'')
		// INSERT SOME DEFAULT DATA TOO, CREATING THE ADMIN WEBSITE
		// AND THE MAIN FRONTEND WEBSITE
		->insertData(
			array('website_id' => 0,
				'code' => 'admin',
				'name' => 'Admin',
				'is_default' => 0))
		->insertData(
			array('website_id' => 1,
				'code' => 'base',
				'name' => 'Main Website',
				'is_default' => 1));

$installer->run();
