<?php
require_controller("Resource_Resources");

class Resource_CacheableResourcesController extends Resource_ResourcesController {
    const CONFIG_N_EXPIRES = "expires";
    const CONFIG_N_LAST_MODIFIED = "last_modified";

    // Override
    public function handle_request() {
        // TODO: to handling 304 header by an interceptor?
        $apf = Dispatcher::getInstance();
        $response = $apf->get_response();

        $cache_control = $this->get_cache_control();
        if ($cache_control) {
            $response->set_header("Cache-Control", $cache_control);
        }

        $expires = @$apf->get_config(self::CONFIG_N_EXPIRES, self::CONFIG_F_RESOURCE);
        $last_modified = @$apf->get_config(self::CONFIG_N_LAST_MODIFIED, self::CONFIG_F_RESOURCE);

        if (isset($last_modified)) {
            $etag = '"'.dechex($last_modified).'"';
        }

        if (isset($etag)) {
            $none_match = @$_SERVER['HTTP_IF_NONE_MATCH'];
            if ($none_match && $none_match == $etag) {
                $response->set_header("HTTP/1.1", "304 ETag Matched", "304");
                exit;
            }
        }

        if (isset($last_modified) && isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])) {
            $tmp = explode(";", $_SERVER['HTTP_IF_MODIFIED_SINCE']);
            $modified_since = @strtotime($tmp[0]);
            if ($modified_since && $modified_since >= $last_modified) {
                $response->set_header("HTTP/1.1", "304 Not Modified", "304");
                exit;
            }
        }

        if (isset($expires)) {
            $response->set_header("Expires", gmdate("D, d M Y H:i:s", $expires) . " GMT");
        }
        if (isset($last_modified)) {
            $response->set_header("ETag", $etag);
            $response->set_header("Last-Modified", gmdate("D, d M Y H:i:s", $last_modified) . " GMT");
        }

        return parent::handle_request();
    }

    protected function get_cache_control() {
        // TODO: get value from config
        return "private, max-age=0;";
    }
}