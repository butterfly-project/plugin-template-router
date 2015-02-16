<?php

namespace Butterfly\Plugin\TemplateRouter;

use Butterfly\Application\RequestResponse\Routing\IRouter;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Marat Fakhertdinov <marat.fakhertdinov@gmail.com>
 */
class Router implements IRouter
{
    const HOME_URI = '/';

    /**
     * @var array
     */
    protected $templateDirs;

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
    protected $homePath;

    /**
     * @param array $templateDirs
     * @param string $handlerActionCode
     * @param string $fileExtension
     * @param string $homePath
     */
    public function __construct(array $templateDirs, $handlerActionCode, $fileExtension, $homePath)
    {
        $this->templateDirs      = $templateDirs;
        $this->handlerActionCode = $handlerActionCode;
        $this->fileExtension     = $fileExtension;
        $this->homePath          = $homePath;
    }

    /**
     * @param Request $request
     * @return array|null ($actionName, array $parameters)
     */
    public function getAction(Request $request)
    {
        $pathInfo = $request->getPathInfo();

        $template = $this->getTemplatePath($pathInfo);

        if (null === $template) {
            return null;
        }

        $request->attributes->set('template', $template);

        return array($this->handlerActionCode, array($request));
    }

    /**
     * @param string $path
     * @return string|null
     */
    protected function getTemplatePath($path)
    {
        if (self::HOME_URI == $path) {
            $path = $this->homePath;
        }

        foreach ($this->templateDirs as $templateDir => $absoluteDir) {
            $filePath = $absoluteDir . $path . $this->fileExtension;

            if (is_readable($filePath)) {
                return $templateDir . $path . $this->fileExtension;
            }
        }

        return null;
    }
}
