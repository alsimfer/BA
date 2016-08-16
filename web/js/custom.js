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
            // { "visible": false, "targets": 0 }
        ],
        "aaSorting": [],
        "iDisplayLength": 25,

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


    /**
     * For a later cause. 
     * The reason this requires special consideration is that when the DataTable is initialised in a hidden element 
     * the browser doesn't have any measurements with which to give the DataTable, and this will result in the misalignment 
     * of columns when scrolling is enabled.
     */
    $('a[data-toggle="tab"]').on( 'shown.bs.tab', function (e) {
        //$.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust();
//        console.log('tab changed');
    } );

 
    // Adjust active class on links from navbar depending on current URL
    adjustActiveListItem();    
    initMedCheckupForm();    
});

// Dynamically adjust links class on the navbar
function adjustActiveListItem() {
    var currentUrl = $(location).attr('href');
    var arr = currentUrl.split('/');  

    $(".nav navbar-nav > li.active").removeClass("active");

    if (jQuery.inArray("patients", arr) !== -1) {
        $("#patients_li").addClass("active");
    } else if (jQuery.inArray("coachings", arr) !== -1) {
        $("#coachings_li").addClass("active");
    } else if (jQuery.inArray("med-checkups", arr) !== -1) {
        $("#med_checkups_li").addClass("active");
    } else if (jQuery.inArray("arrangements", arr) !== -1) {
        $("#arrangements_li").addClass("active");    
    } else if (jQuery.inArray("patient-arrangements", arr) !== -1) {
        $("#patient_arrangements_li").addClass("active");
    } else if (jQuery.inArray("logs", arr) !== -1) {
        $("#logs_li").addClass("active");
    } else if (jQuery.inArray("users", arr) !== -1) {
        $("#users_li").addClass("active");
    }
}

// Use Checkboxes for medCheckup forms to show / not show content.
function initMedCheckupForm() {

    var somKom = $('#somKom');
    var psyKom = $('#psyKom');
    var psyVerKom = $('#psyVerKom');

    var arr = [somKom, psyKom, psyVerKom];

    $.each(arr, function(i, val) {
        // Toggle hidden divs.
        val.change(function() {
            divName = val.attr("id") + "_div";
            var div = $("#" + divName);

            div.toggleClass("hidden");    

            // Check if the text inputs in this div should be disabled depending on correspondent CBs.
            div.find(':checkbox').each(function() {
                var id = $(this).attr("id");
                var divId = id + "Text";
                if ($(this).is(":checked")) {
                    $("#" + divId).attr("disabled", false);
                } else {
                    $("#" + divId).attr("disabled", true);
                }

                $(this).change(function() {
                    if ($(this).is(':checked')) {
                        $("#" + divId).attr("disabled", false);
                    } else {
                        $("#" + divId).attr("disabled", true);
                    }
                });
            });

        })
        
    });

    
}



               
