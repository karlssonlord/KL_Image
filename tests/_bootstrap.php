<?php
// This is global bootstrap for autoloading


require __DIR__.'/../vendor/autoload.php';
require __DIR__.'/../src/app/Mage.php';
require __DIR__.'/../src/app/code/community/KL/Image/Model/Image.php';
require 'unit/FooDetector.php';

Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
