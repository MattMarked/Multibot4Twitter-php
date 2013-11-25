<?php
ini_set('display_errors', 1);
require_once('TwitterAPIExchange.php');

/** Set token access**/
$settings = array(
    'oauth_access_token' => "your oauth_access_token",
    'oauth_access_token_secret' => "your oauth_access_token_secret",
    'consumer_key' => "your consumer_key",
    'consumer_secret' => "your consumer_secret"
);

/** request search, https://dev.twitter.com/docs/api/1.1/ **/
$url = 'https://api.twitter.com/1.1/search/tweets.json';
$requestMethod = 'GET';
$getfield = '?q=#YOUR_HASHTAG&result_type=recent'; /*search some hashtag*/


$twitter = new TwitterAPIExchange($settings);
$result = $twitter->setGetfield($getfield) 
             ->buildOauth($url, $requestMethod)
             ->performRequest();

$result = json_decode ($result);
var_dump ($result->statuses[0]->text);

/** New request for rewtweetting the last post retrieved with previously search**/
$requestMethod = 'POST';
$id = $result->statuses[0]->id_str;
$postfields = array(
    'id' => $id
);
$url = 'https://api.twitter.com/1.1/statuses/retweet/'.$id.'.json';
$twitter = new TwitterAPIExchange($settings);
$twitter->buildOauth($url, $requestMethod)
             ->setPostfields($postfields)
             ->performRequest();




            
