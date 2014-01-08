<?php
require_class("Debugger");
require_class("Component");
class Debugger_DebugComponent extends Component {
    public static function use_styles() {
        $path = classname_to_path(__CLASS__);
        return array($path."Debug.css");
    }

    public function get_view() {
        return "Debug";
    }


    public function get_benchmarks() {
        $benchmarks = Dispatcher::getInstance()->get_debugger()->get_benchmarks();
        return $benchmarks ? $benchmarks : array();
    }

    public function get_messages() {
        $messages = Dispatcher::getInstance()->get_debugger()->get_messages();
        return $messages ? $messages : array();
    }


    public function print_variable($var) {
        $this->var_id++;
        if (is_array($var)) {
            $id = $this->get_html_id() . '_' . $this->var_id;
            echo '<a href="javascript:;" onclick="SystemToggle(\'' . $id . '\')">Array</a>';
            echo '<pre id="' . $id . '" style="display:none"">';
            if (is_array($var)) {
            	print_r($var);
            } else {
            var_dump($var);
            }
            echo '</pre>';
        } else {
            print_r($var);
        }
    }

    private $var_id = 0;
}
