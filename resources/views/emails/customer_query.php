<?php
$client_name=Request::get('name');
$client_mail=Request::get('email');
$client_msg=Request::get('msg');


echo "Nombre : ".$client_name;
echo "<br>";
echo "Email : ".$client_mail;
echo "<br>";
echo "Mensaje : ".$client_msg;


?>