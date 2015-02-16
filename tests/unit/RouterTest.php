<?php

namespace Tests\Routing;

use Butterfly\Plugin\TemplateRouter\Router;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Marat Fakhertdinov <marat.fakhertdinov@gmail.com>
 */
class RouterTest extends \PHPUnit_Framework_TestCase
{
    public function testGetActionCode()
    {
        $routing  = $this->getRouting();
        $request  = Request::create('/index');

        $action   = $routing->getAction($request);
        $template = $request->attributes->get('template');

        $this->assertEquals(array('bfy.controller:index', array($request)), $action);
        $this->assertEquals('auto/index.html.twig', $template);
    }

    public function testGetActionCodeIfUndefinedRoute()
    {
        $routing = $this->getRouting();
        $request = Request::create('/undefined');

        $actionCode = $routing->getAction($request);

        $this->assertNull($actionCode);
    }

    public function testGetActionCodeIfHomePage()
    {
        $routing = $this->getRouting();
        $request = Request::create('/');

        $actionCode = $routing->getAction($request);
        $template   = $request->attributes->get('template');

        $this->assertEquals(array('bfy.controller:index', array($request)), $actionCode);
        $this->assertEquals('auto/index.html.twig', $template);
    }

    /**
     * @return Router
     */
    protected function getRouting()
    {
        $viewDir      = realpath(__DIR__ . '/stub/view');
        $templateDirs = array(
            'auto' => $viewDir . '/auto',
        );

        $handlerActionCode = 'bfy.controller:index';
        $fileExtension     = '.html.twig';
        $homePage          = '/index';

        return new Router($templateDirs, $handlerActionCode, $fileExtension, $homePage);
    }
}
