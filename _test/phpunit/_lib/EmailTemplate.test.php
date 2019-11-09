<?php

namespace Test;

use PHPUnit\Framework\TestCase;

require_once(__DIR__ . '/../AbstractTest.php');

class EmailTemplate extends AbstractTest
{
    /**
     * Test setting view file and decorations
     * @group fast
     */
    public function testSetters()
    {
        $Email = new \EmailTemplate('test');
        
        // assert data and files and folders exist
        $this->assertEquals('test', $Email->getViewFile());
        $this->assertEquals('_default', $Email->getDecorations());
        
        $this->assertTrue(is_dir(EMAIL_VIEW_DIR . '/_core'));
        $this->assertTrue(file_exists(EMAIL_VIEW_DIR . '/_core/header.php'));
        $this->assertTrue(file_exists(EMAIL_VIEW_DIR . '/_core/footer.php'));
        
        $this->assertTrue(is_dir(EMAIL_DECORATIONS_DIR . '/' . $Email->getDecorations()));
        $this->assertTrue(file_exists(EMAIL_DECORATIONS_DIR . '/' . $Email->getDecorations() . '/header.php'));
        $this->assertTrue(file_exists(EMAIL_DECORATIONS_DIR . '/' . $Email->getDecorations() . '/footer.php'));
        
        $Email->setViewFile('testx');
        $Email->setDecorations('testy');
        
        // assert new data is correct
        $this->assertEquals('testx', $Email->getViewFile());
        $this->assertEquals('testy', $Email->getDecorations());
    }
    
    /**
     * Test adding an email to the email queue
     * @group slow
     */
    public function testAddToQueue()
    {
        $this->setUpDB(['email']);
        
        $Email = new \EmailTemplate('test');
        
        // Add the message to queue
        $r = $Email->queue('test@st.ro', 'test', 'test');
        
        // get data from DB
        $EmailQueue = new \EmailQueue();
        $filter = ['too' => 'test@st.ro'];
        $Collection = $EmailQueue->Get($filter, []);
        
        // asserts
        $this->assertEquals(1, $r);
        $this->assertInstanceOf(\Collection::class, $Collection);
        $this->assertCount(1, $Collection);
        $Item = $Collection->getItem();
        $this->assertInstanceOf(\SetterGetter::class, $Item);
        $this->assertEquals('test', $Item->getSubject());
        $this->assertEquals('test', $Item->getBody());
    }
    
    /**
     * Test adding an email to email queue with wrong data
     * @group fast
     */
    public function testAddToQueueNoEmailAddress()
    {
        $Email = new \EmailTemplate('test');
        
        // Add the message to queue
        $r1 = $Email->queue([], '');
        $r2 = $Email->queue([], 'abc');
        
        // assert false was returned, which means error
        $this->assertFalse($r1);
        $this->assertFalse($r2);
    }
    
    /**
     * Test sending email with wrong data
     * @group fast
     */
    public function testSendWithWrongEmailAddress()
    {
        $Email = new \EmailTemplate('test');
        
        // send the email
        $r1 = $Email->send('', 'test');
        $r2 = $Email->send('abc', 'test');
        
        // assert false was returned which means error
        $this->assertFalse($r1);
        $this->assertFalse($r2);
    }
}
