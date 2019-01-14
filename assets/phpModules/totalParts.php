<?php
    // Counts all sets in the catalog and total amount of different types of pieces in the catalog
    $connection = mysqli_connect("mysql.itn.liu.se","lego","","lego"); //Connect to Lego database
    if(mysqli_error($connection)) {
        die("<p>MySQL error:</p> <p>" . mysqli_error($connection) . "</p>"); //Error message if connection failed
    }

    // Number of sets in total
    $setID = "SELECT COUNT(*) FROM sets";
    $setQuery = mysqli_query($connection, $setID);
    if(mysqli_num_rows($setQuery) == 0 ) {
        print("<p>Error in reading of data...</p>");
    } else {
        $numbOfSets = mysqli_fetch_array($setQuery);
    }

    // Number of unique sets
    $uSetID = "SELECT COUNT(DISTINCT inventory.SetID) FROM inventory";
    $uSetQuery = mysqli_query($connection, $uSetID);
    if(mysqli_num_rows($uSetQuery) == 0 ) {
        print("<p>Error in reading of data...</p>");
    } else {
        $uNumbOfSets = mysqli_fetch_array($uSetQuery);
    }

    // Number of pieces in total
    $legoID = "SELECT SUM(inventory.Quantity) FROM inventory WHERE ItemtypeID='P'";
    $legoQuery = mysqli_query($connection, $legoID);
    if(mysqli_num_rows($legoQuery) == 0 ) {
        print("<p>Error in reading of data...</p>");
    } else {
        $numbOfPieces = mysqli_fetch_array($legoQuery);
    }
    
    // Number of unique pieces
    $uLegoID = "SELECT COUNT(DISTINCT inventory.ItemID) FROM inventory WHERE ItemtypeID='P'";
    $uLegoQuery = mysqli_query($connection, $uLegoID);
    if(mysqli_num_rows($uLegoQuery) == 0 ) {
        print("<p>Error in reading of data...</p>");
    } else {
        $uNumbOfPieces = mysqli_fetch_array($uLegoQuery);
    }

    // Store the results in an array with objects
    $data = [];
    $data['title'] = 'I databasen';
    $data['data'][0]['text'] = 'bitar';
    $data['data'][0]['number'] = $numbOfPieces[0];
    $data['data'][0]['img'] = './assets/img/legoSmall.svg'[0];
    $data['data'][1]['text'] = 'set';
    $data['data'][1]['number'] = $numbOfSets[0];
    $data['data'][2]['text'] = 'unika bitar';
    $data['data'][2]['number'] = $uNumbOfPieces[0];
    $data['data'][2]['img'] = './assets/img/legoSmallUnique.svg'[0];
    $data['data'][3]['text'] = 'unika set';
    $data['data'][3]['number'] = $uNumbOfSets[0];
    $totalsResultString = addslashes(json_encode($data));

    // Send the results to javascript for rendering
    echo "<script>",
    "createGraph('numberSlider','$totalsResultString', '.rightGraphics');",
    "</script>"
    ;

    mysqli_close($connection);
?>