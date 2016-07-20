// Document ready.
$(function() {    
    console.log('clicked');

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
        ]

    });
    
    // Listener on any table row click.
    $('table > tbody').on( 'click', 'tr', function () {
        // If clicked already selected row.
        if ($(this).hasClass('selected')) {
            $(this).removeClass('selected');

            $("#edit_button").removeClass("btn-warning");
            $("#edit_button").attr("href", "#");
            $("#modal_toggle_button").removeClass("btn-danger");
            $("#modal_toggle_button").removeAttr("data-target");
            $("#delete_button").removeClass("btn-danger");
            $("#delete_button").removeAttr("href");
        }
        else {
            table.$("tr.selected").removeClass("selected");
            $(this).addClass("selected");

            // Adjust hyperlinks on the edit and delete buttons.
            // Need to find out on what page we are currenly on and extract object id of the current table row.
            var currentUrl = $(location).attr('href');
            var arr = currentUrl.split('/');    
            var objectType = arr[arr.length - 1];
            var objectId = table.row($(this)).data()[0];
            var editTargetUrl = "/" + objectType + "/edit/" + objectId;
            var deleteTargetUrl = "/" + objectType + "/delete/" + objectId;

            $("#edit_button").addClass("btn-warning");
            $("#edit_button").attr("href", editTargetUrl);
            $("#modal_toggle_button").addClass("btn-danger");
            $("#modal_toggle_button").attr("data-target", "#confirmDialog");
            $("#delete_button").attr("href", deleteTargetUrl);
        }
        
    });
 

});


               
