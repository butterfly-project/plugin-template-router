<?php

namespace Butterfly\Plugin\TemplateRouter;

use Butterfly\Component\DI\Container;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Marat Fakhertdinov <marat.fakhertdinov@gmail.com>
 */
class Controller
{
    /**
     * @var
     */
    protected $render;

    /**
     * @var Container
     */
    protected $container;

    public function indexAction(Request $request)
    {
        $template = $request->attributes->get('template');

        $parameters = array(
            'container' => $this->container,
            'data'      => $this->container->getParameter('data.source'),
        );

        return $this->render($template, $parameters);
    }
}
