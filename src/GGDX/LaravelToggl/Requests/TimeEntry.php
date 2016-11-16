<?php namespace GGDX\LaravelToggl\Requests;

use GGDX\LaravelToggl\TogglRequest;

class TimeEntry implements TogglRequestInterface{

    public $description;
    public $id; // Entry ID
    public $pid; // Project ID
    public $wid; // Workspace ID
    public $billable = true;
    public $start;
    public $stop;
    public $duration;
    public $created_with;
    public $tags = [];
    public $duronly = false; // Only return start & stop


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
        return $this->pid;
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

    public function is_billable()
    {
        return $this->billable;
    }

    public function set_billable($billable = true)
    {
        $this->billable = $billable === true ? true : false;

        return $this;
    }

    public function get_start()
    {
        return $this->start;
    }

    public function set_start($start = false)
    {
        $this->start = !$start ? date('c') : $start;

        return $this;
    }

    public function get_stop()
    {
        return $this->stop;
    }

    public function set_stop($stop = false)
    {
        $this->stop = !$stop ? date('c') : $stop;

        return $this;
    }

    public function get_duration()
    {
        return $this->duration;
    }

    public function set_duration($duration)
    {
        $this->duration = $duration;

        return $this;
    }

    public function get_created_with()
    {
        return $this->duration;
    }

    public function set_created_with($created_with = false)
    {
        $this->created_with = !$created_with ? "GGDX\Laravel-Toggl" : $created_with;

        return $this;
    }

    public function get_tags()
    {
        return $this->tags;
    }

    public function set_tags(array $tags = [])
    {
        $this->tags = $tags;

        return $this;
    }

    public function set_tag($tag)
    {
        $this->tags[] = $tag;

        return $this;
    }

    public function get_duration_only()
    {
        return $this->duronly;
    }

    public function set_duration_only($duronly = false)
    {
        $this->duronly = !$duronly ? false : true;

        return $this;
    }



    /**
     * Get Entry
     *
     * @var bool $current - True = return active running timer details; false = single entry.
     * @return Object
     */
    public function get($current = false)
    {
        if($current === true){
            $request =  new TogglRequest(config('toggl.api_key'));

            return $request->get('/api/v8/time_entries/current');
        }

        if($this->id == null){
            throw new \Exception('You must supply an Entry ID');
        }

        return $request->get('/api/v8/time_entries/'.$this->id);
    }


    /**
     * Create new entry
     *
     * @var bool $start - Trigger timer starting
     * @return Object - Time entry
     */
    public function create($start = false)
    {
        if($this->id == null){
            unset($this->id);
        } else {
            return $this->update();
        }

        $request =  new TogglRequest(config('toggl.api_key'));

        $data = $this->prepare_data();

        if(!$start){
            return $request->post('/api/v8/time_entries', ['time_entry' => $data]);
        }

        return $request->post('/api/v8/time_entries/start', ['time_entry' => $data]);
    }


    /**
     * Update entry
     *
     *
     * @return Object - Time entry
     */
    public function update()
    {
        if($this->id == null){
            return $this->create();
        }

        $request =  new TogglRequest(config('toggl.api_key'));

        $data = $this->prepare_data();

        return $request->put('/api/v8/time_entries/'.$this->id, ['time_entry' => $data]);
    }


    /**
     * Delete entry
     *
     *
     * @return  Mixed - null (No record to delete) / array Deleted ID
     */
    public function delete()
    {

        if($this->id == null){
            throw new \Exception('You must supply an Entry ID');
        }

        $request =  new TogglRequest(config('toggl.api_key'));

        return $request->delete('/api/v8/time_entries/'.$this->id);
    }


    /**
     * Start the timer running
     *
     *
     * @return object - Time entry
     */
    public function start_timer()
    {
        $this->set_duration(date('U') * -1);

        return $this->create(true);
    }


    /**
     * Stop the timer
     *
     *
     * @return object - Time entry
     */
    public function stop_timer()
    {
        if($this->id == null){
            throw new \Exception('You must supply an Entry ID');
        }

        $request =  new TogglRequest(config('toggl.api_key'));

        return $request->post('/api/v8/time_entries/'.$this->id.'/stop');
    }



    // For some retarded reason that I will never understand, converting some objects to array results in * being placed inside array keys.

    private function prepare_data()
    {
        return json_decode(json_encode($this),true);
    }
}
