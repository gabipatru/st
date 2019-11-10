<?php

namespace Test;

require_once __DIR__ . '/AbstractTest.php';

class FirstTest extends AbstractTest
{
    /**
     * Total number of tables in the database.
     * When creating a new table increment this number of
     * migration test will fail.
     */
    const NUM_TABLES = 18;

    /**
     * Test running migrations from scratch
     * This will import all migrations on an empty database
     *
     * @return null
     */
    public function testMigrationsFromScratch()
    {
        $this->setUpDB();
        
        // Check how many tables we have
        $Migration = new \Migration();
        $Tables = $Migration->getTables();
        
        $this->assertCount(self::NUM_TABLES, $Tables);
    }
}
