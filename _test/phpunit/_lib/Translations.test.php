<?php

namespace Test;

use PHPUnit\Framework\TestCase;

require_once(__DIR__ . '/../AbstractTest.php');

class Translations extends AbstractTest
{
    /**
     * Test if the Translations class is a singleton
     * @group fast
     */
    public function testSingleton()
    {
        $tr1 = \Translations::getSingleton();
        $tr1->setModule('common', false);
        
        $tr2 = \Translations::getSingleton();
        $tr2->setModule('website', false);
        
        $this->assertTrue($tr1 instanceof \Translations);
        $this->assertTrue($tr2 instanceof \Translations);
        
        $this->assertEquals($tr1->getModule(), $tr2->getModule());
        
        $tr1->setLanguage('ro_RO');
        $tr2->setLanguage('en_EN');
        
        $this->assertEquals($tr1->getLanguage(), $tr2->getLanguage());
    }
    
    /**
     * Test if a langiage exists or not
     * @group fast
     */
    public function testLanguageExists()
    {
        $tr = \Translations::getSingleton();
        
        $this->assertTrue($tr instanceof \Translations);
        $this->assertTrue($tr->checkIfLanguageExists('en_EN'));
        $this->assertTrue($tr->checkIfLanguageExists('ro_RO'));
        $this->assertFalse($tr->checkIfLanguageExists('pl_PL'));
        $this->assertFalse($tr->checkIfLanguageExists('bla'));
    }
    
    /**
     * Test if the number if translated strings for different languages
     * are the same
     * @group fast
     */
    public function testTranslationIntegrity()
    {
        $tr = \Translations::getSingleton();
        
        $this->assertTrue($tr instanceof \Translations);
        
        foreach (\Translations::MODULES as $module) {
            $tr->setLanguage('en_EN');
            $tr->setModule($module);
            $nrStringsEn = $tr->getAllKeys();
            
            $tr->resetTranslations();
            
            $tr->setLanguage('ro_RO');
            $tr->setModule($module);
            $nrStringsRo = $tr->getAllKeys();
            
            $tr->resetTranslations();
            
            $this->assertEquals($nrStringsEn, $nrStringsRo);
        }
    }
    
    /**
     * Test some translations in action
     * @group fast
     */
    public function testTranslations()
    {
        // set up language and module
        $tr = \Translations::getSingleton();
        $tr->setLanguage('en_EN');
        $tr->setModule('common');
        
        // asserts
        $this->assertTrue($tr instanceof \Translations);
        $this->assertEquals('Account', $tr->__('Account'));
        $this->assertEquals('Home', $tr->__('Home'));
        
        $tr->resetTranslations();
        
        // set up language and module
        $tr->setLanguage('ro_RO');
        $tr->setModule('common');
        
        // asserts
        $this->assertTrue($tr instanceof \Translations);
        $this->assertEquals('Cont', $tr->__('Account'));
        $this->assertEquals('Acasa', $tr->__('Home'));
    }
    
    /**
     * Test the complex translation function in action
     * @group fast
     */
    public function testComplexTranslations()
    {
        // set up language and module
        $tr = \Translations::getSingleton();
        $tr->setLanguage('en_EN');
        $tr->setModule('admin');
        
        // asserts
        $this->assertTrue($tr instanceof \Translations);
        $this->assertEquals('Key test was deleted !', $tr->___('Key %s was deleted !', 'test'));
        
        $tr->resetTranslations();
        
        // set up language and module
        $tr->setLanguage('ro_RO');
        $tr->setModule('admin');
        
        // asserts
        $this->assertTrue($tr instanceof \Translations);
        $this->assertEquals('Cheia test_ro a fost stearsa !', $tr->___('Key %s was deleted !', 'test_ro'));
    }
}
