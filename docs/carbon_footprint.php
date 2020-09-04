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
    $distinctSet = "SELECT Distinct(Food_product) FROM Food_emission ORDER BY Food_product ASC";
    $distinctQuery = mysqli_query($db,$distinctSet);
    $distinctArr = array();
    foreach ($distinctQuery as $row) {
        $distinctArr[] = $row;
    }

    $query = "SELECT Food_product,Stage,Emission FROM Food_emission";
    $result = mysqli_query($db,$query);

    $emparray = array();

    while($row =mysqli_fetch_assoc($result)){
        $emparray[]=$row;
    }

    //query to get data from the table "combined_data"
    $query2 = "SELECT * FROM combined_data";
    //execute query
    $result2 = mysqli_query($db, $query2);
    //create an empty array
    $data = array();
    //loop through the returned data
    foreach ($result2 as $row) {
        $data[] = $row;
    }

    //free memory associated with result
    $result -> close();
    $result2 -> close();
    //close connection
    $db -> close();
    //print json_encode($data);
    // echo json_encode($emparray);
?>

<!DOCTYPE HTML>
<!--
	Ion by TEMPLATED
	templated.co @templatedco
	Released for free under the Creative Commons Attribution 3.0 license (templated.co/license)
-->
<html>
	<head>
		<title>Carbon Footprint - Trailblazers</title>
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
						<li><a href="carbon_footprint.php" class="active-page">Carbon Footprint</a></li>
						<li><a href="nutrient.php">Nutrient</a></li>
                        <li><a href="facts.html" >Facts</a></li>
                        <li><a href="about_us.html">About Us</a></li>
					</ul>
				</nav>
			</header><br>
            <div class="breadcrumb align-center">
                <a href="index.html">Home</a>&nbsp; >&nbsp;
                <span>Carbon Footprint</span>
            </div>
		<!-- Main -->
			<div id="main" class="wrapper style1">
				<header class="major">
					<h3>Carbon Footprint</h3>
					<p>Where do emissions from food come from?</p>
				</header>
                <div class="container">
                <div class="row">
                    <div class="6u">
                        <h3>Greenhouse gas emissions<br>in the food production lifecycle</h3>
                        <p>Carbon footprint is the quantity of greenhouse gas in carbon dioxide equivalent (CO2e) which is
                            generated across the supply chain of the product. Knowing how carbon footprint is emitted across this chain helps<br>
                            you plan a nutritious diet in a sustainable way.<br><br>
                            Select the food and hover over the bar chart to see details.<!--* Negative value can be observed when the carbon dioxide
                            absorbed by the plant’s photosynthesis is more than that released by its respiration.-->
                        </p>
                        <div class="row">
                            <h4>Choose food</h4>
                                <div>
                                    <select id="Food_name1" onchange="filterFood()">
                                        <option value="Select">Select</option>
                                        <?php
                                            foreach($distinctArr as $row) {
                                            $food_name = $row['Food_product'];
                                            echo "<option value='$food_name'>$food_name</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                        </div><br /> <!-- row for CHOOSE A TYPE OF FOOD-->
                        <div class="row">
                            <h4>Choose food</h4>
                            <div>
                                <select id="Food_name2" onchange="filterFood2()">
                                    <option value="Select">Select</option>
                                    <?php
                                        foreach($distinctArr as $row) {
                                            $food_name = $row['Food_product'];
                                            echo "<option value='$food_name'>$food_name</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <ul class="actions">
                            <li><a class="button alt" onclick="validateInput()">Submit</a></li>
                        </ul> <br />

                    </div> <!-- first 6u -->
                    <script type ="text/javascript">
                        var dataset = <?php echo json_encode($emparray);?>;
                        var select_data1=dataset;
                        var select_data2=dataset;
                        var select_food1="";
                        var select_food2="";
                        var myChart;
                        var chartExist = false;

                        function filterFood() {
                            select_food1=document.getElementById("Food_name1").value;
                        }
                        function filterFood2() {
                            select_food2=document.getElementById("Food_name2").value;
                        }
                        function validateInput(){
                            if (select_food1.length > 0 && select_food2.length > 0 &&
                                document.getElementById("Food_name1").value != "Select" &&
                                document.getElementById("Food_name2").value != "Select" && chartExist === true)  {
                                myChart.destroy();
                                select_food1=document.getElementById("Food_name1").value;
                                select_food2=document.getElementById("Food_name2").value;
                                select_data1 = select_data1.filter(d => d.Food_product === select_food1);
                                select_data2 = select_data2.filter(d => d.Food_product === select_food2);
                                showfood();
                            } else if (select_food1.length > 0 && select_food2.length > 0 &&
                                document.getElementById("Food_name1").value != "Select" &&
                                document.getElementById("Food_name2").value != "Select" ) {
                                select_food1=document.getElementById("Food_name1").value;
                                select_food2=document.getElementById("Food_name2").value;
                                select_data1 = select_data1.filter(d => d.Food_product === select_food1);
                                select_data2 = select_data2.filter(d => d.Food_product === select_food2);
                                chartExist = true;
                                showfood();
                            }
                        }

                        function showfood(){
                            var chart_x1=[];
                            var chart_y1=[];
                            var chart_x2=[];
                            var chart_y2=[];

                            for(var i in select_data1){
                                chart_x1.push(select_data1[i].Stage);
                                chart_y1.push(select_data1[i].Emission);
                            }

                            for(var i in select_data2){
                                chart_x2.push(select_data2[i].Stage);
                                chart_y2.push(select_data2[i].Emission);
                            }

                            var ctx = document.getElementById('myChart').getContext('2d');
                            var config={
                                type:'bar',
                                data:{
                                    labels:chart_x1,
                                    datasets:[{
                                        label:select_food1 + " - CO2 Emissions per kg product",
                                        data:chart_y1,
                                        backgroundColor: 'rgba(0,150,136,0.7)',
                                        //hoverBackgroundColor: 'rgba(255,152,0,0.7)'
                                        },
                                        {
                                        label:select_food2 + " - CO2 Emissions per kg product",
                                        data:chart_y2,
                                        backgroundColor: 'rgba(156,39,176,0.7)',
                                        //hoverBackgroundColor: 'rgba(255,152,0,0.7)'
                                        }

                                    ]
                                },
                                options: {
                                    scales: {
                                        yAxes: [{
                                            ticks : {
                                            min: -5, //actual min is -2.1
                                            max: 40 //actual max is 39.4
                                            }
                                        }]
                                    }
                                }
                            }
                            Chart.defaults.global.defaultFontSize = 14;
                            myChart = new Chart(ctx, config);
                            chartExist = true;
                            // reset data
                            select_data1 = dataset;
                            select_data2 = dataset;
                        }
                    </script>
                     <div class="6u">
                        <div class="row">
                            <canvas id="myChart" width="100" height="80"></canvas>
                        </div>
                    </div> <!-- 2nd 6u -->
                </div> <!-- 1st row -->
                    <hr class="major" />
                    <h3>TOP FOODS HIGHEST IN SELECTED NUTRIENT</h3><br>
                <div class="row"> <!--div class for the second chart-->
                    <div class ="6u">
                        <h4>Choose nutrient&nbsp;&nbsp;&nbsp;&nbsp;</h4>
                        <div>
                            <select id="nutrient" onchange="filterNutrient()">
                                <option value="Select">Select</option>
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
                            <li><a class="button alt" onclick="validateInput2()">Submit</a></li>
                        </ul>
                    </div> <!-- end of first 6u-->
                    <div class="6u">
                        <canvas id="myChart2" width="100" height="60"></canvas>
                    </div>
                    <br><br>
                </div> <!-- end of second row-->
                    <script type ="text/javascript">
                        var original_data = <?php echo json_encode($data); ?>;
                        var food_data = original_data;
                        var data_filter = "";
                        var select_nutrient = "";
                        var myChart2;
                        var chartExist2 = false;
                        var topLimit;

                        function filterNutrient() {
                            select_nutrient = document.getElementById("nutrient").value;
                        }

                        function validateInput2() {
                            if (select_nutrient.length > 0 && document.getElementById("nutrient").value != "Select" &&
                                chartExist2 === true)  {
                                myChart2.destroy();
                                food_data = food_data.filter(d => d.nutrient === select_nutrient);

                                showFood2();
                            } else if (select_nutrient.length > 0 && document.getElementById("nutrient").value != "Select") {
                                food_data = food_data.filter(d => d.nutrient === select_nutrient);

                                showFood2();
                            }
                        }

                        function showFood2() {
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
                                chart_x.push(splitString); // get food name
                                chart_y.push(top_ten[i].value);
                            }

                            var ctx2 = document.getElementById('myChart2').getContext('2d');
                            var config2 = {
                                type: 'bar',
                                data: {
                                    labels: chart_x,
                                    tooltipText: description_x,
                                    datasets: [{
                                        label:  select_nutrient + ' per 100g of Food',
                                        data: chart_y,
                                        // bootstrap colors
                                        // https://i.pinimg.com/originals/b8/70/f6/b870f6c3cf2f275906616de26cffaa52.png
                                        backgroundColor: 'rgba(76,175,80,0.7)',
                                        //hoverBackgroundColor: 'rgba(255,152,0,0.7)'
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
                            myChart2 = new Chart(ctx2, config2);
                            chartExist2 = true;
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
                                                        <td>${fdata[i].nutrient}</td>
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
                            <th>Nutrient</th>
                            <th>Value</th>
                        </tr>
                        <tbody id="myTable">
                        </tbody>
                    </table>
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