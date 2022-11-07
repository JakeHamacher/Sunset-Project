<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prediction</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php
        # Get location from user
        $location = $_POST['location'];

        $cloudimportance = 1;
        $visivilityimportance = 0.75;
        $humidityimportance = 0.5;
        $windimportance = 0.5;

        # Get data from WeatherStack API for location
        $queryString = http_build_query([
          'access_key' => '3d06701050a2d0a4564c9340af650675',
          'query' => $location,
          'units' => 'f',
        ]);   
        $ch = curl_init(sprintf('%s?%s', 'http://api.weatherstack.com/current', $queryString));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);    
        $json = curl_exec($ch);
        curl_close($ch);   
        $api_result = json_decode($json, true);
        
        # Check that I have all data needed
        /* echo "Temp: {$api_result['current']['temperature']}";
        echo "<br/>";
        echo "Date & Time: {$api_result['location']['localtime']}";
        echo "<br/>";
        echo "Clouds: {$api_result['current']['cloudcover']}";
        echo "<br/>";
        echo "Winds: {$api_result['current']['wind_speed']}";
        echo "<br/>";
        echo "Humidity: {$api_result['current']['humidity']}";
        echo "<br/>";
        echo "Visibility: {$api_result['current']['visibility']}";
        echo "<br/>"; */

        $sunset_score = 100;

        # Cloudcover
        if ($api_result['current']['cloudcover'] != 50 ) {
            $sunset_score = $sunset_score - $cloudimportance * (50 - $api_result['current']['cloudcover'] );
        }

        # Humidity
        $sunset_score = $sunset_score - $humidityimportance * ($api_result['current']['humidity'] / 2);

        # Wind
        if ($api_result['current']['wind_speed'] > 5) {
            $sunset_score = $sunset_score - $windimportance * ($api_result['current']['wind_speed'] * 2);
        }

        # Visibility
        if ($api_result['current']['visibility'] != 10) {
            $sunset_score = $sunset_score - 2 * $api_result['current']['visibility'];
        }

        if ($sunset_score < 0) {
            $sunset_score = 0;
        }
        if ($sunset_score > 100) {
            $sunset_score = 100;
        }

        #echo "$sunset_score";
        $sunset_score = $sunset_score / 10.0;

    ?>
    <br/>
    <h1>We predict a sunset score of <?php echo "$sunset_score"; ?>/10</h1>
    <h2>
        <!--1-4 (mediocre), 5-7 (average), 8-10 (great)-->
        <?php 
            if ($sunset_score >= 0 && $sunset_score <= 4) {
                $sunset_desc = "Bad";
            }
            if ($sunset_score >= 4 && $sunset_score <= 7) {
                $sunset_desc = "Average";
            }
            if ($sunset_score >= 7 && $sunset_score <= 10) {
                $sunset_desc = "Good";
            }
            echo "$sunset_desc";
        ?>
         Sunset
    </h2>
    <br/>
    <a href="index.html">Go Back</a>
</body>
</html>