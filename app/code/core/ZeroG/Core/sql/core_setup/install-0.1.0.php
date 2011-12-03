<?php
/* 
 * Installer script for the tables in ZeroG_Core version 0.1.0
 */

$installer = $this->getInstaller();

/*
 * Look in the file app/code/core/zerog/core/etc/config.xml to see the link
 * between the resource name and the table (in <entities> tag)
 */

// stores a list of all the installed extensions(resources) and their version
$installer->newTable($installer->getResourceTable('core_resource/resource'))
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

$installer->newTable($installer->getResourceTable('core_resource/cache_option'))
		->addColumn('code', $installer::TYPE_TEXT, 50, array(
			'nullable' => FALSE,
			'primary' => TRUE,
			),'')
		->addColumn('value', $installer::TYPE_SMALLINT, 3, array())
		->setComment('List of objects for which caching is enabled');

// config data stored in database, which overwrites config data in xml files
$installer->newTable($installer->getResourceTable('core_resource/config_data'))
		->addColumn('config_id', $installer::TYPE_INTEGER, null, array(
			'unsigned' => TRUE,
			'primary' => TRUE,
			'nullable' => FALSE,
			'identity' => TRUE,
			),'Configuration string id')
		->addColumn('path', $installer::TYPE_TEXT, 255, array(
			'nullable' => FALSE,
			'default' => ''
			), 'Configuration path, separated by slashes')
		->addColumn('website_id', $installer::TYPE_INTEGER, null, array(
			'nullable' => FALSE,
			'default' => 0,
		),'')
		->addColumn('value', $installer::TYPE_TEXT, 255, array(
			'nullable' => FALSE,
			'default' => ''
			),'')
		->setComment('ZeroG specific configuration data');

// core website - stores all this installation's websites
$installer->newTable($installer->getResourceTable('core_resource/website'))
		->addColumn('website_id', $installer::TYPE_INTEGER, null, array(
			'nullable' => FALSE,
			'primary' => TRUE,
			'identity' => TRUE,
			'unsigned' => TRUE,
			),'')
		->addColumn('code', $installer::TYPE_TEXT, 40, array(
			'default' => '',
			'nullable' => FALSE,
		),'')
		->addColumn('name', $installer::TYPE_TEXT, 60, array(
			'default' => '',
			'nullable' => FALSE,
			),'')
		->addColumn('is_default', $installer::TYPE_SMALLINT, 1, array(
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

// core_website_group - you can group multiple website_views together in one group
$installer->newTable($installer->getResourceTable('core_resource/website_group'))
		->addColumn('group_id', $installer::TYPE_INTEGER, null, array(
			'unsigned' => TRUE,
			'identity' => TRUE,
			'primary' => TRUE,
			'nullable' => FALSE
			))
		->addColumn('website_id', $installer::TYPE_INTEGER, null, array(
			'unsigned' => TRUE,
			'nullable' => FALSE,
			'default' => 0
		))
		->addColumn('name', $installer::TYPE_TEXT, 255, array(
			'nullable' => FALSE
		))
		->addColumn('default_website_view_id', $installer::TYPE_INTEGER, null, array(
			'nullable' => FALSE,
			'unsigned' => TRUE
		),'Default website id of this group');

// core_website_view - Each view belongs to a website_group. You usually use views
// to define languages for your website
$installer->newTable($installer->getResourceTable('core_resource/website_view'))
		->addColumn('website_view_id', $installer::TYPE_INTEGER, null, array(
			'nullable' => FALSE,
			'identity' => TRUE,
			'primary' => TRUE,
			'unsigned' => TRUE
		))
		->addColumn('code', $installer::TYPE_TEXT, 255, array())
		->addColumn('website_group_id', $installer::TYPE_INTEGER, null, array(
			'unsigned' => TRUE,
			'nullable' => FALSE
		),'The website group it belongs to')
		->addColumn('website_id', $installer::TYPE_INTEGER, null, array(
			'unsigned' => TRUE,
			'nullable' => FALSE
		),'The website it is linked to')
		->addColumn('name', $installer::TYPE_TEXT, 255, array(
			'nullable' => FALSE
		))
		->addColumn('is_active', $installer::TYPE_SMALLINT, 1, array(
			'unsigned' => TRUE,
			'nullable' => FALSE,
			'default' => 0,
		));

// core_email_template - holds a list of all email templates you could use in your app
$installer->newTable($installer->getResourceTable('core_email_template'))
		->addColumn('template_id', $installer::TYPE_INTEGER, null, array(
			'nullable' => FALSE,
			'primary' => TRUE,
			'identity' => TRUE,
			'unsigned' => TRUE
		))
		->addColumn('template_code', $installer::TYPE_TEXT, 120, array(
			'nullable' => FALSE
			)
		)
		->addColumn('template_text', $installer::TYPE_TEXT, null, array(
			'nullable' => FALSE
		))
		->addColumn('template_subject', $installer::TYPE_TEXT, 200, array(
			'nullable' => FALSE
			))
		->addColumn('template_sender_name', $installer::TYPE_TEXT, 200, array())
		->addColumn('template_sender_email', $installer::TYPE_TEXT, 200, array());

// session data, if you want to store it in the database, in this table
$installer->newTable($installer->getResourceTable('core_resource/session'))
		->addColumn('session_id', $installer::TYPE_TEXT, 255, array(
			'nullable' => FALSE,
			'primary' => TRUE
			),'Session identifier')
		->addColumn('session_expires', $installer::TYPE_INTEGER, null, array(
			'nullable' => FALSE,
			'unsigned' => TRUE
			),'Timestamp when session expires')
		->addColumn('session_data', $installer::TYPE_BLOG, null, array(
			'nullable' => FALSE
			),'Session data');

// url rewrites
$installer->newTable($installer->getResourceTable('core_resource/url_rewrite'))
		->addColumn('url_rewrite_id', $installer::TYPE_INTEGER, null, array(
			'unsigned' => TRUE,
			'primary' => TRUE,
			'nullable' => FALSE,
			'identity' => TRUE
			),'Url rewrite primary key')
		->addColumn('website_view_id', $installer::TYPE_INTEGER, null, array(
			'unsigned' => TRUE,
			'nullable' => FALSE,
			'default' => 0
		),'Website id for which this rewrite is valid')
		->addColumn('request_path', $installer::TYPE_TEXT, 255, array(
			),'url you see in the frontned')
		->addColumn('target_path', $installer::TYPE_TEXT, 255, array(
			),'url processed by ZeroG')
		->addColumn('is_system', $installer::TYPE_SMALLINT, 1, array(
			'unsigned' => TRUE,
			'default' => 1,
			),'Is this a system rewrite or a manual one from the admin?');

// run the installer code
$installer->run();
