<!doctype html>
<html class="no-js" lang="en">
<head>
	<meta charset="utf-8" />
    <meta name="format-detection" content="telephone=yes" />

    <title>Contact</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">
    
<!-- CSS -->
	<link rel="stylesheet" href="css/style.min.css" />


<!-- Google Fonts -->
    <link href='https://fonts.googleapis.com/css?family=Lato:400,900%7cCourgette' rel='stylesheet' type='text/css'>

<!-- Javascript -->  
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script type="text/javascript" src="js/jquery.slicknav.min.js"></script>
      
</head>
  
<body> 
    
<!-- start my code -->
    
<!-- Borders -->
    
    <div id="left"></div>
    <div id="right"></div>
    <div id="top"></div>
    <div id="bottom"></div>  
<!-- End Borders -->
    
<!-- Container -->  
    <div id="container">
        
<!-- Header --> 
        <div id="header_offset">
            <div id="header_menu"> 
                <h2><a class="no_highlight" href="index.html">BirthCompanion.net</a></h2>         

<!-- Menu -->    
                <ul class="menu">
                    <li><a href="index.html">Welcome</a></li>
                    <li><a href="offerings.html">What I Offer</a></li>
                    <li><a href="about.html">About Me</a></li>
                    <li><a href="resources.html">Resources</a></li>
                    <li><a href="#">Contact</a></li>
                </ul>        
<!-- End Menu -->
             </div>  
        </div>          
<!-- End Header -->    

<!-- Main -->  
        <div id="main">
            <h1>Contact</h1>
            <p>For questions or to check my availability, please use the form below or email me at <a class="more underline" href="mailto:paige@birthcompanion.net">paige@birthcompanion.net</a>.</p><br>
        </div>
<!-- End Main -->   
        
<!-- Top Section --> 
        
<!-- PHP Form Code -->
        <?php
// OPTIONS - PLEASE CONFIGURE THESE BEFORE USE!

$yourEmail = "paige@birthcompanion.net"; // the email address you wish to receive these mails through
$yourWebsite = "BirthCompanion.net"; // the name of your website
$thanksPage = ''; // URL to 'thanks for sending mail' page; leave empty to keep message on the same page 
$maxPoints = 4; // max points a person can hit before it refuses to submit - recommend 4
$requiredFields = "name,email,comments"; // names of the fields you'd like to be required as a minimum, separate each field with a comma


// DO NOT EDIT BELOW HERE
$error_msg = array();
$result = null;

$requiredFields = explode(",", $requiredFields);

function clean($data) {
	$data = trim(stripslashes(strip_tags($data)));
	return $data;
}
function isBot() {
	$bots = array("Indy", "Blaiz", "Java", "libwww-perl", "Python", "OutfoxBot", "User-Agent", "PycURL", "AlphaServer", "T8Abot", "Syntryx", "WinHttp", "WebBandit", "nicebot", "Teoma", "alexa", "froogle", "inktomi", "looksmart", "URL_Spider_SQL", "Firefly", "NationalDirectory", "Ask Jeeves", "TECNOSEEK", "InfoSeek", "WebFindBot", "girafabot", "crawler", "www.galaxy.com", "Googlebot", "Scooter", "Slurp", "appie", "FAST", "WebBug", "Spade", "ZyBorg", "rabaz");

	foreach ($bots as $bot)
		if (stripos($_SERVER['HTTP_USER_AGENT'], $bot) !== false)
			return true;

	if (empty($_SERVER['HTTP_USER_AGENT']) || $_SERVER['HTTP_USER_AGENT'] == " ")
		return true;
	
	return false;
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
	if (isBot() !== false)
		$error_msg[] = "No bots please! UA reported as: ".$_SERVER['HTTP_USER_AGENT'];
		
	// lets check a few things - not enough to trigger an error on their own, but worth assigning a spam score.. 
	// score quickly adds up therefore allowing genuine users with 'accidental' score through but cutting out real spam :)
	$points = (int)0;
	
	$badwords = array("adult", "beastial", "bestial", "blowjob", "clit", "cum", "cunilingus", "cunillingus", "cunnilingus", "cunt", "ejaculate", "fag", "felatio", "fellatio", "fuck", "fuk", "fuks", "gangbang", "gangbanged", "gangbangs", "hotsex", "hardcode", "jism", "jiz", "orgasim", "orgasims", "orgasm", "orgasms", "phonesex", "phuk", "phuq", "pussies", "pussy", "spunk", "xxx", "viagra", "phentermine", "tramadol", "adipex", "advai", "alprazolam", "ambien", "ambian", "amoxicillin", "antivert", "blackjack", "backgammon", "texas", "holdem", "poker", "carisoprodol", "ciara", "ciprofloxacin", "debt", "dating", "porn", "link=", "voyeur", "content-type", "bcc:", "cc:", "document.cookie", "onclick", "onload", "javascript");

	foreach ($badwords as $word)
		if (
			strpos(strtolower($_POST['comments']), $word) !== false || 
			strpos(strtolower($_POST['name']), $word) !== false
		)
			$points += 2;
	
	if (strpos($_POST['comments'], "http://") !== false || strpos($_POST['comments'], "www.") !== false)
		$points += 2;
	if (isset($_POST['nojs']))
		$points += 1;
	if (preg_match("/(<.*>)/i", $_POST['comments']))
		$points += 2;
	if (strlen($_POST['name']) < 3)
		$points += 1;
	if (strlen($_POST['comments']) < 15 || strlen($_POST['comments'] > 1500))
		$points += 2;
	if (preg_match("/[bcdfghjklmnpqrstvwxyz]{7,}/i", $_POST['comments']))
		$points += 1;
	// end score assignments

	foreach($requiredFields as $field) {
		trim($_POST[$field]);
		
		if (!isset($_POST[$field]) || empty($_POST[$field]) && array_pop($error_msg) != "Please fill in all the required fields and submit again.\r\n")
			$error_msg[] = "Please fill in all the required fields and submit again.";
	}

	if (!empty($_POST['name']) && !preg_match("/^[a-zA-Z-'\s]*$/", stripslashes($_POST['name'])))
		$error_msg[] = "The name field must not contain special characters.\r\n";
	if (!empty($_POST['email']) && !preg_match('/^([a-z0-9])(([-a-z0-9._])*([a-z0-9]))*\@([a-z0-9])(([a-z0-9-])*([a-z0-9]))+' . '(\.([a-z0-9])([-a-z0-9_-])?([a-z0-9])+)+$/i', strtolower($_POST['email'])))
		$error_msg[] = "That is not a valid e-mail address.\r\n";
	
	if ($error_msg == NULL && $points <= $maxPoints) {
		$subject = "BirthCompanion Form Email ";
		
		$message = "You received this e-mail message through your website: \n\n";
		foreach ($_POST as $key => $val) {
			if (is_array($val)) {
				foreach ($val as $subval) {
					$message .= ucwords($key) . ": " . clean($subval) . "\r\n";
				}
			} else {
				$message .= ucwords($key) . ": " . clean($val) . "\r\n";
			}
		}
		$message .= "\r\n";
		$message .= 'IP: '.$_SERVER['REMOTE_ADDR']."\r\n";
		$message .= 'Browser: '.$_SERVER['HTTP_USER_AGENT']."\r\n";
		$message .= 'Points: '.$points;

		if (strstr($_SERVER['SERVER_SOFTWARE'], "Win")) {
			$headers   = "From: $yourEmail\r\n";
		} else {
			$headers   = "From: $yourWebsite <$yourEmail>\r\n";	
		}
		$headers  .= "Reply-To: {$_POST['email']}\r\n";

		if (mail($yourEmail,$subject,$message,$headers)) {
			if (!empty($thanksPage)) {
				header("Location: $thanksPage");
				exit;
			} else {
				$result = 'Your message was successfully sent.';
				$disable = true;
			}
		} else {
			$error_msg[] = 'Your message could not be sent this time. ['.$points.']';
		}
	} else {
		if (empty($error_msg))
			$error_msg[] = 'Your message looks too much like spam, and could not be sent at this time. ['.$points.']';
	}
}
function get_data($var) {
	if (isset($_POST[$var]))
		echo htmlspecialchars($_POST[$var]);
}
?>  
<!-- End PHP Form Code -->
        
<!-- Form -->        
        <div class="double_sections">
            <div class="double_section_left">
                
                <!--
	Free PHP Mail Form v2.4.4 - Secure single-page PHP mail form for your website
	Copyright (c) Jem Turner 2007-2014
	http://jemsmailform.com/

	This program is free software: you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation, either version 3 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	To read the GNU General Public License, see http://www.gnu.org/licenses/.
-->

<?php
if (!empty($error_msg)) {
	echo '<p class="error">ERROR: '. implode("<br />", $error_msg) . "</p>";
}
if ($result != NULL) {
	echo '<p class="success">'. $result . "</p>";
}
?>

                <form class="form" action="<?php echo basename(__FILE__); ?>" method="post">
                    
                    <noscript>
                        <p><input type="hidden" name="nojs" id="nojs" /></p>
                    </noscript>
                    
                    <div class="column">
                        <label class="less_space" for="name">Name</label>
                        <input type="text" name="name" id="name" placeholder="Your Name" value="<?php get_data("name"); ?>" />
                        
                        <label for="email">Email Address</label>
                        <input type="text" name="email" id="email" placeholder="Your Email" value="<?php get_data("email"); ?>" />
                        
                        <label for="phone">Phone Number</label>
                        <input type="text" name="phone" id="phone" placeholder="Optional" value="<?php get_data("phone"); ?>" />
                        
                        <label for="due_date">Due Date</label>
                        <input class="height" type="date" name="due_date" id="due_date" placeholder="mm/dd/yyyy" value="<?php get_data("due_date"); ?>" />
                        
                        <label for="location">Birth Location</label>
                        <select id="location" name="location" value="<?php get_data("location"); ?>">
                            <option>Where you are giving birth?</option>
                            <option>Atlanta Medical Center</option>
                            <option>Dekalb Medical</option>
                            <option>Emory Eastside Medical Center</option>
                            <option>Emory Johns Creek Hospital</option>
                            <option>Gwinnett Medical Center</option>
                            <option>Northside Hospital</option>
                            <option>Northside Hospital Forsyth</option>
                            <option>Piedmont Hospital</option>
                            <option>North Fulton Hospital</option>
                            <option>Home Birth</option>
                            <option>Other</option>
                        </select>

                        <label for="comments">Tell me about yourself</label>
                        <textarea name="comments" id="comments"><?php get_data("comments"); ?></textarea>
                    </div>

                    <div class="submit-wrap">
                        <input type="submit" name="submit" id="submit" class="submit" value="Send" <?php if (isset($disable) && $disable === true) echo ' disabled="disabled"'; ?> />
                    </div>
                </form><br>
                <p class="smaller_font">Powered by <a class="more underline" target="_blank" href="http://jemsmailform.com/">Jem's PHP Mail Form</a></p>
            </div>        
        
            <div class="double_section_right"><br>
                <img alt="A client with her newborn" class="contact" src="images/cb_266.jpg">
                <p><span class="italic">“Paige was an excellent Doula. After the first time I met her I knew we were compatible. She helped me plan for labor physically, mentally, and emotionally. At my labor she was very helpful and calming. I was very happy after I gave birth. She helped make it a wonderful experience!”</span></p>
                <p class="right"><span class="italic">&#8212; Adeline B.</span></p><br>
                
                <p><span class="italic">“I asked Paige to be my doula for my fourth pregnancy.  This was my first time using a doula, but I knew from experience that my long labors needed additional, dedicated support.  Paige was with me every step of the way.  Without Paige's calm support and encouraging words, I would have felt lost and out of control.  She was my rock.  I trusted her completely and that was a huge relief so that I could focus on my labor. Paige would be my first and only choice if I needed a doula again.  I highly recommend her!”</span></p> 
                <p class="right"><span class="italic">&#8212; Sarah G.</span></p><br>
                
                <p><span class="italic"><span class="bold">Paige was born to be a doula!</span> It is clear that this is her calling. She is extremely warm, while being confident and reassuring. What I appreciated the most about my experience with Paige was the education that she provided. Paige had taken the time to get to know me prior to my labor, so she understood exactly what I was going to need during the laboring process. <span class="bold">Paige was worth every penny and more! I actually enjoyed this labor because of Paige!</span>"</span></p> <p class="right"><span class="italic">&#8212;Stephane M.</span></p>
            </div>           
       </div>   
<!-- End Form -->         
<!-- End Main -->        
    </div>
<!-- End Container -->    
    
<div id="push"></div>
  
<!-- Footer -->      
<div id="footer">
     <ul class="image">
            <li><img alt="CAPPA certified" src="images/CAPPA.jpg"></li>
     </ul>
    
    <ul class="contact">
        <li><a href="mailto:paige@birthcompanion.net">paige@birthcompanion.net</a></li>
    </ul>
        
    <ul class="codegreer">
        <li>Site by <a target="_blank" href="http://www.codegreer.com/">CodeGreer</a></li>
    </ul>
        
    <ul class="copyright">
        <li>Copyright &#169; 2014-2017, Paige Varner, CLD</li>
    </ul>
        
</div>  
<!-- End Footer --> 


<!-- Javascript --> 

<!-- Slicknav -->    
    <script>
	   $(function(){
		  $('.menu').slicknav();
	   });
    </script>    
<!-- End Javascript -->          
    
</body> 
</html> 