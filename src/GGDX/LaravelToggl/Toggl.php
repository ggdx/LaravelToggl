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



    /***********************        COMMENTS          *************************/
    // Gets a Comment
    public function getComment($id = false)
    {
        if(!$id){
            return false;
        }
        return $this->request->get('v2.2/Comments/'.$id);
    }

}
