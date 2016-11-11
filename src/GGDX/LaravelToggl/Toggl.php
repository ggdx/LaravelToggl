<?php namespace GGDX\LaravelToggl;

use Exception;

/**
 * For Toggl API V8
 *
 *
 *
 * 1 - Clients
 * 2 - Projets
 * 2a - Project Users
 * 3 - Tags
 * 4 - Tasks
 * 5 - Time Entries
 * 6 - Users
 * 7 - Workspeaces
 * 7a - Workspace Users
 * 8 - Dashboard
 */

class Toggl{


    private $request;


    public function __construct($config)
    {
        $this->request = new TogglRequest($config['api_key']);
    }



    /***********************       1 - CLIENTS          *************************/
    // Gets all clients visible to user (API KEY USER)

    /**
     * Get Clients
     *
     * Returns either all clients or a single client object
     *
     * @param int id (OPTIONAL)- Get client by ID.
     * @return Object
     */
    public function get_clients($id = false)
    {
        return $id ? $this->request->get('/api/v8/clients/'.$id) : $this->request->get('/api/v8/clients');
    }


    /**
     * Update Client
     *
     * Update a single client
     *
     * @param int id - Get client by ID.
     * @param array data - [name, notes].
     * @return Object - client object.
     */
    public function update_client($id = false, array $data = [])
    {
        if(!$id){
            return false;
        }
        return $this->request->put('/api/v8/clients/'.$id, ['client' => $data]);
    }
}
