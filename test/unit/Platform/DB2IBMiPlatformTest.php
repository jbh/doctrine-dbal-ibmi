<?php

namespace DoctrineDbalIbmiTest\Platform;

use DoctrineDbalIbmiTest\Bootstrap;
use PHPUnit\Framework\TestCase;

/**
 * @covers \DoctrineDbalIbmi\Platform\DB2IBMiPlatform
 */
class DB2IBMiPlatformTest extends TestCase
{
    /**
     * @return iterable<mixed, array<int, string>>
     */
    public function typeMappingProvider()
    {
        return [
            ['smallint', 'smallint'],
            ['bigint', 'bigint'],
            ['integer', 'integer'],
            ['rowid', 'integer'],
            ['time', 'time'],
            ['date', 'date'],
            ['varchar', 'string'],
            ['character', 'string'],
            ['char', 'string'],
            ['nvarchar', 'string'],
            ['nchar', 'string'],
            ['char () for bit data', 'string'],
            ['varchar () for bit data', 'string'],
            ['varg', 'string'],
            ['vargraphic', 'string'],
            ['graphic', 'string'],
            ['varbinary', 'binary'],
            ['binary', 'binary'],
            ['varbin', 'binary'],
            ['clob', 'text'],
            ['nclob', 'text'],
            ['dbclob', 'text'],
            ['blob', 'blob'],
            ['decimal', 'decimal'],
            ['numeric', 'float'],
            ['double', 'float'],
            ['real', 'float'],
            ['float', 'float'],
            ['timestamp', 'datetime'],
            ['timestmp', 'datetime'],
        ];
    }

    /**
     * @param string $dbType
     * @param string $expectedMapping
     *
     * @return void
     *
     * @dataProvider typeMappingProvider
     */
    public function testTypeMappings($dbType, $expectedMapping)
    {
        if (!extension_loaded('ibm_db2')) {
            self::markTestSkipped('ibm_db2 extension not loaded');
        }
        $connection = Bootstrap::getConnection();
        $platform = $connection->getDatabasePlatform();

        self::assertSame($expectedMapping, $platform->getDoctrineTypeMapping($dbType));
    }

    /**
     * @return iterable<mixed, array<int, string|array<string, int|bool>>>
     */
    public function varcharTypeDeclarationProvider()
    {
        return [
            ['VARCHAR(1024)', ['length' => 1024]],
            ['VARCHAR(255)', []],
            ['VARCHAR(255)', ['length' => 0]],
            ['CLOB(1M)', ['fixed' => true, 'length' => 1024]],
            ['CHAR(255)', ['fixed' => true]],
            ['CHAR(255)', ['fixed' => true, 'length' => 0]],
            ['CLOB(1M)', ['length' => 5000]],
        ];
    }

    /**
     * @param string $expectedSql
     * @param array $fieldDef
     *
     * @return void
     *
     * @dataProvider varcharTypeDeclarationProvider
     */
    public function testVarcharTypeDeclarationSQLSnippet($expectedSql, array $fieldDef)
    {
        if (!extension_loaded('ibm_db2')) {
            self::markTestSkipped('ibm_db2 extension not loaded');
        }
        $connection = Bootstrap::getConnection();
        $platform = $connection->getDatabasePlatform();

        self::assertSame($expectedSql, $platform->getVarcharTypeDeclarationSQL($fieldDef));
    }
}
