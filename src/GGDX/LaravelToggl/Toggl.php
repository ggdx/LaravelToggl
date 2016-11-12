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
 * @TODO 4 - Tasks (Toggl Pro)
 * 5 - Time Entries
 * 6 - Users
 * 7 - Workspeaces
 * 7a - Workspace Users
 * 8 - Dashboard
 */

class Toggl{


    private $request, $now;

    public function __construct($config)
    {
        $this->request = new TogglRequest($config['api_key']);

        $this->now = date('c');
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



    /***********************       2a - Project Users          *************************/
    /**
     * Create Project User
     *
     *
     * @param array data - (* = required)
     *                  pid* (int) - Project ID
     *                  uid* (int) - User ID
     *                  wid (int) - Workspace ID - Optional, if not set then user default used
     *                  manager (bool) - Admin rights to project? Default false
     *                  rate (float) - Hourly rate (Toggl Pro)
     * @return Object
     */
    public function create_project_user(array $data = [])
    {
        if(empty($data['wid']) || !strlen($data['wid'])){
            throw new \Exception('Workspace ID required.');
        }
        if(empty($data['uid']) || !strlen($data['uid'])){
            throw new \Exception('User ID required.');
        }
        return $this->request->post('/api/v8/projects',['project_user' => $data]);
    }


    /**
     * Create Project User
     *
     *
     * @param array data - (* = required)
     *                  pid* (int) - Project ID
     *                  uid* (int) - User ID
     *                  wid (int) - Workspace ID - Optional, if not set then user default used
     *                  manager (bool) - Admin rights to project? Default false
     *                  rate (float) - Hourly rate (Toggl Pro)
     * @return Object
     */
    public function update_project_user($id = false, array $data = [])
    {
        if(!$id){
            throw new \Exception('Project User ID required.');
        }

        return $this->request->put('/api/v8/projects',['project_user' => $data]);
    }


    /**
     * Delete Project User
     *
     * @param int id - Project user ID.
     * @return null.
     */
    public function delete_project_user($id = false)
    {
        if(!$id){
            throw new \Exception('Project user ID required.');
        }

        return $this->request->delete('/api/v8/project_users/'.$id);
    }


    /***********************       3 - Tags          *************************/
    /**
     * Create Tag
     *
     *
     * @param array data - (* = required)
     *                  name* (string) - Tag name
     *                  wid* (int) - Workspace ID
     *
     * @return Object
     */
    public function create_tag(array $data = [])
    {
        if(empty($data['wid']) || !strlen($data['wid'])){
            throw new \Exception('Workspace ID required.');
        }
        if(empty($data['name']) || !strlen($data['name'])){
            throw new \Exception('Tag name required.');
        }
        return $this->request->post('/api/v8/tags',['tag' => $data]);
    }

    /**
     * Update Tag
     *
     *
     * @param int id - Tag ID
     * @param array data - (* = required)
     *                  name* (string) - Tag name
     *
     * @return Object
     */
    public function update_tag($id = false, array $data = [])
    {
        if(!$id){
            throw new \Exception('Tag ID required.');
        }
        if(empty($data['name']) || !strlen($data['name'])){
            throw new \Exception('Tag name required.');
        }
        return $this->request->put('/api/v8/tags/'.$id,['tag' => $data]);
    }

    /**
     * Delete Tag
     *
     *
     * @param int id - Tag ID
     *
     * @return Object
     */
    public function delete_tag($id = false)
    {
        if(!$id){
            throw new \Exception('Tag ID required.');
        }

        return $this->request->delete('/api/v8/tags/'.$id);
    }


    /***********************       5 - Time Entries          *************************/

    /**
     * Create a time entry (start timer)
     *
     * NOTE - If a "stop" value is given, you will not need to use stop_timer().
     *
     * @param array data - (* = required)
     *                  description* (string) - Entry description
     *                  wid* (int) - Workspace ID. Default is API user default.
     *                  pid* (int) - Project ID
     *                  tid (int) - Task ID (Toggl Pro)
     *                  billable (bool) - default false (Toggl Pro)
     *                  start* (string) - Entry start time, ISO8601 date AND time
     *                  stop (string) - Entry stop time, ISO8601 date AND time - If not set, timer will run until stopped.
     *                  created_with* (string) - The name of the client app, default GGDX_LaravelToggl
     *                  tags (array) - Array of tag names (string)
     * @return object - Time entry
     */
    public function start_timer(array $data = [])
    {
        if(empty($data['description']) || !strlen($data['description'])){
            throw new \Exception('Description required.');
        }
        if(empty($data['pid']) || !strlen($data['pid'])){
            throw new \Exception('Project ID required.');
        }
        if(empty($data['wid']) || !strlen($data['wid'])){
            throw new \Exception('Workspace ID required.');
        }

        if(empty($data['start']) || !strlen($data['start']) || !$this->validate_date($data['start']) || $data['start'] > $now){
            $data['start'] = $this->now;
        }
        if(empty($data['created_with']) || !strlen($data['created_with'])){
            $data['created_with'] = 'GGDX_LaravelToggl';
        }
        $data['duration'] = date('U') * -1;

        return $this->request->post('/api/v8/time_entries',['time_entry' => $data]);
    }


    /**
     * Stop timer
     *
     * @param int id Time entry ID
     * @return object - Time entry
     */
    public function stop_timer($id = false)
    {
        if(!$id){
            throw new \Exception('Time entry ID is required');
        }

        return $this->request->put('/api/v8/time_entries/'.$id.'/stop');
    }


    /**
     * Get time entry
     *
     * @param int id Time entry ID
     * @return object - Time entry
     */
    public function get_time_entry($id = false)
    {
        if(!$id){
            throw new \Exception('Time entry ID is required');
        }

        return $this->request->get('/api/v8/time_entries/'.$id);
    }


    /**
     * Get running time entry data
     *
     * @return object - Time entry
     */
    public function get_current_entry()
    {
        return $this->request->get('/api/v8/time_entries/current');
    }


    /**
     * Update a time entry
     *
     * @param int id - Time entry ID
     * @param array data
     *                  description (string) - Entry description
     *                  wid (int) - Workspace ID. Default is API user default.
     *                  pid (int) - Project ID
     *                  tid (int) - Task ID (Toggl Pro)
     *                  billable (bool) - default false (Toggl Pro)
     *                  start (string) - Entry start time, ISO8601 date AND time
     *                  stop (string) - Entry stop time, ISO8601 date AND time - If not set, timer will run until stopped.
     *                  created_with (string) - The name of the client app, default GGDX_LaravelToggl
     *                  tags (array) - Array of tag names (string)
     * @return object - Time entry
     */
    public function update_time_entry($id = false, array $data = [])
    {
        if(!$id){
            throw new \Exception('Time entry ID is required');
        }

        return $this->request->put('/api/v8/time_entries/'.$id,['time_entry' => $data]);
    }


    /**
     * Delete time entry
     *
     * @param int id - Time entry ID
     * @return int - Time entry ID
     */
    public function delete_time_entry($id = false)
    {
        if(!$id){
            throw new \Exception('Time entry ID is required');
        }
        return $this->request->delete('/api/v8/time_entries/'.$id);
    }



    /***********************       6 - Users          *************************/

    /**
     * Get current user (API Key Owner)
     *
     * @var bool full - Returns full user data (projects, tme entries, etc.) if true, otherwise just basic user info.
     * @return return object
     */
    public function get_current_user($full = false)
    {
        if($full){
            return $this->request->get('/api/v8/me?with_related_data=true');
        }
        return $this->request->get('/api/v8/me');
    }




    // Helpers

    // Validate ISO 8601
    private function validate_date($date)
    {
        if (preg_match('/^(\d{4})-(\d{2})-(\d{2})T(\d{2}):(\d{2}):(\d{2})/', $date, $parts) == true) {
            $time = gmmktime($parts[4], $parts[5], $parts[6], $parts[2], $parts[3], $parts[1]);

            $input_time = strtotime($date);
            if ($input_time === false) return false;

            return $input_time == $time;
        } else {
            return false;
        }
    }






}
