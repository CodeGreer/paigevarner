<!doctype html>
<html class="no-js" lang="en">
<head>
	<meta charset="utf-8" />
    <meta name="format-detection" content="telephone=yes" />

    <title>Contact</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">
    
<!-- CSS -->
	<link rel="stylesheet" href="css/pv_style.css" />

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
        <p>For questions or to check our availability, please use the form below or <a class="underline" href="mailto:info@birthcompanion.net">send us an email</a>.</p>   

    <div class="two">
        <section>
        <form class="form" action="contact.php" method="post">
                    
            <noscript>
                <p><input type="hidden" name="nojs" id="nojs" /></p>
            </noscript>

            <div class="column">
                <label class="less_space" for="name">Name</label>
                <input type="text" name="name" id="name" placeholder="Your Name" value="" required />

                <label for="email">Email Address</label>
                <input type="text" name="email" id="email" placeholder="Your Email" value="" required />

                <label for="phone">Phone Number</label>
                <input type="text" name="phone" id="phone" placeholder="Optional" value="" />

                <label for="due_date">Due Date</label>
                <input class="height" type="date" name="due_date" id="due_date" value="" required />

                <label for="location">Birth Location</label>
                <select id="location" name="location" value="" required>
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
                <textarea name="comments" id="comments" required></textarea>
            </div>

            <div class="submit-wrap">
                <input type="submit" name="submit" id="submit" class="submit" value="Send"  />
            </div>
        </form>
        </section>
        
        <section>
            <h4>Client testimonials</h4>
            <!-- <img alt="A client with her newborn" class="contact" src="images/doula-mom-newborn.png"> -->
            <p><span class="italic">“Paige was an excellent Doula. After the first time I met her I knew we were compatible. She helped me plan for labor physically, mentally, and emotionally. At my labor she was very helpful and calming. I was very happy after I gave birth. She helped make it a wonderful experience!”</span></p>
            <p class="more">&#8212; Adeline B.</p>

            <p><span class="italic">“I asked Paige to be my doula for my fourth pregnancy.  This was my first time using a doula, but I knew from experience that my long labors needed additional, dedicated support.  Paige was with me every step of the way.  Without Paige's calm support and encouraging words, I would have felt lost and out of control.  She was my rock.  I trusted her completely and that was a huge relief so that I could focus on my labor. Paige would be my first and only choice if I needed a doula again.  I highly recommend her!”</span></p> 
            <p class="more">&#8212; Sarah G.</p>

            <p><span class="italic"><span class="bold">Paige was born to be a doula!</span> It is clear that this is her calling. She is extremely warm, while being confident and reassuring. What I appreciated the most about my experience with Paige was the education that she provided. Paige had taken the time to get to know me prior to my labor, so she understood exactly what I was going to need during the laboring process. <span class="bold">Paige was worth every penny and more! I actually enjoyed this labor because of Paige!</span>"</span></p> 
            <p class="more">&#8212;Stephane M.</p>
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