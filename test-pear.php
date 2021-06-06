<?php 

set_include_path('/home2/primeess/php/');
       require_once "Mail.php";

        $from = "PrimeEssayWritings Support <support@primeessaywritings.com>";
        $to = "pmwaura1@gmail.com, chriskyeu@gmail.com, dgtarchives@gmail.com, benett1972@gmail.com";
        $subject = "Hi!";
        $body = "Hi,\n\nHow are you?";

        $host = "mail.primeessaywritings.com";
        $port = "25";
        $username = "support+primeessaywritings.com";
        $password = "admin3";

        $headers = array ('From' => $from,
          'To' => $to,
          'Subject' => $subject,
	'Content-type' => 'text/html; charset=utf-8; format=flowed');
	$auth =           array ('host' => $host,
            'port' => $port,
            'auth' => true,
            'username' => $username,
            'password' => $password);
        $smtp = Mail::factory('smtp',$auth);

        $mail = $smtp->send($to, $headers, $body);

        if (PEAR::isError($mail)) {
          $text = "<p>" . $mail->getMessage() . "</p>";
         } 

				echo "<pre>".$to .'<br />'.$text.'<br />'.@$sqloo.'<br />';	
				print_r($headers);
				echo "<br />";
				print_r($auth);
				echo "</pre>";

?>
