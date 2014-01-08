<?php
class Router {
    const CONFIG_F_ROUTE          = 'route';
    const CONFIG_N_MAPPINGS       = 'mappings';
    const CONFIG_N_REGEX_FUNCTION = 'regex_function';
    const DEFAULT_REGEX_FUNCTION  = 'ereg';
    const HTTP404_CONTROLLER = "404";
    private $mappings;
    public function get_mappings($key){
        if(!empty($key))
            return $this->mappings[$key];
        else
            return $this->mappings;
    }
    /**
     * Returns the class name of matched controller
     *
     * @return class name in string
     */
    public function mapping() {
        if($this->islowerbroser()){
            return "Error_lowerie";
        }
        $dispatch=Dispatcher::getInstance();
        $mappings = $dispatch->get_config(self::CONFIG_N_MAPPINGS, self::CONFIG_F_ROUTE);
        $regex_function = @$dispatch->get_config(self::CONFIG_N_REGEX_FUNCTION, self::CONFIG_F_ROUTE);
        if (!function_exists($regex_function)) {
            $regex_function = self::DEFAULT_REGEX_FUNCTION;
        }
        if (BASE_URI != '' && strpos($_SERVER['REQUEST_URI'], BASE_URI) === 0) {
            $uri = substr($_SERVER['REQUEST_URI'], strlen(BASE_URI));
        } else {
            $uri = $_SERVER['REQUEST_URI'];
        }
        $pos = strpos($uri, '?');
        if ($pos) {
            $uri = substr($uri, 0, $pos);
        }
        if (empty($uri)) {
            $uri = '/';
        }
        $matches=array();
        foreach ($mappings as $class => $mapping) {
            foreach ($mapping as $pattern) {
                if (@$regex_function($pattern, $uri, $matches)) {
                    $dispatch->get_request()->set_router_matches($matches);
                    return $class;
                }
            }
        }
        //auto mapping
        $auto_mapping = $dispatch->get_config('enabled_auto_router');
        if ($auto_mapping) {
            $class = $this->auto_mapping($uri);
            if ($class) {
                return $class;
            }
        }

        // TODO: 404 controller?
        $class = $dispatch->get_config(self::HTTP404_CONTROLLER, self::CONFIG_F_ROUTE);
        if ($class) {
            return $class;
        }
        $dispatch->get_response()->set_header("HTTP/1.1", "404 Not Found", "404");
        return false;
    }

    public function auto_mapping ($uri) {
        $class_name = $this->format_uri2controller($uri);
        apf_require_controller($class_name,false);
        if (class_exists($class_name . 'Controller')) {
            return $class_name;
        }
        return false;
    }

    protected function format_uri2controller ($uri) {
        $matches = explode('/',$uri);
        array_shift($matches);
        unset($matches[count($matches)-1]);
        $classes = array();
        foreach ($matches as $item) {
            $classes[] = ucfirst($item);
        }
        return implode("_",$classes);
    }
    private function islowerbroser() {
        $userAgent = strtolower($_SERVER["HTTP_USER_AGENT"]);
        if (preg_match("/msie\s*6/", $userAgent) || preg_match("/msie\s*5/", $userAgent) || preg_match("/msie\s*7/", $userAgent)) {
            return true;
        }
        return false;
    }
}
