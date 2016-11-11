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

    /**
     * Get Clients
     *
     * Returns either all clients or a single client object.
     * NOTE - Only shows users visible to the user owning the API key
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
            throw new \Exception('Client ID required.');
        }

        return $this->request->put('/api/v8/clients/'.$id, ['client' => $data]);
    }


    /**
     * Delete Client
     *
     * Deletes a single client
     *
     * @param int id - Get client by ID.
     * @return null.
     */
    public function delete_client($id = false)
    {
        if(!$id){
            throw new \Exception('Client ID required.');
        }

        return $this->request->delete('/api/v8/clients/'.$id);
    }


    /**
     * Get Client Projects
     *
     * Returns object of client projects
     *
     * @param int id - Get client by ID.
     * @param bool/string active - OPTIONAL filter for active (true), inactive (false) and all ("both") projects.
     * @return object.
     */
    public function get_client_projects($id = false, $active = true)
    {
        if(!$id){
            throw new \Exception('Client ID required.');
        }

        return $this->request->get('/api/v8/clients/'.$id.'/projects', ['active' => $active]);
    }





    /***********************       2 - Projects          *************************/
    /**
     * Create Project
     *
     *
     * @param array data - (* = required)
     *                  wid* (int) - Workspace ID
     *                  cid (int) - Client ID
     *                  active (bool) - Is the project active? Default true
     *                  is_private (bool) - Is the project private to your User? Default true
     *                  name* (string) - Project name
     *                  billable (bool) - Is the project billable? - Toggl Pro
     *                  rate (float) - Hourly rate of project - Toggl Pro
     * @return Object
     */
    public function create_project(array $data = [])
    {
        if(empty($data['wid']) || !strlen($data['wid'])){
            throw new \Exception('Workspace ID required.');
        }
        if(empty($data['name']) || !strlen($data['name'])){
            throw new \Exception('Project name required.');
        }
        return $this->request->post('/api/v8/projects',['project' => $data]);
    }


    /**
     * Get Project
     *
     *
     * @param int id - Project ID
     * @return Object
     */
    public function get_project($id = false)
    {
        if(!$id){
            throw new \Exception('Project ID required.');
        }
        return $this->request->get('/api/v8/projects/'.$id);
    }


    /**
     * Update Project
     *
     *
     * @param array data - (* = required)
     *                  wid* (int) - Workspace ID
     *                  cid (int) - Client ID
     *                  active (bool) - Is the project active? Default true
     *                  is_private (bool) - Is the project private to your User? Default true
     *                  name* (string) - Project name
     *                  billable (bool) - Is the project billable? - Toggl Pro
     *                  rate (float) - Hourly rate of project - Toggl Pro
     * @return Object
     */
    public function update_project($id = false, array $data = [])
    {
        if(!$id){
            throw new \Exception('Project ID required.');
        }

        return $this->request->put('/api/v8/projects/'.$id,['project' => $data]);
    }


    /**
     * Delete Project
     *
     * Deletes a single Project (single int)
     * Deletes multiple projects (array of int - [123, 456, 789])
     *
     * @param int id - Get Project by ID.
     * @param array id - Get multiple projects by ID.
     * @return int id - Deleted project ID, or null if nothing deleted.
     */
    public function delete_project($id = false)
    {
        if(!$id){
            throw new \Exception('Project ID required.');
        }
        if(is_array($id)){
            $id = implode(',',$id);
        }
        return $this->request->delete('/api/v8/projects/'.$id);
    }


    /**
     * Get Project Users
     *
     * @param int id - Get Project by ID.
     * @return object - All users attributed to project
     */
    public function project_users($id = false)
    {
        if(!$id){
            throw new \Exception('Project ID required.');
        }
        return $this->request->get('/api/v8/projects/'.$id.'/project_users');
    }


    /**
     * Get Project Tasks (Toggl Pro)
     *
     * @param int id - Get Project by ID.
     * @return object - All users attributed to project
     */
    public function project_tasks($id = false)
    {
        if(!$id){
            throw new \Exception('Project ID required.');
        }
        return $this->request->get('/api/v8/projects/'.$id.'/project_tasks');
    }
}
