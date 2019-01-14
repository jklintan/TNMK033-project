<html>
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Lego Project</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" type="text/css" media="screen" href="assets/css/default.css" />
        <link rel="stylesheet" type="text/css" media="screen" href="assets/css/main.css" />
        <link rel="stylesheet" type="text/css" media="screen" href="assets/css/grapher.css" />
        <script src="assets/js/main.js"></script>
    </head>

    <body>
        <?php
            // Counts all sets in the catalog and total amount of different types of pieces in the catalog

            $connection = mysqli_connect("mysql.itn.liu.se","lego","","lego"); //Connect to Lego database
            if(mysqli_error($connection)) {
                die("<p>MySQL error:</p>\n<p>" . mysqli_error($connection) . "</p>"); //Error message if connection failed
            }

            // Number of sets
            $setID = "SELECT COUNT(*) FROM sets";
            $setQuery = mysqli_query($connection, $setID);
            $numbOfSets = mysqli_fetch_array($setQuery);
            echo("Antal legosatser totalt: ");
            echo($numbOfSets[0]);

            // Number of unique sets
            $uSetID = "SELECT COUNT(DISTINCT inventory.SetID) FROM inventory";
            $uSetQuery = mysqli_query($connection, $uSetID);
            $uNumbOfSets = mysqli_fetch_array($uSetQuery);
            echo("Antalet unika legosatser: ");
            echo($uNumbOfSets[0]);

            // Number of pieces in total
            $legoID = "SELECT SUM(inventory.Quantity) FROM inventory WHERE ItemtypeID='P'";
            $legoQuery = mysqli_query($connection, $legoID);
            $numbOfPieces = mysqli_fetch_array($legoQuery);
            echo("Unika antalet legobitar: ");
            echo($numbOfPieces[0]);

            // Number of unique pieces
            $uLegoID = "SELECT COUNT(DISTINCT inventory.ItemID) FROM inventory WHERE ItemtypeID='P'";
            $uLegoQuery = mysqli_query($connection, $uLegoID);
            $uNumbOfPieces = mysqli_fetch_array($uLegoQuery);
            echo("Unika antalet legobitar: ");
            echo($uNumbOfPieces[0]);

            // Store the results in an array with objects
            $data = [];
            $data[0]['title'] = 'I databasen';
            $data[0]['text'] = 'bitar';
            $data[0]['data'] = $numbOfPieces[0];
            $data[1]['title'] = 'I databasen';
            $data[1]['text'] = 'set';
            $data[1]['data'] = $numbOfSets[0];
            $data[2]['title'] = 'I databasen';
            $data[2]['text'] = 'unika bitar';
            $data[2]['data'] = $uNumbOfPieces[0];
            $data[3]['title'] = 'I databasen';
            $data[3]['text'] = 'unika set';
            $data[3]['data'] = $uNumbOfSets[0];
            $totalsResultString = json_encode($data);

            // Send the results to javascript for rendering
            echo "<script type='text/javascript'>",
            "createGraph('numberSlider','$totalsResultString', '.statistics');",
            "</script>"
            ;

            mysqli_close($connection);
        ?>

    </body>

</html>