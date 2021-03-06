 <?php
    define('DB_SERVER','localhost');
    define('DB_USERNAME','root');
    define('DB_PASSWORD','');
    define('DB_NAME','phpmyadmin');

    $link = mysqli_connect(DB_SERVER, DB_USERNAME,DB_PASSWORD,DB_NAME);

    if($link == false) {
        die("Error: Could not connect. " . mysqli_connect_error());
    }
    //query to get data from the table "combined_data"
    $query = "SELECT * FROM combined_data";
    //execute query
    $result = mysqli_query($link, $query);
    //create an empty array
    $data = array();
    //loop through the returned data
    foreach ($result as $row) {
        $data[] = $row;
    }
    //free memory associated with result
    $result -> close();
    //close connection
    $link -> close();
    //print json_encode($data);
?>

<!--
	Ion by TEMPLATED
	templated.co @templatedco
	Released for free under the Creative Commons Attribution 3.0 license (templated.co/license)
-->
<html>
	<head>
		<title>Nutrient - Trailblazers</title>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
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
						<li><a href="nutrient.php" class="active-page">Nutrient</a></li>
                        <li><a href="facts.html">Facts</a></li>
                        <li><a href="about_us.html">About Us</a></li>
					</ul>
				</nav>
			</header><br>
            <div class="breadcrumb align-center">
                <a href="index.html">Home</a>&nbsp; >&nbsp;
                <span>Nutrient</span>
            </div>

		<!-- Main -->
			<section id="main" class="wrapper style1">
				<header class="major">
					<h3>Nutrient</h3>
					<p>Find out nutrition information</p>
				</header>
				<div class="container">
                    <section>
                        <h3>TOP FOODS HIGHEST IN SELECTED NUTRIENT</h3><br />
                    <div class="row">
                        <div class ="6u">
                            <h4>Choose nutrient&nbsp;&nbsp;&nbsp;&nbsp;</h4>  
                            <div>
                                <select id="nutrient" onchange="filterNutrient()">
                                    <option>Select</option>
                                    <option value="Calcium_mg">Calcium</option>
                                    <option value="Carb_g">Carb</option>
                                    <option value="Fat_g">Fat</option>
                                    <option value="Fiber_g">Fiber</option>
                                    <option value="Protein_g">Protein</option>
                                    <option value="Sugar_g">Sugar</option>
                                    <option value="VitA_mcg">Vitamin A</option>
                                    <option value="VitC_mg">Vitamin C</option>
                                    <option value="VitE_mg">Vitamin E</option>
                                </select>
                            </div>
                        <br>

                        <br>
                        <ul class="actions">
                            <li><a class="button alt" onclick="validateInput()">Submit</a></li>
                        </ul>
                        </div> <!-- end of first 6u-->
                            <div class="6u">
                                <canvas id="myChart" width="100" height="60"></canvas>
                            </div>
                        <br><br>
                    </div> <!-- end of row -->

                <script type ="text/javascript">
                    var original_data = <?php echo json_encode($data); ?>;
                    var food_data = original_data;
                    var data_filter = "";
                    var select_nutrient = "";
                    var myChart;
                    var chartExist = false;
                    var topLimit;

                    function filterNutrient() {
                        select_nutrient = document.getElementById("nutrient").value;
                    }


                    function validateInput() {
                        if (select_nutrient.length > 0 && chartExist === true)  {
                            myChart.destroy();
                            food_data = food_data.filter(d => d.nutrient === select_nutrient);

                            showFood();
                        } else if (select_nutrient.length > 0) {
                            food_data = food_data.filter(d => d.nutrient === select_nutrient);

                            showFood();
                        }
                    }

                    function showFood() {
                        // descending order
                        food_data.sort(function(a, b) {
                            return b.value - a.value;
                        });

                        topLimit = 10;

                        for (var i=0; i<10;i++){
                            if (food_data[i].value === '0') {
                                topLimit = i;
                                break;
                            }
                        }

                        var top_ten = food_data.slice(0,topLimit);
                        var chart_x = [];
                        var description_x = [];
                        var chart_y = [];
                        for(var i in top_ten) {
                            description_x.push(top_ten[i].descrip);
                            var splitString = top_ten[i].food_name;
                            chart_x.push(splitString); // get the first word
                            chart_y.push(top_ten[i].value);
                        }

                        var ctx = document.getElementById('myChart').getContext('2d');
                        var config = {
                            type: 'bar',
                            data: {
                                labels: chart_x,
                                tooltipText: description_x,
                                datasets: [{
                                    label:  select_nutrient + ' per 100g of Food',
                                    data: chart_y,
                                    // bootstrap colors
                                    // https://i.pinimg.com/originals/b8/70/f6/b870f6c3cf2f275906616de26cffaa52.png
                                    backgroundColor: 'rgba(0,150,136,0.7)',
                                    hoverBackgroundColor: 'rgba(255,152,0,0.7)'
                                }]
                            },
                            options: {
                                scales: {
                                    yAxes: [{
                                        ticks: {
                                            //min: 0, //actual 0
                                            //max: 800 //actual is 707
                                        }
                                    }]
                                },
                                responsive: true,
                                tooltips: {
                                    callbacks: {
                                        title: function(tooltipItem, data) {
                                            var title = data.tooltipText[tooltipItem[0].index];
                                            return title;
                                        }
                                    }
                                }

                            }
                        };
                        Chart.defaults.global.defaultFontSize = 14;
                        myChart = new Chart(ctx, config);
                        chartExist = true;
                        // reset data
                        console.log(chart_x);
                        showTable(food_data);
                        food_data = original_data;
                    }

                    function showTable(fdata){
                        var table = document.getElementById("myTable");
                        table.innerHTML = "";
                        for (var i=0; i<topLimit;i++){
                            var j = i+1;
                            var row = `<tr>
                                                        <td>${j}</td>
                                                        <td>${fdata[i].descrip}</td>
                                                        <td>${fdata[i].nutrient_type}</td>
                                                        <td>${fdata[i].value}</td>
                                                    </tr>`
                            table.innerHTML += row;
                        }
                    }
                </script>
                    <table id="table">
                        <tr>
                            <th>Ranking</th>
                            <th>Description</th>
                            <th>Nutrient_unit</th>
                            <th>Value</th>
                        </tr>
                        <tbody id="myTable">
                        </tbody>
                    </table>
                     </section>
                </div> <!-- end of container -->
			</section> <!-- end of main section-->


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