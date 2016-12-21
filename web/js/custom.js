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

 
    // Adjust active class on links from navbar depending on current URL.
    adjustActiveListItem();    
    
    // Call a specific js on a certain page.
    if ($("div").hasClass("d3_graph_div")) {
        var container = $(".d3_graph_div");
        createD3Graph(container);  
    }

    if ($("div").hasClass('med_checkup_div')) {
        initMedCheckupForm();  
    }    
    
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

function createD3Graph() {
    // Since we render the graph in .js, we need to receive the data through AJAX.
    var currentUrl = $(location).attr('href');
    var arr = currentUrl.split('/');    
    var id = arr[arr.length - 1];
    $.ajax({
        type: "POST",
        data: {
            "id": id
        },
        dataType: "json",
        url: "/ajax", 
        success: function(result) {
            // drawLineGraph(result);
            // drawOverlappingAreasGraph(result);
            drawC3(result);
        }
    });
}

function drawLineGraph(result) {
//console.log(result);
    /* implementation heavily influenced by http://bl.ocks.org/1166403 */    
    // There should be a parent container .width_provider with a defined width! 
    parentWidth = $(".width_provider").width();
    parentHeight = 400;
    // define dimensions of graph
    var m = [80, 80, 80, 80]; // margins
    var w = parentWidth - m[1] - m[3]; // width
    var h = parentHeight - m[0] - m[2]; // height
    
    // create a simple data array that we'll plot with a line (this array represents only the Y values, X will just be the index location)
    // var data = [3, 6, 2, 7, 5, 2, 0, 3, 8, 9, 2, 5, 9, 3, 6, 3, 6, 2, 7, 5, 2, 1, 3, 8, 9, 2, 5, 9, 2, 7];
    var data = [];
    jQuery.each(result, function(i, val) {
      data.push(val.weight);
    });
//console.log(data);
    // X scale will fit all values from data[] within pixels 0-w
    var x = d3.scale.linear().domain([0, data.length]).range([0, w]);
    // Y scale will fit values from 0-10 within pixels h-0 (Note the inverted domain for the y-scale: bigger is up!)
    //var y = d3.scale.linear().domain([0, 10]).range([h, 0]);
    // automatically determining max range can work something like this
    var y = d3.scale.linear().domain([d3.min(data), d3.max(data)]).range([h, 0]);

    // create a line function that can convert data[] into x and y points
    var line = d3.svg.line()
      .interpolate("basis")
      // assign the X function to plot our line as we wish
      .x(function(d,i) { 
        // verbose logging to show what's actually being done
        console.log('Plotting X value for data point: ' + d + ' using index: ' + i + ' to be at: ' + x(i) + ' using our xScale.');
        // return the X coordinate where we want to plot this datapoint
        return x(i); 
      })
      .y(function(d) { 
        // verbose logging to show what's actually being done
        console.log('Plotting Y value for data point: ' + d + ' to be at: ' + y(d) + " using our yScale.");
        // return the Y coordinate where we want to plot this datapoint
        return y(d); 
      })

    // Add an SVG element with the desired dimensions and margin.
    var graph = d3.select("#d3_graph").append("svg:svg")
          .attr("width", w + m[1] + m[3])
          .attr("height", h + m[0] + m[2])
        .append("svg:g")
          .attr("transform", "translate(" + m[3] + "," + m[0] + ")");

    // create yAxis
    var xAxis = d3.svg.axis().scale(x).tickSize(-h).tickSubdivide(true);
    // Add the x-axis.
    graph.append("svg:g")
      .attr("class", "x axis")
      .attr("transform", "translate(0," + h + ")")
      .call(xAxis);


    // create left yAxis
    var yAxisLeft = d3.svg.axis().scale(y).ticks(4).orient("left");
    // Add the y-axis to the left
    graph.append("svg:g")
          .attr("class", "y axis")
          .attr("transform", "translate(-25,0)")
          .call(yAxisLeft);
    
    // Add the line by appending an svg:path element with the data line we created above
    // do this AFTER the axes above so that the line is above the tick-lines
    graph.append("svg:path")
      .attr("d", line(data))

}

function drawOverlappingAreasGraph(data) {
    var format = d3.time.format("%Y-%m-%d");

    parentWidth = $(".width_provider").width();
    parentHeight = 400;
    
    var margin = {top: 20, right: 30, bottom: 30, left: 40},
        width = parentWidth - margin.left - margin.right,
        height = parentHeight - margin.top - margin.bottom;

    var x = d3.time.scale()
        .range([0, width]);

    var y = d3.scale.linear()
        .range([height, 0]);

    var z = d3.scale.category20c();

    var xAxis = d3.svg.axis()
        .scale(x)
        .orient("bottom")
        .ticks(d3.time.days);

    var yAxis = d3.svg.axis()
        .scale(y)
        .orient("left");

    var stack = d3.layout.stack()
        .offset("zero")
        .values(function(d) { return d.values; })
        .x(function(d) { return d.date; })
        .y(function(d) { return d.weight; });

    var nest = d3.nest()
        .key(function(d) { return d.id; });

    var area = d3.svg.area()
        .interpolate("cardinal")
        .x(function(d) { return x(d.date); })
        .y0(function(d) { return y(d.y0); })
        .y1(function(d) { return y(d.y0 + d.y); });

    var svg = d3.select("body").append("svg")
        .attr("width", width + margin.left + margin.right)
        .attr("height", height + margin.top + margin.bottom)
      .append("g")
        .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

    data.forEach(function(d) {
        d.date = format.parse(d.date);
        d.value = +d.value;
    });

    var layers = stack(nest.entries(data));

    x.domain(d3.extent(data, function(d) { return d.date; }));
    y.domain([0, d3.max(data, function(d) { return d.y0 + d.y; })]);

    svg.selectAll(".layer")
      .data(layers)
    .enter().append("path")
      .attr("class", "layer")
      .attr("d", function(d) { return area(d.values); })
      .style("fill", function(d, i) { return z(i); });

    svg.append("g")
      .attr("class", "x axis")
      .attr("transform", "translate(0," + height + ")")
      .call(xAxis);

    svg.append("g")
      .attr("class", "y axis")
      .call(yAxis);

}

// http://c3js.org/samples/chart_area_stacked.html
function drawC3(data) {        
    var startWeight = 0, endWeight = 0,
        datesArr = [],
        weightPatArrToFill = [], 
        weightMeanArr = [];

    for (var idx in data) {
        datesArr[idx] = data[idx].date;
        weightPatArrToFill[idx] = parseFloat(data[idx].patient_weight);        
        weightMeanArr[idx] = data[idx].mean;        
        // Set start and endweights.        
        if (startWeight == 0 || startWeight == null || isNaN(startWeight)) {
            startWeight = parseFloat(data[idx].patient_weight);
        }
    }

    lastWeight = getLastWeight(weightPatArrToFill);

    weightPatArr = fillArray(weightPatArrToFill);

    var chart1 = c3.generate({
        bindto: '#d3_graph1',
        data: {
            x: 'date',
            json: {
                date: datesArr,
                data1: weightPatArr,
                data2: weightMeanArr
            },
            
            type: 'spline',
            names: {
                data1: 'Patient',
                data2: 'Alle durchschnittlich'
            }
        },
        axis: {
            x: {
                type: 'timeseries',
                tick: {
                    count: 10,
                    format: '%d-%m-%Y'
                }
            }
        }
    });

    // http://c3js.org/samples/chart_donut.html.
    var chart2 = c3.generate({
        bindto: '#d3_graph2',        
        data: {
            columns: [
                ['data1', startWeight - lastWeight],
                ['data2', lastWeight],
            ],
            type : 'donut',
            onclick: function (d, i) { console.log("onclick", d, i); },
            onmouseover: function (d, i) { console.log("onmouseover", d, i); },
            onmouseout: function (d, i) { console.log("onmouseout", d, i); },

            names: {
                data1: 'Abgenommen',
                data2: 'Aktuelles Gewicht'
            }
        },
        donut: {
            title: "Fortschritt",
        }
    });

}

// [5,3,2,null,null,5,4, null, 12] will returns [5, 3, 2, 3, 4, 5, 4, 8, 12]
function fillArray(arr){
    var ind = -1,
        returnArr = JSON.parse(JSON.stringify(arr)),
//        arr = [null,null,2,null,null,9,4, null, null, null, 10, null, null],
        prevIndex,
        nJumps, valJump;

    for (var currIndex = 0; currIndex < returnArr.length; currIndex++) {
        if (returnArr[currIndex] == null || returnArr[currIndex] == 0) {
            continue;
        } else if (prevIndex === undefined || (nJumps = (currIndex - prevIndex)) === 1) {
            prevIndex = currIndex;
            continue;
        }

        valJump = ((returnArr[currIndex] - returnArr[prevIndex]) / nJumps);

        ind = (+prevIndex);
        while (++ind < (+currIndex))
            returnArr[ind] = returnArr[ind-1] + valJump;

        prevIndex = currIndex;
    }
//console.log(returnArr);
    return returnArr;
}

function getLastWeight(arr) {
    var returnVal = 0;

    if (arr.length > 0) {
        for (var i = arr.length - 1; i >= 0; i--) {
            if (returnVal == 0 || returnVal == null || isNaN(returnVal)) {
                returnVal = arr[i];
            }
        }
    }

    return returnVal;
}


               
