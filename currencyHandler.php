<?php

class currencyHandler {

    //Argument: a  date formated as YYYY/MM/DD.
    //Method uses all other methods in class currencyHandler to do the complete calculation.
    public function doWork($inputDate) {
        //covert date string so that it matches input format of fixer.io
        $date = $this->formatDate($inputDate);

        //get rates of input date.
        $ratesOfInputDate = $this->getRates($date);

        //get rates thirty days before input date
        $ratesTDB = $this->getRates($this->getEarlierDate($date));

        //get variation between input date and 30 days before
        $rateVariation = $this->getRateVariaton($ratesTDB, $ratesOfInputDate);

        //build and print table of data
        $result = $this->buildTable($rateVariation);
    }

    //print array where $key represents currency and $value represents rate differences between two dates.
    public function buildTable($ratesArray) {
        foreach ($ratesArray as $key => $value) {
            if ((int) $value >= 1) {
                print("\033[32m" . $key . ": " . $value . "\033[0m\r\n");
            } else if ((int) $value <= -1) {
                print("\033[31m" . $key . ": " . $value . "\033[0m\r\n");
            } else {
                print $key . ": " . $value . "\r\n";
            }
        }
    }

    //Method makes sure the command is typed correct, either a date formatted as 
    //YYYY/MM/DD or no input at all to return rate varitation of current date
    //Only accept dates between 2000-02-01 - current year. 
    //All months and days are accepted for current year, this could be fixed easily.
    public function checkCommand($command) {
        if ($command == "") {
            $date = date("Y") . "/" . date("m") . "/" . date("d");
            $this->doWork($date);
        } else {
            $parts = array(substr($command, 0, 4), substr($command, 4, 1),
                substr($command, 5, 2), substr($command, 7, 1), substr($command, 8, 2));
            $year = (int) $parts[0];
            $month = (int) $parts[2];
            $day = (int) $parts[4];

            if ($parts[1] == "/" && $parts[3] == "/" &&
                    $year > 1999 && $year <= date("Y") &&
                    $month > 0 && $month <= 12 &&
                    $day > 0 && $day <= 31 &&
                    !($year == 2000 && $month == 1 ||
                    $month == 2 && $day > 28 ||
                    $month == 4 && $day > 30 ||
                    $month == 6 && $day > 30 ||
                    $month == 9 && $day > 30 ||
                    $month == 11 && $day > 30)) {

                $date = $parts[0] . "-" . $parts[2] . "-" . $parts[4];

                $this->doWork($command);
            } else {
                print "Command not found.";
            }
        }
    }
    // format date string, swap all "/" to "-".
    public function formatDate($dateString) {
        $pieces = explode("/", $dateString);
        $formattedDate = $pieces[0] . "-" . $pieces[1] . "-" . $pieces[2];

        return $formattedDate;
    }

    //Method takes two arrays with rates as arguments. 
    //$firstRates: rates from earliest date. $secondRates: rates from input date
    //Method returns an array with rate variation of two arrays
    public function getRateVariaton($firstRates, $secondRates) {
        $rateVariation = array();

        foreach ($firstRates as $key => $val) {
            $rateVariation[$key] = round((($secondRates->$key / $val) - 1) * 100, 2);
        }
        arsort($rateVariation);

        return $rateVariation;
    }

    //get rates from specific date. Argument: "YYYY-MM-DD".
    public function getRates($date) {
        $json = file_get_contents("http://api.fixer.io/$date");
        $info = json_decode($json);
        $rates = $info->rates;

        return $rates;
    }

    //takes a date (String) as argument, returns date (String) 30 days prior the input date. Argument: "YYYY-MM-DD".
    public function getEarlierDate($date) {

        return date_create($date)->modify('-30 day')->format('Y-m-d');
    }

}
?>

