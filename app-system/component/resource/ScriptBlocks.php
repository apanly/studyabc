<?php
require_component('Resource_JavascriptsAndStyles');

class Resource_ScriptBlocksComponent extends Resource_JavascriptsAndStylesComponent {
    public function get_view() {
        return @$this->get_param('head') ? 'ScriptBlocksHead' : 'ScriptBlocks';
    }
}