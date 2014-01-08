<?php
/**
 * APF 组件类，所有页面显示的基础类
 * added by htlv
 */
abstract class Component {
    /**
     * 组件构造函数，每个组件都有父组件，最早的祖先组件是一个页面类
     * @param APF_Component $parent 父组件对象
     * @param int $html_id 组件id
     */
    public function __construct($parent=NULL, $html_id=NULL) {
        $this->parent = $parent;
        $this->html_id = $html_id;
    }


    /**
     * 获取父组件
     * @return Component
     */
    public function get_parent() {
        return $this->parent;
    }

    /**
     * 父组件
     * @var APF_Component
     */
    private $parent;

    //

    /**
     * 获取组件id
     * @return string
     */
    public function get_html_id() {
        return $this->html_id;
    }
    /**
     * 组件id
     * @var int
     */
    private $html_id;
    /**
     * 注册javascript路径，每个路径对应单独的js文件
     * @return array
     */

    public static function use_javascripts() {
        return array();
    }
    /**
     * 注册css路径，每个路径对应单独的css文件
     */
    public static function use_styles() {
        return array();
    }
    /**
     * 注册javascript路径，所有js文件将合并到名为页面类的js中。
     */
    public static function use_boundable_javascripts() {
        return array();
    }
    /**
     * 注册css路径，所有css文件将合并到名为页面类的css中。
     */
    public static function use_boundable_styles() {
        return array();
    }
    /**
     * 注册子组件
     * @return array
     */
    public static function use_component() {
        return array();
    }


    /* begin 2010-11-04 by Jock*/
    public static function use_inline_styles(){
        return false;
    }

    public static function prefetch_javascripts(){
        return array();
    }

    public static function prefetch_styles(){
        return array();
    }

    public static function prefetch_images(){
        return array();
    }
    /* end 2010-11-04 by Jock*/

    /* add by Jock 2011-05-20 */
    public static function use_inline_scripts(){
        return false;
    }
    /**
     * 设置参数，不知何用
     * @param unknown_type $params
     */

    public function set_params($params=array()) {
        $this->params = $params;
    }
    /**
     * 获取参数
     * @return array
     */
    public function get_params() {
        return $this->params;
    }
    /**
     * 获取指定参数
     * @param string $name
     */
    public function get_param($name) {
        return $this->params[$name];
    }
    /**
     * 参数槽
     * @var array
     */
    private $params;
    /**
     * 设置页面变量
     * @param string $name 变量名
     * @param unknown_type $value
     */

    public function assign_data($name, $value) {
        $this->data[$name] = $value;
    }
    /**
     * 获取所有页面变量
     */
    public function get_data() {
        return $this->data;
    }
    /**
     * 变量槽
     * @var array
     */
    private $data = array();
    /**
     * 载入组件显示页面
     */

    public function execute() {
        $view = $this->get_view();
        if ($view) {
            $f = classname_to_path(get_class($this)).$view.'.html';
            $file =  "component/".$f;
            global $G_LOAD_PATH;
            foreach ($G_LOAD_PATH as $path) {
                if (file_exists($path.$file)) {
                    $this->render($path.$file);
                    break;
                }
            }
        }
    }
    /**
     * 开始输出脚本
     */
    public function script_block_begin() {
        ob_start();
    }
    /**
     * 结束脚本输出
     * @param unknown_type $order
     */
    public function script_block_end($order=0) {
        $content = ob_get_contents();
        Dispatcher::getInstance()->register_script_block($content,$order);
        ob_end_clean();
    }
    /**
     * 获取页面框架名称
     */

    abstract public function get_view();
    /**
     * 渲染页面，也就是载入文件……
     * 设置所有页面变量，保证phtml的php脚本可以引用
     * @param string $file 文件路径
     */
    protected function render($file) {
        // 设置变量
        foreach ($this->data as $key=>$value) {
            $$key = $value;
        }
        // 预设请求类和响应类变量，其实大可不必
        $request = Dispatcher::getInstance()->get_request();
        $response = Dispatcher::getInstance()->get_response();
        // 渲染？？？就是include，够高端吧。
        include($file);
    }


    /**
     * 实例化组件对象
     * @return APF_Component
     */
    public function component($class, $params=array()) {
        return Dispatcher::getInstance()->component($this, $class, $params);
    }


    /**
     * 获取组件的页面类
     * @return APF_Page
     */
    protected function get_page() {
        $object = $this->get_parent();
        while ($object) {
            if ($object instanceof View) {
                return $object;
            }
            $object = $object->get_parent();
        }
        return NULL;
    }
}
