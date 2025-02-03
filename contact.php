<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>BCA Notes Hub</title>
    <link rel="stylesheet" href="css/abtcon.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
</head>

<body>
    <nav class="navbar h-nav-resp">
        <div class="logo"><img src="images\projlogoo.png" alt="logo" /></div>
        <ul class="nav-links v-class-resp">
            <li><a href="home.php">Home</a></li>
            <li><a href="semesters.php">Notes</a></li>
            <li><a href="viewquestion_pdfs.php">Questions</a></li>
            <li><a href="about.php">About</a></li>
            <li><a href="contact.php">Contact Us</a></li>
        </ul>
        <div class="right-nav v-class-resp">
            <form method="get" action="search.php" class="search-container">
                <input type="text" placeholder="Search here" name="search" class="search-box" />
                <button type="submit" class="icon-search"><i class="fa fa-search"></i></button>
            </form>
            <button class="logout-btn">Logout</button>
        </div>
        <div class="burger">
            <div class="line"></div>
            <div class="line"></div>
            <div class="line"></div>
        </div>
    </nav>
    <section class="contact-section">
        <h1>Contact Us</h1>
        <div class="contact-links">
            <p><a href="home.php">home</a> /contact</p>
        </div>
    </section>
    <div class="contact-container">
        <form action="https://api.web3forms.com/submit" method="POST" class="contact-form">
            <div class="contact-lefttxt">
                <p>Get in touch</p>
            </div>
            <input type="hidden" name="access_key" value="55ff5d14-b890-4fbc-8b19-9edb69a7271d">
            <input type="text" name="name" placeholder="Your Name" class="contact-inputs" required>
            <input type="email" name="email" placeholder="Your Email" class="contact-inputs" required>
            <textarea name="message" placeholder="Your Message" class="contact-inputs" required></textarea>
            <button type="submit" onclick="send">Submit</button>
        </form>
        <div class="contact-right">
            <div class="contact-righttxt">
                <p>Contact Details</p>
            </div>
            <span>
                <p><i class="fa fa-home"></i>123 Street, Nepal</p>
            </span>
            <span>
                <p><i class="fa fa-phone"></i>+977 9861790431</p>
            </span>
            <span>
                <p><i class="fa fa-envelope"></i>rakuts123@gmail.com</p>
            </span>
        </div>
    </div>
    <script src="javascript/resp.js"></script>
</body>

</html>