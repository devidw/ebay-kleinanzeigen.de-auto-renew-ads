<?php

/**
 * find `adId` GET-Parameter in renewal link inside email body
 *
 * @param string $body
 * @return string
 */
function get_aId(string $body) {
  $pattern = '/adId=3D([0-9a-z=]+)&/';
  $success = preg_match($pattern, $body, $adId);
  $adId = $adId[1];
  $adId = str_replace('=', '', $adId);
  return ($success) ? $adId : false;
}


/**
 * find `uuid` GET-Parameter in renewal link inside email body
 *
 * @param string $body
 * @return string
 */
function get_uuid(string $body) {
  $pattern = '/uuid=3D([0-9a-z-]+)&/';
  $success = preg_match($pattern, $body, $uuid);
  $uuid = $uuid[1];
  return ($success) ? $uuid : false;
}
