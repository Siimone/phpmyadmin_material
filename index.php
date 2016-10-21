<?php
    include "sql_lib.php";
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link href="css/mine.css" rel="stylesheet">
        <link rel="stylesheet" href="https://code.getmdl.io/1.2.1/material.indigo-pink.min.css">
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
        <script src="https://code.getmdl.io/1.2.1/material.min.js"></script>
        <script src="js/core.js"></script>
    </head>
    <body>
        <div class="mdl-layout mdl-js-layout mdl-layout--fixed-drawer mdl-layout--fixed-header">
            <header class="mdl-layout__header">
                <div class="mdl-layout__header-row">
                    <span class="mdl-layout-title">Control Panel</span>
                </div>
                <div class="mdl-layout__tab-bar mdl-js-ripple-effect">
                    <a href="#databases" class="mdl-layout__tab is-active">Databases</a>
                    <a href="#sql" class="mdl-layout__tab">SQL</a>
                    <a href="#export" class="mdl-layout__tab">Export</a>
                </div>
            </header>
            <div class="mdl-layout__drawer">
                <span class="mdl-layout-title" style="text-align: center; padding-left:0px;">
                    <a href="index.php" style="text-decoration: none; color: #424242">
                        SQL Manager with MDL
                    </a>
                </span>
                <nav class="mdl-navigation">
                    <?php
                        $arr = getDB();
                        foreach($arr as $row) {
                            echo "<a class='mdl-navigation__link' href='db.php?db=". $row['Database'] . "'>";
                            echo $row["Database"];
                            echo "</a>";
                        }
                    ?>
                </nav>
            </div>
            <main class="mdl-layout__content">
                <section class="mdl-layout__tab-panel is-active" id="databases">
                    <div class="page-content">
                        <label>Create new database</label>
                        <div class="mdl-textfield mdl-js-textfield">
                            <input class="mdl-textfield__input" type="text" id="DBName">
                            <label class="mdl-textfield__label" for="DBName">insert name</label>
                        </div>
                        <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect" id="createDB">
                          Create!
                        </button>
                    <!--<?php
                        $arr = getDB();
                        echo "<table class='mdl-data-table mdl-js-data-table mdl-data-table--selectable mdl-shadow--2dp'>";
                        echo "<thead>";
                        echo "<tr>";
                        echo "<th>Name</td>";
                        echo "</tr></thead>";
                        foreach($arr as $row) {
                            echo "<tr><td class='mdl-data-table__cell--non-numeric'>" . $row['Database'] . "</td></tr>\n";
                        }
                    ?>-->
                    </div>
                </section>
                <section class="mdl-layout__tab-panel" id="sql">
                    <div class="page-content">
                        <div class="mdl-textfield mdl-js-textfield" id="sql_textarea">
                            <label class="mdl-textfield__label" for="sample5">Write here your query..</label>
                            <textarea class="mdl-textfield__input" type="text" rows="10" id="sample5"></textarea>
                        </div>
                        <br>
                        <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect">
                            Execute
                        </button>
                    </div>
                </section>
                <section class="mdl-layout__tab-panel" id="export">
                    <div class="page-content">
                    </div>
                </section>
                <div id="demo-toast-example" class="mdl-js-snackbar mdl-snackbar">
                    <div class="mdl-snackbar__text"></div>
                    <button class="mdl-snackbar__action" type="button"></button>
                </div>
            </main>
        </div>
    </body>
</html>
