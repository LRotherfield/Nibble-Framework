<?php

class Feeds {

  private static $feed_array = array();
  private static $tweets = array();

  public function __construct() {

  }

  public static function getFeed($url,$single = false) {
    $result = self::curlIt($url);
    foreach ($result->entry as $entry) {
      $feed = new stdClass();
      $feed->title = $entry->title;
      $feed->content = $entry->content;
      foreach($entry->link as $link)
          $feed->link[] = $link['href'];
      if(isset($entry->rights))
        $feed->rights = $entry->rights;
      if($single)
        return $feed;
      array_push(self::$feed_array, $feed);
    }
    unset($feed, $result);
    return self::$feed_array;
  }

  public function getTweets($user, $limit = 5) {
    $result = self::curlIt('http://api.twitter.com/1/statuses/user_timeline.xml?screen_name=' . $user . '&count=' . $limit);
    foreach ($result->status as $entry) {
      $tweet = new stdClass();
      $tweet->id = $entry->id;
      $tweet->link = sprintf('http://twitter.com/%s/statuses/%s', $entry->user->screen_name, $entry->id);
      $tweet->user = $entry->user->screen_name;
      $tweet->author = $entry->user->name;
      $tweet->content = $entry->text;
      $tweet->published = $entry->created_at;
      array_push(self::$tweets, $tweet);
    }
    unset($result, $tweet);
    return self::$tweets;
  }

  public function sendTweet($username, $password, $message) {
    $curl_handle = curl_init();
    curl_setopt($curl_handle, CURLOPT_URL, 'http://twitter.com/statuses/update.xml');
    curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
    curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl_handle, CURLOPT_POST, 1);
    curl_setopt($curl_handle, CURLOPT_POSTFIELDS, "status=$message");
    curl_setopt($curl_handle, CURLOPT_USERPWD, "$username:$password");
    $buffer = curl_exec($curl_handle);
    curl_close($curl_handle);
    // check for success or failure
    if (empty($buffer))
      return false;
    return true;
  }

  private static function curlIt($url){
    $feed = curl_init($url);
    curl_setopt($feed, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($feed, CURLOPT_HEADER, 0);
    $xml = curl_exec($feed);
    curl_close($feed);
    unset($feed);
    return new SimpleXMLElement($xml,LIBXML_NOCDATA);
  }

}