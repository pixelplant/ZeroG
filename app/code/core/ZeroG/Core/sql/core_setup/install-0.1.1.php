<?php
/* 
 * Installer script for the tables in ZeroG_Core version 0.1.1
 */

$installer = $this->getInstaller();

/*
 * Look in the file app/code/core/zerog/core/etc/config.xml to see the link
 * between the resource name and the table (in <entities> tag)
 */

/**
 * Added an attributes table so we can define custom attributes for every table if
 * wa want...
*/

$installer->newTable($installer->getResourceTable('core/attribute'))
		->addColumn('attribute_id', $installer::TYPE_INTEGER, null, array(
			'unsigned' => TRUE,
			'nullable' => FALSE,
			'primary'  => TRUE,
			'identity' => TRUE,
			))
		->addColumn('attribute_name', $installer::TYPE_TEXT, 255, array(
			'nullable' => FALSE,
			'default'  => ''
		))
		->addColumn('source_table', $installer::TYPE_TEXT, 255, array(
			'nullable' => FALSE,
			'default'  => ''
		))
		->setComment('List of custom defined attributes');

// run the installer code
$installer->run();
