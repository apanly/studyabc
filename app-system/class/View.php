<?php
require_class("Component");
/**
 * APF 页面类，是一个特殊的组件
 * added by htlv
 */
abstract class View extends Component {
    /**
     * 页面是所有组件的父组件
     * @param unknown_type $parent
     * @param unknown_type $html_id
     */
    public function __construct($parent=NULL, $html_id=NULL) {
        parent::__construct($parent, $html_id);
    }
    /**
     * 获取页面标题
     * @return string 页面标题
     */
    public function get_title() {
        return "AnjukePHP " . Dispatcher::VERSION;
    }
    /**
     * 获取页面内容类型
     */
    public function get_content_type() {
        return "text/html";
    }
    /**
     * 获取页面编码
     */
    public function get_charset() {
        return "utf-8";
    }
    /**
     * 获取头部组件信息
     */
    public function get_head_sections() {
        return array();
    }
    /**
     * 加载页面框架:
     * 执行get_view方法，获取页面phtml文件名，
     * 然后渲染此phtml文件
     * @see Component::execute()
     */
    public function get_view(){}
    public function execute() {
        $view = $this->get_view();
        if ($view) {
            $file =  "view/" . classname_to_path(get_class($this))
                . $view . ".html";
            global $G_LOAD_PATH;
            foreach ($G_LOAD_PATH as $path) {
                if (file_exists($path.$file)) {
                    $this->render($path.$file, TRUE);
                    break;
                }
            }
        }
    }
    /**
     * 渲染页面文件phtml，也就是执行phtml。
     * 即，页面phtml是作为render方法的一部分来影响最终显示结果的。
     * @param string $file 文件路径
     * @param boolen $send_content_type 内容类型开关
     * @see Component::render()
     */
    public function render($file, $send_content_type) {
        if ($send_content_type) {
            // 设置返回内容类型和编码
            Dispatcher::getInstance()->get_response()
                ->set_content_type(
                    $this->get_content_type(),
                    $this->get_charset()
                );
        }
        // 执行渲染操作
        parent::render($file);
    }
}