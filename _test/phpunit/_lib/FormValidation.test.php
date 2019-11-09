<?php

namespace Test;

use PHPUnit\Framework\TestCase;

require_once(__DIR__ . '/../AbstractTest.php');

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

    public function providerValidateField()
    {
        return [
            ['required', 'string', '', true],
            ['required', '', '', false],
            ['minlength', 'string', 7, false],
            ['minlength', 'string', 5, true],
            ['maxlength', 'string', 7, true],
            ['maxlength', 'string', 5, false],
            ['min', '5', '7', false],
            ['min', '5', '3', true],
            ['max', '5', '7', true],
            ['max', '5', '3', false],
            ['email', 'test', '', false],
            ['email', 'test@test.com', '', true],
            ['url', 'test', '', false],
            ['url', 'http://www.google.ro', '', true],
            ['digits', '1abc', '', false],
            ['digits', '123', '', true],
            ['digits', '1.23', '', true],
            ['digits', '1e5', '', true],
            ['number', '1abc', '', false],
            ['number', '123', '', true],
            ['number', '1.23', '', true],
            ['number', '1e5', '', true]
        ];
    }

    /**
     * Test the validate_field method with different values
     * @group fast
     * @dataProvider providerValidateField
     */
    public function testValidateField($type, $value, $option, $result)
    {
        // init and mock
        $oFVMock = $this->getMockBuilder('\FormValidation')
            ->disableOriginalConstructor()
            ->setMethods([ 'getField', 'getRequestMethod' ])
            ->getMock();
        $oFVMock->method('getField')->willReturn($value);
        $oFVMock->method('getRequestMethod')->willReturn('POST');
        $oFVMock->a_error = false;

        // the test
        $this->invokeMethod($oFVMock, 'validate_field', ['a', $type, $option]);

        // asserts
        if ($result === false) {
            $this->assertNull($oFVMock->a_error);
        } else {
            $this->assertSame('', $oFVMock->a_error);
        }
    }
}
