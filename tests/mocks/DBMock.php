<?php

namespace skyline\user\tests\mocks;

use Codeception\Util\Stub;
use yii\db\ColumnSchema;

class DBMock
{
    public static function getConnection(array $config = null)
    {
        $mock = new DBMock($config);
        return $mock->connection;
    }

    private static array $defaultConfig = [
        'connectionClass' => 'yii\db\Connection',
        'schemaClass' => 'yii\db\mysql\Schema',
        'tableSchemaClass' => 'yii\db\TableSchema',
    ];

    private array $config;
    private $connection;

    private function __construct(?array $config = null)
    {
        if (!isset($config)) {
            throw new \TypeError('a configuration must be provided');
        }

        if (!array_key_exists('columns', $config)) {
            throw new \TypeError('configuration must define a "columns" key');
        }

        if (!is_array($config['columns'])) {
            throw new \TypeError('"columns" key must be an array');
        }

        $this->config = array_merge(DBMock::$defaultConfig, $config);
        $this->init();
    }

    private function init()
    {

        $columns = array_reduce(
            $this->config['columns'],
            function ($iterator, $column) {
                if (!is_array($column)) {
                    return $iterator;
                }
                $schema = new ColumnSchema($column);
                $iterator[$schema->name] = $schema;
                return $iterator;
            },
            []
        );
        $tableSchema = Stub::make(
            $this->config['tableSchemaClass']
        );
        $tableSchema->columns = $columns;

        $schema = Stub::makeEmpty(
            $this->config['schemaClass'],
            [
                'getTableSchema' => $tableSchema,
            ]
        );
        $this->connection = Stub::makeEmpty(
            $this->config['connectionClass'],
            [
                'getSchema' => $schema,
            ]
        );
    }
}
