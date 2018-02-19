<!doctype html>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <title>FEA</title>
    </head>
    <body>
        <div class="jumbotron">
            <h1>Foreign Exchange Analyzer</h1>
            <p class="lead">Compare rates to 30 days prior input date.</p>
            <div class="input-group">
                <input id="searchField" type="text" placeholder="YYYY/MM/DD" name="searchField">
                <button class="button" type="button" onclick="validateInput()">Go</button>
            </div>
            <p class="text-danger" id="errorLabel"></p>
        </div>
        <div class="container-fluid text-center">
            <span id="currencies"></span>
        </div>
        <script>
            //validate input (must be formatted as YYYY/MM/DD) and call getCurrencies to start work in backend.
            //TODO: update regex validation-string so that it is not possible to pick dates not supported by fixer.io.
            function validateInput() {
                var date_regex = /^[0-9]{4}\/(0[1-9]|1[0-2])\/(0[1-9]|[1-2][0-9]|3[0-1])$/;

                if (date_regex.test($("#searchField").val())) {
                    getCurrencies();
                    setErrorLabel("");

                } else if ($("#searchField").val() == "") {
                    document.getElementById("searchField").value = dateToday();
                    getCurrencies();
                    setErrorLabel("");

                } else {
                    setErrorLabel("Input must be formatted as follows: YYYY/MM/DD");

                }
            }
            //returns todays date with correct formatting
            function dateToday() {
                var d = new Date();
                var year = d.getFullYear();
                var month = (d.getMonth() + 1);
                var day = d.getDate();

                if (month < 10) {
                    month = "0" + month.toString();
                }

                if (day < 10) {
                    day = "0" + day.toString();
                }

                return year + "/" + month + "/" + day;
            }

            function setErrorLabel(labelText) {
                document.getElementById("errorLabel").innerHTML = labelText;
            }

            //Forward input to backend with use of AJAX. Set result to html object (<span> currencies)
            function getCurrencies() {
                loadDoc("currencyLoad.php?date=" + $("#searchField").val(), setCurrencies);
            }

            function setCurrencies(xhttp) {
                document.getElementById("currencies").innerHTML = xhttp.responseText;
            }

            //Copied AJAX function
            function loadDoc(url, cFunction) {
                var xhttp;
                xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function () {
                    if (this.readyState == 4 && this.status == 200) {
                        cFunction(this);
                    }
                };
                xhttp.open("GET", url, true);
                xhttp.send();
            }
        </script>

        <!-- Optional JavaScript -->
        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    </body>
</html>