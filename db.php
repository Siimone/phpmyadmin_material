<?php
    include "sql_lib.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
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
  <dialog class="mdl-dialog">
    <h4 class="mdl-dialog__title"></h4>
    <div class="mdl-dialog__content">
      <p id="msg_delete_db">
      </p>
    </div>
    <div class="mdl-dialog__actions">
      <button type="button" class="mdl-button" id="accept_drop">Ok</button>
      <button type="button" class="mdl-button close">Back</button>
    </div>
  </dialog>
    <div class="mdl-layout mdl-js-layout mdl-layout--fixed-drawer mdl-layout--fixed-header">
        <header class="mdl-layout__header">
            <div class="mdl-layout__header-row">
                <span class="mdl-layout-title">Manage Database:&nbsp</span>
                <span class="mdl-layout-title" id="db"><?php echo $_GET['db']; ?> </span>
                <button style="background-color: background-color: rgba(192,192,192,0.3); margin" class="mdl-button mdl-js-button mdl-button--raised manageButton" id="dropDB">
                    <i class='material-icons' style="color: white">delete</i>
                </button>
            </div>
            <div class="mdl-layout__tab-bar mdl-js-ripple-effect">
                <a href="#structure" class="mdl-layout__tab is-active">Structure</a>
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
                    echo "<a class='mdl-navigation__link' href='db.php?db=" . $row['Database'] . "'>" . $row["Database"] . "</a>";
                }
                ?>
            </nav>
        </div>
        <main class="mdl-layout__content" style="overflow-y: auto;">
            <section class="mdl-layout__tab-panel is-active" id="structure">
                <div class="page-content">
                    <?php
                    $db = $_GET['db'];
                    $nameIndex = "Tables_in_" . $db;
                    $arr = structure($db);
                    echo <<< fine
                    <div class="mdl-textfield mdl-js-textfield">
                        <input class="mdl-textfield__input" type="text" id="tableName">
                        <label class="mdl-textfield__label" for="tableName">Insert table name</label>
                    </div>
                    <div class="mdl-textfield mdl-js-textfield" style="width:100px">
                        <input class="mdl-textfield__input" type="text" id="numRows">
                        <label class="mdl-textfield__label" for="numRows">Columns</label>
                    </div>
                    <div id="form"></div>
                    <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect" id="removeTables">
                        Remove Tables
                    </button>
                    <table class="mdl-data-table mdl-js-data-table mdl-data-table--selectable mdl-shadow--2dp">
                        <thead>
                            <tr>
                                <th class="mdl-data-table__cell--non-numeric">Table Name</th>
                                <th>Rows</th>
                            </tr>
                        </thead>
fine;
                    foreach($arr as $row) {
                        $select = select($db,$row[$nameIndex]);
                        echo "<tr>\n";
                        echo "<td class='mdl-data-table__cell--non-numeric' id='". $row[$nameIndex] . "'><a href='table.php?db=". $db . "&table=" . $row[$nameIndex] . "'>" . $row[$nameIndex] . "</a></td>\n";
                        echo "<td>" . mysqli_num_rows($select) . "</td>\n";
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
                        <label class="mdl-textfield__label" for="query">Write here your query..</label>
                        <textarea class="mdl-textfield__input" type="text" rows="10" id="query"></textarea>
                    </div>
                    <p id="res"></p>
                    <br>
                    <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect" id="executeSQL">
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
<script>
    var dialog = document.querySelector('dialog');
    var showDialogButton = document.querySelector('#dropDB');
    if (! dialog.showModal) {
      dialogPolyfill.registerDialog(dialog);
    }
    showDialogButton.addEventListener('click', function() {
      dialog.showModal();
    });
    dialog.querySelector('.close').addEventListener('click', function() {
      dialog.close();
    });
</script>
