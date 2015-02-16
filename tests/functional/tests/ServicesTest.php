<?php

namespace Butterfly\Tests;

class ServicesTest extends BaseDiTest
{
    public function getDataForTestParameter()
    {
        return array(
            array('bfy_plugin.template_router.templates_dirs', array()),
            array('bfy_plugin.template_router.handler_action_code', 'bfy_plugin.template_router.controller:index'),
            array('bfy_plugin.template_router.file_extension', '.html.twig'),
            array('bfy_plugin.template_router.home_path', '/'),
            array('bfy_plugin.template_router.data_source', array()),
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

    public function getDataForTestService()
    {
        return array(
            array('bfy_plugin.template_router.router'),
            array('bfy_plugin.template_router.controller'),
        );
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
