# Project Name
> Eat healthy, live better!

## Table of contents
* [Project overview](#project-overview)
* [Technologies](#technologies)
* [Setup](#setup)
* [Description](#description)
* [Main Functions](#main-functions)
* [Features](#features)
* [Status](#status)

## Project overview
“Eat healthy, live better” is a web application which is designed for young Australian adults (ages 18-25 years) to reduce their personal carbon footprints through food choices. The idea of the project is to slow down global warming by improving the awareness of young Australian adults. Food production takes up a quarter of global greenhouse gas emissions. By calculating the daily meal’s carbon footprint and selecting carbon-deficient recipes, users can make a difference in the environment while developing good health. 

Australia is known to be the second-largest per capita consumption of meat and dairy in the world. Although there are young adults across Australia who want to expand their knowledge around the food carbon footprint and develop nutritious eating habits, they are facing difficulties in finding integrated information. 

Url: [greendiets.tk](#https://greendiets.tk/)

## Technologies
* PHP - version 7.4.10 
* MySQL - version 8.0.21

## Setup
Please refer to the Getting Started section in the Maintenance Document.

## Description 
* __index.html__: this is the Home page where the end users will land on after entering "greendiets.tk" in the Internet Browser. Here, users can take different actions to browse our features. 
  * JavaScript Libraries used: html5shiv.js, jquery.min.js, skel.min.js, skel-layers.min.js, init.js
* __carbon_footprint.php__: this is the What's Your Footprint page where users can find out their current carbon footprint by entering what they normally have for a day. 
  * JavaScript Libraries used: html5shiv.js, jquery.min.js, bootstrap.min.js, bootstrap-select.min.js, skel.min.js, skel-layers.min.js, init.js, Chart.min.js
* __meal_plan.php__: this is the Meal Planning page where users can build their own recipes adding food.
  * JavaScript Libraries used: html5shiv.js, jquery.min.js, bootstrap.min.js, bootstrap-select.min.js, skel.min.js, skel-layers.min.js, init.js, savy.min.js, bootstrapValidator.min.js
* __recipes.php__: this is the Recipes page where users can check out our recommended vegetarian recipes - all of which are low in carbon footprint.
  * JavaScript Libraries used: html5shiv.js, jquery.min.js, bootstrap.min.js, bootstrap-select.min.js, skel.min.js, skel-layers.min.js, init.js, savy.min.js, bootstrapValidator.min.js
  * 50 recipes php files available in /trailblazers-web/docs - each recipe is named as the recipe name and stored as a php file. 
* __facts.html__: this is the Facts page where users can get to know more about carbon footprint and test their knowledge with the quiz. 
  * JavaScript Libraries used: html5shiv.js, jquery.min.js, skel.min.js, skel-layers.min.js, init.js, jquery.quiz-min.js

## Main Functions
* __carbon_footprint.php__
  * validateInput(): validate user input and fetch data value based on the selected food item
  * showFood(): display a bar chart based on selected food items
* __meal_plan.php__
  * openDiv(), save(), load(): use localStorage for sidebar user profile
  * addIngredient(): calculate and return greenhouse gas emissions and calories based on user input
  * checkNutrientData(): fetch values of nutrients and return them
  * show_footprint(): display the tree image with dynamic calculation of how many tree are required to offset user's carbon footprint
  * calculate_calories(): calculate bmr and required calories based on user profile
  * calculate_nutrient(): calculate daily nutrient recommendation based on user profile
* __recipes.php__
  * openDiv(), save(), load(): use localStorage for sidebar user profile
  * searchFunction(): search and dislay based on user input on the search bar
  * calculate_calories(): calculate bmr and required calories based on user profile
  * calculate_nutrient(): calculate daily nutrient recommendation based on user profile
* __50 recipes php files__
  * openDiv(), save(), load(): use localStorage for sidebar user profile
  * calculate_calories(): calculate bmr and required calories based on user profile
  * calculate_nutrient(): calculate daily nutrient recommendation based on user profile
  * converTime(): convert time related to cooking and display time in the correct format
  * findRecipe(): fetch values of serving size and time related to cooking and display them in the correct format
  * findEmission(): fetch the amount of carbon emissions of the recipe and display them in the correct format
  * findNutrition(): fetch values of calories, carbohydrates, fat, and protein of the recipe and display them in the correct format
  * getRecipeNutrient(): calculate nutrient amount based on the serving size the user enters
  * findIngredients(): fetch required ingredients of the recipe and display them in the correct format
  * findInstructions(): fetch instructions of the recipe and display them in the correct format

## Features
List of pages and respective features
* __What’s Your Footprint?__ - Find out your present carbon footprint 
* __Meal Planning (Step 1)__ - Determine your daily energy requirements through our BMR Calculator 
* __Meal Planning (Step 2)__ - Build your personalised meal plans by using our Footprint Calculator feature to find out how many trees are required to offset your meal's carbon footprint
* __Recipes__ - Discover healthy, vegetarian, carbon-deficient recipes 
* __Facts__ - Allows users to test their knowledge around the topic “Carbon footprints” 

## Status
Project is finished.
