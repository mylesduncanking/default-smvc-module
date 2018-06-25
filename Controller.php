<?php
/**
 * Controller - base controller
 *
 * @author David Carr - dave@daveismyname.com
 * @version 2.2
 * @date June 27, 2014
 * @date updated Sept 19, 2015
 */

namespace Core;

use Core\View;
use Core\Language;

use Helpers\Session;

use ScwHelpers\Tokens;
use ScwHelpers\Useful;

/**
 * Core controller, all other controllers extend this base controller.
 */
abstract class Controller
{
    /**
     * View variable to use the view class.
     *
     * @var string
     */
    public $view;

    /**
     * Language variable to use the languages class.
     *
     * @var string
     */
    public $language;

    /**
     * On run make an instance of the config class and view class.
     */
    public function __construct()
    {
        /** initialise the views object */
        $this->view = new View();

        /** initialise the language object */
        $this->language = new Language();

        /** include module model */
        $class = get_class($this);
        if (Useful::inString('Modules\\', $class)) {
            $model = '\\'.str_replace('Controllers', 'Models', $class);
            if (class_exists($model)) {
                $this->model = new $model();
            } else {
                $this->model = new \Models\Standard();
            }
        }
    }

    public function renderModule($data, $legacyData = array())
    {
        if (is_string($data)) {
            return $this->renderModuleLegacy($data, $legacyData);
        }

        Session::set('breadcrumb', $data['breadcrumb']);
        
        Tokens::createToken();

        $callingMethod = get_class($this).'\\'.debug_backtrace()[1]['function'];

        $view = str_replace('Controllers', 'views', $callingMethod);
        $view = str_replace('\\', '/', $view);
        $view = str_replace('Modules/', '', $view);

        View::renderTemplate('header', $data);
        View::renderModule($view, $data);
        View::renderTemplate('footer', $data);
    }

    public function renderModuleLegacy($view, $data)
    {
        Session::set('breadcrumb', $data['breadcrumb']);
        
        Tokens::createToken();

        View::renderTemplate('header', $data);
        View::renderModule($view, $data);
        View::renderTemplate('footer', $data);
    }
}
