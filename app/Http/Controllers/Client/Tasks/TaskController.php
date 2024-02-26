<?php
namespace App\Http\Controllers\Client\Tasks;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;


class TaskController extends Controller
{
    public function taskList(Request $request)
    {
         //Create Client object to deal with
         $client = new Client();

         // Define the request parameters
         $url = $request->api_url;
 
         $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer '.$request->api_token,
            'Accept' => 'application/json',
         ];
         $perPage = !empty($request->per_page)?$request->per_page:10;
         $search = !empty($request->search)?$request->search:'';
         $status = !empty($request->status)?$request->status:'';
         // POST Data
         $data = [
             'per_page' => $perPage,
             'search' => $search,
             'status' => $status,
         ];
 
  
         // POST request using the created object
         $postResponse = $client->post($url, [
             'headers' => $headers,
             'json' => $data,
         ]);
 
         // Get the response code
         $responseCode = $postResponse->getStatusCode();
         dd($postResponse);
         return response()->json(['response_code' => $responseCode]);

        //  // URL
        // $apiURL = $request->api_url;
        // $perPage = !empty($request->per_page)?$request->per_page:10;
        // $search = !empty($request->search)?$request->search:'';
        // $status = !empty($request->status)?$request->status:'';
        // // POST Data
        // $postInput = [
        //     'per_page' => $perPage,
        //     'search' => $search,
        //     'status' => $status,
        // ];
  
        // // Headers
        // $headers = [
        //     'Authorization' => 'Bearer '.$request->api_token,
        //     'Accept' => 'application/json',
        //     'Content-Type' => 'application/json',
        // ];
  
        // $response = Http::withHeaders($headers)->post($apiURL, $postInput);
  
        // $statusCode = $response->status();
        // $responseBody = json_decode($response->getBody(), true);
      
        // echo $statusCode;  // status code

        // dd($responseBody); // body response
    }
}
