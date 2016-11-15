<?php namespace GGDX\LaravelToggl\Requests;

use GGDX\LaravelToggl\TogglRequest;

class ProjectUser implements TogglRequestInterface{

    public $id; // Project User ID
    public $pid; // Project ID
    public $uid; // User ID
    public $wid; // Workspace ID
    public $manager = false;
    public $rate; // Toggl Pro


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
        return !$data ? config('toggl.default_workspace') : $data;
    }

    public function get_project_user_id()
    {
        return $this->id;
    }

    public function set_project_user_id($data)
    {
        $this->id = $data;

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

    public function get_user_id()
    {
        return $this->uid;
    }

    public function set_user_id($data)
    {
        $this->uid = $data;

        return $this;
    }

    public function is_manager()
    {
        return $this->manager;
    }

    public function set_manager($data = false)
    {
        $this->manager = !$data ? false : true;

        return $this;
    }

    public function get_rate()
    {
        return $this->uid;
    }

    public function set_rate($data)
    {
        $this->rate = $data;

        return $this;
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
        //dd($data);
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
    public function delete($id = false)
    {
        $request =  new TogglRequest(config('toggl.api_key'));

        if($id){
            $this->set_project_user_id($id);
        }

        return $request->delete('/api/v8/project_users/'.$this->id);
    }







    // For some retarded reason that I will never understand, converting some objects to array results in * being placed inside array keys.

    private function prepare_data()
    {
        return json_decode(json_encode($this),true);
    }
}
