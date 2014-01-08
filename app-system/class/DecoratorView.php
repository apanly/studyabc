<?php
require_class("View");
/**
 * 页面装饰器，其实是页面的骨架，起占位作用。
 * 所有的组件和实际页面内容都依附于装饰器。
 * 所谓的“装饰器”其实就是页面模板。
 * 在页面模板中：
 * real_page方法显示页面主体；
 * component方法显示特定组件；
 */
abstract class DecoratorView extends View {
    /**
     * 重载了组件的execute方法
     * 载入装饰器
     * @see APF_Page::execute()
     */
    public function execute() {
        $view = $this->get_decorator();
        Dispatcher::getInstance()->debug("decorator: $view");
        $file =  "view/".$view.".html";
        global $G_LOAD_PATH;
        foreach ($G_LOAD_PATH as $path) {
            if (file_exists($path.$file)) {
                $this->render($path.$file, TRUE);
                break;
            }
        }

    }
    /**
     * 获取装饰器名称
     * @return string 装饰器路径
     */
    public function get_decorator() {
        return "mainDecorator";
    }
    /**
     * 本方法在页面模板中调用，负责渲染页面的主题内容。
     * 跟get_view方法并无区别
     */
    public function real_page() {
        $view = $this->get_view();
        if ($view) {
            $file =  "view/" . classname_to_path(get_class($this))
                . $view . ".html";
            global $G_LOAD_PATH;
            foreach ($G_LOAD_PATH as $path) {
                if (file_exists($path.$file)) {
                    $this->render($path.$file, FALSE);
                    break;
                }
            }
        }
    }
}