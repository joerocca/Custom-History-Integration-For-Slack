<?php


# Values from slack post
$command = $_POST['command'];
$text = $_POST['text'];
$token = $_POST['token'];
$username = $_POST['user_name'];
$channel = $_POST['channel_name'];
$responseUrl = $_POST['response_url'];

# Check the token and make sure the request is from your team 
if($token != 'EA5hVwmBbcFsBk4nmeQH4K2K'){ #replace this with team token
  $msg = "The token for the slash command doesn't match. Check your script.";
  die($msg);
  echo $msg;
}


$user_agent = "HistoryTodayForSlack/1.0 (https://github.com/joerocca; jprocca813@gmail.com)";


#Get correct API link based on command
if ($text === "") 
{
	$url_to_check = "http://history.muffinlabs.com/date";
}
else
{
	$url_to_check = "http://history.muffinlabs.com/date/".$text."";
}

// Set up cURL 
$ch = curl_init($url_to_check);

# Set up options for cURL 
# We want to get the value back from our query 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

# Send in our user agent string 
curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);

# Make the call and get the response 
$ch_response = curl_exec($ch);

# Close the connection 
curl_close($ch);

# Decode the JSON array 
$response_array = json_decode($ch_response,true);


# Build Response 

if($ch_response === FALSE)
{
  $reply = "API could not be reached.";
}
else
{

    $randomFactInt = rand(0, count($response_array["data"]["Events"]));

    if ($text === "") 
    {


       $reply = "*History Fact for Today* \nIn ".$response_array["data"]["Events"][$randomFactInt]["year"].", ".$response_array["data"]["Events"][$randomFactInt]["text"]."";

       $ch = curl_init($responseUrl);

          $data = "payload=" . json_encode(array(

              "response_type" => "in_channel",
              "text"   =>  $reply
              
            
          ));

          curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
          curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
          curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
          $result = curl_exec($ch);
          curl_close($ch);
    }
    else
    {
       $reply = "*History Fact for ".$text."* \nIn ".$response_array["data"]["Events"][$randomFactInt]["year"].", ".$response_array["data"]["Events"][$randomFactInt]["text"]."";


          $ch = curl_init($responseUrl);

          $data = "payload=" . json_encode(array(

              "response_type" => "in_channel",
              "text"   =>  $reply

            
          ));

          curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
          curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
          curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
          $result = curl_exec($ch);
          curl_close($ch);
    }

    
}

?>
