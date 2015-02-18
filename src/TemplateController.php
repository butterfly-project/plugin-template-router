<?php

namespace Butterfly\Plugin\TemplateRouter;

use Butterfly\Adapter\Twig\IRenderer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Marat Fakhertdinov <marat.fakhertdinov@gmail.com>
 */
class TemplateController
{
    /**
     * @var IRenderer
     */
    protected $renderer;

    /**
     * @var array
     */
    protected $data;

    /**
     * @var string|null
     */
    protected $templateOf404;

    /**
     * @param IRenderer $renderer
     * @param array $data
     * @param string $templateOf404
     */
    public function __construct(IRenderer $renderer, array $data, $templateOf404 = null)
    {
        $this->renderer      = $renderer;
        $this->data          = $data;
        $this->templateOf404 = $templateOf404;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $template = $request->attributes->get('template');

        return $this->render($template, $this->data);
    }

    /**
     * @return Response
     */
    public function page404Action()
    {
        if (empty($this->templateOf404)) {
            return new Response('404 - Page not found');
        }

        return $this->render($this->templateOf404, $this->data);
    }

    /**
     * @param string $template
     * @param array $parameters
     * @return Response
     */
    protected function render($template, array $parameters)
    {
        $content = $this->renderer->render($template, $parameters);

        return new Response($content);
    }
}
