<?php

namespace ntentan\wyf\controllers;

use ntentan\Controller;
use ntentan\Context;
use ntentan\Session;
use ntentan\View;
use ntentan\utils\Text;

/**
 * Base controller for all WYF application modules you want to appear in the menu.
 */
class WyfController extends Controller
{

    /**
     * The wyf package name of this controller.
     * @var string
     */
    private $package;

    /**
     * The name of this controller.
     * @var mixed
     */
    private $name;

    /**
     * The URL path of this controller.
     * @var mixed
     */
    private $path;

    /**
     * The base of the title string for this controller.
     * @var string
     */
    private $titleBase;

    /**
     * An instance of the view used for rendering actions in this controller.
     * @var View
     */
    private $view;

    /**
     * WyfController constructor.
     * @param View $view
     */
    public function __construct(View $view)
    {
        $context = Context::getInstance();
        $this->view = $view;
        $appName = $context->getParameter('wyf.app_name');
        $this->view->set('route_breakdown', explode('/', $context->getParameter('route')));
        $this->view->set('wyf_app_name', $appName);
        $this->view->set('wyf_logout_url', $context->getUrl('auth/logout'));
        $this->view->set('notification', Session::get('notification'));
        Session::set('notification', null);
        $this->titleBase = "{$appName}";
        $class = get_class($this);
        $namespace = addslashes($context->getNamespace());
        if (preg_match("/$namespace\\\\app\\\\(?<base>.*)\\\\controllers\\\\(?<name>.*)Controller/", $class, $matches)) {
            $this->package = strtolower(Text::deCamelize(str_replace("\\", ".", $matches['base'])) . "." . Text::deCamelize($matches['name']));
            $this->name = str_replace("_", " ", Text::deCamelize($matches['name']));
            $this->path = str_replace('.', '/', $this->package);
        }
        $this->view->set('menu', $context->getParameter('wyf.menu'));
    }

    /**
     * Set the title displayed on top of the page.
     * @param $title
     */
    protected function setTitle($title)
    {
        $this->view->set('wyf_title', $title);
    }

    /**
     * Return a brief description of this controller.
     */
    public function getDescription()
    {
        
    }

    /**
     * Get the name of the controller.
     * @return string
     */
    public function getWyfName()
    {
        return $this->name;
    }

    /**
     * Get the wyf package name for this controller.
     * @return string
     */
    public function getWyfPackage()
    {
        return $this->package;
    }

    /**
     * Get a URL path to this controller.
     * @return mixed
     */
    public function getWyfPath()
    {
        return $this->path;
    }

}
