// Document ready.
$(function() {   

    // Initialise bootstrap datePicker
    $('#dateTimePickerContainer .input-group.date').datetimepicker({
        locale: 'de',
        format: 'DD.MM.YYYY'
    });

    // Initialise bootstrap datetimepicker
    $('#dateTimePickerContainer .input-group.time').datetimepicker({
        locale: 'de',
        format: 'DD.MM.YYYY HH:mm'
    });

    // Initialise select2
    var select = $('select').select2({
        theme: "bootstrap"
    })

    // Initialise DataTable
    var table = $('table').DataTable({
        "language": {
            "lengthMenu": "_MENU_ Datensätze pro Seite",
            "zeroRecords": "Nichts gefunden",
            "info": "Seite _PAGE_ von _PAGES_",
            "infoEmpty": "Keine Datensätze verfügbar",
            "infoFiltered": "(von insgesamt _MAX_ Einträge gefiltert)",
            "search": "Suchen:",
            "paginate": {
                "first":      "erste",
                "last":       "letzte",
                "next":       "nächste",
                "previous":   "vorherige"
            },
        },
        "columnDefs": [
            { "visible": false, "targets": 0 }
        ],
        responsive: true

    });
    
    // Listener on any table_main row click.
    $('.table_main > tbody').on( 'click', 'tr', function () {
        // If clicked already selected row.
        if ($(this).hasClass('selected')) {
            $(this).removeClass('selected');

            $("#edit_li").addClass("disabled");
            $("#edit_link").removeAttr("href");
            $("#info_li").addClass("disabled");
            $("#info_link").removeAttr("href");
            $("#modal_toggle_li").addClass("disabled");
            $("#modal_toggle_link").removeAttr("data-target");
            
            $("#delete_button").removeAttr("href");
        }
        else {
            table.$("tr.selected").removeClass("selected");
            $(this).addClass("selected");

            // Adjust hyperlinks on the edit, info and delete.
            // Need to find out on what page we are currenly on and extract object id of the current table row.
            var currentUrl = $(location).attr('href');
            var arr = currentUrl.split('/');    
            var objectType = arr[arr.length - 1];
            var objectId = table.row($(this)).data()[0];
            var infoTargetUrl = "/" + objectType + "/info/" + objectId;
            var editTargetUrl = "/" + objectType + "/edit/" + objectId;
            var deleteTargetUrl = "/" + objectType + "/delete/" + objectId;

            $("#info_li").removeClass("disabled");
            $("#info_link").attr("href", infoTargetUrl);
            $("#edit_li").removeClass("disabled");
            $("#edit_link").attr("href", editTargetUrl);
            $("#modal_toggle_li").removeClass("disabled");
            $("#modal_toggle_link").attr("data-target", "#confirmDialog");
            
            $("#delete_button").attr("href", deleteTargetUrl);
        }
        
    });
 
    // Adjust active class on links from navbar depending on current URL
    adjustActiveListItem();    

    /**
     * For a later cause. 
     * The reason this requires special consideration is that when the DataTable is initialised in a hidden element 
     * the browser doesn't have any measurements with which to give the DataTable, and this will result in the misalignment 
     * of columns when scrolling is enabled.
     */
    $('a[data-toggle="tab"]').on( 'shown.bs.tab', function (e) {
        //$.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust();
        console.log('tab changed');
    } );

    
});

// Dynamically adjust links class on the navbar
function adjustActiveListItem() {
    var currentUrl = $(location).attr('href');
    var arr = currentUrl.split('/');  

    $(".nav navbar-nav > li.active").removeClass("active");

    if (jQuery.inArray("patients", arr) !== -1) {
        $("#patients_li").addClass("active");
    } else if (jQuery.inArray("med-checkups", arr) !== -1) {
        $("#med_checkups_li").addClass("active");
    } else if (jQuery.inArray("arrangements", arr) !== -1) {
        $("#arrangements_li").addClass("active");
    } else if (jQuery.inArray("log", arr) !== -1) {
        $("#log_li").addClass("active");
    }
}



               
