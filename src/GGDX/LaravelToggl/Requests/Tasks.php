<?php namespace GGDX\LaravelToggl\Requests;

use GGDX\LaravelToggl\TogglRequest;

class Tasks implements TogglRequestInterface{

    public $name;
    public $id; // Task ID
    public $pid; // Project ID
    public $uid; // User ID
    public $wid; // Workspace ID
    public $estimated_seconds;
    public $tracked_seconds;
    public $active = true;


    public function __construct($wid = false)
    {
        $this->set_workspace_id($wid);
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


    public function get_id()
    {
        return $this->id;
    }

    public function set_id($id)
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

    public function get_name()
    {
        return $this->name;
    }

    public function set_name($name)
    {
        $this->name = $name;

        return $this;
    }

    public function get_estimated_seconds()
    {
        return $this->estimated_seconds;
    }

    public function set_estimated_seconds($estimated_seconds)
    {
        $this->estimated_seconds = $estimated_seconds;

        return $this;
    }

    public function get_tracked_seconds()
    {
        return $this->tracked_seconds;
    }

    public function set_tracked_seconds($tracked_seconds)
    {
        $this->tracked_seconds = $tracked_seconds;

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



    /**
     * Get Tasks
     *
     *
     * @return Object
     */
    public function get()
    {
        if($this->pid != null){
            $project = new Projects($this->wid);
            return $project->get_tasks();
        }

        if($this->id == null){
            throw new \Exception('You must supply a Task ID');
        }

        return $request->get('/api/v8/tasks/'.$this->id);
    }


    /**
     * Create new task
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

        if($this->pid == null){
            throw new \Exception('You must supply a Project ID');
        }

        $request =  new TogglRequest(config('toggl.api_key'));

        $data = $this->prepare_data();

        return $request->post('/api/v8/tasks', ['task' => $data]);
    }

    /**
     * Update task
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

        return $request->put('/api/v8/tasks/'.$this->id, ['task' => $data]);
    }


    /**
     * Delete task
     *
     *
     * @return  Mixed - null (No record to delete) / array Deleted ID
     */
    public function delete()
    {
        $request =  new TogglRequest(config('toggl.api_key'));

        if($this->id == null){
            throw new \Exception('You must supply a Task ID');
        }

        return $request->delete('/api/v8/tasks/'.$this->id);
    }



    // For some retarded reason that I will never understand, converting some objects to array results in * being placed inside array keys.

    private function prepare_data()
    {
        return json_decode(json_encode($this),true);
    }
}
