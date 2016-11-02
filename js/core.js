var db, table, field, value,id, columnNumbers, jsonInsert,sql, primaryKey = -1;
var arrayType = [], arrayNames = [], arrayAutoIncrement = [], arrayValues = [];
var typeWithValues = ("int","varchar");

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
        var viewData = {
            data : []
        };
        sql = "INSERT INTO `" + table.trim() + "` (";
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
            console.log("Val = " + row.val());
            console.log(isNumber(row.val()));
            if( isNumber(row.val()) && (row.val()).indexOf('-') == -1 )
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
        var counter = 0;
        $( "#form" ).find("select").each(function (){
            arrayType[counter] = $(this).val();
            counter++;
        });
        counter = 0;
        $( "#form" ).find(".colName").each(function (){
            arrayNames[counter] = $(this).val();
            counter++;
        });
        counter = 0;
        $( "#form" ).find(".pK").each(function (){
            if(document.getElementById('col' + counter).checked){
                primaryKey = counter;
            }
            counter++;
        });
        counter = 0;
        $( "#form" ).find(".colValues").each(function (){
            arrayValues[counter] = $(this).val();
            console.log($(this).val());
            counter++;
        });
        sql = "CREATE TABLE `" + db + "`.`" + table + "` (";
        for(var i=0; i < columnNumbers; i++){
            sql = sql + "`" + arrayNames[i] + "` " + arrayType[i] + ( typeWithValues.indexOf(arrayType[i]) && arrayValues[i] != '' ? "(" + arrayValues[i] + ")" : "") + (primaryKey == i ? " PRIMARY KEY " : "") + ",";
        }
        sql = sql.substring(0, sql.length-1);
        sql = sql + ");"
        console.log(sql);
        ajax("new_table");
    });
});

function createFormNewTable(numberOfColumns){
    $('#form').empty();
	$('#form').append("<table class='mdl-data-table mdl-js-data-table mdl-shadow--2dp'><tr><th class='thForm' style='text-align:left;'>Name</th><th class='thForm' style='text-align:left;'>Type</th><th class='thForm' style='text-align:left;'>Value</th><th class='thForm' style='text-align:left;'>Primary Key</th><th class='thForm'>Auto Increment</td></tr>");
    for(var i=0; i < numberOfColumns; i++){
        var selectType = "<td class='mdl-data-table__cell--non-numeric formBuildTable'><select class='typeCol' id='colId" + i + "'><option value='tinyint'>Tinyint</option><option value='smallint'>SmallInt</option><option value='mediumint'>MediumInt</option><option value='int'>Int</option><option value='bigint'>BigInt</option><option value='varchar'>Varchar</option><option value='date'>Date</option><option value='text'>Text</option></select></td>";        var columnValue = "<td class='mdl-data-table__cell--non-numeric formBuildTable'><div class='mdl-textfield mdl-js-textfield' style='width:120px; margin-right:40px'><input class='mdl-textfield__input colValues' type='text' id='colValueId" + i + "'><label class='mdl-textfield__label' for='" + i + "'>Value</label></div></td>";
        var columnName = "<td class='mdl-data-table__cell--non-numeric formBuildTable'><div class='mdl-textfield mdl-js-textfield' style='width:120px; margin-right:40px'><input class='mdl-textfield__input colName' type='text' id='colNameId" + i + "'><label class='mdl-textfield__label' for='" + i + "'>Column Name</label></div></td>";
		var primaryKey = "<td class='formBuildTable'><label class='mdl-radio mdl-js-radio mdl-js-ripple-effect' for='col" + i + "'><input type='radio' id='col" + i + "' class='mdl-radio__button pK' name='primaryKey' value='1'><span class='mdl-radio__label'>p</span></label></td>";
		var autoIncrement = "<td class='mdl-data-table__cell--non-numeric formBuildTable'><label class='mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect' style='display:inline' for='auto_inc_" + i + "'><input type='checkbox' id='auto_inc_" + i + "' class='mdl-checkbox__input' checked></label></td>";

        $('#form').append(columnName);
        $('#form').append(selectType);
        $('#form').append(columnValue);
		$('#form').append(primaryKey);
		$('#form').append(autoIncrement);
		$('#form').append("<br>");
    }
	$('#form').append("</table>");
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
