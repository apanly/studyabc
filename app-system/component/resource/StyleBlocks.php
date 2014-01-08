<?php
require_component('Resource_JavascriptsAndStyles');

class Resource_StyleBlocksComponent extends Resource_JavascriptsAndStylesComponent {
    public function get_view() {
        return "StyleBlocks";
    }

    public function enabled_inline_styles () {
        return Dispatcher::getInstance()->get_config('enabled_inline_styles','resource')
        && Dispatcher::getInstance()->get_use_inline_styles(); // enabled and used
    }

    public function get_inline_styles () {
        $url = $this->get_boundable_styles_url();
        $key = "css-".md5($url);
        apf_require_class('APF_Cache_Factory');
        $mem = APF_Cache_Factory::get_instance()->get_memcache();
        $css = $mem->get($key);
        if ($css) {
            return $css;
        }
        apf_require_class('APF_Http_Client_Factory');
        $c = APF_Http_Client_Factory::get_instance()->get_curl();
        $c->set_url($url);
        $c->execute();
        $css = $c->get_response_text();
        $mem->set($key,$css,0,0);
        return $css;
    }
}