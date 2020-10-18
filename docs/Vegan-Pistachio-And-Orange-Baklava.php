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

$calorieData = "SELECT * FROM combined_data WHERE nutrient='Energy_kcal' ORDER BY food_name ASC";
$calorieQuery = mysqli_query($db,$calorieData);
$calorieArray = array();
foreach ($calorieQuery as $row) {
    $calorieArray[] = $row;
}

$nutrientData = "SELECT * FROM combined_data WHERE nutrient!='Energy_kcal' and nutrient!= 'sum_emission' ORDER BY food_name ASC";
$nutrientQuery = mysqli_query($db,$nutrientData);
$nutrientArray = array();
foreach ($nutrientQuery as $row) {
    $nutrientArray[] = $row;
}

$recommendData = "SELECT * FROM nutrient_recommender WHERE type='Normal' ORDER BY gender ASC";
$recommendQuery = mysqli_query($db,$recommendData);
$recommendArray = array();
foreach ($recommendQuery as $row) {
    $recommendArray[] = $row;
}

$recipeData = "SELECT * FROM recipes ORDER BY number ASC";
$recipeQuery = mysqli_query($db,$recipeData);
$recipeArray = array();
foreach ($recipeQuery as $row) {
    $recipeArray[] = $row;
}

function fill_select_box(){
    $db = db_connect();
    $sql="SELECT DISTINCT(food_group) FROM combined_data ORDER BY food_group ASC";
    $result=mysqli_query($db,$sql);
    $output='';
    while($row=mysqli_fetch_array($result)){
        $output .='<option value="'.$row["food_group"].'">'.$row["food_group"].'</option>';
    }
    echo $output;
}

?>

<!--
	Ion by TEMPLATED
	templated.co @templatedco
	Released for free under the Creative Commons Attribution 3.0 license (templated.co/license)
-->
<!DOCTYPE HTML>
<html>
<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <title>Recipes - Trailblazers</title>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <!--[if lte IE 8]><script src="js/html5shiv.js"></script><![endif]-->
    <!-- jQuery library -->
    <script src="js/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>
    <script src="js/skel.min.js"></script>
    <script src="js/skel-layers.min.js"></script>
    <script src="js/init.js"></script>
    <script src="js/Chart.min.js"></script>
    <!-- autosave plugin -->
    <script src="js/savy.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.bootstrapvalidator/0.5.3/js/bootstrapValidator.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
    <script type="text/javascript">
        $(document).ready(function(){
            var count = 0;
            var bmr;
            var nutriArray;
            var recipeDict = {};
            var pbEnergy = 0;
            var pbCarbs = 0;
            var pbFat = 0;
            var pbProtein = 0;

            // initialise pb first
            class ProgressBar {
                constructor(element, initialValue=0) {
                    this.valueElem = element.querySelector('.progress-bar-value');
                    this.fillElem = element.querySelector('.progress-bar-fill');

                    this.setValue(initialValue);
                }
                setValue (newValue) {
                    if (newValue < 0) {
                        newValue = 0;
                    }

                    this.value=newValue;
                    this.update();
                }
                update () {
                    const percentage = this.value + "%";
                    if (this.value > 100) {
                        this.fillElem.style.width = "100%";
                        this.valueElem.textContent = percentage;
                    } else {
                        this.fillElem.style.width = percentage;
                        this.valueElem.textContent = percentage;
                    }

                }
            }

            const pb1 = new ProgressBar(document.querySelector('.progress-bar-energy'), 0);
            const pb2 = new ProgressBar(document.querySelector('.progress-bar-carb'), 0);
            const pb3 = new ProgressBar(document.querySelector('.progress-bar-fat'), 0);
            const pb4 = new ProgressBar(document.querySelector('.progress-bar-protein'), 0);


            //use localStorage - openDiv(), save(), load() - for sidebar profile
            function openDiv() {
                var profile = document.getElementById("total_result2");
                var stepOne = document.getElementById("bmr_calculator_form")
                if(profile.style.display === "none"){
                    stepOne.style.display = "none";
                    profile.style.display = "block";
                    document.getElementById("result_bmr").innerHTML = localStorage.getItem('resultBmr');
                    document.getElementById("bar_bmr").innerHTML = localStorage.getItem('barBmr');
                    document.getElementById("bar_carb_bmr").innerHTML = localStorage.getItem('barCarbBmr');
                    document.getElementById("bar_fat_bmr").innerHTML = localStorage.getItem('barFatBmr');
                    document.getElementById("bar_protein_bmr").innerHTML = localStorage.getItem('barProteinBmr');
                    document.getElementById("bar_calories").innerHTML = localStorage.getItem('barCalories');
                    document.getElementById("bar_carb").innerHTML = localStorage.getItem('barCarb');
                    document.getElementById("bar_fat").innerHTML = localStorage.getItem('barFat');
                    document.getElementById("bar_protein").innerHTML = localStorage.getItem('barProtein');
                    pb1.setValue(parseFloat(localStorage.getItem('pbEnergy')));
                    pb2.setValue(parseFloat(localStorage.getItem('pbCarbs')));
                    pb3.setValue(parseFloat(localStorage.getItem('pbFat')));
                    pb4.setValue(parseFloat(localStorage.getItem('pbProtein')));
                    bmr = localStorage.getItem('bmr');
                    nutriArray = JSON.parse(localStorage.getItem('nutriArray'));
                    //console.log(nutriArray)
                    recipeDict = JSON.parse(localStorage.getItem('recipeDict'));
                    $('#recipe_list').append(localStorage.getItem('recipeList'));
                }
            }

            function save() {
                openDiv();
                var saveDiv = document.getElementById("total_result2");
                if (saveDiv.style.display === "block") {
                    localStorage.setItem("isVisible", true);
                    localStorage.resultBmr = document.getElementById("result_bmr").innerHTML; //Male 22yrs 80kg Light Activity
                    localStorage.barBmr = document.getElementById("bar_bmr").innerHTML; // user required calories (2502.59 kcal)
                    localStorage.barCarbBmr = document.getElementById("bar_carb_bmr").innerHTML; // user required carb (450.45 kcal)
                    localStorage.barFatBmr = document.getElementById("bar_fat_bmr").innerHTML; // user required fat (20.8 kcal)
                    localStorage.barProteinBmr = document.getElementById("bar_protein_bmr").innerHTML; // user required protein (83.2 kcal)
                    localStorage.barCalories = document.getElementById("bar_calories").innerHTML; //0 kcal
                    localStorage.barCarb = document.getElementById("bar_carb").innerHTML; // 0g
                    localStorage.barFat = document.getElementById("bar_fat").innerHTML; // 0g
                    localStorage.barProtein = document.getElementById("bar_protein").innerHTML; // 0g
                    localStorage.setItem("pbEnergy", pbEnergy);
                    localStorage.setItem("pbCarbs", pbCarbs);
                    localStorage.setItem("pbFat", pbFat);
                    localStorage.setItem("pbProtein", pbProtein);
                    localStorage.setItem("bmr", bmr); // 2502.50
                    localStorage.setItem("nutriArray", JSON.stringify(nutriArray)); // [required fat, required protein, x, x, x, x, required carb]
                    localStorage.setItem("recipeDict",JSON.stringify(recipeDict)); //save it to recipeDict where all the recipe names exist; {"Vegan":["4","4","18","10"]}
                }
            }

            function load() {
                var isVisible = localStorage.getItem("isVisible");
                if (isVisible == "true") { //if user has already saved the profile
                    openDiv();
                }
            }

            load();

            $(function(){
                $('.auto_save').savy('load');
            });

            $('.selectpicker').selectpicker({
                style: 'btn-default',
                size: false,
                //width: 'fit'
            });

            $('[data-toggle="tooltip"]').tooltip();
            const hamburgerBtn = document.getElementById('hamburgerBtn');
            const navBar = document.getElementById('navBar');
            hamburgerBtn.addEventListener('click', () => {
                //console.log("Button clicked");
                navBar.classList.toggle('open');
            });




            $('#bmr_calculator_form').on('submit',function(event){
                event.preventDefault();
            });


            $(function(){
                $("#calories_button").click(function(){
                    if($('#bmr_calculator_form')[0].checkValidity() === true){
                        bmr = calculate_calories();
                        nutriArray = calculate_nutrient(bmr);
                        $('#bar_calories').html(0+"&nbsp;kcal");
                        $('#bar_carb').html(0+"&nbsp;g");
                        $('#bar_fat').html(0+"&nbsp;g");
                        $('#bar_protein').html(0+"&nbsp;g");
                        save();

                    }
                });
            });

            $(function(){
                $("#return_button").click(function(){
                    $('#total_result2').css("display","none");
                    $('#bmr_calculator_form').css("display","block");
                });
            });

            findRecipe("Vegan Pistachio And Orange Baklava");
            findEmission("Vegan Pistachio And Orange Baklava");
            findNutrition("Vegan Pistachio And Orange Baklava");
            findIngredients("Vegan Pistachio And Orange Baklava");
            findInstructions("Vegan Pistachio And Orange Baklava");

            $(function(){
                $("#print_button").click(function(){
                    window.print();
                });
            });

            load();


            // Add to List button
            $('#add_button').click(function(){
                if(document.getElementById("bmr_calculator_form").style.display === "block"){
                    var text = "Please save your profile in STEP 1 first";
                    alert(text);
                } else {
                    var recipeName = document.getElementById('recipe_name').innerHTML;
                    var fileName = location.href.split("/").slice(-1);
                    //if already exists
                    if (fileName in recipeDict) {
                        alert("Recipe already exists in your list");
                    } else {
                        var recipeNutrient = getRecipeNutrient(); // calories, carbs, fat, protein
                        //console.log(recipeNutrient);

                        $('#bar_calories').html(recipeNutrient[0] + "&nbsp;kcal");
                        var user_cal = localStorage.getItem('barBmr').match(/[0-9.]+/g); // extract float from string
                        var cal_percent = Number(recipeNutrient[0] / (user_cal) * 100).toFixed(0);
                        pb1.setValue(cal_percent);

                        $('#bar_carb').html(recipeNutrient[1] + "&nbsp;g");
                        var user_carb = localStorage.getItem('barCarbBmr').match(/[0-9.]+/g); // extract float from string
                        var carb_percent = Number(recipeNutrient[1] / (user_carb) * 100).toFixed(0);
                        pb2.setValue(carb_percent);

                        $('#bar_fat').html(recipeNutrient[2] + "&nbsp;g");
                        var user_fat = localStorage.getItem('barFatBmr').match(/[0-9.]+/g); // extract float from string
                        var fat_percent = Number(recipeNutrient[2] / (user_fat) * 100).toFixed(0);
                        pb3.setValue(fat_percent);

                        $('#bar_protein').html(recipeNutrient[3] + "&nbsp;g");
                        var user_protein = localStorage.getItem('barProteinBmr').match(/[0-9.]+/g); // extract float from string
                        var protein_percent = Number(recipeNutrient[3] / (user_protein) * 100).toFixed(0);
                        pb4.setValue(protein_percent);

                        //console.log(document.getElementById('recipe_name').innerHTML)

                        //console.log(fileName);
                        var recipeServe = document.getElementById('serve_number').value;
                        var newItem ='<div class="recipeDiv" data-id='+fileName+' style="display: inline-block">' + '<a style="color: black;" href='+fileName+'><p class="recipe_div">' +recipeName+ '</p>' + '<span style="overflow: visible;margin-right: 3px;vertical-align: middle;" class="glyphicon glyphicon-list my-tooltip" title='+recipeServe+'&nbsp;Serve></span></a>'+
                            '<button id="remove_button" style="display: inline-block;vertical-align: middle;" class="btn btn-danger btn-xs delete" style="position: absolute;"><span class="glyphicon glyphicon-remove"></button>' + '</div>';
                        $('#recipe_list').append(newItem); // add to recipe list div

                        pbEnergy += parseFloat(cal_percent); // add to progress bar values
                        pbCarbs += parseFloat(carb_percent);
                        pbFat += parseFloat(fat_percent);
                        pbProtein += parseFloat(protein_percent);

                        var recipeValues = [cal_percent,carb_percent,fat_percent,protein_percent,user_cal,user_carb,user_fat,user_protein];
                        recipeDict[fileName] = recipeValues;
                        //localStorage.setItem("recipeDict",JSON.stringify(recipeDict)); //moved to save(); save it to recipeDict where all the recipe names exist; {"Vegan-":["4","4","18","10"]}
                        var updateValue = document.getElementById('recipe_list').innerHTML;
                        localStorage.setItem("recipeList",updateValue); // save the recipe list div to local storage

                        save(); // to save the wording for barCalories, barCarb, barFat, barProtein, pbEnergy/Carbs/Fat/Protein
                    }
                }
            })

            $("#recipe_list").on("click", '.delete', function() {
                var confirmation = confirm('Are you sure you want to delete this meal?');
                if (confirmation) {
                    var temp = $(this).closest("div[data-id]").attr('data-id');
                    console.log(temp);
                    var tempP1 = recipeDict[temp][0];
                    var tempP2 = recipeDict[temp][1];
                    var tempP3 = recipeDict[temp][2];
                    var tempP4 = recipeDict[temp][3];
                    var userCal = recipeDict[temp][4];
                    var userCarb = recipeDict[temp][5];
                    var userFat= recipeDict[temp][6];
                    var userProtein= recipeDict[temp][7];
                    pbEnergy -= parseFloat(tempP1);
                    pb1.setValue(pbEnergy);
                    pbCarbs -= parseFloat(tempP2);
                    pb2.setValue(pbCarbs);
                    pbFat -= parseFloat(tempP3);
                    pb3.setValue(pbFat);
                    pbProtein -= parseFloat(tempP4);
                    pb4.setValue(pbProtein);

                    delete recipeDict[temp];
                    console.log(recipeDict)

                    $(this).closest('.recipeDiv').remove();
                    var updateValue = document.getElementById('recipe_list').innerHTML;
                    localStorage.setItem("recipeList",updateValue); //update the recipe list div
                    save();
                }
            });
        });
    </script>

    <script type="text/javascript">
        var male = "";
        var female = "";
        var appear = false;
        var valid = false;
        var valid2 = false;
        var isValid = true;
        var isValid2 = true;
        var rowExists = false;
        var calorieData =  <?php echo json_encode($calorieArray);?>;
        var nutrientData =  <?php echo json_encode($nutrientArray);?>;
        var recommendData = <?php echo json_encode($recommendArray);?>;
        var selectedRow = null;
        var imgExists = false;
        var imgName = "";

        function checkNutrientData(nutrientDict){
            var carbDict = {};
            var fatDict = {};
            var proteinDict = {};
            var vitADict = {};
            var vitCDict = {};
            var vitEDict = {};
            var calciumDict = {};
            var totalNutrient = []; // Carbohydrates, Fats, Proteins, Vitamin A, Vitamin C, Vitamin E, Calcium
            // once we have a dictionary of Ingredient: amount (gram), go through nutrientData to get nutrient value
            for (var item in nutrientDict) {
                for (var k = 0; k < nutrientData.length; k++) {
                    if ((item === nutrientData[k].food_name) && (nutrientData[k].nutrient === "Carb_g")) {
                        carbDict[item] = Number(nutrientDict[item] / 100 * nutrientData[k].value).toFixed(2);
                    } else if ((item === nutrientData[k].food_name) && (nutrientData[k].nutrient === "Fat_g")) {
                        fatDict[item] =  Number(nutrientDict[item] / 100 * nutrientData[k].value).toFixed(2);
                    } else if ((item === nutrientData[k].food_name) && (nutrientData[k].nutrient === "Protein_g")) {
                        proteinDict[item] =  Number(nutrientDict[item] / 100 * nutrientData[k].value).toFixed(2);
                    } else if ((item === nutrientData[k].food_name) && (nutrientData[k].nutrient === "VitA_mcg")) {
                        vitADict[item] =  Number(nutrientDict[item] / 100 * nutrientData[k].value).toFixed(2);
                    } else if ((item === nutrientData[k].food_name) && (nutrientData[k].nutrient === "VitC_mg")) {
                        vitCDict[item] =  Number(nutrientDict[item] / 100 * nutrientData[k].value).toFixed(2);
                    } else if ((item === nutrientData[k].food_name) && (nutrientData[k].nutrient === "VitE_mg")) {
                        vitEDict[item] =  Number(nutrientDict[item] / 100 * nutrientData[k].value).toFixed(2);
                    } else if ((item === nutrientData[k].food_name) && (nutrientData[k].nutrient === "Calcium_mg")) {
                        calciumDict[item] =  Number(nutrientDict[item] / 100 * nutrientData[k].value).toFixed(2);
                    }
                }
            }
            // totalNutrient; Carbohydrates, Fats, Proteins, Vitamin A, Vitamin C, Vitamin E, Calcium
            var carbSum = 0;
            var fatSum = 0;
            var proteinSum = 0;
            var vitASum = 0;
            var vitCSum = 0;
            var vitESum = 0;
            var calciumSum = 0;
            for (var item in carbDict){ carbSum += parseFloat(carbDict[item])};
            for (var item in fatDict){ fatSum += parseFloat(fatDict[item])};
            for (var item in proteinDict){ proteinSum += parseFloat(proteinDict[item])};
            for (var item in vitADict){ vitASum += parseFloat(vitADict[item])};
            for (var item in vitCDict){ vitCSum += parseFloat(vitCDict[item])};
            for (var item in vitEDict){ vitESum += parseFloat(vitEDict[item])};
            for (var item in calciumDict){ calciumSum += parseFloat(calciumDict[item])};

            totalNutrient.push(Number(carbSum).toFixed(2), Number(fatSum).toFixed(2), Number(proteinSum).toFixed(2),
                Number(vitASum).toFixed(2), Number(vitCSum).toFixed(2),Number(vitESum).toFixed(2), Number(calciumSum).toFixed(2));

            document.getElementById("total_result").style.display="block";

            return totalNutrient;
        }

        function show_footprint(totalGas){
            //in tonnes
            document.getElementById("tree_image").style.display="block";
            document.getElementById("img_tree").style.display="block";
            imgExists = true;

        }

        function onSelected(id){
            document.getElementById(id).style.visibility ="hidden";
        }

        function checkRow () {
            if (rowExists)
            {
                document.getElementById("calculate_button").style.visibility = "visible";

            }
            else {
                document.getElementById("calculate_button").style.visibility = "hidden";
                document.getElementById("total_result").style.display = "none";
                document.getElementById("footprint_image").style.display = "none";
                document.getElementById(imgName).style.display = "none";
                imgExists = false;
            }
        }

        function calculate_calories() {
            //valid2 = validateInput2();
            //if(valid2==true) {
            var gender = "";
            var height = parseFloat(document.getElementById("height").value);
            var weight = parseFloat(document.getElementById("weight").value);
            var age = parseInt(document.getElementById("age").value);
            var activity = "";
            var bmr = "";

            activity = checkActivity();
            if (document.getElementById('male').checked) {
                gender = "Male";
                bmr = (10 * weight) + (6.25 * height) - (5 * age) + 5;
                bmr = bmr * activity;
                bmr = Number(bmr).toFixed(2);
            } else if (document.getElementById('female').checked) {
                gender = "Female";
                bmr = (10 * weight) + (6.25 * height) - (5 * age) - 161;
                bmr = bmr * activity;
                bmr = Number(bmr).toFixed(2);
            }
            document.getElementById("bmr_calculator_form").style.display = "none";
            document.getElementById("total_result2").style.display = "block";
            activity = document.getElementById("activity").value;
            document.getElementById("result_bmr").innerHTML = gender+" · "+age+"yrs"+" · "+ weight+"kg"+" · "+activity+"&nbsp;Activity";
            document.getElementById("bar_bmr").innerHTML = bmr + "&nbsp;kcal";
            return bmr;
        }

        function calculate_nutrient(bmr_value) {
            var tempArray;
            var maleArray;
            var femaleArray;
            var userAge;
            var nutriArray; // nutrient
            var bmr = bmr_value;
            if (document.getElementById('male').checked) {
                tempArray = recommendData.filter(function (x) {
                    return x.gender == "male";
                });
                userAge = document.getElementById('age').value;
                maleArray = checkAge(userAge,tempArray);
                nutriArray = checkNutrient(maleArray, bmr);
            } else if (document.getElementById('female').checked) {
                tempArray = recommendData.filter(function (x) {
                    return x.gender == "female";
                });
                userAge = parseInt(document.getElementById('age'));
                femaleArray = checkAge(userAge,tempArray);
                nutriArray = checkNutrient(femaleArray, bmr);
            }

            document.getElementById("result_nutrient").innerHTML = "Carbohydrates " + nutriArray[6] +"g, " +
                "Fats " + nutriArray[0] + "g, " + "Proteins " + nutriArray[1] + "g, " + "Vitamin A " + nutriArray[2] +"mcg, " +
                "Vitamin C " + nutriArray[3] +"mg, " + "Vitamin E " + nutriArray[4] + "mg, " +
                "Calcium " + nutriArray[5] +"mg";
            document.getElementById("bar_carb_bmr").innerHTML = nutriArray[6] + "&nbsp;g";
            document.getElementById("bar_fat_bmr").innerHTML = nutriArray[0] + "&nbsp;g";
            document.getElementById("bar_protein_bmr").innerHTML = nutriArray[1] + "&nbsp;g";
            return nutriArray;
        }

        function checkAge(userAge, userArray){
            var genderArray = userArray;

            if(userAge>0 && userAge <4) {
                genderArray = genderArray.filter(function (x) {
                    return x.age_range == "1-3";
                });
            } else if (userAge>3 && userAge <9) {
                genderArray = genderArray.filter(function (x) {
                    return x.age_range == "4-8";
                });
            } else if (userAge>8 && userAge <14) {
                genderArray = genderArray.filter(function (x) {
                    return x.age_range == "9-13";
                });
            } else if (userAge>13 && userAge <19) {
                genderArray = genderArray.filter(function (x) {
                    return x.age_range == "14-18";
                });
            } else if (userAge>18 && userAge <31) {
                genderArray = genderArray.filter(function (x) {
                    return x.age_range == "19-30";
                });
            } else if (userAge>30 && userAge <51) {
                genderArray = genderArray.filter(function (x) {
                    return x.age_range == "31-50";
                });
            } else if (userAge>50 && userAge <71) {
                genderArray = genderArray.filter(function (x) {
                    return x.age_range == "51-70";
                });
            } else if (userAge > 70) {
                genderArray = genderArray.filter(function (x) {
                    return x.age_range == ">71";
                });
            }
            return genderArray;
        }

        function checkNutrient(userArray, bmr_value){
            var nutriArray = []; // Fats, Proteins, Vitamin A, Vitamin C, Vitamin E, Calcium, Carbohydrates,
            var nutriCarbo;
            var nutriFat;
            var nutriPro;
            var nutriVA;
            var nutriVC;
            var nutriVE;
            var nutriCal;
            var bmr = bmr_value;
            for (var key in userArray){
                if (userArray[key].nutrient_type == "Fat(g)"){
                    nutriFat = userArray[key].value;
                } else if (userArray[key].nutrient_type == "Protein(g)"){
                    nutriPro = userArray[key].value;
                } else if (userArray[key].nutrient_type == "Vit_A(mcg)"){
                    nutriVA = userArray[key].value;
                } else if (userArray[key].nutrient_type == "Vit_C(mg)"){
                    nutriVC = userArray[key].value;
                } else if (userArray[key].nutrient_type == "Vit_E(mg)"){
                    nutriVE = userArray[key].value;
                } else if (userArray[key].nutrient_type == "Calcium(mg)"){
                    nutriCal = userArray[key].value;
                }
            }

            nutriArray.push(nutriFat,nutriPro, nutriVA, nutriVC, nutriVE, nutriCal);

            //check activity and multiply value
            var nutriFactor;
            if (document.getElementById("activity").value=="Sedentary"){
                nutriFactor = 1.4;
            } else if (document.getElementById("activity").value=="Light"){
                nutriFactor = 1.6;
            } else if (document.getElementById("activity").value=="Moderate") {
                nutriFactor = 1.8;
            } else if (document.getElementById("activity").value=="Very Active") {
                nutriFactor = 2;
            } else { //extra active
                nutriFactor = 2.2;
            }
            // range of carbs 45% ~ 65%
            nutriCarbo = (Number(nutriFactor*bmr*0.45/4).toFixed(2)); //https://healthyeating.sfgate.com/recommended-amount-percent-carbohydrates-per-day-7287.html

            for (var i=0; i<nutriArray.length; i++) {
                nutriArray[i] *= nutriFactor; // multiply value times 2
                nutriArray[i] = Number(nutriArray[i].toFixed(2));
            }

            // the last item of nutriArray is the range of carbs
            nutriArray.push(nutriCarbo);

            return nutriArray;
        }

        function checkActivity(){
            if (document.getElementById("activity").value=="Sedentary"){
                return 1.2;
            } else if (document.getElementById("activity").value=="Light"){
                return 1.375;
            } else if (document.getElementById("activity").value=="Moderate") {
                return 1.55;
            } else if (document.getElementById("activity").value=="Very Active") {
                return 1.725;
            } else { //extra active
                return 1.9;
            }
        }
    </script>

    <!--js specific to this file-->
    <script>
        var recipeData = <?php echo json_encode($recipeArray);?>;
        var serves;
        var prep_time;
        var cook_time;
        var total_time;
        var carbon_emissions;
        var car_km;
        var calories;
        var carbs;
        var fat;
        var protein;
        var ingredients;
        var instructions;

        // convert minutes to HOUR and MINUTES format
        function convertTime(getTime){
            var time = getTime;
            var time_hour;
            var time_min;
            if (time / 60 > 0) {
                time_hour = parseInt(time / 60);
                time_min = "";
                if (time % 60 > 0) {
                    time_min = time % 60;
                    time_min = time_min + " " + "minutes";
                }
            }
            if (time_hour == 0) {
                time = " " + time_min;
            } else {
                time = time_hour  + " " + "hour" + " " + time_min;
            }
            return time;
        }

        function findRecipe(recipe){
            for(var i=0; i<recipeData.length;i++) {
                if(recipe === recipeData[i].recipe_name) {
                    serves = recipeData[i].serving;
                    prep_time = recipeData[i].prep_time;
                    cook_time = recipeData[i].cook_time;
                    total_time = recipeData[i].total_time;
                } else {continue;}
            }

            prep_time = convertTime(prep_time);
            cook_time = convertTime(cook_time);
            total_time = convertTime(total_time);

            document.getElementById("serves").innerHTML = serves;
            document.getElementById("prep_time").innerHTML = prep_time;
            document.getElementById("cook_time").innerHTML = cook_time;
            document.getElementById("total_time").innerHTML = total_time;
        }

        function findEmission(recipe){
            for(var i=0; i<recipeData.length;i++) {
                if(recipe === recipeData[i].recipe_name) {
                    carbon_emissions = recipeData[i].carbon_emissions;
                } else {continue;}
            }
            document.getElementById("recipe_emission").innerHTML = carbon_emissions + " " + "kg CO2e";
            findCar(carbon_emissions);
        }

        function findCar(emissions){
            car_km = Number(emissions / 0.118).toFixed(2);
            document.getElementById("recipe_car").innerHTML = car_km + " " + "km";
        }

        function findNutrition(recipe){
            for(var i=0; i<recipeData.length;i++) {
                if(recipe === recipeData[i].recipe_name) {
                    calories = Number(recipeData[i].energy/serves).toFixed();
                    carbs = Number(recipeData[i].carb/serves).toFixed(2);
                    fat = Number(recipeData[i].fat/serves).toFixed(2);
                    protein = Number(recipeData[i].protein/serves).toFixed(2);
                } else {continue;}
            }
            document.getElementById("recipe_calories").innerHTML = calories + " " + "kcal";
            document.getElementById("recipe_carbs").innerHTML = carbs + " " + "g";
            document.getElementById("recipe_fat").innerHTML = fat + " " + "g";
            document.getElementById("recipe_protein").innerHTML = protein + " " + "g";
        }

        function getRecipeNutrient(){
            // divide it by serving
            var recipeNutrient = [];
            recipeNutrient[0] = calories/serves;
            recipeNutrient[1] = carbs/serves;
            recipeNutrient[2] = fat/serves;
            recipeNutrient[3] = protein/serves;
            // get serving size
            var servingSize = parseInt(document.getElementById("serve_number").value);
            for(var i=0; i < recipeNutrient.length; i++) {
                recipeNutrient[i] = recipeNutrient[i] * servingSize;
                recipeNutrient[i] = Number(recipeNutrient[i]).toFixed(2);
            }
            return recipeNutrient;
        }

        function findIngredients(recipe){
            for(var i=0; i<recipeData.length;i++) {
                if(recipe === recipeData[i].recipe_name) {
                    ingredients = recipeData[i].ingredients;
                } else {continue;}
            }
            // get rid of the brackets []
            ingredients = ingredients.replace(/[\[\]']+/g,'');
            // Split string with commas to new line
            ingredients = ingredients.split(",").join("<br />");
            document.getElementById("recipe_ingredients").innerHTML = ingredients;
        }

        function findInstructions(recipe){
            var result = "";
            var pre;
            var array;
            for(var i=0; i<recipeData.length;i++) {
                if(recipe === recipeData[i].recipe_name) {
                    instructions = recipeData[i].instructions;
                } else {continue;}
            }
            // get rid of the brackets []
            instructions = instructions.replace(/[\[\]']+/g,'');
            array = instructions.split(",");
            // Split string with commas to new line
            //instructions = instructions.split(",");

            for(var i=0; i<array.length;i++){
                pre= i + 1 + ".";
                result = result + pre + " " + array[i] + "<br />";
            }
            document.getElementById("recipe_instructions").innerHTML = result;
        }
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
<body id="top" class="meal_body">
<!-- Header -->
<header id="header" class="skel-layers-fixed">
    <h5 class="team_logo"><a href="index.html">Trailblazers</a></h5>
    <nav id="nav">
        <ul>
            <li><a href="index.html">Home</a></li>
            <li><a href="carbon_footprint.php">What's Your Footprint?</a></li>
            <li><a href="meal_plan.php">Meal Planning</a></li>
            <li><a href="recipes.php" class="active-page">Recipes</a></li>
            <li><a href="facts.html" >Facts</a></li>
        </ul>
    </nav>
</header>
<div class="breadcrumb container">
    <a href="index.html">Home</a>&nbsp; >&nbsp;
    <a href="recipes.php">Recipes</a>&nbsp; >&nbsp;
    <span>Vegan Pistachio And Orange Baklava</span>
</div>
<!-- Banner -->
<div class="container">
    <div id="hamburgerBox"></div>
    <div id="hamburgerBtn">&#9776 </div>
</div>
<br>
<div class="container">
    <nav id="navBar">
        <div class="nav-brand">
            <form method="post" id="bmr_calculator_form" style="display:block;">
                <p style="font-weight: bold">STEP 1.<br>Check your daily energy requirements</p>
                <div id="form-group2" class="form-group2">
                    <p class="bmr_form">Gender</p><br>
                    <div class="first_label" style="display: inline-block;">
                        <input class="first_label auto_save" type="radio" id="male" name="gender" checked/>
                        <label for="male" style="color:#000000;">Male</label>
                        <input class="auto_save" type="radio" id="female" name="gender" />
                        <label for="female" style="color:#000000;">Female</label>
                    </div><br>
                    <p class="bmr_form">Height</p>
                    <input class="input_height auto_save" id="height" name="height" type="number" min="1" step="0.01" placeholder="cm" required><br>

                    <p class="bmr_form">Weight</p>
                    <input class="input_weight auto_save" id="weight" name="weight" type="number" min="1" step="0.01" placeholder="kg" required>
                    <br>

                    <p class="bmr_form">Age</p>
                    <input class="input_age auto_save" id="age" name="age"  type="number" min="1" step="1" placeholder="Enter age" required>
                    <br>

                    <p class="bmr_form">Activity</p>
                    <select class="input_activity auto_save" name="activity" id="activity" required>
                        <option class="input_activity auto_save" value="">Select activity level</option>
                        <option class="input_activity auto_save" value="Sedentary">Sedentary: little to no exercise</option>
                        <option class="input_activity auto_save" value="Light">Light: exercise 1-3 times per week</option>
                        <option class="input_activity auto_save" value="Moderate">Moderate: exercise 4-5 times per week</option>
                        <option class="input_activity auto_save" value="Very Active">Very active: intense exercise 6-7 times per week </option>
                        <option class="input_activity auto_save" value="Extra Active">Extra active: very intense exercise daily</option>
                    </select>&nbsp;<span class='glyphicon glyphicon-info-sign my-tooltip'
                                         title="Exercise: 15-30 mins of elevated heart rate activity&#013;Intense: 45-120 mins of elevated heart rate activity&#013;Very intense: 2+ hrs of elevated heart rate activity"></span>
                    <!--class="btn btn-primary"-->
                    <br><br><br>
                    <input type="submit" name="submit" class="profile" id="calories_button" value="SAVE PROFILE" />
                    <hr class="major" />
                </div> <!--div form-group-->
            </form>
        </div>
        <div class="result2" id="total_result2" style="display:none;">
            <p style="display: inline-block; margin-bottom:3px;color: black;">YOUR PROFILE</p><p id="result_bmr" style="display: inline-block; margin-bottom:5px;"></p>
            <ul class="actions">
                <li><a id="return_button" class="button profile">EDIT PROFILE</a></li>
            </ul>

            <p id="result_nutrient" style="display: none"></p>

            <div>
                <p style="display: inline-block; margin-bottom: 3px;color: black;">DAILY RECOMMENDATIONS</p>
            </div>
            <div>
                <p style="display:inline-block; margin: 0;">Energy&nbsp;</p><br>
                <p id="bar_calories" style="display: inline-block; margin: 0;"></p><p style="display: inline-block; margin: 0;">&nbsp;/&nbsp;</p><p id="bar_bmr" style="display: inline-block; margin: 0;"></p>
            </div>
            <div class="progress-bar-energy">
                <div class="progress-bar-value"></div>
                <div class="progress-bar-fill"></div>
            </div>

            <div>
                <p style="display: inline-block; margin: 0;">Carbs&nbsp;</p><span class='glyphicon glyphicon-info-sign my-tooltip' title="Carbohydrates"></span><br>
                <p id="bar_carb" style="display: inline-block; margin: 0;"></p><p style="display: inline-block; margin: 0;">&nbsp;/&nbsp;</p><p id="bar_carb_bmr" style="display: inline-block; margin: 0;"></p>
            </div>
            <div class="progress-bar-carb">
                <div class="progress-bar-value"></div>
                <div class="progress-bar-fill"></div>
            </div>


            <div>
                <p style="display: inline-block; margin: 0;">Fat&nbsp;</p><br> <!--pb3-->
                <p id="bar_fat" style="display: inline-block; margin: 0;"></p><p style="display: inline-block; margin: 0;">&nbsp;/&nbsp;</p><p id="bar_fat_bmr" style="display: inline-block; margin: 0;"></p>
            </div>
            <div class="progress-bar-fat">
                <div class="progress-bar-value"></div>
                <div class="progress-bar-fill"></div>
            </div>

            <div>
                <p style="display: inline-block; margin: 0;">Protein&nbsp;</p><br> <!--pb4-->
                <p id="bar_protein" style="display: inline-block; margin: 0;"></p><p style="display: inline-block; margin: 0;">&nbsp;/&nbsp;</p><p id="bar_protein_bmr" style="display: inline-block; margin: 0;"></p>
            </div>
            <div class="progress-bar-protein">
                <div class="progress-bar-value"></div>
                <div class="progress-bar-fill"></div>
            </div>
            <br>
            <div id="recipe_list">
            </div>
        </div>
    </nav>
</div>

<div class="container align-center recipe-container" style="margin-top: 30px;">
    <div class="row">
        <div class="5u" style="margin-top: 10px;">
            <img id="vegan_pistacio" src="images/recipe/Vegan Pistachio And Orange Baklava.jpg" class="image recipe_img_main">
            <div class="align-left" style="margin-top: 5px;">
                <button id="add_button" style="text-decoration: none;vertical-align: top;" class="btn btn-primary btn-sm">
                    <span class="	glyphicon glyphicon-list"></span> Add to List
                </button>
                <input style="width: 40px; height: 30px;vertical-align: top;" class="selectpicker" type="number" value="1" min="1" step="1" id="serve_number"
                       onkeypress="return (event.charCode == 8 || event.charCode == 0) ? null : event.charCode >= 48 && event.charCode <= 57" required>
                <p style="display: inline-block;font-size: 10pt;text-transform: none;">Serve</p>
            </div>
            <div class="align-left" style="margin-top: 5px;">
                <button id="print_button" style="text-decoration: none" class="btn btn-warning btn-sm">
                    <span class="glyphicon glyphicon-print"></span> Print
                </button>
            </div>
        </div>
        <div class="7u align-left" style="margin-top: 10px;">
            <h3 id="recipe_name">Vegan Pistachio And Orange Baklava</h3>
            <img class="tree_icons" src="images/tree_icon.png" height="30"/>&nbsp;&nbsp;<span class='glyphicon glyphicon-info-sign my-tooltip' title="required to offset footprint per one serve"></span>
            <hr class="minor" />
            <p class="recipe_heading">Serves&nbsp;&nbsp;</p><p id="serves"></p><br>
            <p class="recipe_heading">Prep Time&nbsp;&nbsp;</p><p id="prep_time"></p><br>
            <p class="recipe_heading">Cook Time&nbsp;&nbsp;</p><p id="cook_time"></p><br>
            <p class="recipe_heading">Total Time&nbsp;&nbsp;</p><p id="total_time"></p>
            <hr class="minor" />
            <img src="images/foot_icon.png" width="40"/></a><p style="text-transform: none" id="recipe_emission"></p>
            <p style="text-transform: none">produced by eating this recipe per one serve</p>
            <br>
            <img src="images/car_icon.png" width="50"/></a><p style="text-transform: none" id="recipe_car"></p>
            <p style="text-transform: none">Equivalent of driving a medium petrol car per one serve</p>
            <hr class="minor" />
        </div>
    </div>
    <div class="row align-left">
        <div class="5u">
            <br>
            <p class="recipe_heading">Nutrition Per Serving&nbsp;&nbsp;</p><br>
            <p style="color: #000000; text-transform: none">Calories&nbsp;&nbsp;</p><p style="text-transform: none" id="recipe_calories"></p><br>
            <p style="color: #000000; text-transform: none">Carbs&nbsp;&nbsp;</p><p style="text-transform: none" id="recipe_carbs"></p><br>
            <p style="color: #000000; text-transform: none">Fat&nbsp;&nbsp;</p><p style="text-transform: none" id="recipe_fat"></p><br>
            <p style="color: #000000; text-transform: none">Protein&nbsp;&nbsp;</p><p style="text-transform: none" id="recipe_protein"></p><br><br>
            <p class="recipe_heading">Ingredients&nbsp;&nbsp;</p><br><p style="text-transform: none;font-weight: 300;color: #000000;line-height: 1.75em;" id="recipe_ingredients"></p><br>
        </div>
        <div class="7u">
            <br>
            <p class="recipe_heading">Instructions&nbsp;&nbsp;</p><br><p style="text-transform: none; font-weight: 300;line-height: 1.75em;padding-right:5em;" id="recipe_instructions"></p><br>
        </div>
    </div><br>

</div>


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