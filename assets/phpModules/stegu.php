<?php
    //Summera antalet satser och bitar som Stefan har i sin samling
    $connection = mysqli_connect("mysql.itn.liu.se","lego","","lego"); //Connect to Lego database
    if(mysqli_error($connection)) {
        die("<p>MySQL error:</p> <p>" . mysqli_error($connection) . "</p>"); //Error message if connection failed
    }
                                
    $querySets = "SELECT SUM(collection.Quantity) FROM collection";
    $queryPieces = "SELECT SUM(collection.Quantity*inventory.Quantity) FROM collection, inventory WHERE collection.SetID=inventory.SetID";
    $resultS = mysqli_query($connection, $querySets);
    $resultP = mysqli_query($connection, $queryPieces);

    if(mysqli_num_rows($resultP) == 0 || mysqli_num_rows($resultS) == 0 ) {
        print("<p>Error in reading of data...</p>");
    } else {
        while($row = mysqli_fetch_array($resultS)) {
            $steguSets = $row[0];
            //print("<p> Antal Set: $steguSets </p>");
        }
        while($row2 = mysqli_fetch_array($resultP)) {
            $steguPieces = $row2[0];
            //print("<p> Antal bitar totalt: $steguPieces </p>");
        }

    }

    // Number of sets in total
    $setID = "SELECT COUNT(*) FROM sets";
    $setQuery = mysqli_query($connection, $setID);
    if(mysqli_num_rows($setQuery) == 0 ) {
        print("<p>Error in reading of data...</p>");
    } else {
        $numbOfSets = mysqli_fetch_array($setQuery);
    }

    // Number of pieces in total
    $legoID = "SELECT SUM(inventory.Quantity) FROM inventory WHERE ItemtypeID='P'";
    $legoQuery = mysqli_query($connection, $legoID);
    if(mysqli_num_rows($legoQuery) == 0 ) {
        print("<p>Error in reading of data...</p>");
    } else {
        $numbOfPieces = mysqli_fetch_array($legoQuery);
    }

    // Store the results in an array with objects
    $data = [];
    $data['title'] = 'Stefans samling, set';
    $data['data'][0]['text'] = ' = Andelen satser som Stefan äger';
    $data['data'][0]['number'] = $steguSets;
    $data['data'][1]['text'] = ' = Andelen satser som Stefan saknar';
    $data['data'][1]['number'] = $numbOfSets[0] - $steguSets;
    $totalsResultString = addslashes(json_encode($data));

    $data2 = [];
    $data2['title'] = 'Stefans samling, bitar';
    $data2['data'][0]['text'] = '= Andelen bitar som Stefan äger';
    $data2['data'][0]['number'] = $steguPieces;
    $data2['data'][1]['text'] = '= Andelen bitar som Stefan saknar';
    $data2['data'][1]['number'] = $numbOfPieces[0] - $steguPieces;
    $totalsResultString2 = addslashes(json_encode($data2));

    // Send the results to javascript for rendering
    echo "<script>",
    "createGraph('pieChart','$totalsResultString', '.rightGraphics');",
    "</script>"
    ;
    
    echo "<script>",
    "createGraph('pieChart','$totalsResultString2', '.rightGraphics');",
    "</script>"
    ;

    mysqli_close($connection);
?>