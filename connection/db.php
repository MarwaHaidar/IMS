<?php
$connection = mysqli_connect('localhost','root','','ims');
if(!$connection)
{
    echo "Not Connected on databse".mysqli_connect_error();
}
?>