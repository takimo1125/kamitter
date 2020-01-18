<?php
return [
  "driver" => "smtp",
  "host" => "smtp.mailgun.org",
  "port" => 587,
  "from" => array(
      "address" => "from@example.com",
      "name" => "Example"
  ),
  "username" => "postmaster@sandboxc5fae876a625413db54647b1d32422c7.mailgun.org",
  "password" => "3b57021227d9db871a5d4beb9dc3e015-5645b1f9-b23286e3",
  "sendmail" => "/usr/sbin/sendmail -bs"
];
