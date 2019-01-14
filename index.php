<!DOCTYPE html>
<html lang="sv">

    <head>
        <!-- Include head -->
        <?php
            include 'assets/phpModules/head.php';
        ?>
    </head>

    <body class="main">
        <!-- Search box for set searching -->
        <div class="start">
        <!-- Include header menu -->
        <?php
            include 'assets/phpModules/header.php';
        ?>
            <div class="centerBox">
                <h1>STATISTIK OM LEGODATABASEN</h1>
                <p>Här kan du söka på en legosats som du vill veta mer om...</p>
                <div id="searchField">
                        <form action="search.php" method="GET">
                            <input required type="text" name="query" placeholder="Tanker Wagon..." autofocus>
                            <button type="submit"><i class="fas fa-search"></i></button>
                        </form>
                </div>
                <a href="#how" class="transparent smooth-scroll">
                    <i id="scrollDownArrow" class="fas fa-chevron-down"></i>
                </a>
            </div>
        </div>

        <div class="rowBanner fade-up" id="how">
                <h1>Hur du använder denna hemsida</h1>
                <p>På denna hemsida kan du hitta statistik över de flesta legobitar och set som Lego har gett ut. I rutan högst upp på sidan kan du söka på set och bitar i databasen och se lite statistik
                    gällande din sökning. På fliken topplista som du hittar i menyn på toppen av sidan kan du hitta spännande och blandad info om legoset.
                    De flesta diagram går att scrolla i eller klicka sig vidare med hjälp av pilar. Om du är intresserad av statistik över databasen i helhet kan du fortsätta scrolla nedåt.
                </p>
        </div>

        <!-- Display statistics -->
        <div class="stats">
            <h1>Statistik </h1>
            <div class="row">
                <div class="leftGraphics"></div>
                <div class="rightGraphics"></div>
            </div>
        </div>
        <?php
            include 'assets/phpModules/tidSets.php';
            include 'assets/phpModules/timeChart.php';
            include 'assets/phpModules/totalParts.php';
            include 'assets/phpModules/stegu.php';
            include 'assets/phpModules/footer.php';
        ?>
    </body>
</html>