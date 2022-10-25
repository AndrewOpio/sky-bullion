<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>

<link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.0/css/bootstrap.min.css">
<link href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>

<script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css">

<script type ="text/javascript">
   //Adding a booking
   function addBooking(e)
    {
       e.preventDefault();

       var fname = document.getElementById("first_name").value;
       var lname = document.getElementById("last_name").value;
       var email = document.getElementById("email").value;
       var contact = document.getElementById("contact").value;
       var departure_airport = document.getElementById("departure_airport").value;
       var arrival_airport = document.getElementById("arrival_airport").value;
       var departure_icao = document.getElementById("departure_icao").value;
       var arrival_icao = document.getElementById("arrival_icao").value;
       var departure_date = document.getElementById("departure_date").value;
       var return_date = document.getElementById("return_date").value;
       var passengers = document.getElementById("passengers").value;
       var trip_type = return_date ? "round trip" : "one way";
       var aircraft_id = document.getElementById("aircraft").value;

       if (fname == "" || lname == "" || email == "" || contact == "" || departure_airport == ""
          || arrival_airport == "" || departure_icao == "" || arrival_icao == "" || departure_date == "" || passengers == "") {
          alert("Please enter all required fields")
          return;
       }

       var save = document.getElementById("save").innerHTML ="saving...";

       var ajaxurl = '<?php echo admin_url('admin-ajax.php');?>';

       var data = {
            action: 'sky_bookings',
            type: 'add',
            fname,
            lname,
            email,
            contact,
            departure_icao,
            arrival_icao,
            departure_date,
            return_date,
            passengers,
            trip_type,
            aircraft_id,
            booking_status: "processing"
       }

        $.ajax({ 
            url:ajaxurl,
            type:"POST",
            data: data,
            success : function( response ){

              //console.log(response);

                if ($.trim(response) == "success") {
                    alert("Booking details submitted successfully.")
                    location.reload(true);

                } else if($.trim(response) == "failed") {
                    alert("An error occured.");
                    var save = document.getElementById("save").innerHTML ="save";

                }  else if($.trim(response) == "not found") {
                    alert("Flight estimates not found.");
                    var save = document.getElementById("save").innerHTML ="save";
                }
            },

        });
    }


   //Deleting booking
   function deleteBooking(id)
   {
      var ajaxurl = '<?php echo admin_url('admin-ajax.php');?>';
       //alert(id);
      var data = {
          action: 'sky_bookings',
          type: 'delete',
          id: id
      };

      var del = document.getElementById("delete"+id).innerHTML ="Deleting...";

      $.post(ajaxurl, data, function(response) {
           if ($.trim(response) == "success") {
                alert("Booking details deleted successfully.")
                location.reload(true);

            } else if($.trim(response) == "failed") {
                alert("An error occured.")
                var del = document.getElementById("delete"+id).innerHTML ="Delete";
            }
      }); 
   }


   //Updating a booking
   function updateBooking(e, id)
   {
        e.preventDefault();

        var fname = document.getElementById("first_name" + id).value;
        var lname = document.getElementById("last_name" + id).value;
        var email = document.getElementById("email" + id).value;
        var contact = document.getElementById("contact" + id).value;
        var departure_airport = document.getElementById("departure_airport" + id).value;
        var arrival_airport = document.getElementById("arrival_airport" + id).value;
        var departure_icao = document.getElementById("departure_icao" + id).value;
        var original_departure_icao = document.getElementById("original_departure_icao" + id).value;
        var arrival_icao = document.getElementById("arrival_icao" + id).value;
        var original_arrival_icao = document.getElementById("original_arrival_icao" + id).value;
        var departure_date = document.getElementById("departure_date" + id).value;
        var return_date = document.getElementById("return_date" + id).value;
        var passengers = document.getElementById("passengers" + id).value;
        var original_passengers = document.getElementById("original_passengers" + id).value;
        var trip_type = return_date ? "round trip" : "one way";
        var aircraft_id = document.getElementById("aircraft" + id).value;
        var original_aircraft_id = document.getElementById("original_aircraft" + id).value;
        var booking_status = document.getElementById("status" + id).value;

        if (fname == "" || lname == "" || email == "" || contact == "" || departure_airport == ""
        || arrival_airport == "" || departure_icao == "" || arrival_icao == "" || departure_date == "" || passengers == "") {
          alert("Please enter all required fields")
          return;
        }

        var edit = document.getElementById("update" + id).innerHTML = "Updating...";

        var ajaxurl = '<?php echo admin_url('admin-ajax.php');?>';

        var data = {
            action: 'sky_bookings',
            type: 'update',
            id,
            fname,
            lname,
            email,
            contact,
            departure_icao,
            original_departure_icao,
            arrival_icao,
            original_arrival_icao,
            departure_date,
            return_date,
            passengers,
            original_passengers,
            trip_type,
            aircraft_id,
            original_aircraft_id,
            booking_status
        }

        $.ajax({ 
            url:ajaxurl,
            type:"POST",
            data: data,
            success : function( response ){  
                        
                if ($.trim(response) == "success") {
                    alert("Booking details updated successfully.")
                    location.reload(true);

                } else if($.trim(response) == "failed") {
                    alert("An error occured.")
                    var edit = document.getElementById("update" + id).innerHTML = "Update";

                } else if($.trim(response) == "0") {
                    alert("Flight estimates not found.");
                    var edit = document.getElementById("update" + id).innerHTML = "Update";
                }
            },

        });
    }

</script>


<style>
    tr:nth-child(odd) {
        background-color: rgba(150, 212, 212, 0.4) !important;
    }

    button.multiselect {
        background-color: initial;
        border: 1px solid #8c8c8c;
    }
    ul.multiselect-container {height:200px;overflow-y:scroll;}
    input.multiselect-search {margin-top: 5px;}


    @media (max-width: 768px){
    .dataTables_wrapper .dt-buttons {
        text-align-last: center;
        margin-top: 15px;
        margin-bottom: 15px;
    }
   } 

   @media (min-width: 769px){
    .dataTables_wrapper .dt-buttons {
      margin-top: 20px;
      margin-bottom: -30px;

    }
   } 

  .return_date {
     background-color: white !important;
  }
</style>

<div class = "main">
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Add New Booking</h4>
      </div>

      <form method="POST" id = "add">
        <div class="input">
          <div class = "row" style = "margin-top:5px;">
            <div class="form-group col-md-6">
              <label>First Name:</label>
              <input type="text" class="form-control" id = "first_name" name = "first_name" placeholder = "Enter first name..">
            </div>
            
            <div class="form-group col-md-6">
              <label>Last Name:</label>
              <input type="text" class="form-control" id = "last_name" name = "last_name" placeholder = "Enter last name..">
            </div>
          </div>
          <div class = "row" style = "margin-top:5px;">
            <div class="form-group col-md-6">
              <label>Email:</label>
              <input type="text" class="form-control" id = "email" name = "email" placeholder = "Enter email..">
            </div>
            
            <div class="form-group col-md-6">
              <label>Contact:</label>
              <input type="text" class="form-control" id = "contact" name = "contact" placeholder = "Enter contact..">
            </div>
          </div>

          <div class = "row" style = "margin-top:5px;">
            <div class="form-group col-md-12">
              <label>Aircraft:</label><br>
              <select id="aircraft" name="aircraft" required>
              <?php              
              if($aircrafts){
                foreach ($aircrafts as $aircraft) {
              ?>  
                 <option value = "<?php echo $aircraft->id; ?>"><?php echo $aircraft->aircraft_name; ?></option>
              <?php
                }
              }
              ?>        
              </select>
            </div>
          </div>

          <div class = "row" style = "margin-top:8px;">
            <div class="form-group col-md-12">
              <label>Departure Airport:</label>
              <input type="text" class="form-control" id = "departure_airport" name = "departure_airport" placeholder = "Enter departure airport..">
              <input type = "hidden" name = "departure_icao" id = "departure_icao" required/>

              <div class="dropdown-menu" style = "margin-top: 3px;" id="suggestion-box1"></div>
            </div>
          </div>

          <div class = "row" style = "margin-top:8px;">
            <div class="form-group col-md-12">
              <label>Arrival Airport:</label>
              <input type="text" class="form-control" id = "arrival_airport" name = "arrival_airport" placeholder = "Enter arrival airport..">
              <input type = "hidden" name = "arrival_icao" id = "arrival_icao" required/>

              <div class="dropdown-menu" style = "margin-top: 3px;" id="suggestion-box2"></div>
            </div>
          </div>

          <div class = "row" style = "margin-top:8px;">
            <div class="form-group col-md-6">
              <label>Departure Date:</label>
              <input type = "date" name = "departure_date" autocomplete="off" onchange = "setBoundary()" id = "departure_date" class="form-control"  placeholder = "Enter departure date.." required/>
            </div>
            <div class="form-group col-md-6">
              <label>Return Date:</label>
              <input type = "date" name = "return_date" autocomplete="off" id = "return_date" class="form-control"  placeholder = "Enter arrival date.." required/>
            </div>
          </div>
          <div class = "row" style = "margin-top:5px; margin-bottom: 10px;">
            <div class="form-group col-md-12">
              <label>Passengers:</label>
              <input type="text" class="form-control" id = "passengers" name = "passengers" placeholder = "Enter number of passengers..">
            </div>
          </div>
          <input type="hidden" name = "add" value = "add">
        </div>
      </form>

      <div class="modal-footer">
        <button type="button" class="button" id = "save" onclick ="addBooking(event)" style = "margin-right:8px;">Save</button>
        <button type="button" class="button" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div class = "card">
  <div class = "card-header" style = "height: 35px; display: flex;">
      <h6><b>Bookings</b></h6>
      <img width=20 height=20 style = "float:right; margin-left: auto;" src = "<?php echo plugin_dir_url( __FILE__ ) .'../../images/booking.png';?>" alt = "booking"/>
  </div>

  <div>
    <div class = "card-body table-responsive">
        <table id = "table" class="table table-striped table-hover" border = "0">
            <thead>
                <tr class = "header" style = "background-color: white !important;">
                    <td class = "text">Id</td>
                    <td class = "text">First Name</td>
                    <td class = "text">Last Name</td>
                    <td class = "text">Email</td>
                    <td class = "text">Contact</td>
                    <td class = "text">Departure Airport</td>
                    <td class = "text">Arrival Airport</td>
                    <td class = "text">Departure Date</td>
                    <td class = "text">Return Date</td>
                    <td class = "text">Passengers</td>
                    <td class = "text">Trip Type</td>
                    <td class = "text">Aircraft</td>
                    <td class = "text">Minimum Price (USD)</td>
                    <td class = "text">Maximum Price (USD)</td>
                    <td class = "text">Flight Hours</td>
                    <td class = "text">Flight Minutes</td>
                    <td class = "text">Fueling Stop</td>
                    <td class = "text">Status</td>
                    <th class = "text  notexport">Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php
            if($bookings){
              $i = 1;
              foreach ($bookings as $booking) {
                global $wpdb; 
                $table_name3 = $wpdb->prefix."sky_airports";

                $departure_airport = $wpdb->get_results("SELECT * FROM $table_name3 WHERE ICAO = '$booking->departure_airport'");
                $arrival_airport = $wpdb->get_results("SELECT * FROM $table_name3 WHERE ICAO = '$booking->arrival_airport'");
            ?>
                <div id="<?php echo $booking->id."delete"; ?>" class="modal fade" role="dialog">
                  <div class="modal-dialog modal-dialog-centered">
                    <!-- Modal content-->
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title">Delete Booking</h4>
                      </div>
                      <div class="modal-body">
                        <p>Are sure you want to delete this booking?</p>
                        <button class="button" id = "<?php echo "delete".$booking->id; ?>" style = "width: 100%;" onclick ="deleteBooking(<?php echo $booking->id; ?>)">Delete</button>
                      </div>                      
                    </div>
                  </div>
                </div>


                <div id="<?php echo $booking->id."update"; ?>" class="modal fade" role="dialog">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title">Update Booking Information</h4>
                      </div>

                      <form method="POST" id = "add">
                        <div class="input">
                            <div class = "row" style = "margin-top:5px;">
                                <div class="form-group col-md-6">
                                    <label>First Name:</label>
                                    <input type="text" class="form-control" id = "<?php echo "first_name".$booking->id?>" name = "first_name" placeholder = "Enter first name.." value = "<?php echo $booking->first_name; ?>">
                                </div>
                                
                                <div class="form-group col-md-6">
                                    <label>Last Name:</label>
                                    <input type="text" class="form-control" id = "<?php echo "last_name".$booking->id?>" name = "last_name" placeholder = "Enter last name.." value = "<?php echo $booking->last_name; ?>">
                                </div>
                            </div>
                            <div class = "row" style = "margin-top:5px;">
                                <div class="form-group col-md-6">
                                    <label>Email:</label>
                                    <input type="text" class="form-control" id = "<?php echo "email".$booking->id?>" name = "email" placeholder = "Enter email.." value = "<?php echo $booking->email; ?>">
                                </div>
                                
                                <div class="form-group col-md-6">
                                    <label>Contact:</label>
                                    <input type="text" class="form-control" id = "<?php echo "contact".$booking->id?>" name = "contact" placeholder = "Enter contact.." value = "<?php echo $booking->contact; ?>">
                                </div>
                            </div>

                            <div class = "row" style = "margin-top:5px;">
                                <div class="form-group col-md-12">
                                    <label>Aircraft:</label><br>
                                    <select id="<?php echo "aircraft".$booking->id?>" name="<?php echo "aircraft".$booking->id?>" required>
                                    <?php
                                    if($aircrafts){
                                        foreach ($aircrafts as $aircraft) {
                                    ?>  
                                        <option value = "<?php echo $aircraft->id; ?>" <?php echo $booking->aircraft_id == $aircraft->id ? "selected" : "" ; ?>><?php echo $aircraft->aircraft_name; ?></option>
                                    <?php
                                        }
                                    }
                                    ?>        
                                    </select>
                                    <input type = "hidden" name = "original_aircraft" id = "<?php echo "original_aircraft".$booking->id; ?>" value = "<?php echo $booking->aircraft_id; ?>" required/>
                                </div>
                            </div>

                            <div class = "row" style = "margin-top:8px;">
                                <?php
                                    foreach ($departure_airport as $airport) {
                                    $da = $airport->name;
                                    }
                                ?>
                                <div class="form-group col-md-12">
                                    <label>Departure Airport:</label>
                                    <input type="text" class="form-control" id = "<?php echo "departure_airport".$booking->id; ?>" name = "departure_airport" placeholder = "Enter departure airport.." value = "<?php echo $da; ?>">
                                    <input type = "hidden" name = "departure_icao" id = "<?php echo "departure_icao".$booking->id; ?>" value = "<?php echo $booking->departure_airport; ?>" required/>

                                    <input type = "hidden" name = "original_departure_icao" id = "<?php echo "original_departure_icao".$booking->id; ?>" value = "<?php echo $booking->departure_airport; ?>" required/>

                                    <div class="dropdown-menu" style = "margin-top: 3px;" id="<?php echo "suggestion-box1".$booking->id; ?>"></div>
                                </div>
                            </div>

                            <div class = "row" style = "margin-top:8px;">
                                <?php
                                    foreach ($arrival_airport as $airport) {
                                       $aa = $airport->name;
                                    }
                                ?>

                                <div class="form-group col-md-12">
                                    <label>Arrival Airport:</label>
                                    <input type="text" class="form-control" id = "<?php echo "arrival_airport".$booking->id; ?>" name = "arrival_airport" placeholder = "Enter arrival airport.." value = "<?php echo $aa; ?>">
                                    <input type = "hidden" name = "arrival_icao" id = "<?php echo "arrival_icao".$booking->id; ?>" value = "<?php echo $booking->arrival_airport; ?>" required/>

                                    <input type = "hidden" name = "original_arrival_icao" id = "<?php echo "original_arrival_icao".$booking->id; ?>" value = "<?php echo $booking->arrival_airport; ?>" required/>

                                    <div class="dropdown-menu" style = "margin-top: 3px;" id="<?php echo "suggestion-box2".$booking->id; ?>"></div>
                                </div>
                            </div>

                            <div class = "row" style = "margin-top:8px;">
                                <div class="form-group col-md-6">
                                  <label>Departure Date:</label>
                                  <input type = "date" id = "<?php echo "departure_date".$booking->id?>" onchange = "setUpdateBoundary('<?php echo 'departure_date'.$booking->id; ?>', '<?php echo 'return_date'.$booking->id; ?>')" name = "departure_date" autocomplete="off" class="form-control"  placeholder = "Enter departure date.." value = "<?php echo $booking->departure_date; ?>" required/>
                                </div>
                                <div class="form-group col-md-6">
                                <label>Return Date:</label>
                                <input type = "date" id = "<?php echo "return_date".$booking->id?>" name = "return_date" autocomplete="off" class="form-control"  placeholder = "Enter arrival date.." required value = "<?php echo $booking->return_date; ?>"/>
                                </div>
                            </div>
                            <div class = "row" style = "margin-top:10px; margin-bottom: 10px;">
                                <div class="form-group col-md-6">
                                  <label>Passengers:</label>
                                  <input type="text" class="form-control" id = "<?php echo "passengers".$booking->id?>" name = "passengers" placeholder = "Enter number of passengers.." value = "<?php echo $booking->passengers; ?>">

                                  <input type="hidden" class="form-control" id = "<?php echo "original_passengers".$booking->id?>" name = "original_passengers" value = "<?php echo $booking->passengers; ?>">
                                </div>

                                <div class="form-group col-md-6">
                                  <label>Status:</label><br>
                                  <select style = "width: 100%" class = "form-select" id="<?php echo "status".$booking->id?>" name="status" required>
                                    <option value = "pending" <?php echo $booking->booking_status == "pending" ? "selected" : ""; ?>>Pending</option>
                                    <option value = "processing" <?php echo $booking->booking_status == "processing" ? "selected" : ""; ?>>Processing</option>       
                                    <option value = "completed" <?php echo $booking->booking_status == "completed" ? "selected" : ""; ?>>Completed</option>              
                                  </select>
                                </div>
                            </div>
                        </div>
                    </form>
                      
                      <div class="modal-footer">
                        <button type="button" class="button"  id = "<?php echo "update".$booking->id; ?>" style = "margin-right: 10px;" onclick ='updateBooking(event, <?php echo $booking->id; ?>)'>Update</button>
                        <button type="button" class="button" data-dismiss="modal">Close</button>
                      </div>
                    </div>
                  </div>
                </div>

                <script>
                  $(document).ready(function(){
                      var date = new Date();

                      date.setDate(date.getDate()+4);

                      var month = parseInt(date.getMonth() + 1) < 10 ? "-0" + parseInt(date.getMonth() + 1) : "-" + parseInt(date.getMonth() + 1);
                      var day = date.getDate()  < 10 ? "-0" + date.getDate()  : "-" + date.getDate();
                      var today = date.getFullYear() + month + day;

                      var departure_date = document.getElementById("departure_date<?php echo $booking->id; ?>");
                      var return_date = document.getElementById("return_date<?php echo $booking->id; ?>");

                      departure_date.min = today;

                      return_date.min = "<?php echo $booking->departure_date; ?>";

                      if (!departure_date.value) {
                        return_date.disabled = true;
                        $('#return_date').addClass('return_date');

                      } else {
                        return_date.disabled = false;

                      }
                  })
                </script>

                <script>
                    $('#<?php echo "aircraft".$booking->id?>').multiselect({		
                        nonSelectedText: 'Select Aircraft',	
                        buttonWidth: '100%',
                        includeSelectAllOption: true,
                        enableFiltering: true
                    });
                </script>


                <script>
                    // AJAX call for autocomplete 
                    $(document).ready(function(){
                        $("#<?php echo "departure_airport".$booking->id; ?>").keyup(function(){
                            if ($(this).val()) {
                                var ajaxurl = '<?php echo admin_url('admin-ajax.php');?>';

                                var data = {
                                    action: 'sky_booking_locations',
                                    keyword: $(this).val(),
                                    id: <?php echo $booking->id; ?>,
                                    icao: "departure_icao"
                                };
                                $.ajax({
                                type: "POST",
                                url: ajaxurl,
                                data: data,
                                beforeSend: function(){
                                    $("#<?php echo "departure_airport".$booking->id; ?>").css("background","#e2e2e2 url(facebook.png) no-repeat 165px");
                                },
                                success: function(data){
                                    $("#<?php echo "suggestion-box1".$booking->id; ?>").show();
                                    $("#<?php echo "suggestion-box1".$booking->id; ?>").html(data);
                                    $("#<?php echo "departure_airport".$booking->id; ?>").css("background","#FFF");
                                }
                                });
                            } else {
                                $("#<?php echo "suggestion-box1".$booking->id; ?>").hide(); 
                            }
                        });


                        $("#<?php echo "arrival_airport".$booking->id; ?>").keyup(function(){
                            if ($(this).val()) {
                                var ajaxurl = '<?php echo admin_url('admin-ajax.php');?>';

                                var data = {
                                    action: 'sky_booking_locations',
                                    keyword: $(this).val(),
                                    id: <?php echo $booking->id; ?>,
                                    icao: "arrival_icao"
                                };
                                $.ajax({
                                type: "POST",
                                url: ajaxurl,
                                data: data,
                                beforeSend: function(){
                                    $("#<?php echo "arrival_airport".$booking->id; ?>").css("background","#e2e2e2 url(facebook.png) no-repeat 165px");
                                },
                                success: function(data){
                                    $("#<?php echo "suggestion-box2".$booking->id; ?>").show();
                                    $("#<?php echo "suggestion-box2".$booking->id; ?>").html(data);
                                    $("#<?php echo "arrival_airport".$booking->id; ?>").css("background","#FFF");
                                }
                                });
                            } else {
                                $("#<?php echo "suggestion-box2".$booking->id; ?>").hide(); 
                            }
                        });
                    });

                    <?php 
                        echo '
                        function selectDeparture'.$booking->id.'(val1, val2)
                        {
                            $("#departure_airport'.$booking->id.'").val(val2);
                            $("#departure_icao'.$booking->id.'").val(val1);
                            $("#suggestion-box1'.$booking->id.'").hide();
                        }';

                        echo '
                        function selectArrival'.$booking->id.'(val1, val2)
                        {
                            $("#arrival_airport'.$booking->id.'").val(val2);
                            $("#arrival_icao'.$booking->id.'").val(val1);
                            $("#suggestion-box2'.$booking->id.'").hide();
                        }';
                    ?>
                </script>

                <div id="<?php echo $booking->id."view"; ?>" class="modal fade" role="dialog">
                    <div class="modal-dialog modal-dialog-centered">
                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-body">
                                <img src= "<?php echo $booking->aircraft_photo; ?>" width = 100% height = 100% alt = "aircraft"/>
                            </div>                      
                        </div>
                    </div>
                </div>

                <tr>
                    <td class = "data"><?php echo $i; ?></td>
                    <td class = "data"><?php echo $booking->first_name; ?></td> 
                    <td class = "data"><?php echo $booking->last_name; ?></td> 
                    <td class = "data"><?php echo $booking->email; ?></td>
                    <td class = "data"><?php echo $booking->contact; ?></td>  
                    <td class = "data">
                        <?php
                            foreach ($departure_airport as $airport) {
                               echo $airport->name;
                            }
                        ?>
                    </td>                  
                    <td class = "data">
                        <?php
                            foreach ($arrival_airport as $airport) {
                               echo $airport->name;
                            }
                        ?>
                    </td>                  
                    <td class = "data"><?php echo $booking->departure_date; ?></td>                  
                    <td class = "data"><?php echo $booking->return_date; ?></td>                  
                    <td class = "data"><?php echo $booking->passengers; ?></td> 
                    <td class = "data"><?php echo $booking->trip_type; ?></td>   
                    <td class = "data">
                        <?php
                            $aircraft = $wpdb->get_results("SELECT * FROM $table_name2 WHERE id = '$booking->aircraft_id'");
                            foreach ($aircraft as $craft) {
                               echo $craft->aircraft_name;
                            }
                        ?>
                    </td>   
                    <td class = "data"><?php echo number_format($booking->minimum_price); ?></td>                                                                    
                    <td class = "data"><?php echo number_format($booking->maximum_price); ?></td>                                                                    
                    <td class = "data"><?php echo $booking->flight_hours; ?></td>                                                                    
                    <td class = "data"><?php echo $booking->flight_minutes; ?></td>                                                                    
                    <td class = "data">
                        <?php
                          if($booking->fueling_stop != "") {
                            $fueling_stop = $wpdb->get_results("SELECT * FROM $table_name3 WHERE ICAO = '$booking->fueling_stop'");
                            foreach ($fueling_stop as $fuel_stop) {
                               echo $fuel_stop->name;
                            }
                          }
                        ?>  
                    </td>                                                                    
                    <td class = "data"><?php echo $booking->booking_status; ?></td>                                                                    
                    <td class = "action">
                        <div style = "display: flex;">
                          <button type = "button" class = "button" data-toggle = "modal" data-target = "#<?php echo $booking->id."update"; ?>" data-backdrop = "true">Update</button>
                          <button type = "button" class = "button" style = "margin-left: 5px;" data-toggle = "modal" data-target = "#<?php echo $booking->id."delete"; ?>" data-backdrop = "true">Delete</button>
                        </div>
                    </td>
                </tr>
            <?php
                $i++;
              }
            }
            ?>
            </tbody>
        </table>
     </div>
  </div>
  
  <div class = "card-footer">
      <button type = "button" style = "margin-left : 5px;" class = "button" data-toggle = "modal" data-target = "#myModal" data-backdrop = "true"><span class = "add">&plus;</span>Add Booking</button>
  </div>
</div>
</div>


<script>
   $(document).ready(function(){
      var date = new Date();

      date.setDate(date.getDate()+4);

      var month = parseInt(date.getMonth() + 1) < 10 ? "-0" + parseInt(date.getMonth() + 1) : "-" + parseInt(date.getMonth() + 1);
      var day = date.getDate()  < 10 ? "-0" + date.getDate()  : "-" + date.getDate();
      var today = date.getFullYear() + month + day;

      var departure_date = document.getElementById("departure_date");
      var return_date = document.getElementById("return_date");

      departure_date.min = today;

      if (!departure_date.value) {
         return_date.disabled = true;
         $('#return_date').addClass('return_date');
      }
   })


   //Set the boundary of the departure and arrival date
   function setBoundary()
   {
      var departure_date = document.getElementById("departure_date");
      var return_date = document.getElementById("return_date");

      if (departure_date.value) {
         return_date.disabled = false;
         return_date.min = departure_date.value;
      }
   }


  //Set the boundary of the departure and arrival date
  function setUpdateBoundary(dep, ret)
  {

      var departure_date = document.getElementById(dep);
      var return_date = document.getElementById(ret);

      if (departure_date.value) {
        return_date.disabled = false;
        return_date.min = departure_date.value;
      }
  }
</script>

<script>
    $(document).ready(function () {
        $('#table').DataTable( {
            dom:'lBfrtip',
            buttons: [{
               extend: 'pdfHtml5',
               className: 'button',
               text: 'Print PDF',
               orientation: 'landscape',
               pageSize: 'LEGAL',
               exportOptions: {
                   columns: ':not(.notexport)'
               }

            },
            {
               extend: 'excel',
               className: 'button',
               text: 'Print Excel Sheet',
               orientation: 'landscape',
               pageSize: 'LEGAL',
               exportOptions: {
                   columns: ':not(.notexport)'
               } 
            }],

            "scrollX": true

        } );

        $('#aircraft').multiselect({		
          nonSelectedText: 'Select Aircraft',	
          buttonWidth: '100%',
          includeSelectAllOption: true,
          enableFiltering: true
        });

    });
</script>


<script>
   // AJAX call for autocomplete 
   $(document).ready(function(){
      $("#departure_airport").keyup(function(){
         if ($(this).val()) {
            var ajaxurl = '<?php echo admin_url('admin-ajax.php');?>';

            var data = {
               action: 'sky_airport_locations',
               keyword: $(this).val(),
               icao: 'icao_departure'
            };
            $.ajax({
            type: "POST",
            url: ajaxurl,
            data: data,
            beforeSend: function(){
                  //$("#departure_airport").css("background","#e2e2e2 url(facebook.png) no-repeat 165px");
            },
            success: function(data){
                  $("#suggestion-box1").show();
                  $("#suggestion-box1").html(data);
                  $("#departure_airport").css("background","#FFF");
            }
            });
         } else {
            $("#suggestion-box1").hide(); 
         }
      });


      $("#arrival_airport").keyup(function(){
         if ($(this).val()) {
            var ajaxurl = '<?php echo admin_url('admin-ajax.php');?>';

            var data = {
               action: 'sky_airport_locations',
               keyword: $(this).val(),
               icao: 'icao_arrival'
            };
            $.ajax({
            type: "POST",
            url: ajaxurl,
            data: data,
            beforeSend: function(){
                  //$("#departure_airport").css("background","#e2e2e2 url(facebook.png) no-repeat 165px");
            },
            success: function(data){
                  $("#suggestion-box2").show();
                  $("#suggestion-box2").html(data);
                  $("#arrival_airport").css("background","#FFF");
            }
            });
         } else {
            $("#suggestion-box2").hide(); 
         }
      });
   });

   //To select departure airport
   function selectDeparture(val1, val2) {
         $("#departure_airport").val(val2);
         $("#departure_icao").val(val1);
         $("#suggestion-box1").hide();
   }

   //To select departure airport
   function selectArrival(val1, val2) {
         $("#arrival_airport").val(val2);
         $("#arrival_icao").val(val1);
         $("#suggestion-box2").hide();
   }
</script>
