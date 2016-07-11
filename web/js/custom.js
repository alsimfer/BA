// JQuery Dialog.
$(function() {    
console.log('clicked');

    var table = $('#patients_table').DataTable({});

    var trClicked = function (tr) {
        var data = table.row(tr).data();
        console.log(data);
    }
    
    $('#patients_table tbody').on( 'click', 'tr', function () {
        trClicked(this);

        if ($(this).hasClass('selected')) {
            $(this).removeClass('selected');
            $("#edit_button").removeClass("btn-warning");
            $("#delete_button").removeClass("btn-danger");
        }
        else {
            table.$("tr.selected").removeClass("selected");
            $(this).addClass("selected");
            $("#edit_button").addClass("btn-warning");
            $("#delete_button").addClass("btn-danger");
        }
    });
 
    $('#edit_button').click( function () {
        table.row('.selected').remove().draw( false );
    } );

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