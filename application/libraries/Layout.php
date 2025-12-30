<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Layout
{
    public $data = array();
    public $view = null;
    public $viewFolder = null;
    public $layoutsFodler = 'layouts';
    public $layout = 'main';

    /**
     * [$view_js description]
     * @var [mixed]
     */
    public $view_js;
    public $view_css = '';
    public $title = '';
    public $subtitle = '';
    public $title_icon = '';

    // Blade like method
    private $sections = [];
    private $sectionStack = [];
    private $stacks = []; // For handling css/js files (css/scripts)

    /**
     * For set $this of codeigniter
     * @var [type]
     */
    private $ci;

    public function __construct()
    {
        $this->ci =& get_instance();
        $this->title = getenv('APP_NAME');
    }

    public function setSection($name, $content)
    {
        $this->sections[$name] = $content;
    }


    public function startSection($name)
    {
        $this->sectionStack[] = $name;
        ob_start();
    }

    public function endSection()
    {
        $name = array_pop($this->sectionStack);
        $this->sections[$name] = ob_get_clean();
    }

    public function yield($name, $default = '')
    {
        return isset($this->sections[$name]) ? $this->sections[$name] : $default;
    }

    public function push($name, $content)
    {
        if (!isset($this->stacks[$name])) {
            $this->stacks[$name] = [];
        }

        $this->stacks[$name][] = $content;
    }

    public function yieldStack($name, $glue = "\n")
    {
        echo isset($this->stacks[$name]) ? implode($glue, $this->stacks[$name]) : '';
    }

    public function setLayout($layout)
    {
        $this->layout = $layout;
    }

    public function setLayoutFolder($layoutFolder)
    {
        $this->layoutsFodler = $layoutFolder;
    }

    public function render($view, $data = [], $return = false)
    {
        $controller = str_replace('Controller', '', $this->ci->router->fetch_class());
        $slug_controller = camelToSlug($controller, '-');

        $method = $this->ci->router->fetch_method();
        // Build the actual view path. If viewFolder is provided, append the view name to it.
        $contentView = !($this->viewFolder) ? $slug_controller .'/'. $view : rtrim($this->viewFolder, '/') .'/'. $view;
        // $view = !($this->view) ? $method : $this->view;

        $loadedData = array();
        $loadedData['view'] = $contentView;
        $loadedData['data'] = $data;
        $loadedData['title'] = $this->title;
        $loadedData['subtitle'] = $this->subtitle;
        $loadedData['title_icon'] = $this->title_icon;
        $loadedData['content'] = $this->ci->load->view($contentView, $data, true);

        if (!empty($this->view_js)) {
            if (is_string($this->view_js)) {
                $view_js = $slug_controller .'/'. $this->view_js;

            } elseif (is_array($this->view_js)) {
                $view_js = [];
                foreach ($this->view_js as $key => $js) {
                    $view_js[] = $slug_controller .'/'. $js;
                }

            }

            $loadedData = array_merge($loadedData, ['view_js' => $view_js]);
        }

        if (!empty($this->view_css)) {
            if (is_string($this->view_css)) {
                $view_css = $slug_controller .'/'. $this->view_css;

            } elseif (is_array($this->view_css)) {
                $view_css = [];
                foreach ($this->view_css as $key => $css) {
                    $view_css[] = $slug_controller .'/'. $css;
                }

            }

            $loadedData = array_merge($loadedData, ['view_css' => $view_css]);
        }

        $layoutPath = $this->layoutsFodler.'/'.$this->layout;
        return $this->ci->load->view($layoutPath, $loadedData, $return);
    }

    public function renderPartial($view, $data = [], $return = false)
    {
        $controller = str_replace('Controller', '', $this->ci->router->fetch_class());
        $slug_controller = camelToSlug($controller, '-');

        $method = $this->ci->router->fetch_method();
        $contentView = !($this->viewFolder) ? $slug_controller .'/'. $view : rtrim($this->viewFolder, '/') .'/'. $view;

        $loadedData = array();
        $loadedData['data'] = $data;

        return $this->ci->load->view($contentView, $data, $return);
    }
}
