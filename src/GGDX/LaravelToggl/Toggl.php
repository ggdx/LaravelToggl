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
            throw new \Exception('wid (Workspace ID) required.');
        }
        if(empty($data['name']) || !strlen($data['name'])){
            throw new \Exception('name (Project name) required.');
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
            throw new \Exception('wid (Workspace ID) required.');
        }
        if(empty($data['uid']) || !strlen($data['uid'])){
            throw new \Exception('uid (User ID) required.');
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
            throw new \Exception('wid (Workspace ID) required.');
        }
        if(empty($data['name']) || !strlen($data['name'])){
            throw new \Exception('name (Tag name) required.');
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
            throw new \Exception('description required.');
        }
        if(empty($data['pid']) || !strlen($data['pid'])){
            throw new \Exception('pid (Project ID) required.');
        }
        if(empty($data['wid']) || !strlen($data['wid'])){
            throw new \Exception('wid (Workspace ID) required.');
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


    /**
     * Update current user (API Key Owner)
     *
     * @var array data - [
     *                      fullname => string
     *                      email => string
     *                      send_product_emails => bool
     *                      send_weekly_report => bool
     *                      send_timer_notifications => bool
     *                      store_start_and_stop_time => bool
     *                      beginning_of_week => int (between 0 - 6)
     *                      timezone => string, i.e. "Europe/London"
     *                      timeofday_format => string, either "H:mm" (24h) OR "h:mm A" (12h)
     *                      date_format => string, either of the following - "YYYY-MM-DD", "DD.MM.YYYY", "DD-MM-YYYY", "MM/DD/YYYY", "DD/MM/YYYY", "MM-DD-YYYY"
     *                ]
     *
     * @return return object - User object
     */
    public function update_current_user(array $data = [])
    {
        if(!count($data)){
            throw new \Exception("You need some data to update a user.");
        }
        return $this->request->put('/api/v8/me', ['user' => $data]);
    }


    /**
     * Get new API key
     *
     * NOTE - You MUST change your key, referenced in /config/toggl.php (so that's env('TOGGL_KEY')). The old one will not work going forwards.
     *
     * @return string API Key
     */
    public function reset_api_key()
    {
        return $this->request->post('/api/v8/reset_token');
    }


    /**
     * Create new user
     *
     * @param array $data - [
     *                     fullname => string
     *                     email => string
     *                     password => string
     *                     timezone => string (i.e. UTC, etc.)
     *                     created_with => string (name of app, default is GGDX_LaravelToggl)
     *                ] ALL REQUIRED
     * @return object User Object (includes new user API key)
     */
    public function create_user(array $data = [])
    {
        if(empty($data['fullname']) || !strlen($data['fullname'])){
            throw new \Exception("fullname is required");
        }
        if(empty($data['email']) || !strlen($data['email'])){
            throw new \Exception("email is required");
        }
        if(empty($data['password']) || !strlen($data['password'])){
            throw new \Exception("password is required");
        }
        if(empty($data['timezone']) || !strlen($data['timezone'])){
            throw new \Exception("timezone is required");
        }
        if(empty($data['created_with']) || !strlen($data['created_with'])){
            $data['created_with'] = 'GGDX_LaravelToggl';
        }
        return $this->request->post('/api/v8/signups',["user" => $data]);
    }



    /***********************       7 - WORKSPACE          *************************/

    /**
     * Get Workspaces
     *
     * Returns all workspaces belonging to API key user, or if $id set - single workspace.
     *
     * @param int $id Workspace ID (optional)
     * @return Object
     */
    public function get_workspace($id = false)
    {
        return $id ? $this->request->get('/api/v8/workspaces/'.$id) : $this->request->get('/api/v8/workspaces');
    }


    /**
     * Update Workspace
     *
     * @param int $id Workspace ID
     * @param array $data [
     *                     name => string
     *                     default_currency => string (GBP, EUR, etc.)
     *                     default_hourly_rate => float
     *                     only_admins_may_create_projects => bool (whether only the admins can create projects or everybody), ADMIN OF WORKSPACE ONLY
     *                     only_admins_see_billable_rates => bool (whether only the admins can see billable rates or everybody), ADMIN OF WORKSPACE ONLY
     *                     rounding => int (0 = no rounding, -1 = round down, 1 = round up)
     *                     rounding_minutes => int (only available if $data['rounding'] != 0)
     *                ]
     * @return Object
     */
    public function update_workspace($id = false, array $data = [])
    {
        if(!$id){
            throw new \Exception('Workspace ID is required');
        }
        if(!count($data)){
            throw new \Exception("You need some data to update a workspace.");
        }
        return $this->request->put('/api/v8/workspaces/'.$id, ['workspace' => $data]);
    }


    /**
     * Get Workspaces Users
     *
     * @param int $id Workspace ID
     * @return Object
     */
    public function get_workspace_users($id = false)
    {
        if(!$id){
            throw new \Exception('Workspace ID is required');
        }
        return $this->request->get('/api/v8/workspaces/'.$id.'/users');
    }


    /**
     * Get Workspaces Clients
     *
     * @param int $id Workspace ID
     * @return Object
     */
    public function get_workspace_clients($id = false)
    {
        if(!$id){
            throw new \Exception('Workspace ID is required');
        }
        return $this->request->get('/api/v8/workspaces/'.$id.'/clients');
    }


    /**
     * Get Workspaces Projects
     *
     * @param int $id Workspace ID
     * @param bool/string $active (true, false, "both") - Filter by active, inactive or all projects
     * @param bool $actual_hours - Gets by actual completed hours per project. Default false
     * @return Object
     */
    public function get_workspace_projects($id = false, $active = true, $actual_hours = false)
    {
        if(!$id){
            throw new \Exception('Workspace ID is required');
        }
        return $this->request->get('/api/v8/workspaces/'.$id.'/projects?active='.$active.'&actual_hours='.$actual_hours);
    }


    /**
     * Get Workspaces Tasks
     *
     * @param int $id Workspace ID
     * @param bool/string $active (true, false, "both") - Filter by active, inactive or all tasks
     * @return Object
     */
    public function get_workspace_tasks($id = false, $active = true)
    {
        if(!$id){
            throw new \Exception('Workspace ID is required');
        }
        return $this->request->get('/api/v8/workspaces/'.$id.'/tasks?active='.$active);
    }


    /**
     * Get Workspaces Tags
     *
     * @param int $id Workspace ID
     * @return Object
     */
    public function get_workspace_tags($id = false)
    {
        if(!$id){
            throw new \Exception('Workspace ID is required');
        }
        return $this->request->get('/api/v8/workspaces/'.$id.'/tags');
    }





    /***********************       7a - WORKSPACE USERS         *************************/

    /**
     * Invite users to Workspace
     *
     * @param int $id Workspace ID
     * @param array $data Array of email addresses
     * @return object
     */
    public function invite_users($id = false, array $data = [])
    {
        if(!$id){
            throw new \Exception('Workspace ID is required');
        }
        if(!count($data)){
            throw new \Exception('At least one email address is needed to invite.');
        }
        return $this->request->post('/api/v8/workspaces/'.$id.'/invite',['emails' => $data]);
    }

    /**
     * Update workspace user (Only admin flag may be edited)
     *
     * @param int $id Workspace User ID
     * @param array $data - [
     *                     admin => bool - if user is admin of workspace
     *                ]
     * @return object
     */
    public function update_workspace_user($id = false, array $data = [])
    {
        if(!$id){
            throw new \Exception('Workspace User ID is required (note, NOT Workspace ID and NOT User ID)');
        }
        if(!count($data)){
            throw new \Exception("You need some data to update a workspace.");
        }
        return $this->request->put('/api/v8/workspace_users/'.$id,['workspace_user' => $data]);
    }


    /**
     * Delete workspace user
     *
     * @param int id - Workspace user ID
     * @return int - Workspace user ID
     */
    public function delete_workspace_user($id = false)
    {
        if(!$id){
            throw new \Exception('Workspace User ID is required (note, NOT Workspace ID and NOT User ID)');
        }
        return $this->request->delete('/api/v8/workspace_users/'.$id);
    }



    /***********************      8 - Dashboard         *************************/

    /**
     * Get dashboard
     *
     * @param int id - Workspace ID
     * @return object
     */
    public function get_dashboard($id = false)
    {
        if(!$id){
            throw new \Exception('Workspace ID is required');
        }
        return $this->request->get('/api/v8/dashboard/'.$id);
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
