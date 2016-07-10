// JQuery Dialog.
$(function() {
    var dialog = $('.dialog').dialog({
        autoOpen: false,
        modal: true,
        width: 'auto',
        modal: true
    });
    
    
});


// Object types are customer, payment, article, deal, shipment.
function trClickListener(table) {
    
    // Find out what table we are currently browsing (customers/articles/payments...).
    var table_id = $(table.table().node()).attr('id');

    var arr = table_id.split('_');    
    var object_type = arr[0];
    
    // Save the HTML of the first button.
    var add_button = $('#extra_button :first').prop("outerHTML");    
    
    // When clicked.
    $('#' + table_id).on('click', 'tr', function() {
        // Hide form.
        $('#extra_content').html('');
        
        $(this).addClass('highlight').siblings().removeClass('highlight');
        var data = table.row(this).data();
        var id = $(this).attr('id');
      
        if (object_type == 'customers') {
            // Create edit button.
            var params = {'class_name': 'ajax_accounting_AjaxCustomer', 'action': 'generate_form', 'id': id};
            params.row_data = data;            
            var request = JSON.stringify(params);
            
            var edit_button = '<button id=\'edit_button\' class=\'btn btn-default btn-block btn-warning text-left\' ' + 
                    'onclick=\'ajax_request(' + request + ')\' ' + 
                    'name=\'button\' type=\'button\'><span class=\'glyphicon glyphicon-edit\' aria-hidden=\'true\'>&nbsp</span>Изменить</button>';
            
            // Create delete button.
            params = {'class_name': 'ajax_accounting_AjaxCustomer', 'action': 'delete_customer', 'id': id};
            request = JSON.stringify(params);
            
            var delete_button = '<button id=\'delete_button\' class=\'btn btn-default btn-block btn-danger text-left\' ' +   
                    'onclick=\'ajax_request(' + request + ')\' ' + 
                    'name=\'button\' type=\'button\'><span class=\'glyphicon glyphicon-remove\' aria-hidden=\'true\'>&nbsp</span>Удалить</button>';
            
            // Put new buttons on the screen.
            $('#extra_button').html(add_button + edit_button + delete_button);
        } else if (object_type == 'articles') {
            // Create edit button.
            var params = {'class_name': 'ajax_accounting_AjaxArticle', 'action': 'generate_form', 'id': id};
            params.row_data = data;            
            var request = JSON.stringify(params);
            
            var edit_button = '<button id=\'edit_button\' class=\'btn btn-default btn-block btn-warning text-left\' ' + 
                    'onclick=\'ajax_request(' + request + ')\' ' + 
                    'name=\'button\' type=\'button\'><span class=\'glyphicon glyphicon-edit\' aria-hidden=\'true\'>&nbsp</span>Изменить</button>';
            
            // Create delete button.
            params = {'class_name': 'ajax_accounting_AjaxArticle', 'action': 'delete_article', 'id': id};
            request = JSON.stringify(params);
            
            var delete_button = '<button id=\'delete_button\' class=\'btn btn-default btn-block btn-danger text-left\' ' +   
                    'onclick=\'ajax_request(' + request + ')\' ' + 
                    'name=\'button\' type=\'button\'><span class=\'glyphicon glyphicon-remove\' aria-hidden=\'true\'>&nbsp</span>Удалить</button>';
            
            // Put new buttons on the screen.
            $('#extra_button').html(add_button + edit_button + delete_button);
        } else if (object_type == 'payments') {
            // Create edit button.
            var params = {'class_name': 'ajax_accounting_AjaxPayment', 'action': 'generate_form', 'id': id};
            params.row_data = data;            
            var request = JSON.stringify(params);
            
            var edit_button = '<button id=\'edit_button\' class=\'btn btn-default btn-block btn-warning text-left\' ' + 
                    'onclick=\'ajax_request(' + request + ')\' ' + 
                    'name=\'button\' type=\'button\'><span class=\'glyphicon glyphicon-edit\' aria-hidden=\'true\'>&nbsp</span>Изменить</button>';
            
            // Create delete button.
            params = {'class_name': 'ajax_accounting_AjaxPayment', 'action': 'delete_payment', 'id': id};
            request = JSON.stringify(params);
            
            var delete_button = '<button id=\'delete_button\' class=\'btn btn-default btn-block btn-danger text-left\' ' +   
                    'onclick=\'ajax_request(' + request + ')\' ' + 
                    'name=\'button\' type=\'button\'><span class=\'glyphicon glyphicon-remove\' aria-hidden=\'true\'>&nbsp</span>Удалить</button>';
            
            // Put new buttons on the screen.
            $('#extra_button').html(edit_button);
        } else if (object_type == 'shipments') {
            // Create edit button.
            var params = {'class_name': 'ajax_accounting_AjaxShipment', 'action': 'generate_form', 'id': id};
            params.row_data = data;            
            var request = JSON.stringify(params);
            
            var edit_button = '<button id=\'edit_button\' class=\'btn btn-default btn-block btn-warning text-left\' ' + 
                    'onclick=\'ajax_request(' + request + ')\' ' + 
                    'name=\'button\' type=\'button\'><span class=\'glyphicon glyphicon-edit\' aria-hidden=\'true\'>&nbsp</span>Изменить</button>';
            
            // Create delete button.
            params = {'class_name': 'ajax_accounting_AjaxShipment', 'action': 'delete_shipment', 'id': id};
            request = JSON.stringify(params);
            
            var delete_button = '<button id=\'delete_button\' class=\'btn btn-default btn-block btn-danger text-left\' ' +   
                    'onclick=\'ajax_request(' + request + ')\' ' + 
                    'name=\'button\' type=\'button\'><span class=\'glyphicon glyphicon-remove\' aria-hidden=\'true\'>&nbsp</span>Удалить</button>';
            
            // Put new buttons on the screen.
            $('#extra_button').html(edit_button);
        } else if (object_type == 'deals') {
            // Create edit button.
            var params = {'class_name': 'ajax_accounting_AjaxDeal', 'action': 'generate_form', 'id': id};
            params.row_data = data;            
            var request = JSON.stringify(params);
            
            var edit_button = '<button id=\'edit_button\' class=\'btn btn-default btn-block btn-warning text-left\' ' + 
                    'onclick=\'ajax_request(' + request + ')\' ' + 
                    'name=\'button\' type=\'button\'><span class=\'glyphicon glyphicon-edit\' aria-hidden=\'true\'>&nbsp</span>Изменить</button>';

            // Create delete button.
            params = {'class_name': 'ajax_accounting_AjaxDeal', 'action': 'delete_deal', 'id': id};
            request = JSON.stringify(params);
            
            var delete_button = '<button id=\'delete_button\' class=\'btn btn-default btn-block btn-danger text-left\' ' +   
                    'onclick=\'ajax_request(' + request + ')\' ' + 
                    'name=\'button\' type=\'button\'><span class=\'glyphicon glyphicon-remove\' aria-hidden=\'true\'>&nbsp</span>Удалить</button>';
            
            // Put new buttons on the screen.
            $('#extra_button').html(add_button + edit_button + delete_button);
        }
                
    });
               
}


function showDialog(class_name, article_id) {
    console.log(article_id);
    
    $.ajax({
        type: 'post',
        url: 'ajax.php',
        data: {'class_name': class_name, 'article_id': article_id},
           
        success: function(result) {
            var response = JSON.parse(result);                        
                   
            if (typeof response.messages !== 'undefined') {
                showMessages(response.messages);    
            } else {                
                var dialog = $('.dialog').dialog();
                dialog.dialog('option', 'title', 'Загрузить новую картинку.');
                dialog.html(response);
                dialog.dialog('open');
                                
            }            
        },
        error: function(result) {
            alert(result);
        }
    });    
    
    
}

function ajax_request(params) {    
    $.ajax({
        type: 'post',
        url: 'ajax.php',
        data: params,
           
        success: function(result) {
            var response = JSON.parse(result);

            $('#message').empty();
            if (typeof response.messages !== 'undefined') {
                showMessages(response.messages);    
            }

            $.each(response, function(i, val) {
                $('#' + val.id).html(val.html);
            });          
            
            if (typeof response.script !== 'undefined') {
                $('#script' ).html('<script>' + response.script + '</script>');
            }
                                                           
        },
        error: function(result) {
            alert(result);
        }
    });
}

function showMessages(messages) {
    if (typeof messages.success !== 'undefined' && messages.success.length > 0) {
        $('#message').prepend('<div class=\'alert alert-success\' role=\'alert\'>' + messages.success + '</div>');
    }
    
    if (typeof messages.info !== 'undefined' && messages.info.length > 0) {
        $('#message').prepend('<div class=\'alert alert-info\' role=\'alert\'>' + messages.info + '</div>');
    }
    
    if (typeof messages.warning !== 'undefined' && messages.warning.length > 0) {
        $('#message').prepend('<div class=\'alert alert-warning\' role=\'alert\'>' + messages.warning + '</div>');
    }
    
    if (messages.danger !== null && typeof messages.danger !== 'undefined' && messages.danger.length > 0) {
        $('#message').prepend('<div class=\'alert alert-danger\' role=\'alert\'>' + messages.danger + '</div>');
    }
    
}

/**
 * Create a DataTable.
 * sClassName - class where the table data is prepared.
 * bClickable - if this table should be clickable.
 */ 

$.fn.tableConstructor = function(sClassName, bClickable) {
    var table = $(this);    
    $.ajax({
        type: 'post',
        url: 'table_ajax.php',
        data: {'class_name': sClassName},
           
        success: function(result) {
            response = JSON.parse(result);
            if (typeof response.messages !== 'undefined') {
                showMessages(response.messages);
                return;
            }      
            
            // Add a footer
            var footerHTML = '<tfoot><tr>';
            for (var i = 0; i < response.aoColumns.length; i++) {
                footerHTML += '<td></td>';
            }
            footerHTML += '</tr></tfoot>';
            table.append(footerHTML);
            
            response.footerCallback = function ( row, data, start, end, display ) {
                var api = this.api(), data;     
                // Remove the formatting to get integer data for summation
                var intVal = function ( i ) {
                    return typeof i === 'string' ?
                        i.replace(/[\$,]/g, '')*1 :
                        typeof i === 'number' ?
                            i : 0;
                };
   
                // Add a sum to footer according to sClassName. 
                $(response.abSum).each(function(index) {
                    if (this == true) {                       
                        total = api
                        .column(index)
                        .data()
                        .reduce( function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0 );

                        // Total over this page
                        pageTotal = api
                            .column(index, { page: 'current'})
                            .data()
                            .reduce( function (a, b) {
                                return intVal(a) + intVal(b);
                            }, 0 );
   
                        // Update footer
                        $(api.column(index).footer()).html(
                            pageTotal.toFixed(2) +' / '+ total.toFixed(2)
                        );
                    }
                });
            };
            
            // When datatable is completed. 
            response.fnInitComplete = function(oSettings, json) {
                // Set a table reference to DataTable object.
                table = table.DataTable();
                if (bClickable == true) {
                    trClickListener(table);    
                }
                
                // This should be somewhere else.
                $(".thumbnail").click(function() {
                    var tableRow = $(this).closest( "tr" );
                    var rowData = table.row(tableRow).data();
                    
                    showDialog('ajax_dialog_UploadPicture', rowData.id);
                });

                // Add classes to headers.
                if (typeof response.asHeaderClasses !== 'undefined') {
                    var columnsAmount = response.aoColumns.length;
                
                    for (var i = 0; i < columnsAmount; i++) {
                        var title = table.column(i).header();
                        
                        if (response.asHeaderClasses[i].length > 0) {
                            var className = response.asHeaderClasses[i];
                            $(title).addClass(className);    
                        }                         
                    }
                }

                                           
            };
            
            return table.DataTable(response);
            
        },
        error: function(result) {
            alert(result);       
        }
    });
    
        
    
    
};



