<?php
namespace App\Classes\Http;
class Http
{
    public $client;
    public $urls;
    public $code;

    public function __construct(\GuzzleHttp\Client $client)
    {
        $this->client = $client;
        $this->urls = config('monitor.http.urls');
        $this->code = config('monitor.http.code');
    }

    /**
     * 请求url
     * @return array|int
     */
    public function ping()
    {
        $failed = [];
        foreach ($this->urls as $url) {
            try {
                $res = $this->client->request('GET', $url);
                $currentCode = $res->getStatusCode();
                if ($currentCode >= $this->code) {
                    array_push($failed, ['url' => $url, 'desc' => $currentCode]);
                }
            } catch (\Exception $ex) {
                array_push($failed, ['url' => $url, 'desc' => $ex->getMessage()]);
            }
        }
        return $failed;
    }
}