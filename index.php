<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php 
        echo "Where are you?";
        $loc = <input type="text" name="location" id="">;
        $location = '';

        $queryString = http_build_query([
          'access_key' => 'YOUR_ACCESS_KEY',
          'query' => $location,
        ]);
        
        $ch = curl_init(sprintf('%s?%s', 'https://api.weatherstack.com/current', $queryString));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        $json = curl_exec($ch);
        curl_close($ch);
        
        $api_result = json_decode($json, true);
        
        echo "Current temperature in $location is {$api_result['current']['temperature']}℃", 
    ?>
</body>
</html>