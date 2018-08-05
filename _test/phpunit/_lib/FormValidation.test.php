<?php 
namespace Test;

use PHPUnit\Framework\TestCase;

require_once(__DIR__ .'/../AbstractTest.php');

class FormValidation extends AbstractTest
{
    /**
     * Test if the FV is properly initialised with simple values
     * @group fast
     */
    public function testFVinitSimpleData()
    {
        $FV = new \FormValidation([
            'rules' => [
                'name'          => 'required',
                'description'   => ''
            ],
            'messages' => [
                'name' => 'test'
            ]
        ]);
        
        $oItem = new \SetterGetter();
        $oItem->setName('Test');
        $oItem->setDescription('Desc');
        
        // init FV
        $FV->initDefault($oItem);
        
        // second group of asserts
        $this->assertEquals('Test', $FV->name);
        $this->assertEquals('Desc', $FV->description);
    }
    
    /**
     * Test if the FV is properly initialised with complex values
     * @group fast
     */
    public function testFVinitComplexData()
    {
        $FV = new \FormValidation([
            'rules' => [
                'item_id'       => 'required',
                'category_id'   => '',
                'name'          => 'required',
                'description'   => ''
            ],
            'messages' => [
                'name' => 'test'
            ]
        ]);
        
        $oItem = new \SetterGetter();
        $oItem->setItemId(10);
        $oItem->setCategoryId(2);
        $oItem->setName('Test');
        $oItem->setDescription('Desc');
        
        // init FV
        $FV->initDefault($oItem);
        
        // second group of asserts
        $this->assertEquals(10, $FV->item_id);
        $this->assertEquals(2, $FV->category_id);
        $this->assertEquals('Test', $FV->name);
        $this->assertEquals('Desc', $FV->description);
    }
}