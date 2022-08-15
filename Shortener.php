<?php 
/**
 * @package ShortvlyURLShortener
 * @author Shortvly
 * @copyright 2022 Shortvly
 */

namespace Shortvly;

class Shortener{
	/**
	 * API Key
	 * @var null
	 */
	protected $key = NULL;
	/**
	 * API URL
	 * @var null
	 */
	protected $url = NULL;

	/**
	 * Endpoint
	 * @var null
	 */
	protected $endpoint = NULL;
	/**
	 * HTTP Data
	 * @var array
	 */
	protected $data = [];
	/**
	 * [__construct description]
	 * @author Shortvly
	 * @version 2.0
	 */
	public function __construct(string $url, string $key){
		$this->url = rtrim($url, "/");	
		$this->key = trim($key);	
	}
	/**
	 * Shorten Request
	 * @param   array $url [description]
	 * @return  [type]      [description]
	 */
	public function shorten(array $data) : object {
		$this->endpoint = 'url/add';
		$this->data = $data;
		return $this->http();		
	}
	/**
	 * URL Details & Stats
	 * @return  [type]        [description]
	 */
	public function stats(int $urlid) : object {
		
		$this->endpoint = 'url/stats'.$urlid;
		return $this->get();		
	
	}

	/*****************************************
	 * 
	 * LINKS
	 * 
	 *****************************************/
	/**
	 * List links
	 * @param $limit, $page, $order [optional]
	 * @return  [type]        [description]
	 */
	public function list_links($limit=25, $page=null, $order=null) {
		
		$this->endpoint = 'urls?'.'limit='.$limit;
		return $this->get();		
	}

	

	/*****************************************
	 * 
	 * ACCOUNT
	 * 
	 *****************************************/

	/**
	 * Get information on the account.
	 * @return  [type] [description]
	 */
	public function account() {		

		$this->endpoint = 'account';
		return $this->get();
	}

	public function updateAccount(array $data) : object {
		$this->endpoint = 'account/update';
		$this->method = 'PUT';
		$this->data = $data;	
		return $this->http();
	}

	/*****************************************
	 * 
	 * USERS (admin only)
	 * 
	 *****************************************/

	/**
	 * Create User: Admin Only
	 * @param   array  $data [description]
	 * @return  [type]       [description]
	 */
	public function createUser(array $data){
		$this->endpoint = 'user/create';
		$this->data = $data;
		return $this->http();	
	}
	/**
	 * Get User
	 * @param   int    $userid [description]
	 * @return  [type]         [description]
	 */
	public function getUser(int $userid){
		$this->endpoint = 'user/get';
		$this->data = [
				'userid' => $userid,
		];
		return $this->http();		
	}






	/**
	 * Make a request Call
	 * @return  [type]           [description]
	 */
	private function http(){
		$curl = curl_init();
	    curl_setopt($curl, CURLOPT_URL, $this->url.'/api/'.$this->endpoint);
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);  
	    curl_setopt($curl, CURLOPT_MAXREDIRS, 2);
	    curl_setopt($curl, CURLOPT_TIMEOUT, 10);
	    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
	    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $this->method ? $this->method : "POST");
	    curl_setopt($curl, CURLOPT_POSTFIELDS , json_encode($this->data));
	    curl_setopt($curl, CURLOPT_HTTPHEADER, [
	      "Authorization: Token {$this->key}",
	      "Content-Type: application/json",
	    ]);

	    $response = curl_exec($curl);
	    curl_close($curl);
	    return json_decode($response);		
	}

	private function get(){
		$curl = curl_init();
	    curl_setopt($curl, CURLOPT_URL, $this->url.'/api/'.$this->endpoint);
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);  
	    curl_setopt($curl, CURLOPT_MAXREDIRS, 2);
	    curl_setopt($curl, CURLOPT_TIMEOUT, 10);
	    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
	    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
	    curl_setopt($curl, CURLOPT_HTTPHEADER, [
	      "Authorization: Token {$this->key}",
	      "Content-Type: application/json",
	    ]);

	    $response = curl_exec($curl);
	    curl_close($curl);
	    return json_decode($response);		
	}
}
