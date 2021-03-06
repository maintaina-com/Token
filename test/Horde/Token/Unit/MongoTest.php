<?php
/**
 * Copyright 2013-2017 Horde LLC (http://www.horde.org/)
 *
 * See the enclosed file LICENSE for license information (LGPL). If you
 * did not receive this file, see http://www.horde.org/licenses/lgpl21.
 *
 * @category  Horde
 * @copyright 2013-2017 Horde LLC
 * @license   http://www.horde.org/licenses/lgpl21 LGPL 2.1
 * @package   Token
 */
namespace Horde\Token\Unit;
use Horde\Token\BackendTestCase as BackendTestCase;

/**
 * Test the MongoDB token backend.
 *
 * @author    Gunnar Wrobel <wrobel@pardus.de>
 * @category  Horde
 * @copyright 2013-2017 Horde LLC
 * @license   http://www.horde.org/licenses/lgpl21 LGPL 2.1
 * @package   Token
 */
class MongoTest extends BackendTestCase
{
    private $_dbname = 'horde_token_mongodbtest';
    private $_mongo;

    protected function _getBackend(array $params = array())
    {
        if (($config = self::getConfig('TOKEN_MONGO_TEST_CONFIG', __DIR__ . '/..')) &&
            isset($config['token']['mongo'])) {
            $factory = new Horde_Test_Factory_Mongo();
            $this->_mongo = $factory->create(array(
                'config' => $config['token']['mongo'],
                'dbname' => $this->_dbname
            ));
        }

        if (empty($this->_mongo)) {
            $this->markTestSkipped('MongoDB not available.');
        }

        return new Horde_Token_Mongo(array_merge($params, array(
            'mongo_db' => $this->_mongo,
            'secret' => 'abc'
        )));
    }

    public function tearDown(): void
    {
        if (!empty($this->_mongo)) {
            $this->_mongo->selectDB(null)->drop();
        }

        parent::tearDown();
    }

}
