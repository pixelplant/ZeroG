<?php
/* 
 * Installer script for the tables in ZeroG_Page version 0.1.0
 */

$installer = $this->getInstaller();

/*
 * Look in the file app/code/core/zerog/core/etc/config.xml to see the link
 * between the resource name and the table (in <entities> tag)
 */

// url rewrites
$installer->newTable($installer->getResourceTable('page/cms'))
		->addColumn('page_id', $installer::TYPE_INTEGER, null, array(
			'unsigned' => TRUE,
			'primary' => TRUE,
			'nullable' => FALSE,
			'identity' => TRUE
			))
		->addColumn('title', $installer::TYPE_TEXT, 255, array(
			'nullable' => FALSE,
			'default' => ''
		))
		->addColumn('meta_keywords', $installer::TYPE_TEXT, 255, array(
			),'SEO meta keywords')
		->addColumn('meta_description', $installer::TYPE_TEXT, 255, array(
			),'SEO meta description')
		->addColumn('identifier', $installer::TYPE_TEXT, 100, array(
			'nullable' => FALSE,
			))
		->addColumn('content_heading', $installer::TYPE_TEXT, 255, array(
			'nullable' => FALSE,
			'default' => ''
			))
		->addColumn('content', $installer::TYPE_TEXT, null, array(
			'nullable' => TRUE
			));

// run the installer code
$installer->run();
