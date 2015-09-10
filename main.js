$(document).ready(function(){
    add_contact_info();
    toggle_location_sub_menu('.location-indiv-tab');
    //noaa_ajax_call();
    cdip_curl_request();

});

/**********************
 * functionName: add_contact_info();
 * @purpose: dynamically adds contact information
 * @params: N/A;
 * @globals: N/A
 * @return: N/A;
 */

function add_contact_info() {
    var email_add = $('.email_add');
    var e_icon = '<span class="glyphicon glyphicon-envelope"></span>';
    var e_name = ' alexmattingley';
    var server_name = '@gmail.com';
    var phe_class = $('.p_Num');
    var phe_icon = '<span class="glyphicon glyphicon-phone-alt"></span>';
    var pNum = ' (949) 280-6557';
    email_add.html(e_icon + e_name + server_name);
    phe_class.html(phe_icon + pNum);
}

/***************************
 * functionName: toggle_location_sub_menu();
 * @purpose: toggles the sub_menus from visible to invisible on click of the locations tab.
 * @param: location_tab
 * @globals: N/A
 * @return: N/A
 */
function toggle_location_sub_menu(location_tab){
    $(location_tab).click(function(){
        var toggle_parent = "#"+this.getAttribute("id");
        var sub_menu = $(toggle_parent + "+ul");
        sub_menu.slideToggle("slow");
    });
}

/***************************
 * functionName: noaa_ajax_call();
 * @purpose: Calls noaa api to obtain buoy data
 * @param:
 * @globals:
 * @return:
 */

function noaa_ajax_call() {
    $.ajax({
        url: "http://www.ncdc.noaa.gov/cdo-web/api/v2/datasets",
        headers:{
          token: "OcJDgFIwRvIxJoMBOrBzoWELwwTrTjzp"
        },
        data:{
            stationid:"COOP:010957"
        },
        method: "GET",
        cache: false,
        dataType: 'json',
        success: function(response) {
           console.log(response);

        }
    });
}

function cdip_curl_request(){
    $.ajax({
        url: "data_handlers/cdip_curl_request.php",
        cache: false,
        dataType: 'text',
        success: function(response) {
            var super_load_length = response.length;
            var split_by_line = response.split("\n");
            var all_buoy_info = [];
            for(var i=3; i < split_by_line.length; i++){ //eliminates headers and other unnecessary data
                all_buoy_info.push(split_by_line[i]);
            }
            //console.log(all_buoy_info); //so this each line of the table.
            console.log(all_buoy_info[0]); //length is gonna be 78 for each array
            for(var i = 0; i < all_buoy_info[0].length; i++){ //this loops through the individual
                var buoy_object = {};
                buoy_object[i] = all_buoy_info[0][i];
                console.log("buoy_object" , buoy_object);
                //station #: 0-2
                //station name: 4-29
                //DOM PST: 30-31
                //Time PST: 33-36
                //Peak Period(TP): 51-52
                //Peak Direction(DP): 55-56
                //wave-heigh(Meters)t: 47-49
            }
            console.log("buoy_object" , buoy_object);



            //for(var i = 0; i <= 234; i++){
            //    //console.log(response[i]);
            //    var buoy_object = {};
            //    buoy_object[i] = response[i];
            //    console.log(buoy_object);
            //
            //}
        }
    });
}