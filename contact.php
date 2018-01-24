<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * 

	Jem's PHP Mail Form Premium v2.1.2
	Secure single-page PHP mail form for your website
	Copyright (c) Jem Turner 2014-2017
	http://jemsmailform.com/

* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

// OPTIONS - PLEASE CONFIGURE THESE BEFORE USE!

// the email address you wish to receive these mails through
$primary_recipient = "info@birthcompanion.net"; 

// additional recipients to be "CC"ed into the email (the email address of these 
// recipients will be visible in the mail); separate each email with a comma
$cc_recipients = ""; 

// additional recipients to be "BCC"ed into the email (the email address of these 
// recipients will NOT be visible in the mail); separate each email with a comma
$bcc_recipients = ""; 

$yourWebsite = "Birth Companion"; // the name of your website
$thanksPage = 'thank-you.html'; // URL to 'thanks for sending mail' page; leave empty to keep message on the same page 
$maxPoints = 4; // max points a person can hit before it refuses to submit - recommend 4
$requiredFields = "name,email,comments"; // names of the fields you'd like to be required as a minimum, separate each field with a comma
$prevent_repeats = true; // prevent rapid submits (submissions less than 60 seconds apart)


// DO NOT EDIT BELOW HERE
session_start();

function generate_nonce( $length = 32 ) {
	if ( function_exists( 'openssl_random_pseudo_bytes' ) ) {
		# we have php5 :)
		return substr( base64_encode( openssl_random_pseudo_bytes( 1000 ) ), 0, $length );
	} else {
		# php4 makes babies cry :(
		return substr( base64_encode( rand( 0, 1000 ) ), 0, $length );
	}
}
function destroy_nonce() {
	unset( $_SESSION['nonce'] );
}

if ( !isset( $_SESSION['nonce'] ) ) {
	$token = generate_nonce();
	$_SESSION['nonce'][$token] = strtotime( "+1 hour" );
}

$error_msg = array();
$result = null;

$requiredFields = explode( ",", $requiredFields );

function clean($data) {
	$data = trim( stripslashes( strip_tags( $data ) ) );
	return $data;
}
function is_bot() {
	$bots = array( "Indy", "Blaiz", "Java", "libwww-perl", "Python", "OutfoxBot", "User-Agent", "PycURL", "AlphaServer", "T8Abot", "Syntryx", "WinHttp", "WebBandit", "nicebot", "Teoma", "alexa", "froogle", "inktomi", "looksmart", "URL_Spider_SQL", "Firefly", "NationalDirectory", "Ask Jeeves", "TECNOSEEK", "InfoSeek", "WebFindBot", "girafabot", "crawler", "www.galaxy.com", "Googlebot", "Scooter", "Slurp", "appie", "FAST", "WebBug", "Spade", "ZyBorg", "rabaz" );

	foreach ( $bots as $bot )
		if ( stripos( $_SERVER['HTTP_USER_AGENT'], $bot ) !== false )
			return true;

	if ( empty( $_SERVER['HTTP_USER_AGENT'] ) || $_SERVER['HTTP_USER_AGENT'] == " " )
		return true;
	
	return false;
}

function is_valid_email( $email_address ) {
	if ( function_exists( 'filter_var' ) ) {
		# we have php5 :)
		if ( filter_var( $email_address, FILTER_VALIDATE_EMAIL ) !== false )
			return true;
		
		return false;
	} else {
		# php4 makes babies cry :(
		if ( preg_match( '/^([a-z0-9])(([-a-z0-9._])*([a-z0-9]))*\@([a-z0-9])(([a-z0-9-])*([a-z0-9]))+(\.([a-z0-9])([-a-z0-9_-])?([a-z0-9])+)+$/i', strtolower( $email_address ) ) )
			return true;
		
		return false;
	}
}
function is_valid_url( $web_address ) {
	if ( function_exists( 'filter_var' ) ) {
		# we have php5 :)
		if ( filter_var( $web_address, FILTER_VALIDATE_URL ) !== false )
			return true;
		
		return false;
	} else {
		# php4 makes babies cry :(
		if ( preg_match( '/^(http|https):\/\/(([A-Z0-9][A-Z0-9_-]*)(\.[A-Z0-9][A-Z0-9_-]*)+)(:(\d+))?\/?/i', $web_address ) )
			return true;
		
		return false;
	}
}
function validate_nonce( $token ) {
	if ( !isset( $token ) || !isset( $_SESSION['nonce'][$token] ) )
		return false; # token or session missing

	if ( time() > $_SESSION['nonce'][$token] ) {
		destroy_nonce();
		return false; # expired
	}

	if ( $token != key( $_SESSION['nonce'] ) ) {
		destroy_nonce();
		return false; # submitted token doesn't match session
	}
	
	return true;
}

if ( $_SERVER['REQUEST_METHOD'] == "POST" ) {
	if ( validate_nonce( $_POST['token'] ) !== true ) {
		$error_msg[] = "Invalid submission";
		destroy_nonce();
	}
	
	if ( is_bot() !== false )
		$error_msg[] = "No bots please! UA reported as: ". $_SERVER['HTTP_USER_AGENT'];
		
	// lets check a few things - not enough to trigger an error on their own, but worth assigning a spam score.. 
	// score quickly adds up therefore allowing genuine users with 'accidental' score through but cutting out real spam :)
	$points = (int)0;
	
	if ( isset( $_SESSION['last_submit'] ) ) {
		if ( time()-$_SESSION['last_submit'] > 60 && time()-$_SESSION['last_submit'] < 360 )
			$points += 2;
		
		if ( true == $prevent_repeats && time()-$_SESSION['last_submit'] < 60 ) {
			$error_msg[] = "You have only just filled in the form; please do not send multiple form submissions.";
		}
	} else {
		$_SESSION['last_submit'] = time();
	}
	
	$badwords = array("adult", "beastial", "bestial", "blowjob", "clit", "cum", "cunilingus", "cunillingus", "cunnilingus", "cunt", "ejaculate", "fag", "felatio", "fellatio", "fuck", "fuk", "fuks", "gangbang", "gangbanged", "gangbangs", "hotsex", "hardcode", "jism", "jiz", "orgasim", "orgasims", "orgasm", "orgasms", "phonesex", "phuk", "phuq", "pussies", "pussy", "spunk", "xxx", "viagra", "phentermine", "tramadol", "adipex", "advai", "alprazolam", "ambien", "ambian", "amoxicillin", "antivert", "blackjack", "backgammon", "texas", "holdem", "poker", "carisoprodol", "ciara", "ciprofloxacin", "debt", "dating", "porn", "link=", "voyeur", "content-type", "bcc:", "cc:", "document.cookie", "onclick", "onload", "javascript");

	foreach ( $badwords as $word )
		if (
			strpos( strtolower( $_POST['comments'] ), $word ) !== false || 
			strpos( strtolower( $_POST['name'] ), $word ) !== false
		)
			$points += 2;
	
	if ( strpos( $_POST['comments'], "http://" ) !== false || strpos( $_POST['comments'], "www." ) !== false )
		$points += 2;
	if ( isset( $_POST['nojs'] ) )
		$points += 1;
	if ( preg_match( "/(<.*>)/i", $_POST['comments'] ) )
		$points += 2;
	if ( strlen( $_POST['name']) < 3 )
		$points += 1;
	if ( strlen( $_POST['comments'] ) < 15 || strlen( $_POST['comments'] > 1500 ) )
		$points += 2;
	if ( preg_match( "/[bcdfghjklmnpqrstvwxyz]{7,}/i", $_POST['comments'] ) )
		$points += 1;
	// end score assignments

	if ( !empty( $requiredFields ) ) {
		foreach( $requiredFields as $field ) {
			trim( $_POST[$field] );
			
			if ( !isset( $_POST[$field] ) || empty( $_POST[$field] ) )
				$error_msg['empty_fields'] = "Please fill in all the required fields and submit again.";
		}
	}

	// updated regex from http://stackoverflow.com/questions/5963228/regex-for-names-with-special-characters-unicode
	if ( !empty( $_POST['name'] ) && !preg_match( "~^(?:[\p{L}\p{Mn}\p{Pd}\'\x{2019}]+\s?[\p{L}\p{Mn}\p{Pd}\'\x{2019}]+\s?)+$~u", stripslashes( $_POST['name'] ) ) )
		$error_msg[] = "The name field must not contain special characters.\r\n";
	if ( !empty( $_POST['email'] ) && !is_valid_email( $_POST['email'] ) )
		$error_msg[] = "That is not a valid e-mail address.\r\n";
	if ( !empty( $_POST['url'] ) && !is_valid_url( $_POST['url'] ) )
		$error_msg[] = "Invalid website url.\r\n";
	
	if ( $error_msg == NULL && $points <= $maxPoints ) {
		$subject = "Contact Page - ". $yourWebsite;
		
		$message = "You received this e-mail message through your website: \n\n";
		foreach ( $_POST as $key => $val ) {
			if ( $key == 'token' || $key == 'submit' )
				continue; // we don't need these in the email
			
			if ( is_array( $val ) ) {
				foreach ( $val as $subval ) {
					$message .= ucwords( $key ) . ": " . clean( $subval ) . "\r\n";
				}
			} else {
				$message .= ucwords( $key ) . ": " . clean( $val ) . "\r\n";
			}
		}
		$message .= "\r\n";
		$message .= 'IP: '. $_SERVER['REMOTE_ADDR']."\r\n";
		$message .= 'Browser: '. $_SERVER['HTTP_USER_AGENT']."\r\n";
		$message .= 'Points: '. $points;

		if ( strstr( $_SERVER['SERVER_SOFTWARE'], "Win" ) ) {
			$headers   = "From: $primary_recipient\r\n";
		} else {
			$headers   = "From: $yourWebsite <$primary_recipient>\r\n";	
		}
		$headers  .= "Reply-To: {$_POST['email']}\r\n";

		if ( '' != $cc_recipients ) {
			$headers .= "CC: ". $cc_recipients;
		}		
		if ( '' != $bcc_recipients ) {
			$headers .= "BCC: ". $bcc_recipients;
		}
		
		$headers .= "Content-Transfer-Encoding: 8bit\r\n";
		$headers .= "Content-type: text/plain; charset=UTF-8\r\n";
		

		if ( mail( $primary_recipient, $subject, $message, $headers ) ) {
			destroy_nonce();
			
			if ( !empty( $thanksPage ) ) {
				header( "Location: $thanksPage" );
				exit;
			} else {
				$result = 'Your mail was successfully sent.';
				$disable = true;
			}
		} else {
			destroy_nonce();
			$error_msg[] = 'Your mail could not be sent this time. ['.$points.']';
		}
	} else {
		if ( empty( $error_msg ) ) {
			// error message is empty so it must be a points problem
			$error_msg[] = 'Your mail looks too much like spam, and could not be sent this time. ['.$points.']';
		} else {
			// ooops, someone made an error - let's remove the last submission time so they don't get peed off
			unset( $_SESSION['last_submit'] );
		}
	}
}
function get_data( $var ) {
	if ( isset( $_POST[$var] ) )
		echo htmlspecialchars( $_POST[$var] );
}
?>

                
<!-- End PHP for Contact Form --> 

<!doctype html>
<html class="no-js" lang="en">
<head>
	<meta charset="utf-8" />
    <meta name="format-detection" content="telephone=yes" />

    <title>Contact Us | Birth Companion | Atlanta Labor Doula</title>

    <link rel="canonical" href="http://www.birthcompanion.net/contact.php">
    <meta charset="utf-8"/>
    <meta name="format-detection" content="telephone=yes" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords" content="Atlanta, labor, doula, pregnancy, childbirth, homebirth" >
    <meta name="description" content="Labor doula serving Atlanta and surrounding counties: creating a calm, safe space for your birth experience.">
    
<!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/manifest.json">
    <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#694199">
    <meta name="theme-color" content="#ffffff">
    
<!-- FACEBOOK -->
    <meta property="og:title" content="Birth Companion - Contact Us" >
    <meta property="og:site_name" content="Birth Companion">
    <meta property="og:url" content="http://www.birthcompanion.net/contact.php/" >
    <meta property="og:description" content="For questions or to check our availability, please use the form at our website." >
    <meta property="og:image" content="http://birthcompanion.net/images/doulas.png" >
    <meta property="og:type" content="website" >
    <meta property="og:locale" content="en_US" >

<!-- TWITTER  -->
    <meta property="twitter:card" content="summary" >
    <meta property="twitter:title" content="Birth Companion - Contact Us" >
    <meta property="twitter:description" content="For questions or to check our availability, please use the form at our website." >
    <meta property="twitter:creator" content="@" >
    <meta property="twitter:url" content="http://www.birthcompanion.net/contact.php" >
    <meta property="twitter:image" content="http://birthcompanion.net/images/doulas.png" >
    
<!-- CSS -->
	<link rel="stylesheet" href="css/style.min.css" />

</head>
  
<body> 
    
<!-- start my code -->
    
<!-- Borders -->
    <div id="left"></div>
    <div id="right"></div>
    <div id="top"></div>
    <div id="bottom"></div>  
<!-- End Borders -->

<!-- Header --> 
    <header class="header_offset">
        <h2 class="title"><a href="index.html">BirthCompanion.net</a></h2>

<!-- Menu -->    
        <a href="#navigation" id="menu-toggle" class="menu-toggle">
            <span>&#9776;</span>
        </a>

        <nav id="navigation" class="main-menu">

            <a href="#" id="menu-close" class="menu-close">
              <span class="none">&#215;</span>
            </a>

            <ul>
                <li><h2><a href="index.html">BirthCompanion.net</a></h2></li>
                <li><a href="index.html">Welcome</a></li>
                <li><a href="offerings.html">What We Offer</a></li>
                <li><a href="about.html">About Us</a></li>
                <li><a href="resources.html">Resources</a></li>
                <li><a href="#">Contact</a></li>
            </ul>
        </nav>

        <a href="#menu-toggle" class="backdrop" hidden></a>
<!-- End Menu -->  
    </header>
<!-- End Header --> 

<!-- Main -->  
    <main>
        <h2>Contact</h2>
        <p>For questions or to check our availability, please use the form below or email us at <a class="underline" href="mailto:info@birthcompanion.net">info@birthcompanion.net</a>.</p>            
        
        <div class="two">
            <section>
                <h4>Contact Us</h4>
            
                <form action="<?php echo basename( __FILE__ ); ?>" method="post" id="form" class="form">    
                    <noscript>
                        <p><input type="hidden" name="nojs" id="nojs" /></p>
                    </noscript>

                    <input type="hidden" name="token" value="<?php if ( is_array( $_SESSION['nonce'] ) ) echo key( $_SESSION['nonce'] ); ?>">

                    <div class="column">
                        <label class="less_space" for="name">Name</label>
                        <input type="text" name="name" id="name" value="<?php get_data("name"); ?>" placeholder="Your Name" required />

                        <label for="email">Email Address</label>
                        <input type="text" name="email" id="email" value="<?php get_data("email"); ?>" placeholder="Your Email" required />

                        <label for="phone">Phone Number</label>
                        <input type="text" name="phone" id="phone" value="<?php get_data("phone"); ?>" placeholder="Optional"  />

                        <label for="due_date">Due Date</label>
                        <input class="height" type="date" name="due_date" id="due_date" value="<?php get_data("due_date"); ?>" required />

                        <label for="location">Birth Location</label>
                        <select id="location" name="location" value="<?php get_data("location"); ?>" required>
                            <option value="" disabled selected hidden>Where you are giving birth?</option>
                            <option value="0">Atlanta Medical Center</option>
                            <option value="1">Dekalb Medical</option>
                            <option value="2">Eastside Medical Center</option>
                            <option value="3">Emory Johns Creek Hospital</option>
                            <option value="4">Gwinnett Medical Center</option>
                            <option value="5">Northside Hospital</option>
                            <option value="6">Northside Hospital Forsyth</option>
                            <option value="7">Piedmont Hospital</option>
                            <option value="8">North Fulton Hospital</option>
                            <option value="9">Atlanta Birth Center</option>
                            <option value="10">Home Birth</option>
                            <option value="11">Other</option>
                        </select>

                        <label for="comments">Tell us about yourself</label>
                        <textarea name="comments" id="comments" required><?php get_data("comments"); ?></textarea>
                    </div>

                    <div class="submit-wrap">
                        <input type="submit" name="submit" id="submit" class="submit" value="Send" <?php if (isset($disable) && $disable === true) echo 'disabled="disabled"'; ?> />
                    </div>
                </form>
            </section>

            <section>
                <h4>Client testimonials</h4>
                <!-- <img alt="A client with her newborn" class="contact" src="images/doula-mom-newborn.png"> -->
                <p><span class="italic">“Paige was an excellent Doula. After the first time I met her I knew we were compatible. She helped me plan for labor physically, mentally, and emotionally. At my labor she was very helpful and calming. I was very happy after I gave birth. She helped make it a wonderful experience!”</span></p>
                <p class="client">&#8212; Adeline B.</p>

                <p><span class="italic">“I asked Paige to be my doula for my fourth pregnancy.  This was my first time using a doula, but I knew from experience that my long labors needed additional, dedicated support.  Paige was with me every step of the way.  Without Paige's calm support and encouraging words, I would have felt lost and out of control.  She was my rock.  I trusted her completely and that was a huge relief so that I could focus on my labor. Paige would be my first and only choice if I needed a doula again.  I highly recommend her!”</span></p> 
                <p class="client">&#8212; Sarah G.</p>

                <p><span class="italic">Paige was born to be a doula! It is clear that this is her calling. She is extremely warm, while being confident and reassuring. What I appreciated the most about my experience with Paige was the education that she provided. Paige had taken the time to get to know me prior to my labor, so she understood exactly what I was going to need during the laboring process. Paige was worth every penny and more! I actually enjoyed this labor because of Paige!"</span></p> 
                <p class="client">&#8212;Stephane M.</p>
            </section> 
        </div>
<!-- End Form --> 
    
    </main>
<!-- End Main -->      
   
  
<!-- Footer -->      
    <footer>
        <nav>
            <ul>
                <li><a href="#">Contact Us</a></li>
                <li class="order">Copyright &#169; 2014-2018, Paige Varner, CLD</li>
                <li>Site by <a target="_blank" href="https://codegreer.com/">CodeGreer</a></li>
            </ul>
        </nav>
    </footer>  
<!-- End Footer --> 
         
</body> 
</html> 