<?php

namespace Tests\Routing;

use Butterfly\Plugin\TemplateRouter\Router;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Marat Fakhertdinov <marat.fakhertdinov@gmail.com>
 */
class RouterTest extends \PHPUnit_Framework_TestCase
{
    public function getDataForTestGetActionCode()
    {
        $routing1 = array(
            array(
                'dir'             => __DIR__ . '/stub/view/example1',
                'prefix_uri'      => '',
                'prefix_template' => '',
            ),
        );

        $routing2 = array(
            array(
                'dir'             => __DIR__ . '/stub/view/example2',
                'prefix_uri'      => 'auto',
                'prefix_template' => '',
            ),
        );

        $routing3 = array(
            array(
                'dir'             => __DIR__ . '/stub/view/example3',
                'prefix_uri'      => '',
                'prefix_template' => 'auto',
            ),
        );

        return array(
            array($routing1, '/',               'index.html.twig'),
            array($routing1, '/index',          'index.html.twig'),
            array($routing1, '/index/',         'index.html.twig'),
            array($routing1, '/users/index',    'users/index.html.twig'),
            array($routing1, '/users/index/',   'users/index.html.twig'),

            array($routing2, '/auto/',              'index.html.twig'),
            array($routing2, '/auto/index',         'index.html.twig'),
            array($routing2, '/auto/index/',        'index.html.twig'),
            array($routing2, '/auto/users/index',   'users/index.html.twig'),
            array($routing2, '/auto/users/index/',  'users/index.html.twig'),

            array($routing3, '/',               'auto/index.html.twig'),
            array($routing3, '/index',          'auto/index.html.twig'),
            array($routing3, '/index/',         'auto/index.html.twig'),
            array($routing3, '/users/index',    'auto/users/index.html.twig'),
            array($routing3, '/users/index/',   'auto/users/index.html.twig'),
        );
    }

    /**
     * @dataProvider getDataForTestGetActionCode
     *
     * @param array $templateDirs
     * @param string $uri
     * @param string $expectedTemplate
     */
    public function testGetActionCode(array $templateDirs, $uri, $expectedTemplate)
    {
        $routing = $this->getRouting($templateDirs);
        $request  = Request::create($uri);

        $action   = $routing->getAction($request);
        $template = $request->attributes->get('template');

        $this->assertEquals(array('bfy.controller:index', array($request)), $action);
        $this->assertEquals($expectedTemplate, $template);
    }

    public function testGetActionCodeIfUndefinedRoute()
    {
        $templateDirs = array(
            array(
                'dir' => __DIR__ . '/stub/view/auto'
            ),
        );

        $routing = $this->getRouting($templateDirs);
        $request = Request::create('/undefined');

        $actionCode = $routing->getAction($request);

        $this->assertNull($actionCode);
    }

    /**
     * @param array $templateDirs
     * @return Router
     */
    protected function getRouting(array $templateDirs)
    {
        $handlerActionCode = 'bfy.controller:index';
        $fileExtension     = '.html.twig';
        $homePage          = '/index';

        return new Router($templateDirs, $handlerActionCode, $fileExtension, $homePage);
    }
}
