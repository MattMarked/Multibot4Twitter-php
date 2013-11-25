<?php
ini_set('display_errors', 1);
require_once('TwitterAPIExchange.php');

/** Setting up the access token **/
$settings = array(
    'oauth_access_token' => "your oauth_access_token",
    'oauth_access_token_secret' => "your oauth_access_token_secret",
    'consumer_key' => "your consumer_key",
    'consumer_secret' => "your consumer_secret"
);

/** GET Request to retrieve an array full of your following**/
$url = 'https://api.twitter.com/1.1/friends/ids.json';
$requestMethod = 'GET';
$getfield = '?screen_name=YOUR_USERNAME_HERE&count=2000'; /*set your username and the number of following you want to retrieve, max is 5000*/

/** GET request **/
/** Note: set getfield BEFORE calling buildOauth(); **/

$twitter = new TwitterAPIExchange($settings);
$result = $twitter->setGetfield($getfield) 
             ->buildOauth($url, $requestMethod)
             ->performRequest();

$result = json_decode ($result); /*Decode the JSON result*/
$foll_array = (array)$result;
$conta = 1;
$cursor = -1;
$full_following = array(); /*enumerate your following and create a formatted array*/
do
  {
    foreach ($foll_array['ids'] as $key => $val) 
    {
      $full_following[$conta] = $val;
      $conta++; 
    }
    $cursor = $result->next_cursor;
} while ($cursor > 0);
echo "Number of following:" .$conta."\n"; 
$reversed = array_reverse($full_following); /*Reverse your array following to have on first position the first you have followed*/


/*Request for get your follower*/
$url = 'https://api.twitter.com/1.1/followers/ids.json';
$requestMethod = 'GET';
$getfield = '?screen_name=YOUR_USERNAME_HERE&count=2000';
$twitter = new TwitterAPIExchange($settings);
$result = $twitter->setGetfield($getfield) 
             ->buildOauth($url, $requestMethod)
             ->performRequest();

$result = json_decode ($result); 
//FULL FOLLOWERS ARRAY WITH CURSOR ( FOLLOWERS > 5000)
    $e = 1;
    $cursor = -1;
    $full_followers = array();
    $follower_array = (array)$result;
    do {
       foreach ($follower_array['ids'] as $key => $val) {

            $full_followers[$e] = $val;
            $e++; 
      }
           $cursor = $result->next_cursor;

      } while ($cursor > 0);
echo "Number of followers:" .$e."\n";

//IF I AM FOLLOWING USER AND HE IS NOT FOLLOWING ME BACK, I UNFOLLOW HIM
$index=1;
$unfollow_total=0;
foreach( $reversed as $iFollow )
{
    $isFollowing = in_array( $iFollow, $full_followers );
     
    echo $index .":"."$iFollow: ".( $isFollowing ? 'OK' : '!!!' )."\n";
    $index++;
    if( !$isFollowing )
    {
        $requestMethod = 'POST';
        $postfields = array(
        'user_id' => $iFollow
            );
        $url = 'https://api.twitter.com/1.1/friendships/destroy.json'; /*Remove that user from friends*/
        $twitter = new TwitterAPIExchange($settings);
        $twitter->buildOauth($url, $requestMethod)
                 ->setPostfields($postfields)
                 ->performRequest();
        $unfollow_total++;
        echo "just unfollowed someone"."\n";
    } if ($unfollow_total === 999) break;
}
echo "Total unfollowed on this run : ".$unfollow_total."\n";





/**foreach($result->statuses as $status)
{
    if (strpos($status->text, '@') == FALSE) 
    {
        $newtweet = $status->text;
        break; 
    }

}**/




            
