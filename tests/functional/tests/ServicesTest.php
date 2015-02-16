<?php

namespace Butterfly\Tests;

class ServicesTest extends BaseDiTest
{
    protected static function getAdditionalConfigPaths()
    {
        return array(
            self::$baseDir . '/config/test.yml',
        );
    }

    public function getDataForTestParameter()
    {
        return array(
            array('bfy_plugin.auth.id_parameter_name', 'user_id'),
        );
    }

    public function getDataForTestService()
    {
        return array(
            array('bfy_plugin.auth.identification'),
            array('bfy_plugin.auth.authorization_router'),
        );
    }

    /**
     * @dataProvider getDataForTestParameter
     * @param string $parameterName
     * @param mixed $expectedValue
     */
    public function testParameter($parameterName, $expectedValue)
    {
        $this->assertEquals($expectedValue, self::$container->getParameter($parameterName));
    }

    /**
     * @dataProvider getDataForTestService
     * @param string $serviceName
     */
    public function testService($serviceName)
    {
        self::$container->get($serviceName);
    }
}
