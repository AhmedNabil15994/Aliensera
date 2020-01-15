/**
 * Master Js
 */

console.log("[x] Loading Master js .... Done");

// function date_time()  {
//     date = new Date;
//     year = date.getFullYear();
//     month = date.getMonth();
//     months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
//     d = date.getDate();
//     day = date.getDay();
//     days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
//
//     h = date.getHours();
//     if(h < 10) {
//         h = "0"+h;
//     }
//
//     m = date.getMinutes();
//     if(m<10) {
//         m = "0"+m;
//     }
//
//     s = date.getSeconds();
//     if(s<10) {
//         s = "0"+s;
//     }
//
//     result = '( '+d+' '+months[month]+' '+year+' &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  '+h+':'+m+':'+s+' )';
//     document.getElementById('date_time').innerHTML = result;
//     setTimeout('date_time("date_time");', '1000');
//     return true;
// }

function initDatePicker() {
    $('#date_added').datepicker({
        todayBtn: "linked",
        keyboardNavigation: false,
        forceParse: false,
        calendarWeeks: true,
        autoclose: true
    });

    $('.date_modified').datepicker({
        todayBtn: "linked",
        keyboardNavigation: false,
        forceParse: false,
        calendarWeeks: true,
        autoclose: true,
        format: "dd-mm-yyyy"
    });

    $('.date_noPrevious').datepicker({
        todayBtn: "linked",
        keyboardNavigation: false,
        forceParse: false,
        calendarWeeks: true,
        autoclose: true,
        format: "dd-mm-yyyy",
        startDate: new Date()
    });

    $('.date-single').datetimepicker({
        format: 'DD-MM-YYYY'
    });
}

$(function () {
    $('.datetimepicker').datetimepicker();
});

// function GetFollowToday(){
//     $.get("/GetBookingToday", function(data) {
//         $('.BookingTodayFollow').text(data);
//         $('.HeaderNotify').text(data);
//     });
// }

// function HeaderNotify() {
//     var count = 0;
//
//     $.get("/GetBookingToday", function(booking) {
//         count = count + parseInt(booking);
//         $('.HeaderNotify').text(count);
//     });
// }

$(document).ready(function() {
    // $('.footable').footable();

    initDatePicker();
});


$(document).ready(function() {
    $('.summernote').summernote();
});

$('.nav a').filter('[href="' + window.location.href + '"]').parents('li').addClass('active');
$('.nav a').filter('[href="' + window.location.href + '"]').parents('ul').addClass('in');

$("#fileUpload").on('change', function () {
    //Get count of selected files
    var countFiles = $(this)[0].files.length;
    var imgPath = $(this)[0].value;
    var extn = imgPath.substring(imgPath.lastIndexOf('.') + 1).toLowerCase();
    var image_holder = $("#image-holder");
    image_holder.empty();
    var extintions = new Array('ppt','pptx','pdf','docx','doc','dotx','dot','dox','ppv','xlsx','xlsm','xml','txt','csv','xlc','jpg', 'jpeg', 'JPG', 'JPEG', 'png', 'PNG', 'gif', 'GIF');
    if (extintions.includes(extn)) {
        if (typeof (FileReader) !== "undefined") {
            //loop for each file selected for uploaded.
            for (var i = 0; i < countFiles; i++) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $("<img />", {
                        "src": e.target.result,
                        "class": "thumb-image",
                        "width": "250px",
                        "height": "250px"
                    }).appendTo(image_holder);
                };
                image_holder.show();
                reader.readAsDataURL($(this)[0].files[i]);
            }
        } else {
            alert("This browser does not support FileReader.");
        }
    } else {
        alert("Pls select only images");
    }
});

// $('.dual_select').bootstrapDualListbox({
//     selectorMinimalHeight: 160
// });

    $(function () {
        $('#tableList').DataTable({
            "paging": false,
            "lengthChange": false,
            "searching": true,
            "Processing": true,
            "scrollCollapse": true,
            "ordering": false,
            "info": true,
            "autoWidth": true
        });
    });

    NoData('city');
    NoData('area');

    getCity();

    function getCity() {
        var countryId = $("#country").val();

        if(typeof countryId !== "undefined") {
            if (countryId != '' && countryId != 0) {
                $.get("/ddm/city/" + countryId, function (data, status) {
                    if (data.status.status == 0) {
                        NoData('city');
                    } else {
                        Select('city');
                        $.each(data.data, function (key, value) {
                            $('#city').append($("<option></option>")
                                .attr("value", data.data[key].id)
                                .text(data.data[key].title));

                            var city_id = $("#city_id").val(); //Get var from hidden input
                            $('#city option[value="' + parseInt(city_id) + '"]').attr('selected', 'selected');
                        });
                    }
                    getArea();
                });
            }
        }

        if (countryId == '' || countryId == 0) {
            NoData('city');
            NoData('area');
        }
    }

    function getArea() {
        var cityID = $("#city").val();

        if(typeof cityID !== "undefined") {
            if (cityID != '' && cityID != 0) {
                $.get("/ddm/area/" + cityID, function (data, status) {
                    if (data.status.status == 0) {
                        NoData('area');
                    } else {
                        Select('area');
                        $.each(data.data, function (key, value) {
                            $('#area_id').append($("<option></option>")
                                .attr("value", data.data[key].id)
                                .text(data.data[key].title));

                            var area_id = $("#area_id").val(); //Get var from hidden input
                            $('#area  option[value="' + parseInt(area_id) + '"]').attr('selected', 'selected');
                        });
                    }
                });
            }
        }
    }

    $('#country').change(function () {
        getCity()
    });
    $('#city').change(function () {
        getArea()
    });

    //Select Option
    function Select(name) {
        var selector = '#' + name + '';
        $(selector).empty();
        $(selector).append($("<option></option>").attr("value", 0).text('Select ...'));
        $(selector).prop("disabled", false);
    }

    //No data option
    function NoData(name) {
        var selector = '#' + name + '';
        $(selector).empty();
        $(selector).append($("<option></option>").attr("value", 0).text('-- No data --'));
        $(selector).prop("disabled", true);
    }

$(document).ready(function() {
    $('.fancybox').fancybox();
});
