<?php
require_class("Controller");
class Error_HTTP404Controller extends Controller
{
        public function handle_request(){
            return "Error_HTTP404";
        }
}
