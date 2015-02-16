<?php

namespace Butterfly\Plugin\TemplateRouter;

use Butterfly\Adapter\Twig\IRenderer;
use Butterfly\Component\DI\Container;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Marat Fakhertdinov <marat.fakhertdinov@gmail.com>
 */
class TemplaetController
{
    /**
     * @var IRenderer
     */
    protected $render;

    /**
     * @var Container
     */
    protected $container;

    /**
     * @param IRenderer $render
     * @param Container $container
     */
    public function __construct(IRenderer $render, Container $container)
    {
        $this->container = $container;
        $this->render    = $render;
    }

    /**
     * @param Request $request
     * @return string
     */
    public function indexAction(Request $request)
    {
        $template = $request->attributes->get('template');

        $parameters = array(
            'container' => $this->container,
            'data'      => $this->container->getParameter('bfy_plugin.template_router.data_source'),
            'request'   => $request,
        );

        return $this->render->render($template, $parameters);
    }
}
