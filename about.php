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
    <section class="about-section">
        <h1>About Us</h1>
        <div class="about-links">
            <p><a href="home.php">home</a> /about</p>
        </div>
    </section>
    <section class="about-content">
        <div class="about-container">
            <div class="about-img">
                <img src="images/abtimg.png" alt="pic">
            </div>
            <div class="abt-parag">
                <p class="txt-big">Why Choose Us?</p>
                <p class="txt-small">
                    BCA Notes Hub is an educational website that strives to offer
                    students a comprehensive set of reference notes, syllabi, and past
                    questions. his website aims to empower BCA students with
                    well-organized study materials to help them excel academically.
                </p>
                <div>
                    <p class="sub-txt">Notes</p>
                    <p class="txt-small">
                        With a collection of semester-wise subject notes, this website
                        enables students to find resources based on their specific
                        needs.
                    </p>
                </div>
                <div>
                    <p class="sub-txt">Questions</p>
                    <p class="txt-small">
                        We also support your sucess by providing a comprehensive set of
                        all past paper questions and other significant questions.
                    </p>
                </div>
            </div>
        </div>
    </section>
    <script src="javascript/resp.js"></script>
</body>

</html>