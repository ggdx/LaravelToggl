<?php namespace GGDX\LaravelToggl\Requests;

use GGDX\LaravelToggl\TogglRequest;

class Projects implements TogglRequestInterface{

    public $name; // Project Name
    public $wid; // Workspace ID
    public $cid; // Client ID
    public $pid; // Project ID
    public $active;
    public $is_private;
    public $template;
    public $template_id;
    public $billable;
    public $auto_estimates;
    public $estimated_hours;
    public $at;
    public $color;
    public $rate;

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

    public function get_name()
    {
        return $this->wid;
    }

    public function set_name($data)
    {
        $this->name = $data;

        return $this;
    }

    public function get_client_id()
    {
        return $this->cid;
    }

    public function set_client_id($data)
    {
        $this->cid = $data;

        return $this;
    }

    public function get_project_id()
    {
        return $this->pid;
    }

    public function set_project_id($data)
    {
        $this->pid = $data;

        return $this;
    }

    public function is_active()
    {
        return $this->active;
    }

    public function set_active($data = true)
    {
        $this->active = $data === true ? true : false;

        return $this;
    }


    /**
     * Get Projects
     *
     * Returns either all clients or a single client object.
     * NOTE - Only shows users visible to the user owning the API key
     *
     * @param int $cid (OPTIONAL)- Get client by ID.
     * @return Object
     */
    public function get($pid = false)
    {
        $request =  new TogglRequest(config('toggl.api_key'));
        if($pid){
            $this->set_client_id($pid);
        }

        return $this->pid != null ? $request->get('/api/v8/projects/'.$this->pid) : $request->get('/api/v8/projects');
    }


    /**
     * Create new client
     *
     *
     * @return Object
     */
    public function create()
    {
        if($this->pid == null){
            unset($this->pid);
        } else {
            return $this->update();
        }
        $request =  new TogglRequest(config('toggl.api_key'));
        $data = $this->prepare_data();
        return $request->post('/api/v8/projects', ['project' => $data]);
    }

    /**
     * Update client
     *
     *
     * @return Object
     */
    public function update()
    {
        if($this->pid == null){
            return $this->create();
        }
        $request =  new TogglRequest(config('toggl.api_key'));
        $data = $this->prepare_data();
        return $request->put('/api/v8/projects/'.$this->pid, ['project' => $data]);
    }


    /**
     * Delete client
     *
     *
     * @return  Mixed - null success / array error
     */
    public function delete($data = false)
    {
        $request =  new TogglRequest(config('toggl.api_key'));
        if($data){
            $this->set_client_id($data);
        }
        return $request->delete('/api/v8/clients/'.$this->pid);
    }




    // For some retarded reason that I will never understand, converting some objects to array results in * being placed inside array keys.

    private function prepare_data()
    {
        return json_decode(json_encode($this),true);
    }
}
