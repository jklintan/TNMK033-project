<?php
    $connection1	=	mysqli_connect("mysql.itn.liu.se","lego", "", "lego"); //Connect to Lego database
    if (!$connection1){
        die("<p>MySQL error:</p> <p>" . mysqli_error($connection1) . "</p>"); //Error message if connection failed
    }

    $data1 = "SELECT DISTINCT Partname, SUM(Quantity) FROM inventory, parts WHERE inventory.SetID='$_POST[query]' AND ItemID=PartID GROUP BY Partname ORDER BY Partname ASC";
    $contents1 = mysqli_query($connection1, $data1);

    $things1 = [];

    while ($row = mysqli_fetch_row($contents1)) {
        $things1[] = $row;
    }

    for($i = 0; $i < count($things1); $i++){
        $things1[$i]['text'] = $things1[$i][0];
        $things1[$i]['number'] = $things1[$i][1];
        unset($things1[$i][0]);
        unset($things1[$i][1]);
    }



    //query for set information
    $titleData = "SELECT Setname, SetID FROM sets WHERE sets.SetID='$_POST[query]'";
    $titleQuery = mysqli_query($connection1, $titleData);
    $title = mysqli_fetch_assoc($titleQuery);

    //get set URL
    $prefix1 = "http://www.itn.liu.se/~stegu76/img.bricklink.com/";
    $SetID1 = $title['SetID'];
    $filename1 = "SL/$SetID1.jpg";
    $setURL1 = "$prefix1$filename1";
    $jsClickId = str_replace('.', '', $SetID1);

    $jsTarget = a . $jsClickId;

    //assign proper data structure for js rendering
    $legoData = [];
    $legoData['title'] = $title['Setname'];
    $legoData['data'] = $things1;
    $legoData['dataType'] = "pieces";
    $legoData['url'] = $setURL;

    $totalsResultString = addslashes(json_encode($legoData));

    // Send the results to javascript for rendering
    echo "<script>",
    "createGraph('histogram','$totalsResultString', '.$jsTarget');",
    "</script>"
    ;
?>