<?php
// Use this script at own risk. www.releasepirate.com cant be held responsible for the use of this code.  This script is AS IS

// Contact subject
$subject ="$subject";
// Details
$message="$detail";

// Mail of sender
$mail_from="$customer_mail";
// From
$header="from: $name <$mail_from>";

// Enter your email address
$to ='tracker@torrentstorage.com';

$send_contact=mail($to,$subject,$message,$header);

// Check, if message sent to your email
// display message "We've recived your information"
if($send_contact){
echo "We've recieved your contact information";
}
else {
echo "Something went wrong";
}
?>