<?php

/**
 * find `adId` GET-Parameter in renewal link inside email body
 *
 * @param string $body
 *
 * @return string|bool
 */
<<<<<<< HEAD
 function get_adId(string $body): string|bool {
=======
function get_adId(string $body) {
>>>>>>> fe5d83945a5f77e951c101cb5768217646e8ac43
  $pattern = '/adId=3D([0-9a-z=]+)&/';
  $success = preg_match($pattern, $body, $adId);
  return ($success) ? str_replace('=', '', $adId[1]) : false;
}


/**
 * find `uuid` GET-Parameter in renewal link inside email body
 *
 * @param string $body
 *
 * @return string|bool
 */
function get_uuid(string $body): string|bool {
  $pattern = '/uuid=3D([0-9a-z-=]+)&/';
  $success = preg_match($pattern, $body, $uuid);
  return ($success) ? str_replace('=', '', $uuid[1]) : false;
}
