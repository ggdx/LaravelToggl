<?php namespace GGDX\LaravelToggl\Requests;

interface TogglRequestInterface{
    public function get_workspace_id();
    public function set_workspace_id($data);
    public function get();
    public function create();
    public function update();
}
