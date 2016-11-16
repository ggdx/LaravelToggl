<?php namespace GGDX\LaravelToggl\Requests;

use GGDX\LaravelToggl\TogglRequest;

class Clients implements TogglRequestInterface{

    /**
     * Client ID
     *
     * @var int
     */
    public $cid;

    /**
     * Client name
     *
     * @var string
     */
    public $name;

    /**
     * Client note
     *
     * @var string
     */
    public $notes;

    /**
     * Workspace ID
     *
     * @var int
     */
    public $wid;


    /**
     * Get Clients
     *
     * Returns either all clients or a single client object.
     * NOTE - Only shows users visible to the user owning the API key
     *
     * @param int $cid (OPTIONAL)- Get client by ID.
     * @return Object
     */
    public function get($cid = false)
    {
        $request =  new TogglRequest(config('toggl.api_key'));
        if($cid){
            $this->set_client_id($cid);
        }

        return $this->cid != null ? $request->get('/api/v8/clients/'.$this->cid) : $request->get('/api/v8/clients');
    }

    /**
     * Get Clients' Projects
     *
     * Returns either all projects belonging to client.
     *
     * @param int $cid - Client ID.
     * @return Object
     */
    public function get_client_projects($cid = false)
    {
        $request =  new TogglRequest(config('toggl.api_key'));
        if($cid){
            $this->set_client_id($cid);
        }
        return $request->get('/api/v8/clients/'.$this->cid.'/projects');
    }


    /**
     * Create new client
     *
     *
     * @return Object
     */
    public function create()
    {
        if($this->cid == null){
            unset($this->cid);
        } else {
            return $this->update();
        }

        $request =  new TogglRequest(config('toggl.api_key'));

        $data = $this->prepare_data();

        return $request->post('/api/v8/clients', ['client' => $data]);
    }

    /**
     * Update client
     *
     *
     * @return Object
     */
    public function update()
    {
        if($this->cid == null){
            return $this->create();
        }

        $request =  new TogglRequest(config('toggl.api_key'));

        $data = $this->prepare_data();

        return $request->put('/api/v8/clients/'.$this->cid, ['client' => $data]);
    }


    /**
     * Delete client
     *
     *
     * @return  Mixed - null success / array error
     */
    public function delete()
    {
        $request =  new TogglRequest(config('toggl.api_key'));

        if($this->cid == null){
            throw new \Exception('You must supply a Client ID');
        }

        return $request->delete('/api/v8/clients/'.$this->cid);
    }


    // Getters & Setters

    public function __construct($wid = false)
    {
        $this->set_workspace_id($wid);
    }

    public function get_client_id()
    {
        return $this->cid;
    }

    public function set_client_id($cid = null)
    {
        $this->cid = $cid;

        return $this;
    }

    public function get_client_name()
    {
        return $this->name;
    }

    public function set_client_name($name = null)
    {
        $this->name = $name;

        return $this;
    }

    public function get_note()
    {
        return $this->notes;
    }

    public function set_note($note = null)
    {
        $this->notes = $note;

        return $this;
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

    // For some retarded reason that I will never understand, converting some objects to array results in * being placed inside array keys.

    private function prepare_data()
    {
        return json_decode(json_encode($this),true);
    }
}
