<?php

namespace App\Http\Drivers;

use App\FreelancerAccount;
use Illuminate\Support\Facades\Auth;
use Ixudra\Curl\Facades\Curl;

class Freelancer
{
    private $curl, $account;

    public function __construct($account = NULL)
    {
        $this->curl = new Curl();
        $this->account = $account;
    }

    public function init()
    {
        $this->account = FreelancerAccount::where('id', $this->account ? $this->account->id : Auth::user()->freelancer_account_id)->first();
    }

    public function get($url, $data, $headers = [])
    {
        $this->init();
        array_push($headers, 'freelancer-oauth-v1: ' . $this->account->access_token);
        // dd(env('FREELANCER_API_URL').$url);
        $response = Curl::to(env('FREELANCER_API_URL') . $url)
            ->withHeaders($headers)
            ->withData($data)
            ->asJsonResponse()
            ->get();
        return $response;
    }

    public function post($url, $data, $headers = [], $files = [])
    {
        $this->init();
        array_push($headers, 'freelancer-oauth-v1: ' . $this->account->access_token);
        $response = Curl::to(env('FREELANCER_API_URL') . $url)
            ->withHeaders($headers)
            ->withData($data);
        if (!empty($files)) {
            foreach ($files as $key => $file) {
                $response->withFile('files[]', $file['path'], $file['mimeType'], $file['name']);
            }
        }
        $response = $response->asJsonResponse()
            ->post();
        return $response;
    }

    public function put($url, $data, $headers = [])
    {
        $this->init();
        array_push($headers, 'freelancer-oauth-v1: ' . $this->account->access_token);
        $response = Curl::to(env('FREELANCER_API_URL') . $url)
            ->withHeaders($headers)
            ->withData($data)
            ->asJsonResponse()
            ->put();
        return $response;
    }


    public function downloadAttachment($url, $name, $headers = [])
    {
        $this->init();
        array_push($headers, 'freelancer-oauth-v1: ' . $this->account->access_token);
        $response = Curl::to(env('FREELANCER_API_URL') . $url)
            ->withHeaders($headers)
            ->get();
        $fileUrl = htmlspecialchars_decode(explode('"', explode('href', $response)[1])[1]);
        $file = Curl::to($fileUrl)
            ->download(public_path('attachments/messages/' . $name));
        return ($file ? true : false);
    }
}
