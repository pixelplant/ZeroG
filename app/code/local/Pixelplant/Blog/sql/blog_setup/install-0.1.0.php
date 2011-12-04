<?php
/* 
 * Installer script for the tables in Pixelplant_Blog version 0.1.0
 */

$installer = $this->getInstaller();

// Blog posts table
$installer->newTable($installer->getResourceTable('blog_resource/post'))
		->addColumn('post_id', $installer::TYPE_INTEGER, null, array(
			'unsigned' => TRUE,
			'primary' => TRUE,
			'nullable' => FALSE,
			'identity' => TRUE
			),'Stores each blog post id')
		->addColumn('cat_id', $installer::TYPE_INTEGER, null, array(
			'nullable' => FALSE,
			'unsigned' => TRUE,
			'default' => 0,
			),'The category id this post is linked to')
		->addColumn('title', $installer::TYPE_TEXT, 255, array(
			'nullable' => FALSE,
			'default' => '',
		),'Title of the blog post')
		->addColumn('post_content', $installer::TYPE_TEXT, null, array(
			'nullable' => FALSE,
		))
		->addColumn('created_time', $installer::TYPE_TIMESTAMP, null, array(
			//'default' => NULL
			))
		->addColumn('updated_time', $installer::TYPE_TIMESTAMP, null, array(
			//'default' => NULL
			))
		->addColumn('meta_keywords', $installer::TYPE_TEXT, 200, array(
			'nullable' => FALSE
			))
		->addColumn('meta_description', $installer::TYPE_TEXT, null, array(
			'nullable' => FALSE
			))
		->setComment('Holds a list of blog posts for the Pixelplant_Blog extension')
		->insertData(
				array('title' => 'Bunădimineața soare',
					'post_content' => '<p><strong>Acesta</strong> este o postare de probă.</p>',
					'cat_id' => '1',
					'meta_keywords' => 'pixelplant,blog,zerog,postare,proba',
					'meta_description' => 'Descrierea meta pentru această postare'));

// Blog categories table
$installer->newTable($installer->getResourceTable('blog_resource/category'))
		->addColumn('cat_id', $installer::TYPE_INTEGER, null, array(
			'nullable' => FALSE,
			'primary' => TRUE,
			'unsigned' => TRUE,
			'identity' => TRUE,
			),'Stores each blog category id')
		->addColumn('title', $installer::TYPE_TEXT, 150, array(
			'nullable' => FALSE,
			'default' => '',
		))
		->setComment('List of blog categories for the Pixelplant_Blog extension')
		->insertData(
				array('cat_id' => 1,
					'title' => 'Categorie test'));

// run the installer code
$installer->run();
