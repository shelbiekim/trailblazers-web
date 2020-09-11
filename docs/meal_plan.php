<?php
define("DB_server","localhost");
define("DB_user","root");
define("DB_password","toor33"); //toor33
define("DB_name","phpmyadmin");
function db_connect(){
    $connection = mysqli_connect(DB_server,DB_user,DB_password,DB_name);
    return $connection;
};
$db = db_connect();
$emissionData = "SELECT * FROM combined_data WHERE nutrient='sum_emission' ORDER BY food_name ASC";
$emissionQuery = mysqli_query($db,$emissionData);
$emissionArray = array();
foreach ($emissionQuery as $row) {
    $emissionArray[] = $row;
}

$calorieData = "SELECT * FROM combined_data WHERE nutrient='Energy_kcal' ORDER BY food_name ASC";
$calorieQuery = mysqli_query($db,$calorieData);
$calorieArray = array();
foreach ($calorieQuery as $row) {
    $calorieArray[] = $row;
}

$sql="SELECT DISTINCT(food_group) FROM combined_data ORDER BY food_group ASC";
$result=mysqli_query($db,$sql);


?>

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
    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function(){
           $("#food_group").change(function(){
              var group = $(this).val();
              $.ajax({
                 url:"action.php",
                 method:"POST",
                 data:{foodGroup: group},
                  success:function(data){
                     $("#food_name").html(data);
                  }
              });
           });
        });

    </script>
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
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">

</head>
<body id="top" class="bg-info">

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
</header>
<!-- Banner -->
<div class="mealBanner">
    <div class="breadcrumb align-center">
        <br>
        <a href="index.html" style="color:#ffffff;">Home</a>&nbsp; >&nbsp;
        <span>Meal Planning</span>
    </div>
    <br><br><br><br>
        <header class="major">
            <h3>Meal Planning</h3>
            <p>Eat healthy with eco-friendly meals</p>
        </header>
</div><br>

    <div class="container">
        <div class="row">
                <div class="12u align-center">
                    <h3>Let's calculate your carbon footprint</h3>
                    <p>Add and calculate carbon footprint of ingredients in your recipe.</p><br>
                </div>
                    <div class="5u">
                        <div id="form-group" class="form-group">
                                <label for="food_group">INGREDIENT GROUP</label>
                                <select name ="food_group" id="food_group" onchange=onSelected("groupValidationError")>
                                    <option value="" disabled selected>Select</option>
                                    <?php
                                        while($row=mysqli_fetch_array($result)){
                                    ?>
                                    <option value="<?= $row['food_group']; ?>"><?= $row['food_group']; ?></option>
                                    <?php } ?>
                                </select>
                                <div class="validation-error" style="visibility:hidden;" id="groupValidationError">This field is required</div>

                                <label for="food_name">INGREDIENT</label>
                                <select name ="food_name" id="food_name" onchange=onSelected("ingredientValidationError")>
                                    <option value="" disabled selected>Select</option>
                                </select>
                                <div class="validation-error" style="visibility:hidden;" id="ingredientValidationError">This field is required</div>

                                <label for="amount">AMOUNT</label>
                                <!--validation for user input to be only numeric-->
                                <input id="amountInput" placeholder="Enter weight" type="text"  maxlength="3" onkeypress="isInputNumber(event);">

                                <div class="tooltip"><i id="info" class="fa fa-info-circle" data-toggle="tooltip"></i>
                                    <span class="tooltiptext">Max. 999</span>
                                </div>

                                <script>
                                    function isInputNumber(evt){
                                        onSelected("amountValidationError");
                                        var ch = String.fromCharCode(evt.which);
                                        if(!(/[0-9]/.test(ch))) {
                                            evt.preventDefault();
                                        }
                                    }
                                </script>
                                <div class="validation-error" style="visibility:hidden;" id="amountValidationError">Please enter between 1 and 999</div>

                                <label for="unit">UNIT</label>
                                <select name ="unit" id="unit" onchange=onSelected("unitValidationError")>
                                    <option value="" disabled selected>Select</option>
                                    <option value="g">g</option>
                                    <option value="kg">kg</option>
                                </select>
                                <div class="tooltip"><i id="info" class="fa fa-info-circle" data-toggle="tooltip"></i>
                                    <span class="tooltiptext">g (gram)<br>kg (kilogram)</span>
                                </div>
                                <div class="validation-error" style="visibility:hidden;" id="unitValidationError">This field is required</div>
                            <br>

                            <ul class="actions">
                                <li><a id="add_button" class="button alt add" onclick="add()">ADD</a></li>
                                <li><a id="save_button" class="button alt save" style="visibility:hidden;" onclick="save()">SAVE</a></li>
                            </ul>

                            <script type="text/javascript">
                                var ingGroup = "";
                                var ingredient = "";
                                var amount = "";
                                var unit = "";
                                var metric = "";
                                var emissionValue = "";
                                var finalValue = "";
                                var calorie = "";
                                var finalCalorie = "";
                                var appear = false;
                                var valid = false;
                                var isValid = true;
                                var rowExists = false;
                                var emissionData = <?php echo json_encode($emissionArray);?>;
                                var calorieData =  <?php echo json_encode($calorieArray);?>;
                                var selectedRow = null;


                                function onSelected(id){
                                    document.getElementById(id).style.visibility ="hidden";
                                }

                                function add(){
                                    valid = validateInput();
                                    if (valid === true) {
                                        addIngredient();
                                        insertRecord(ingGroup, ingredient, amount, metric, finalValue, finalCalorie);
                                        valid = false;
                                        resetForm();
                                        selectedRow = null;
                                        rowExists = true;
                                        checkRow();
                                    }

                                }

                                function checkRow () {
                                    if (rowExists)
                                    {
                                        document.getElementById("calculate_button").style.visibility = "visible";

                                    }
                                    else {
                                        document.getElementById("calculate_button").style.visibility = "hidden";
                                        document.getElementById("total_result").style.visibility = "hidden";
                                    }
                                }

                                function validateInput() {
                                    ingGroup = document.getElementById("food_group").value;
                                    ingredient = document.getElementById("food_name").value;
                                    amount = parseInt(document.getElementById("amountInput").value);
                                    unit = document.getElementById("unit").value;

                                    // validate and show error message
                                    if (ingGroup==""){
                                        isValid=false;
                                        document.getElementById("groupValidationError").style.visibility ="visible";
                                    } else if (ingredient==""){
                                        isValid=false;
                                        document.getElementById("ingredientValidationError").style.visibility ="visible";
                                    } else if (!(amount>0)) {
                                        isValid=false;
                                        document.getElementById("amountValidationError").style.visibility ="visible";
                                    } else if (unit=="") {
                                        isValid=false;
                                        document.getElementById("unitValidationError").style.visibility ="visible";
                                    } else  {
                                        isValid = true;
                                        if (document.getElementById("groupValidationError").style.visibility === "visible")
                                            document.getElementById("groupValidationError").style.visibility ="hidden";
                                        else if (document.getElementById("ingredientValidationError").style.visibility === "visible")
                                            document.getElementById("ingredientValidationError").style.visibility ="hidden";
                                        else if (document.getElementById("amountValidationError").style.visibility === "visible")
                                            document.getElementById("amountValidationError").style.visibility ="hidden";
                                        else if (document.getElementById("unitValidationError").style.visibility === "visible")
                                            document.getElementById("unitValidationError").style.visibility ="hidden";
                                    }
                                    return isValid;
                                }


                                function addIngredient() {
                                    for(var i=0; i<emissionData.length;i++) {
                                        //console.log(dataset[i]);
                                        if(ingredient === emissionData[i].food_name) {
                                            emissionValue = emissionData[i].value;
                                        } else {continue;}
                                    }

                                    for(var i=0; i<calorieData.length;i++) {
                                        //console.log(dataset[i]);
                                        if(ingredient === calorieData[i].food_name) {
                                            calorie = calorieData[i].value;
                                        } else {continue;}
                                    }

                                    if (unit === "g") {
                                        finalValue = amount / 100 * emissionValue;
                                        finalValue = Number(finalValue).toFixed(2);
                                        finalValue += " kg";
                                        finalCalorie = amount / 100 * calorie;
                                        finalCalorie = Number(finalCalorie).toFixed(2);
                                        //amount = amount/1000;
                                        metric = "g";
                                        finalCalorie += " kcal";
                                    } else {
                                        finalValue = amount * emissionValue * 10;
                                        finalValue = Number(finalValue).toFixed(2);
                                        finalValue += " kg";
                                        finalCalorie = amount * calorie * 10;
                                        finalCalorie = Number(finalCalorie).toFixed(2);
                                        metric = "kg";
                                        finalCalorie += " kcal";
                                    }

                                }

                                function save(){
                                    valid = validateInput();
                                    if (valid === true) {
                                        addIngredient();
                                        selectedRow.cells[0].innerHTML = ingGroup;
                                        selectedRow.cells[1].innerHTML = ingredient;
                                        selectedRow.cells[2].innerHTML = amount + metric;
                                        selectedRow.cells[3].innerHTML = finalValue;
                                        selectedRow.cells[4].innerHTML = finalCalorie;
                                        valid = false;
                                        resetForm();
                                        selectedRow = null;
                                        document.getElementById("save_button").style.visibility = "hidden";
                                    }
                                }


                                function insertRecord(grp,ing,amt,met,val,cal){
                                    // get the table by id, create a new rows and cell and set values into cell
                                    var table = document.getElementById("table").getElementsByTagName('tbody')[0];
                                    var newRow = table.insertRow(table.length);
                                    cell1 = newRow.insertCell(0);
                                    cell1.innerHTML = grp; //ingredient
                                    cell2 = newRow.insertCell(1);
                                    cell2.innerHTML = ing; //ingredient
                                    cell3 = newRow.insertCell(2);
                                    cell3.innerHTML = amt + met; //amount
                                    cell4 = newRow.insertCell(3);
                                    cell4.innerHTML = val; //value
                                    cell5 = newRow.insertCell(4);
                                    cell5.innerHTML = cal; //calories
                                    cell6 = newRow.insertCell(5);
                                    cell6.innerHTML = '<a onClick="onEdit(this)">Edit</a>&nbsp;&nbsp;' +
                                        '<a onClick="onDelete(this)">Delete</a>';
                                }

                                // when Edit is clicked on
                                function onEdit(td) {
                                    appear=true;
                                    show_hide();
                                    selectedRow = td.parentElement.parentElement; //return corresponding row

                                    document.getElementById("food_group").value = selectedRow.cells[0].innerHTML;
                                    // force an onchange event
                                    $("#food_group").trigger("change");


                                    document.getElementById("food_name").value = selectedRow.cells[1].innerHTML;
                                    //document.getElementById("food_name").setAttribute('value',selectedRow.cells[1].innerHTML);

                                    var cellAmount = selectedRow.cells[2].innerHTML;
                                    var returnAmount = "";
                                    var returnUnit = "";
                                    for (var i = 0; i < cellAmount.length; i++){
                                        if (cellAmount.charAt(i) != "k" && cellAmount.charAt(i) != "g") {
                                            returnAmount += cellAmount.charAt(i);
                                        }
                                        else {
                                            if (cellAmount.charAt(i) == "k") {
                                                returnUnit = "kg";
                                                break;
                                            } else {
                                                returnUnit="g";
                                                break;
                                            }
                                        }
                                    }
                                    document.getElementById("amountInput").value = returnAmount;
                                    document.getElementById("unit").value = returnUnit;
                                }

                                // delete row
                                function onDelete(td){
                                    row = td.parentElement.parentElement;
                                    document.getElementById("table").deleteRow(row.rowIndex);
                                    resetForm();
                                    var x = document.getElementById("table").rows.length;
                                    if (x < 2) {
                                        rowExists = false;
                                    }
                                    checkRow();
                                }
                                // calculate greenhouse gases and calories
                                function calculate(){
                                    var greenHouse = "";
                                    var kcal = "";
                                    var myTable = document.getElementById("table"), sumGas = 0, sumCal = 0;
                                    for (var i = 1; i < myTable.rows.length; i++) {
                                        console.log(i);

                                        for (var j = 0; j < myTable.rows[i].cells[3].innerHTML.length; j++){
                                            if (myTable.rows[i].cells[3].innerHTML.charAt(j) != "k") {
                                                greenHouse += myTable.rows[i].cells[3].innerHTML.charAt(j);
                                            }
                                            else break;
                                        }

                                        sumGas += parseFloat(greenHouse);
                                        greenHouse = "";

                                        for (var j = 0; j < myTable.rows[i].cells[4].innerHTML.length; j++){
                                            if (myTable.rows[i].cells[4].innerHTML.charAt(j) != "k") {
                                                kcal += myTable.rows[i].cells[4].innerHTML.charAt(j);
                                            }
                                            else break;
                                        }

                                        sumCal += parseFloat(kcal);
                                        kcal = "";
                                    }

                                    sumGas = Number(sumGas).toFixed(2);
                                    sumCal = Number(sumCal).toFixed(2);
                                    console.log(sumGas + " " + sumCal);
                                    document.getElementById("carbon_footprint").innerHTML = sumGas + " kg of greenhouse gases (CO2 equivalents)";
                                    document.getElementById("total_calories").innerHTML = sumCal + " kcal";
                                    document.getElementById("total_result").style.visibility="visible";
                                }

                                function show_hide() {
                                    if (appear === true){
                                        document.getElementById("save_button").style.visibility ="visible";
                                    }
                                }

                                // reset ingredient form
                                function resetForm() {
                                    document.getElementById("food_group").value = "";
                                    document.getElementById("food_name").value = "";
                                    document.getElementById("amountInput").value = "";
                                    document.getElementById("unit").value = "";
                                    selectedRow = null;
                                }

                            </script>
                        </div> <!--div form-group-->
                    </div> <!--6u-->
                    <div class="7u">
                        <table class="list" id="table">
                            <tr>
                                <th>Ingredient Group</th>
                                <th>Ingredient</th>
                                <th>Amount</th>
                                <th>Greenhouse Gases
                                    <div class="tooltip"><i id="info" class="fa fa-info-circle" data-toggle="tooltip"></i>
                                        <span class="tooltiptext">Greenhouse gases emitted by producing a kilogram of ingredient</span>
                                    </div>
                                </th>
                                <th>Calories
                                    <div class="tooltip"><i id="info" class="fa fa-info-circle" data-toggle="tooltip"></i>
                                        <span class="tooltiptext">1 kcal is the energy required to raise the temperature of 1kg of water by 1°C.</span>
                                    </div>
                                </th>

                                <th>Action</th>
                            </tr>
                            <tbody id="myTable">
                            </tbody>
                        </table>
                        <div class="align-right">
                            <ul class="actions">
                                <li><a id="calculate_button" class="button alt calculate" style="visibility:hidden;" onclick="calculate()">CALCULATE</a></li>
                            </ul>
                        </div>
                        <div class="result align-left" id="total_result" style="visibility:hidden;" >
                            <h4>Total Carbon Footprint :&nbsp;</h4><h4 id="carbon_footprint"></h4>
                            <h4>Total Calories of Your Recipe :&nbsp;</h4><h4 id="total_calories"></h4>
                        </div>
                    </div>

        </div><br> <!-- row-->

        <hr class="major" />
        <h3 class="align-center"> How much calories should you eat per day? </h3>
        <p class="align-center">Find it out by entering your information.</p>
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