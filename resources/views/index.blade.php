<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="initial-scale=1, width=device-width" />
    <link rel="stylesheet" href="{{ asset('assets/css/welcome.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/welcome-global.css') }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Secular One:wght@400&display=swap" />
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <link rel="icon" href="{{ asset('assets/images/logo.png') }}" type="image/x-icon">
    <link href='https://unpkg.com/boxicons/css/boxicons.min.css' rel='stylesheet'>

</head>
<body>
    <div class="desktop-1">
        <header class="navigation-bar">
            <div class="frame-c">
                <div class="logo">
                    <img class="logo-child" alt="" src="{{ asset('assets/images/logo.png') }}" />
                </div>
                <h3 class="postify">POSTIFY</h3>
            </div>  
            <div class="button-frame">
                <div class="button">
                    <a href="#frame-a">Home</a>
                </div>
                <div class="button1">
                    <a href="#features-title-wrapper">Features</a>
                </div>
                <div class="button2">
                    <a href="#team-section">About Us</a>
                </div>
                <div class="button3">
                    <a href="#footer-section">Quick Links</a>
                </div>
                <button class="button4">
                    <a href="{{ route('login') }}" class="text1">Login</a>
                </button>
            </div>
        </header>
        <section id="frame-a" class="frame-a section">
            <div class="frame-j">
                <h1 class="meet-postify">Meet Postify</h1>
                <div class="empowering-businesses-with">
                    Welcome to Postify, a groundbreaking social media that redefines digital engagement. Curate your online presence, express individuality, and explore tailored content. Welcome to the next frontier of social connectivity!
                </div>
                <button class="buttonvariant4">
                    <a href="{{route ('sign-up')}}" class="start-your-free">Sign Up</a>
                </button>
            </div>
            <div class="image-slider">
                <img src="../assets/images/astronaut_selfie_1.jpg" alt="Illustration 1">
                <img src="../assets/images/astronaut_selfie_2.jpg" alt="Illustration 2">
                <img src="../assets/images/astronaut_selfie_3.jpg" alt="Illustration 3">
                <img src="../assets/images/astronaut_selfie_4.jpg" alt="Illustration 4">
            </div>
        </section>
        <div id="features-title-wrapper" class="features-title-wrapper section">
            <h2 class="section-title">Features</h2>
        </div>
        <section class="four-box-section section">
            <div class="box">
                <img src="../assets/images/share_2.svg" alt="Share Icon" class="icon">
                <h3>Share</h3>
            </div>
            <div class="box">
                <img src="../assets/images/explore.svg" alt="Explore Icon" class="icon">
                <h3>Explore</h3>
            </div>
            <div class="box">
                <img src="../assets/images/connect.svg" alt="Connect Icon" class="icon">
                <h3>Connect</h3>
            </div>
            <div class="box">
                <img src="../assets/images/create.svg" alt="Create Icon" class="icon">
                <h3>Create</h3>
            </div>
        </section>
        <div id="team-section" class="team-section">
            <h2 class="team-title">Meet our Team</h2>
            <div class="team-grid">
                <div class="team-box red">
                    <a href="https://www.facebook.com/james.nabayra29" target="_blank">
                    <img src="../assets/images/nabayra.jpg" alt="Project Manager / Developer">
                    </a>
                    <p>Project Manager / Developer</p>
                    <p>(James Nabayra)</p>
                </div>
                <div class="team-box blue">
                    <img src="../assets/images/mingo.jpg" alt="Technical Lead / Developer">
                    <p>Full Stack Developer</p>
                    <p>(Ed Judah Mingo)</p>
                </div>
                <div class="team-box green">
                    <img src="../assets/images/fidel.jpg" alt="Quality Assurance / Developer">
                    <p>Quality Assurance / Developer</p>
                    <p>(Diana Rose Fidel)</p>
                </div>
                <div class="team-box yellow">
                    <img src="../assets/images/villamarzo.jpg" alt="UI/UX Designer / Developer">
                    <p>UI/UX Designer / Developer</p>
                    <p>(Kazel Villamarzo)</p>
                </div>
                <div class="team-box purple">
                    <img src="../assets/images/casim.jpg" alt="UI/UX Designer / Developer">
                    <p>UI/UX Designer / Developer</p>
                    <p>(Karen Alonica Casim)</p>
                </div>
                <div class="team-box orange">
                    <img src="../assets/images/malapad.jpg" alt="UI/UX Designer / Developer">
                    <p>UI/UX Designer / Developer</p>
                    <p>(James Malapad)</p>
                </div>
            </div>
        </div>
        <footer id="footer-section" class="footer-section">
            <div class="footer-container">
                <div class="postify-parent">
                    <h3 class="footer-title">POSTIFY</h3>
                    <p>Explore the world through Postify — Elevate your online experience, express authentically, and connect meaningfully in the digital realm. Welcome to a new era of social connectivity!</p>
                </div>
                <div class="follow-us-parent">
                    <h3 class="footer-title">FOLLOW US</h3>
                    <div class="social-links">
                        <a href="https://www.facebook.com/profile.php?id=61555653720240" class="social-button">
                            <i class='bx bxl-facebook'></i>
                        </a>
                        <a href="https://github.com/PostifySystematica" class="social-button">
                            <i class='bx bxl-github'></i>
                        </a>
                        <a href="https://twitter.com/i/flow/login?redirect_after_login=%2FPostify27" class="social-button">
                            <i class='bx bxl-twitter'></i>
                        </a>
                    </div>
                </div>
                <div class="quick-links-parent">
                    <h3 class="footer-title">Quick Links</h3>
                    <ul class="quick-links">
                        <li><a href="https://www.facebook.com/profile.php?id=61555653720240">About</a></li>
                        <li><a href="https://www.facebook.com/profile.php?id=61555653720240">Team</a></li>
                        <li><a href="https://www.facebook.com/profile.php?id=61555653720240">About Us</a></li>
                        <li><a href="https://www.facebook.com/profile.php?id=61555653720240">Contact</a></li>
                    </ul>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>