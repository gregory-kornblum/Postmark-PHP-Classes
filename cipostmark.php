<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Postmark Delivery Controller
 * 
 * @author Gregory Kornblum
 */
class Cipostmark extends Controller
{
	/**
	 * 200 = sucess
	 * 401 = missing or incorrect key
	 * 422 = malformed request (i.e. JSON was not in the correct format)
	 * 500 = Postmark server error
	 */ 
	public $status_code;
	
	/**
	 * Postmark::__construct()
	 * 
	 * Load's the CURL library by Alex Polsky
	 * http://alexpolski.com/2008/04/20/curl-library-11-for-codeigniter/
	 * 
	 * @return
	 */
	public function __construct()
	{
        parent::__construct();
        
        $this->load->library('curl');
	}


	/**
	 * Postmark::send()
	 * 
	 * Posting all mail fields to this method while also
	 * posting a secret in order to prevent it being used 
	 * as an open relay.
	 * 
	 * @return
	 */
	public function send()
	{
		$to = $this->input->post('to');		
		$from = $this->input->post('from');
		$subject = $this->input->post('subject');
		$html_body = $this->input->post('html_body');
		$plain_body = $this->input->post('plain_body');
		$secret = $this->input->post('secret'); //PREVENT OPEN RELAYING
		
		$url = $this->config->item('postmark_api');
		
		if($secret == $this->config->item('postmark_secret'))
		{
			$post_fields = 
			'{
				From:     "'.$from.'",
				To:       "'.$to.'",
				Subject:  "'.$subject.'",
				HtmlBody: "'.$html_body.'",
				TextBody: "'.$plain_body.'"
			}';
			
			$headers = array
						(
							'Accept: application/json',
							'Content-Type: application/json',
							'X-Postmark-Server-Token: ' . $this->config->item('postmark_key')
						);
			
			$this->curl->http_post($url, $post_fields, $headers);
			
			$this->status_code = $this->curl->get_http_code();
			
			$this->curl->close();
		}
		else
		{
			show_404();
		}
	}
}
