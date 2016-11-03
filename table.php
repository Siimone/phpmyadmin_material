<?php
    include "sql_lib.php";
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link rel="stylesheet" href="https://code.getmdl.io/1.2.1/material.indigo-pink.min.css">
        <link href="css/mine.css" rel="stylesheet">
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
        <script src="https://code.getmdl.io/1.2.1/material.min.js"></script>
        <script src="js/core.js"></script>
    </head>
    <body>
        <div class="mdl-layout mdl-js-layout mdl-layout--fixed-drawer mdl-layout--fixed-header">
            <header class="mdl-layout__header">
                <div class="mdl-layout__header-row">
                    <span class="mdl-layout-title">Manage Table:&nbsp</span>
                    <span class="mdl-layout-title" id="table"><?php echo $_GET['table']; ?> </span>
                </div>
                <div class="mdl-layout__tab-bar mdl-js-ripple-effect">
                    <a href="#data" class="mdl-layout__tab is-active">Data</a>
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
            <main class="mdl-layout__content" style="overflow-y: auto;">
                <section class="mdl-layout__tab-panel is-active" id="data">
                    <div class="page-content">
                        <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect" id="removeRows">
                            Remove Rows
                        </button>
                        <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect" id="addRow">
                            Add row
                        </button>
                        <br>
                    <?php
                        $db = $_GET['db'];
                        $table = $_GET['table'];
                        echo "<input type='hidden' id='db' value=". $_GET['db'] . ">";
                        $arr = select($db,$table);
                        $result = getColumnsNames($db,$table);
                        $columns_names = array();

                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                array_push($columns_names, $row['Field']);
                            }
                        }
                        $result = getColumnsNames($db,$table);
                        for($i=0; $i < sizeof($columns_names); $i++){
                            echo "<label>" . $columns_names[$i] . "</label>\n";
                            echo "<div class='mdl-textfield mdl-js-textfield' style='width:100px'>
                                <input class='mdl-textfield__input newData' type='text' id='" . $columns_names[$i] . "' required>
                                <label class='mdl-textfield__label' for='tableName'></label>
                            </div>";
                        }
                        echo "<table class='mdl-data-table mdl-js-data-table mdl-data-table--selectable mdl-shadow--2dp'>";
                        echo "<thead>";
                        echo "<tr>";
                        $primaryKey = '';
                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                if($row['Key'] == "PRI"){
                                    $primaryKey = $row['Field'];
                                    echo "<th class='pk' style='color: red; text-align:left;'> " . ($row['Field']) . "<br><u>" . $row['Type'] . "</u></td>";
                                }else {
                                    echo "<th style='text-align:left;'>" . ($row['Field']) . "<br><u>" . $row['Type'] . "</u></td>";
                                }
                            }
                        }
                        echo "<input type='hidden' id='pk' value=". $primaryKey . ">";
                        echo "</tr></thead>";
                        foreach($arr as $row) {
                            echo "<tr>\n";
                            for($i=0; $i < sizeof($columns_names); $i++){
                                if($columns_names[$i] == $primaryKey){
                                    echo "<td class='mdl-data-table__cell--non-numeric " . $columns_names[$i] . " pk'>" . $row[$columns_names[$i]] . "</td>\n";
                                }else{
                                    echo "<td class='mdl-data-table__cell--non-numeric " . $columns_names[$i] . "'>" . $row[$columns_names[$i]] . "</td>\n";
                                }
                            }
                            echo "</tr>\n";
                        }
                        echo <<< fine
                      </tbody>
                    </table>
fine;
                    ?>
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
