<?php
/* 
 * Installer script for the tables in ZeroG_Cron version 0.1.0
 */

$installer = $this->getInstaller();

/*
 * Look in the file app/code/core/zerog/cron/etc/config.xml to see the link
 * between the resource name and the table (in <entities> tag)
 */

// config data stored in database, which overwrites config data in xml files
$installer->newTable($installer->getResourceTable('cron/schedule'))
		->addColumn('schedule_id', $installer::TYPE_INTEGER, null, array(
			'unsigned' => TRUE,
			'primary' => TRUE,
			'nullable' => FALSE,
			'identity' => TRUE,
			),'Cron job id')
		->addColumn('job_code', $installer::TYPE_TEXT, 255, array(
			'nullable' => FALSE,
			'default' => ''
			), 'Cron job code')
		->addColumn('status', $installer::TYPE_ENUM, null, array(
			'nullable' => FALSE,
			'default' => 'pending',
			'values' => array('pending', 'running', 'success', 'missed', 'error')
		),'')
		->addColumn('created_at', $installer::TYPE_DATETIME, null, array(
			'nullable' => FALSE,
			'default' => '0000-00-00 00:00:00'
			),'')
		->addColumn('scheduled_at', $installer::TYPE_DATETIME, null, array(
			'nullable' => FALSE,
			'default' => '0000-00-00 00:00:00'
			),'')
		->addColumn('executed_at', $installer::TYPE_DATETIME, null, array(
			'nullable' => FALSE,
			'default' => '0000-00-00 00:00:00'
			),'')
		->addColumn('finished_at', $installer::TYPE_DATETIME, null, array(
			'nullable' => FALSE,
			'default' => '0000-00-00 00:00:00'
			),'')
		->setComment('ZeroG cron job schedules');

// run the installer code
$installer->run();
