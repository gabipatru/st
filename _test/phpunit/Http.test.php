<?php
namespace Test;

use PHPUnit\Framework\TestCase;

require_once(__DIR__ .'/AbstractTest.php');
require_once(TRAITS_DIR .'/Http.trait.php');

/**
 * Test the Http trait by using the View object
 */
class HttpTest extends AbstractTest
{
    /**
     * Test the urlFormat function
     * @group fast
     * @dataProvider providerUrlFormat
     */
    public function testUrlFormat($test, $expected)
    {
        $view = \View::getSingleton();
        
        // test
        $newValue = $view->urlFormat($test);
        
        // assert
        $this->assertEquals($expected, $newValue);
    }
    
    public function providerUrlFormat()
    {
        return [
            ['otto moto', 'otto-moto'],
            ['otto_moto', 'otto-moto'],
            ['otto moto_moto', 'otto-moto-moto']
        ];
    }
}