<?php

namespace Butterfly\Plugin\TemplateRouter;

use Butterfly\Application\RequestResponse\Routing\IRouter;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Marat Fakhertdinov <marat.fakhertdinov@gmail.com>
 */
class Router implements IRouter
{
    const EMPTY_URI = '/';

    /**
     * @var array
     */
    protected $routes;

    /**
     * @var string
     */
    protected $handlerActionCode;

    /**
     * @var string
     */
    protected $fileExtension;

    /**
     * @var string
     */
    protected $defaultUri;

    /**
     * @param array $routes
     * @param string $handlerActionCode
     * @param string $fileExtension
     * @param string $defaultUri
     */
    public function __construct(array $routes, $handlerActionCode, $fileExtension, $defaultUri)
    {
        $this->routes            = $routes;
        $this->handlerActionCode = $handlerActionCode;
        $this->fileExtension     = $fileExtension;
        $this->defaultUri        = $defaultUri;
    }

    /**
     * @param Request $request
     * @return array|null ($actionName, array $parameters)
     */
    public function getAction(Request $request)
    {
        $uri      = $request->getPathInfo();
        $template = $this->getTemplateUri($uri);

        if (null === $template) {
            return null;
        }

        $request->attributes->set('template', $template);

        return array($this->handlerActionCode, array($request));
    }

    /**
     * @param string $uri
     * @return string|null
     */
    protected function getTemplateUri($uri)
    {
        foreach ($this->routes as $route) {
            $prefixUri      = isset($route['prefix_uri']) ? $route['prefix_uri'] : '';
            $prefixTemplate = isset($route['prefix_template']) ? $route['prefix_template'] : '';
            $dir            = $route['dir'] . DIRECTORY_SEPARATOR . $prefixTemplate;

            $uri = $this->prepareUri($uri, $prefixUri);
            if (null === $uri) {
                continue;
            }

            if (!$this->checkFile($uri, $dir)) {
                continue;
            }

            return $this->getTemplateName($uri, $prefixTemplate);
        }

        return null;
    }

    /**
     * @param string $uri
     * @param string $prefix
     * @return string|null
     */
    protected function prepareUri($uri, $prefix)
    {
        if (!empty($prefix)) {
            $prefix = '/' . $prefix;

            if (0 === strpos($uri, $prefix)) {
                $uri = substr($uri, mb_strlen($prefix));
            }
        }

        $uriLength = mb_strlen($uri);

        if (1 < $uriLength && '/' == $uri[$uriLength-1]) {
            $uri = substr($uri, 0, -1);
        }

        if (self::EMPTY_URI == $uri) {
            $uri = $this->defaultUri;
        }

        return $uri;
    }

    /**
     * @param string $uri
     * @param string $dir
     * @return bool
     */
    protected function checkFile($uri, $dir)
    {
        $filePath = $dir . $uri . $this->fileExtension;

        return is_readable($filePath);
    }

    /**
     * @param string $uri
     * @param string $prefix
     * @return string
     */
    protected function getTemplateName($uri, $prefix)
    {
        $name = substr($uri . $this->fileExtension, 1);

        if (!empty($prefix)) {
            $name = $prefix . '/' . $name;
        }

        return $name;
    }
}
