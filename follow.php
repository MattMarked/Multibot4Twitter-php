<?php
ini_set('display_errors', 1);
require_once('TwitterAPIExchange.php');

/** Set access token **/
$settings = array(
    'oauth_access_token' => "your oauth_access_token",
    'oauth_access_token_secret' => "your oauth_access_token_secret",
    'consumer_key' => "your consumer_key",
    'consumer_secret' => "your consumer_secret"
);

/** search request, https://dev.twitter.com/docs/api/1.1/ **/
$url = 'https://api.twitter.com/1.1/search/tweets.json';
$requestMethod = 'GET';
$getfield = '?q=#YOUR_HASHTAG&result_type=recent&count=10'; /*search for some hashtag*/

/** get request**/
/** Note: set getfield BEFORE calling buildOauth(); **/

$twitter = new TwitterAPIExchange($settings);
$result = $twitter->setGetfield($getfield) 
             ->buildOauth($url, $requestMethod)
             ->performRequest();

$result = json_decode ($result); /*Decode the result*/
var_dump ($result->statuses[0]->text); /*Debug string, erase it if you don't want to see it*/

/**New request for follow the first 10 user retrieved with the hashtag search**/
foreach($result->statuses as $status)
{
    $url = 'https://api.twitter.com/1.1/friendships/create.json';
    $requestMethod = 'POST';
    $postfields = array(
        'user_id' => $status->user->id
    );
    $twitter = new TwitterAPIExchange($settings);
    $twitter->buildOauth($url, $requestMethod)
                 ->setPostfields($postfields)
                 ->performRequest();
    echo '.';
    
}


            
