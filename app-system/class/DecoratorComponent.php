<?php
require_class("Component");

abstract class DecoratorComponent extends Component {
    public function execute() {
        $view = $this->get_decorator();
        Dispatcher::getInstance()->debug("decorator: $view");
        $file =  "component/".$view.".html";
        global $G_LOAD_PATH;
        foreach ($G_LOAD_PATH as $path) {
            if (file_exists($path.$file)) {
                $this->render($path.$file, TRUE);
                break;
            }
        }
    }

    public function get_decorator() {
        return "Decorator";
    }

    public function real_component() {
        $view = $this->get_view();
        if ($view) {
            $file =  "component/".apf_classname_to_path(get_class($this)).$view.'.html';
            global $G_LOAD_PATH;
            foreach ($G_LOAD_PATH as $path) {
                if (file_exists($path.$file)) {
                    $this->render($path.$file);
                    break;
                }
            }
        }
    }
}
