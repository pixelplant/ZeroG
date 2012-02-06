<?php
/*
 * Installer script for the tables in Thedev_Newsletter version 0.1.0
 */

$installer = $this->getInstaller();

/*
 * Look in the file etc/config.xml to see the link
 * between the resource name and the table (in <entities> tag)
 */

// url rewrites
$installer->newTable($installer->getResourceTable('newsletter/item'))
		->addColumn('item_id', $installer::TYPE_INTEGER, null, array(
			'unsigned' => TRUE,
			'primary' => TRUE,
			'nullable' => FALSE,
			'identity' => TRUE
			))
		->addColumn('title', $installer::TYPE_TEXT, 255, array(
			'nullable' => FALSE,
			'default' => ''
		));

// run the installer code
$installer->run();
