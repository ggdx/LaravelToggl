<?php namespace GGDX\LaravelToggl\Requests;

use GGDX\LaravelToggl\TogglRequest;

class Dashboard{


    /**
     * Workspace ID
     *
     * @var int
     */
    public $wid;

    public function __construct($wid = false)
    {
        $this->set_workspace_id($wid);
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




    public function get_workspace_id()
    {
        return $this->wid;
    }

    public function set_workspace_id($wid)
    {
        $this->wid = !$wid ? config('toggl.default_workspace') : $wid;

        return $this;
    }
}
