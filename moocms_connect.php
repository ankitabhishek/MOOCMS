
<?php
$dbc = 
mysqli_connect('localhost', 'root', '', 'moocms')
OR die(mysqli_connect_error());
mysqli_set_charset($dbc, 'UTF-8');
