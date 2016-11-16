<?php namespace GGDX\LaravelToggl\Requests;

use GGDX\LaravelToggl\TogglRequest;

class Users implements TogglRequestInterface{

    /**
     * User ID
     *
     * NOTE: You cannot update and save this property.
     *
     * @var int
     */
    public $id;

    /**
     * API Key
     *
     * @var string
     */
    public $api_token;

    /**
     * Default workspace
     *
     * @var int
     */
    public $default_wid;

    /**
     * Email
     *
     * @var string
     */
    public $email;

    /**
     * Time of day format
     *
     * ACCEPTABLE VALUES
     * ---
     * H:mm" for 24-hour format
     * "h:mm A" for 12-hour format (AM/PM)
     *
     * @var string
     */
    public $timeofday_format;

    /**
     * Date format
     *
     * "YYYY-MM-DD", "DD.MM.YYYY", "DD-MM-YYYY", "MM/DD/YYYY", "DD/MM/YYYY", "MM-DD-YYYY"
     *
     * @var string
     */
    public $date_format;

    /**
     * Whether start and stop time are saved on time entry
     *
     * @var bool
     */
    public $store_start_and_stop_time = true;

    /**
     * Beginning of week
     * 0 - 6 (0 = Sunday)
     *
     * @var int (max 6, min 0)
     */
    public $beginning_of_week = 1;

    /**
     * Langauge
     *
     * @var string
     */
    public $language;

    /**
     * Profile image URL
     *
     * @var string
     */
    public $image_url;


    /**
     * Should a pie-chart be visible on sidebar?
     *
     * @var bool
     */
    public $sidebar_piechart = false;


    /**
     * User timezone
     *
     * Example: "Europe/London"
     *
     * @var string
     */
    public $timezone;





    public function __construct($wid = false)
    {
        $this->set_workspace_id($wid);
    }


    /**
     * Get user (current)
     *
     * @param bool $full_details - return full details (projects, etc.).
     * @return Object
     */
    public function get($full_details = false)
    {
        $url_vars = '';

        if($full_details){
            $url_vars = '?with_related_data=true';
        }

        $request =  new TogglRequest(config('toggl.api_key'));

        return $request->get('/api/v8/me'.$url_vars);
    }


    /**
     * Create user
     *
     *
     * @return Object
     */
    public function create()
    {
        $request =  new TogglRequest(config('toggl.api_key'));

        $data = $this->prepare_data();

        return $request->post('/api/v8/signups', ['user' => $data]);
    }

    /**
     * Update user
     *
     *
     * @return Object
     */
    public function update()
    {
        $request =  new TogglRequest(config('toggl.api_key'));

        $data = $this->prepare_data();

        return $request->put('/api/v8/me', ['user' => $data]);
    }

    /**
     * Reset API key
     *
     * NOTE: Your must update config('toggl.api_key').
     *
     * @return Object
     */
    public function reset_api_key()
    {
        $request =  new TogglRequest(config('toggl.api_key'));

        return $request->post('/api/v8/reset_token');
    }





    /*
    * Getters & Setters
    */
    public function get_workspace_id()
    {
        return $this->default_wid;
    }

    public function set_workspace_id($default_wid)
    {
        $this->default_wid = !$default_wid ? config('toggl.default_workspace') : $default_wid;

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

    public function get_api_token()
    {
        return $this->api_token;
    }

    public function set_api_token($api_token)
    {
        $this->api_token = $api_token;

        return $this;
    }

    public function get_email()
    {
        return $this->email;
    }

    public function set_email($email)
    {
        $this->email = $email;

        return $this;
    }

    public function get_timeofday_format()
    {
        return $this->timeofday_format;
    }

    public function set_timeofday_format($timeofday_format)
    {
        $this->timeofday_format = $timeofday_format;

        return $this;
    }

    public function get_date_format()
    {
        return $this->date_format;
    }

    public function set_date_format($date_format)
    {
        $this->date_format = $date_format;

        return $this;
    }

    public function get_store_start_and_stop_time()
    {
        return $this->store_start_and_stop_time;
    }

    public function set_store_start_and_stop_time($store_start_and_stop_time = true)
    {
        $this->store_start_and_stop_time = $store_start_and_stop_time === true ? true : false;

        return $this;
    }

    public function get_langauge()
    {
        return $this->langauge;
    }

    public function set_langauge($langauge)
    {
        $this->langauge = $langauge;

        return $this;
    }

    public function get_image_url()
    {
        return $this->image_url;
    }

    public function set_image_url($image_url)
    {
        $this->image_url = $image_url;

        return $this;
    }

    public function get_sidebar_piechart()
    {
        return $this->sidebar_piechart;
    }

    public function set_sidebar_piechart($sidebar_piechart = false)
    {
        $this->sidebar_piechart = !$sidebar_piechart ? false : true;

        return $this;
    }

    public function get_timezone()
    {
        return $this->timezone;
    }

    public function set_timezone($timezone)
    {
        $this->timezone = $timezone;

        return $this;
    }



    // For some retarded reason that I will never understand, converting some objects to array results in * being placed inside array keys.

    private function prepare_data()
    {
        return json_decode(json_encode($this),true);
    }
}
