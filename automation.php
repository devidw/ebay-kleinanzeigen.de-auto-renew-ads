<?php
require_once(__DIR__.'/parser.php');

$json = file_get_contents(__DIR__.'/config.json');
$config = json_decode($json);

// create save directory if it doesn't exist already
if (!is_dir($config->saveDir)) {
  mkdir($config->saveDir);
}

// make sure imap extension is available as it's needed
if (!extension_loaded('imap')) {
  throw new \Exception('php imap extension is needed');
}

$connection = imap_open(
  $config->imap->server,
  $config->imap->username,
  $config->imap->password
);

// list all mailboxes
// $mailboxes = imap_list($connection, $config->imap->server, '*');
// var_dump($mailboxes);

$mailbox = imap_check($connection);

if ($mailbox === false || $mailbox->Nmsgs === 0) {
  imap_close($connection);
  throw new Exception('imap check failed or mailbox is empty');
}

$overviews = imap_fetch_overview($connection, "1:{$mailbox->Nmsgs}");
// print_r($overviews);

foreach ($overviews as $overview) {
  $subject = imap_utf8($overview->subject);
  $msgno = $overview->msgno;

  if (str_contains($subject, 'Deine Anzeige läuft in einer Woche aus')) {
    $body = imap_fetchbody($connection, $msgno, '1');
    $body = preg_replace('/\r|\n/', '', $body); // remove line breaks
    // $body = quoted_printable_decode($body); // https://stackoverflow.com/a/4016098/13765033
    // echo $body;
    $save_path = $config->saveDir.'/email_'.time().'.txt';
    file_put_contents($save_path, $body);

    $adId = get_adId($body);
    $uuid = get_uuid($body);
    // print_r($uuid);
    // print_r($adId);

    if ($adId && $uuid) {
      $request = "https://www.ebay-kleinanzeigen.de/m-anzeige-verlaengern-mail.html?adId=$adId&uuid=$uuid";
      // echo $request;
      $response = file_get_contents($request);
      // echo $response;
      $save_path = $config->saveDir.'/response_'.time().'.html';
      file_put_contents($save_path, $response);

      echo <<<HTML
      <embed src="$save_path#site-content" style="width:500px; height: 300px; border: 0.1rem solid red;">
      HTML;

      // move email into trash
      // imap_mail_move($connection, $msgno, 'INBOX.Trash');
      imap_delete($connection, $msgno);
    }
  }
}

imap_close($connection, CL_EXPUNGE);
