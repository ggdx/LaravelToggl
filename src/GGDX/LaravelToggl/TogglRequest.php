<?php namespace  GGDX\LaravelToggl;

use Exception;
use InvalidArgumentException;
use GuzzleHttp\Psr7\Request;
class TogglRequest{

    // CLient constants
    const REQ_POST = 'POST';
    const REQ_PUT = 'PUT';
    const REQ_GET = 'GET';
    const REQ_DELETE = 'DELETE';
    const REQ_CREATE = 'CREATE';

    // Endpoint starts here
    private $base_url = "https://www.toggl.com/";

    // Build errors
    private $errors = [];

    // The other bits
    private $key;


    /**
     * Constructor
     *
     *
     * @param str key   API key
     * @return
     * @throws Exception
     */

    public function __construct($key = false)
    {
        if(!$key){
            throw new Exception("You need an API key");
        }
        $this->key = $key;

    }



    /**
     * Errors
     *
     *
     * @return string|array|bool
     */
    public function errors()
    {
        return $this->errors;
    }



    /**
     * Post
     *
     *
     * @param str url Endpoint
     * @param array data
     * @return string|bool|null
     */
    public function post($url, array $data = [])
    {
        $data = $this->sanitizeBools($data);
        return $this->request(self::REQ_POST,$url, $data);
    }


    /**
     * Create
     *
     *
     * @param str url Endpoint
     * @param string data - JSON
     * @return string|bool|null
     */
    public function create($url, $data)
    {
        return $this->request(self::REQ_CREATE, $url, $data);
    }


    /**
     * Put
     *
     *
     * @param str url Endpoint
     * @param array data
     * @return string|bool|null
     */
    public function put($url, array $data = [])
    {
        $data = $this->sanitizeBools($data);
        return $this->request(self::REQ_POST,$url, $data);
    }



    /**
     * Get
     *
     *
     * @param str url Endpoint
     * @return string|bool|null
     */
    public function get($url, array $data = [])
    {
        $data = $this->sanitizeBools($data);
        return $this->request(self::REQ_GET, $url, $data);
    }




    /**
     * Delete
     *
     *
     * @param str url Endpoint
     * @param array data
     * @return string|bool|null
     */
    public function delete($url, array $data = [])
    {
        return $this->request(self::REQ_DELETE,$url, $data);
    }




    /**
     * Request
     *
     * The meat of the provider.
     *
     * @param const method
     * @param str url Endpoint
     * @param array data
     * @return string|bool|null
     */
    private function request($method, $url, $data)
    {
        //dd($data);
        // Initialize client
        $client = new \GuzzleHttp\Client([
            'base_uri' => $this->base_url,
            'headers' => [
                'Authorization' => 'Basic '.base64_encode($this->key.':')
            ]
        ]);
        try {
            switch ($method) {
                case in_array($method, ['GET','POST']):
                    if(count($data)){
                        $response = $client->request($method,$url.'?'.http_build_query($data));
                    } else {
                        $response = $client->request($method,$url);
                    }
                    break;
                case 'CREATE':
                    $response = $client->request('POST',$url, ['json' => $data]);
                    break;
                default:
                    $request = new Request($method, $url);
                    $response = $client->send($request);
                    break;
            }
        } catch (Exception $e) {
            $this->errors = [$e->getMessage()];
            $response = false;
        }

        return $response;

    }


    private function sanitizeBools(array $data = [])
    {
        foreach ($data as $key => $value) {
            if($value === true){
                $data[$key] = "true";
            } elseif ($value === false){
                $data[$key] = "false";
            }
        }
        return $data;
    }

}
