<?php

/**
 * Postmark Delivery Class
 *    
 * @author Gregory Kornblum
 */
class Postmark
{
	/**
	 * 200 = sucess
	 * 401 = missing or incorrect key
	 * 422 = malformed request (i.e. JSON was not in the correct format)
	 * 500 = Postmark server error
	 */ 
	public $status_code;
	
	/**
	 * Postmark::send()
	 * 
	 * Posts an email message to the Postmark.com API 
	 * 
	 * @param string $to
	 * @param string $from
	 * @param string $subject
	 * @param string $html_body
	 * @param string $plain_body
	 * @return int HTTP status code
	 */
	public function send($to,$from,$subject,$html_body,$plain_body)
	{
		$post_url = 'http://api.postmarkapp.com/email';


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
						'X-Postmark-Server-Token: ed742D75-5a45-49b6-a0a1-5b9ec3dc9e5d' //replace this key with the one provided to you
					);
						
		$handle_id = @curl_init();
					
		@curl_setopt($handle_id, CURLOPT_URL, $post_url);
		@curl_setopt($handle_id, CURLOPT_RETURNTRANSFER, true);
		@curl_setopt($handle_id, CURLOPT_POST, true);
		@curl_setopt($handle_id, CURLOPT_POSTFIELDS, $post_fields);
		@curl_setopt($handle_id, CURLOPT_HTTPHEADER, $headers);
			
		@curl_exec($handle_id);
		
		$this->status_code = @curl_getinfo($handle_id, CURLINFO_HTTP_CODE);
		
		@curl_close($handle_id);
		
		return $this->status_code;
	}
	
	
}