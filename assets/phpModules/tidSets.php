<?php
    $connection	=	mysqli_connect("mysql.itn.liu.se","lego", "", "lego"); //connect to lego db

    if(!$connection){
        die("<p>MySQL error:</p> <p>" . mysqli_error($connection) . "</p>"); //Error message if connection failed
    }
        
    //query database for number how many sets were released during a year
    $orderedSets = "SELECT Year, COUNT(*) FROM sets GROUP BY Year ORDER BY Year ASC";
    $query = mysqli_query($connection, $orderedSets);

    //array for storing values fetched from database
    $setsByTime = [];

    //assign values from database into an array
    if(mysqli_num_rows($query) == 0 ) {
        print("<p>Error in reading of data...</p>");
    } else {
        while($row = mysqli_fetch_row($query)){
            $setsByTime[] = $row;
        }
    }

    //change keys for js rendering
    for($i = 0; $i < count($setsByTime) - 1; $i++){
        $setsByTime[$i]['text'] = $setsByTime[$i][0];
        $setsByTime[$i]['number'] = $setsByTime[$i][1];
        unset($setsByTime[$i][0]);
        unset($setsByTime[$i][1]);
    }
        
    //array for storing title and data, for rendering
    $legoData = [];
    $legoData['title'] = "Antal nya satser per år";
    $legoData['data'] = $setsByTime;
    $legoData['dataType'] = "sets";

    //send to js for rendering
    $setsByTimeString = json_encode($legoData);
    echo "<script>",
    "createGraph('histogram', '$setsByTimeString', '.leftGraphics');",
    "</script>"
    ;

?>