<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once('twitter/twitter.class.php');

class Kwetter extends CI_Controller {

	function __construct()
	{
		parent::__construct();
	}

	function index()
	{
		$this->load->view('welcome_message');
	}
	
	function publish()
	{
	
		$tweet = $this->input->post("qtweet");
	
		//$tweet = "Qtag feliciteert Cristian Vorstius kruijff van QTAG AMAZING MEDIA met een persoonlijke QRcode + mobiele contactpagina! #fenexpo #qrcode";
		
		//$tweet = "Qtag feliciteert Cristian Vorstius Kruijff ";
		$tweet = $tweet . " http://bit.ly/gNVs75";
		
		$consumerKey = "KqxORGrW8Qom0BDa37sew";
		$consumerSecret = "w6rbLPUsFZskxBYQKMoQg9CdxBk0tuP8BiZ5l1jgWc";
		$accessToken = "82625591-2CU7xAI3RCCZ62p7BRoTr5NZ8f2R4osyVGTChVbWk";
		$accessTokenSecret = "YBHg125wTl3rM6ZV8yn7ktimLkiH117SCuW68mU1Y";

/*		
		Consumer key
Tt7h55y6opcAETkCvdwjsQ
Consumer secret

K4Q4oP8XA5XpQajk1EXJLnbJg2VUZAPDFEZRBjY

Access Token (oauth_token)

90830420-K7pOCXVoDT7WC0Ql6GfztY6XwcN3J3LXQDdvdngfg

Access Token Secret (oauth_token_secret)

7AGUN0sXq599Efbwm7qdhwS3agUhia3eGN6V2DxMzc4
*/
 
 	
		$consumerKey_qtag = "Tt7h55y6opcAETkCvdwjsQ";
		$consumerSecret_qtag = "K4Q4oP8XA5XpQajk1EXJLnbJg2VUZAPDFEZRBjY";
		$accessToken_qtag = "90830420-K7pOCXVoDT7WC0Ql6GfztY6XwcN3J3LXQDdvdngfg";
		$accessTokenSecret_qtag = "7AGUN0sXq599Efbwm7qdhwS3agUhia3eGN6V2DxMzc4";
				
	//fonstwitter	$twitter = new Twitter($consumerKey, $consumerSecret, $accessToken, $accessTokenSecret);   //fonstwitter
		$twitter = new Twitter($consumerKey_qtag, $consumerSecret_qtag, $accessToken_qtag, $accessTokenSecret_qtag);  //qtag twitter
		$twitter->send($tweet);
		
		$this->load->view('twitter_ok');
	}
}