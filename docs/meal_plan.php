<?php
define("DB_server","localhost");
define("DB_user","root");
define("DB_password","");
define("DB_name","phpmyadmin");
function db_connect(){
    $connection = mysqli_connect(DB_server,DB_user,DB_password,DB_name);
    return $connection;
};
$db = db_connect();
$query = "SELECT Food_product,Stage,Emission FROM Food_emission";
$result = mysqli_query($db,$query);

//free memory associated with result
$result -> close();

//close connection
$db -> close();

?>

<!DOCTYPE HTML>
<!--
	Ion by TEMPLATED
	templated.co @templatedco
	Released for free under the Creative Commons Attribution 3.0 license (templated.co/license)
-->
<html>
<head>
    <title>Meal Planning - Trailblazers</title>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <!-- Slideshow -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="" />
    <meta name="keywords" content="" />
    <!--[if lte IE 8]><script src="js/html5shiv.js"></script><![endif]-->
    <script src="js/jquery.min.js"></script>
    <script src="js/skel.min.js"></script>
    <script src="js/skel-layers.min.js"></script>
    <script src="js/init.js"></script>
    <script src="js/Chart.min.js"></script>
    <noscript>
        <link rel="stylesheet" href="css/skel.css" />
        <link rel="stylesheet" href="css/style.css" />
        <link rel="stylesheet" href="css/style-xlarge.css" />
    </noscript>
    <link rel="apple-touch-icon" sizes="180x180" href="/favicon_io/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon_io/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon_io/favicon-16x16.png">
    <link rel="icon" type="image/png" sizes="512x512" href="/favicon_io/android-chrome-512x512.png">
    <link rel="icon" type="image/png" sizes="192x192" href="/favicon_io/android-chrome-192x192.png">
    <link rel="manifest" href="/favicon_io/site.webmanifest">
    <link rel="mask-icon" href="/favicon_io/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">

</head>
<body id="top">

<!-- Header -->
<header id="header" class="skel-layers-fixed">
    <h1>Trailblazers</h1>
    <nav id="nav">
        <ul>
            <li><a href="index.html">Home</a></li>
            <li><a href="carbon_footprint.php">Carbon Footprint</a></li>
            <li><a href="meal_plan.php" class="active-page">Meal Planning</a></li>
            <li><a href="facts.html" >Facts</a></li>
            <li><a href="about_us.html">About Us</a></li>
        </ul>
    </nav>
</header><br>
<div class="breadcrumb align-center">
    <a href="index.html">Home</a>&nbsp; >&nbsp;
    <span>Meal Planning</span>
</div>
<!-- Main -->
<div id="main" class="wrapper style1">
    <header class="major">
        <h3>Meal Planning</h3>
        <p>Eat healthy with eco-friendly meals</p>
    </header>
    <div class="container">
        <div class="row">
            <div class="6u">
                <h3>What is your carbon footprint of food?</h3>
                <p>Add and calculate carbon footprint of ingredients in your recipe.</p>
                <br>
                <br>
                <br>
                <br>
                <br>
                <br>
                <br>
            </div> <!-- first 6u -->

            <div class="6u">
                <div class="row">
                    <canvas id="myChart" width="100" height="80"></canvas>
                </div>
            </div> <!-- 2nd 6u -->
        </div> <!-- 1st row -->
        <hr class="major" />
        <h3> What is your daily nutrient requirements? </h3>
        <p>Find it out by clicking on each nutrient.</p>
        <div class="row"> <!--div class for the second chart-->
            <div class ="6u">
                <br>
                <br>
                <br>
                <br>
                <br>
                <br>
                <br>
            </div> <!-- end of first 6u-->
            <div class="6u">
                <canvas id="myChart2" width="100" height="60"></canvas>
            </div>
            <br><br>
        </div> <!-- end of second row-->
    </div> 	<!-- 1st Container -->
</div> 	<!-- main wrapper -->
</section>


<!-- Footer -->
<footer id="footer">
    <div class="container">
        <ul class="copyright">
            <li>Copyright &copy; 2020 Trailblazers.</li>
            <li>Design: TEMPLATED</li>
            <li>Images: Pexels</li>
        </ul>
    </div>
</footer>
</body>
</html>