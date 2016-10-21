var db, table, field, value,id, columnNumbers, jsonInsert,sql;
var arrayType = [], arrayNames = [];

$(document).ready(function() {

    $('#numRows').on('input',function(e){
        db = (document.getElementById('db').innerHTML).trim();
        table = document.getElementById('tableName').value;
        columnNumbers = document.getElementById('numRows').value;
        createFormNewTable(columnNumbers);
    });

    $('#dropDB').on('click', function(e){
        db = (document.getElementById('db').innerHTML).trim();
        document.querySelector('.mdl-dialog__title').innerHTML = "Confirm query:";
        document.querySelector('#msg_delete_db').innerHTML = "DROP DATABASE " + db.trim() + ";";
    });

    $('#accept_drop').on('click', function (e){
        var dialog = document.querySelector('dialog');
        dialog.close();
        ajax("drop_db");
    });

    $('#dropTable').on('click', function(e){
        db = document.getElementById('db').value;
        table = (document.getElementById('table').innerHTML).trim();
        ajax("drop_table");
    });

    $('#createDB').on('click', function(e){
        db = (document.getElementById('DBName').value).trim();
        ajax("new_db");
    });

    $('#executeSQL').on('click', function(e){
        console.log('eee');
        var query = document.getElementById('query').value;
        console.log(query);
        $.ajax({
            url: "sql_lib.php.php",
            type: 'get',
            data : { query : query },
            success : function success(e){
                console.log(e);
                document.getElementById('res').innerHTML = e;
            },
            error : function error(){
                console.log(e);
            }
        });
    });

    $('#removeRows').on('click', function (e){
        table = (document.getElementById('table').innerHTML).trim();
        db = document.getElementById('db').value;
        $('.is-selected').children().each(function(){
            var row = $(this);
            if(row.hasClass('pk')){
                field = document.getElementById('pk').value;
                id = row.html();
                ajax("remove_rows");
            }
        });
    });

    $('#addRow').on('click', function (e){
        table = document.getElementById('table').innerHTML;
        db = document.getElementById('db').value;
        sql = "INSERT INTO `" + table.trim() + "` (";
        var viewData = {
            data : []
        };
        $('.newData').each(function(){
            var row = $(this);
            sql = sql + "`" + row.attr('id') + "`,";
            jsonData = {};
            jsonData[row.attr('id')] = row.val();
            viewData.data.push(jsonData);
        });
        sql = sql + ") VALUES (";
        $('.newData').each(function(){
            var row = $(this);
            if( isNumber(row.val()) )
                sql = sql + row.val() + ",";
            else {
                sql = sql + "\"" + row.val() + "\"" + ",";
            }
        });
        sql = sql + ");"
        sql = sql.replace(/[,)]{2,}/g, ')');
        ajax("add_row");
    });

    function isNumber(obj) {
        return !isNaN(parseFloat(obj))
    }

    $('#removeTables').on('click', function (e){
        db = (document.getElementById('db').innerHTML).trim();
        console.log(db);
        $('.is-selected').children().each(function(){
            if( $(this).attr('id') != null){
                table = $(this).attr('id');
                ajax("drop_tables");
            }
        });
    });

    $(document).on('click', '#createTable', function(){
        columnNumbers = document.getElementById('numRows').value;
        var contatore = 0;
        $( "#form" ).find("select").each(function (){
            arrayType[contatore] = $(this).val();
            contatore++;
        });
        contatore = 0;
        $( "#form" ).find(".colName").each(function (){
            arrayNames[contatore] = $(this).val();
            contatore++;
        });
        sql = "CREATE TABLE `" + db + "`.`" + table + "` (";
        for(var i=0; i < columnNumbers; i++){
            sql = sql + "`" + arrayNames[i] + "`" + arrayType[i] + ","
        }
        sql = sql + ");";
        sql = sql.replace(/[,)]{2,}/g, ')');
        console.log(sql);
        ajax("new_table");
    });
});

function createFormNewTable(numberOfColumns){
    $('#form').empty();
    for(var i=0; i < numberOfColumns; i++){
        var selectType = "<select class='typeCol' id='colId" + i + "'><option value='int'>Int</option><option value='Text'>Text</option><option value='opel'>Opel</option><option value='audi'>Audi</option></select>";
        var columnName = "<div class='mdl-textfield mdl-js-textfield' style='width:120px; margin-left:40px'><input class='mdl-textfield__input colName' type='text' id='colNameId" + i + "'><label class='mdl-textfield__label' for='" + i + "'>Column Name</label></div>";
        $("#form").append(selectType + columnName + "<br>");
    }
    var btn = "<button style='background-color: background-color: rgba(192,192,192,0.3); margin-bottom:40px;' class='mdl-button mdl-js-button mdl-button--raised manageDB' id='createTable'><i class='material-icons' style='color: white'>add</i></button><br>";
    $('#form').append(btn);
    componentHandler.upgradeDom();
}

function toast(msg) {
    'use strict';
    var snackbarContainer = document.querySelector('#demo-toast-example');
    var data = {message: msg};
    snackbarContainer.MaterialSnackbar.showSnackbar(data);
}

function ajax(action){
    console.log(action);
    $.ajax({
        url: "sql_lib.php",
        type: 'get',
        data : { action : action, field: field, value : id, db : db, table : table, jsonData : jsonInsert, sql : sql},
        success : function(response){
            console.log(response);
            toast(response);
            //if(response != 1)
            //    toast(response);
            //else
            //    toast("Action completed!");
                //if(action != "drop_db")
                //    location.reload();
                //else
                //    window.location.href = "index.php";
        },
        error : function(error){
            console.log(error);
        }
    });
}
