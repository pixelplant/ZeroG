<?php
/* 
 * Installer script for the tables in ZeroG_Core version 0.1.0
 */

$installer = \Z::getModel('core/installer');

$installer->addTable('core_resource');
$installer->addField('uid', $installer::INT);
$installer->addField('extension', $installer::VARCHAR);
$installer->addField('version', $installer::VARCHAR);

$installer->run();




