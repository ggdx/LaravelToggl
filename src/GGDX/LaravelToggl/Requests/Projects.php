<?php namespace GGDX\LaravelToggl\Requests;

use GGDX\LaravelToggl\TogglRequest;

class Projects implements TogglRequestInterface{

    /**
     * Project Name
     *
     * @var string - REQUIRED
     */
    public $name;

    /**
     * Workspace ID
     *
     * @var int - REQUIRED
     */
    public $wid;

    /**
     * Client ID
     *
     * @var int
     */
    public $cid;

    /**
     * Project ID
     *
     * @var int
     */
    public $pid;

    /**
     * Is project active?
     *
     * @var bool
     */
    public $active = true;

    /**
     * Is project private?
     *
     * @var bool
     */
    public $is_private = true;

    /**
     * Can this project be used as a template?
     *
     * @var bool
     */
    public $template = true;

    /**
     * Template ID
     *
     * @var int
     */
    public $template_id;

    /**
     * Is this project billable?
     *
     * TOGGL PRO ONLY
     *
     * @var bool
     */
    public $billable = true;

    /**
     * Whether the estimated hours are automatically calculated based on task estimations or manually fixed based on the value of $this->estimated_hours
     *
     * TOGGL PRO ONLY
     *
     * @var bool
     */
    public $auto_estimates = false;

    /**
     * If auto_estimates is true then the sum of task estimations is returned, otherwise user inserted hours
     *
     * TOGGL PRO ONLY
     *
     * @var int
     */
    public $estimated_hours;

    /**
     * Toggl ID of project color
     *
     * @var int
     */
    public $color;

    /**
     * Hourly rate of project
     *
     * TOGGL PRO ONLY
     *
     * @var float
     */
    public $rate;

    public function __construct($wid = false)
    {
        $this->set_workspace_id($wid);
    }




    /**
     * Get Projects
     *
     * @param int $pid - Project ID.
     * @return Object
     */
    public function get($pid = false)
    {
        $request =  new TogglRequest(config('toggl.api_key'));

        if($pid){
            $this->set_project_id($pid);
        }

        if($this->pid == null){
            return $request->get('/api/v8/workspaces/'.$this->wid.'/projects');
        }

        return $request->get('/api/v8/projects/'.$this->pid);
    }


    /**
     * Create new project
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
     * Update project
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
     * Delete project
     *
     *
     * @return  Mixed - null (No record to delete) / array Deleted PID
     */
    public function delete()
    {
        $request =  new TogglRequest(config('toggl.api_key'));

        if($this->pid == null){
            throw new \Exception('You must supply a Project ID');
        }

        return $request->delete('/api/v8/projects/'.$this->pid);
    }



    /**
     * Get Project Users
     *
     * @param int $pid - Project ID (can be set via set_project_id() or as a function variable).
     * @return Object
     */
    public function get_users($pid = false)
    {
        $request =  new TogglRequest(config('toggl.api_key'));

        if($pid){
            $this->set_project_id($pid);
        }

        if($this->pid == null){
            throw new \Exception('You need to specify a Project ID');
        }

        return $request->get('/api/v8/projects/'.$this->pid.'/project_users');
    }



    /**
     * Get Project Tasks
     *
     * @param int $pid - Project ID (can be set via set_project_id() or as a function variable).
     * @return Object
     */
    public function get_tasks($pid = false)
    {
        $request =  new TogglRequest(config('toggl.api_key'));

        if($pid){
            $this->set_project_id($pid);
        }

        if($this->pid == null){
            throw new \Exception('You need to specify a Project ID');
        }

        return $request->get('/api/v8/projects/'.$this->pid.'/tasks');
    }





    /*
    *
    *   Getters & Setters
    *
    */
    public function get_workspace_id()
    {
        return $this->wid;
    }

    public function set_workspace_id($wid)
    {
        $this->wid = !$wid ? config('toggl.default_workspace') : $wid;

        return $this;
    }

    public function get_name()
    {
        return $this->name;
    }

    public function set_name($name)
    {
        $this->name = $name;

        return $this;
    }

    public function get_client_id()
    {
        return $this->cid;
    }

    public function set_client_id($cid)
    {
        $this->cid = $cid;

        return $this;
    }

    public function get_project_id()
    {
        return $this->pid;
    }

    public function set_project_id($pid)
    {
        $this->pid = $pid;

        return $this;
    }

    public function is_active()
    {
        return $this->active;
    }

    public function set_active($active = true)
    {
        $this->active = $active === true ? true : false;

        return $this;
    }

    public function is_private()
    {
        return $this->is_private;
    }

    public function set_private($private = false)
    {
        $this->is_private = !$private ? false : true;

        return $this;
    }

    public function is_template()
    {
        return $this->template;
    }

    public function set_template($template = false)
    {
        $this->template = !$template ? false : true;

        return $this;
    }

    public function get_template_id()
    {
        return $this->template_id;
    }

    public function set_template_id($tid)
    {
        $this->template_id = $tid;
    }

    public function is_billable()
    {
        return $this->billable;
    }

    public function set_billable($billable = true)
    {
        $this->billable = $billable === true ? true : false;

        return $this;
    }

    public function is_auto_estimate()
    {
        return $this->auto_estimates;
    }

    public function set_auto_estimate($auto = false)
    {
        $this->auto_estimates = !$auto ? false : true;

        return $this;
    }

    public function get_estimated_hours()
    {
        return !$this->auto_estimates ? false : (int) $this->estimated_hours;
    }

    public function set_estimated_hours($eh)
    {
        if($this->estimated_hours !== false){
            $this->estimated_hours = (int) $eh;
        }

        return $this;
    }

    public function get_color()
    {
        return $this->color;
    }

    public function set_color($color)
    {
        $this->color = $color;

        return $this;
    }

    public function get_rate()
    {
        return $this->rate;
    }

    public function set_rate($rate)
    {
        $this->rate = $rate;

        return $this;
    }


    // For some retarded reason that I will never understand, converting some objects to array results in * being placed inside array keys.

    private function prepare_data()
    {
        return json_decode(json_encode($this),true);
    }
}
