<?php namespace GGDX\LaravelToggl\Requests;

use GGDX\LaravelToggl\TogglRequest;

class Tags implements TogglRequestInterface{

    public $id; // Tag ID
    public $wid; // Workspace ID
    public $name;

    public function __construct($wid = false)
    {
        $this->wid = $this->set_workspace_id($wid);
    }

    public function get_workspace_id()
    {
        return $this->wid;
    }

    public function set_workspace_id($wid)
    {
        return !$wid ? config('toggl.default_workspace') : $wid;
    }

    public function get_id()
    {
        return $this->id;
    }

    public function set_id($id)
    {
        $this->id = $data;
        return $this;
    }

    public function get_tag()
    {
        return $this->name;
    }

    public function set_tag($name)
    {
        $this->name = $data;

        return $this;
    }





    /**
     * Get Tags
     *
     * @param int $id - Project ID.
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
     * Create tag
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

        return $request->post('/api/v8/tags', ['tag' => $data]);
    }

    /**
     * Update tag
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

        return $request->put('/api/v8/tags/'.$this->id, ['tag' => $data]);
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
            $this->set_id($id);
        }

        return $request->delete('/api/v8/tags/'.$this->id);
    }







    // For some retarded reason that I will never understand, converting some objects to array results in * being placed inside array keys.

    private function prepare_data()
    {
        return json_decode(json_encode($this),true);
    }
}
