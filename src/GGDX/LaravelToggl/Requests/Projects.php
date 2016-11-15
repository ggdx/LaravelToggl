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

    public function is_private()
    {
        return $this->is_private;
    }

    public function set_private($data = false)
    {
        $this->is_private = !$data ? false : true;

        return $this;
    }

    public function is_template()
    {
        return $this->template;
    }

    public function set_template($data = false)
    {
        $this->template = !$data ? false : true;

        return $this;
    }

    public function get_template_id()
    {
        return $this->template_id;
    }

    public function set_template_id($data)
    {
        $this->template_id = $data;
    }

    public function is_billable()
    {
        return $this->billable;
    }

    public function set_billable($data = true)
    {
        $this->billable = $data === true ? true : false;

        return $this;
    }

    public function is_auto_estimate()
    {
        return $this->auto_estimates;
    }

    public function set_auto_estimate($data = false)
    {
        $this->auto_estimates = !$data ? false : true;

        return $this;
    }

    public function get_estimated_hours()
    {
        return !$this->auto_estimates ? false : (int) $this->estimated_hours;
    }

    public function set_estimated_hours($data)
    {
        if($this->auto_estimates !== false){
            $this->estimated_hours = (int) $data;
        }

        return $this;
    }

    public function get_color()
    {
        return $this->color;
    }

    public function set_color($data)
    {
        $this->color = $data;

        return $this;
    }

    public function get_rate()
    {
        return $this->rate;
    }

    public function set_rate($data)
    {
        $this->rate = $data;

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
