<?php
defined('_JEXEC') or die("No cargó el ost_api");
error_reporting(E_ALL);
        ini_set('display_errors', '1');
class ost_api{
  
  function config(){
    $config = array(
        'url'=>'http://127.0.0.1/ticket.tuagencia24.com/upload/api/tickets.json',  // URL to site.tld/api/tickets.json //Antes era -> http://your.domain.tld/api/tickets.json
        'key'=>'E9059472F7AC91544A453862B5D10C7B'  // API Key goes here //PUTyourAPIkeyHERE
    );

    if($config['url'] === 'http://your.domain.tld/api/tickets.json') {
      echo "<p style=\"color:red;\"><b>Error: No URL</b><br>You have not configured this script with your URL!</p>";
      echo "Please edit this file ".__FILE__." and add your URL at line 18.</p>";
      die();  
    }   

    if(IsNullOrEmptyString($config['key']) || ($config['key'] === 'PUTyourAPIkeyHERE'))  {
      echo "<p style=\"color:red;\"><b>Error: No API Key</b><br>You have not configured this script with an API Key!</p>";
      echo "<p>Please log into osticket as an admin and navigate to: Admin panel -> Manage -> Api Keys then add a new API Key.<br>";
      echo "Once you have your key edit this file ".__FILE__." and add the key at line 19.</p>";
      die();
    }
    return $config;
  }

  function data($name,$email,$phone,$itinerary){
    $data = array(
    'name'      =>      $name,  // from name aka User/Client Name
    'email'     =>      $email,  // from email aka User/Client Email
    'phone'     =>      $phone,  // phone number aka User/Client Phone Number
    'subject'   =>      'Administracion',  // test subject, aka Issue Summary
    'message'   =>      $itinerary,  // test ticket body, aka Issue Details.
    'ip'        =>      $_SERVER['REMOTE_ADDR'], // Should be IP address of the machine thats trying to open the ticket.
    'topicId'   =>      '19' // the help Topic that you want to use for the ticket 
    );
    return $data;
  }
}

  function curl_post($config,$array){
    function_exists('curl_version') or die('CURL support required');
    function_exists('json_encode') or die('JSON support required');

    #set timeout
    set_time_limit(30);

    #curl post
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $config['url']);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($array));
    curl_setopt($ch, CURLOPT_USERAGENT, 'osTicket API Client v1.8');
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array( 'Expect:', 'X-API-Key: '.$config['key']));
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, FALSE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    $result=curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($code != 201)
        die('Unable to create ticket: '.$result);

    $ticket_id = (int) $result;
  }


  function IsNullOrEmptyString($question){
    return (!isset($question) || trim($question)==='');
  }

# Add in attachments here if necessary
# Note: there is something with this wrong with the file attachment here it does not work.
// $data['attachments'][] =
// array('file.txt' =>
//         'data:text/plain;base64;'
//             .base64_encode(file_get_contents('/file.txt')));  // replace ./file.txt with /path/to/your/test/filename.txt
?>