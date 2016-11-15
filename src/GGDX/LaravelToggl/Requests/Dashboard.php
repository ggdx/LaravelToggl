<?php namespace GGDX\LaravelToggl\Requests;

use GGDX\LaravelToggl\TogglRequest;

class Dashboard{

    public $wid;

    public function __construct($wid = false)
    {
        $this->wid = $this->set_workspace_id($wid);
    }

    public function get_workspace_id()
    {
        return $this->wid;
    }

    public function set_workspace_id($data)
    {
        return !$data ? null : $data;
    }




    /**
     * Get Dashboard
     *
     * @return Object
     */
    public function get()
    {
        $request =  new TogglRequest(config('toggl.api_key'));

        return $request->get('/api/v8/dashboard/'.$this->wid);
    }
}
