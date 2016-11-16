<?php namespace GGDX\LaravelToggl\Requests;

use GGDX\LaravelToggl\TogglRequest;

class ProjectUser implements TogglRequestInterface{

    /**
     * Project User ID
     *
     * NOTE: This IS NOT the User ID ($this->uid) or Project ID ($this->pid). This is the relational ID.
     * NOTE: You cannot change and save this property.
     *
     * @var int
     */
    public $id;

    /**
     * Project ID
     *
     * @var int - REQUIRED
     */
    public $pid;

    /**
     * User ID
     *
     * @var int - REQUIRED
     */
    public $uid;

    /**
     * Workspace ID
     *
     * @var int
     */
    public $wid;

    /**
     * Is this project user a manager of the project?
     *
     * @var bool
     */
    public $manager = false;

    /**
     * Hourly rate of project user
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
     * Get Project Users
     *
     * @param int $pid - Project ID.
     * @return Object
     */
    public function get($pid = false)
    {
        if($pid){
            $this->set_project_id($pid);
        }

        if($this->pid == null){
            throw new \Exception('You must specify a Project ID');
        }

        $project = new Projects($this->wid);

        return $project->get_users($this->pid);
    }


    /**
     * Create new project user
     *
     *
     * @return Object
     */
    public function create()
    {
        if($this->id == null){
            unset($this->id);
        } else {
            return $this->update();
        }

        $request =  new TogglRequest(config('toggl.api_key'));

        $data = $this->prepare_data();

        return $request->post('/api/v8/project_users', ['project_user' => $data]);
    }

    /**
     * Update project user
     *
     *
     * @return Object
     */
    public function update()
    {
        if($this->id == null){
            return $this->create();
        }

        $request =  new TogglRequest(config('toggl.api_key'));

        $data = $this->prepare_data();

        return $request->put('/api/v8/project_users/'.$this->id, ['project_user' => $data]);
    }


    /**
     * Delete project user
     *
     *
     * @return  Mixed - null (No record to delete) / array Deleted PID
     */
    public function delete()
    {
        $request =  new TogglRequest(config('toggl.api_key'));

        if($this->id == null){
            throw new \Exception('You must supply a Project User ID');
        }

        return $request->delete('/api/v8/project_users/'.$this->id);
    }





    /*
    *   Getters & Setters
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

    public function get_project_user_id()
    {
        return $this->id;
    }

    public function set_project_user_id($id)
    {
        $this->id = $id;

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

    public function get_user_id()
    {
        return $this->uid;
    }

    public function set_user_id($uid)
    {
        $this->uid = $uid;

        return $this;
    }

    public function is_manager()
    {
        return $this->manager;
    }

    public function set_manager($manager = false)
    {
        $this->manager = !$manager ? false : true;

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
