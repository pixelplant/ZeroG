<?php
/* 
 * Installer script for the tables in Pixelplant_Blog version 0.1.1
 */

$installer = $this->getInstaller();

// Posts - Website view reference table
$installer->newTable($installer->getResourceTable('blog/site'))
		->addColumn('post_id', $installer::TYPE_INTEGER, null, array(
			'unsigned' => TRUE,
			'nullable' => TRUE,
			),'Post id')
		->addColumn('website_view_id', $installer::TYPE_INTEGER, null, array(
			'nullable' => TRUE,
			'unsigned' => TRUE,
			),'Website view where the post is visible')
		->setComment('Posts - website view reference table');

$installer->updateTable($installer->getResourceTable('blog/post'))
		->addColumn('published', $installer::TYPE_INTEGER, 1, array(
			'nullable' => FALSE,
			'default'  => 1
		),'Is the post published or not?');

// run the installer code
$installer->run();
