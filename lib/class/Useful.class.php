<?php

class Useful {

  public static function stripper($val) {
    foreach (array(' ', '&nbsp;', '\n', '\t', '\r') as $strip)
      $val = str_replace($strip, '', (string) $val);
    return $val === '' ? false : $val;
  }

  public static function slugify($text) {
    return strtolower(trim(preg_replace('/\W+/', '-', $text), '-'));
  }

  public static function randomString($length = 10, $return = '') {
    $string = 'qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM1234567890';
    while ($length-- > 0)
      $return .= $string[mt_rand(0, strlen($string) - 1)];
    return $return;
  }

  public static function formatDateTime($date, $format='d/m/Y') {
    $date = explode(' ', $date);
    $t = explode(':', $date[1]);
    $d = explode('-', $date[0]);
    $timestamp = mktime($t[0], $t[1], $t[2], $d[1], $d[2], $d[0]);
    return date($format, $timestamp);
  }

  public static function copyFile($file1, $file2) {
    if (file_exists($file2))
      return false;
    $content = @file_get_contents($file1);
    $openedfile = fopen($file2, "w");
    fwrite($openedfile, $content);
    fclose($openedfile);
    return $content === false ? false : true;
  }

  public static function checkFilesInDir($dir, &$files = array()) {
    $files = (array)$files;
    if (!file_exists($dir))
      return;
    if ($handle = opendir($dir)) {
      while (false !== ($file = readdir($handle)))
        if ($file != "." && $file != "..")
          $files[] = $file;
      closedir($handle);
    }
  }

  public static function setReporting($bool = false) {
    if (DEV_ENV || $bool) {
      error_reporting(E_ALL | E_STRICT);
      ini_set('display_errors', 1);
    } else {
      error_reporting(E_ALL);
      ini_set('display_errors', 'Off');
      ini_set('log_errors', 1);
    }
  }

  public static function array_search_recursive($needle, $haystack, $strict=false, $path=array()) {
    if (!is_array($haystack))
      return false;

    foreach ($haystack as $key => $val) {
      if (is_array($val) && $subPath = self::array_search_recursive($needle, $val, $strict, $path)) {
        $path = array_merge($path, array($key), $subPath);
        return $path;
      } elseif ((!$strict && $val == $needle) || ($strict && $val === $needle)) {
        $path[] = $key;
        return $path;
      }
    }
    return false;
  }

  public static function stickyRadio($field, $val) {
    return isset($_SESSION['sticky'], $_SESSION['sticky'][$field]) && $_SESSION['sticky'][$field] == $val ? 'checked="checked"' : '';
  }

  public static function stickyCheckbox($field, $val) {
    return isset($_SESSION['sticky'], $_SESSION['sticky'][$field]) && in_array($val, $_SESSION['sticky'][$field]) ? 'checked="checked"' : '';
  }

  public static function stickyText($field) {
    return isset($_SESSION['sticky'], $_SESSION['sticky'][$field]) ? htmlentities($_SESSION['sticky'][$field], ENT_QUOTES, 'utf-8') : '';
  }

  public static function stickySelect($field, $val) {
    return isset($_SESSION['sticky'], $_SESSION['sticky'][$field]) && $_SESSION['sticky'][$field] == $val ? 'selected="selected"' : '';
  }

  public static function sendMail($to, $from, $subject, $message) {
    $headers = 'From: ' . $from . "\n";
    $headers .= 'MIME-Version: 1.0' . "\n";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\n";
    $message = preg_replace('/{URL}/', URL, $message);
    $message = preg_replace('/{SITE_NAME}/', SITE_NAME, $message);
    mail($to, $subject, $message, $headers);
  }

  public static function writeFile($name,$content){
    $fh = fopen($name, 'w') or die("can't open file");
    fwrite($fh, $content);
    fclose($fh);
  }

}