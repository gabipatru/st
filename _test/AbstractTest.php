<?php
declare(strict_types=1);

namespace Test;

use PHPUnit\Framework\TestCase;

require_once(__DIR__ .'/../_config/paths.php');
require_once(CLASSES_DIR .'/mvc.php');

abstract class AbstractTest extends TestCase {
    
    public function setUp() {
        //register the autoloading class
        spl_autoload_register('mvc::autoload');
    }
    
    public function tearDown() {
        
    }
}