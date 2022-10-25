<?php 
   $page =  get_page_by_title("TRIP DETAILS");
   $id = $page->ID;
   $perma = get_permalink($id);

    if(!isset($_SESSION['departure_airport'])) {
      wp_redirect($perma);
    }

    function post_data($data)
    {  
        $url = "https://frc.aviapages.com/flight_calculator/";

        $json = json_encode($data);
        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $headers[] =  'Authorization: Token D7M5P5d0hk45Xcjxes7F1o34dn00lip3pc6A';
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST,"POST");
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, true); // Do not send to screen
        curl_setopt($ch, CURLOPT_USERAGENT, 'SKYBULLION');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response=curl_exec($ch);
        curl_close($ch);
        $response=json_decode($response);
        return $response;
    }
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>

<style>
   .name {
    display: flex;
    align-items: center; 
    justify-content: center;
    max-width: 50%;
    padding-top: 5px;
    padding-bottom: 5px;
   }

   @media (max-width: 768px){
    .name{
        max-width: 100%;
    }
    .details {
        margin-top: 30px;
    }
   } 

   .right-btn {
      margin-left: 10px;
   }
   .form-container {
      width: 80%;
      margin: auto;
      margin-top: 30px;
      border-radius: 0px;
      box-shadow: 0px 8px 20px 0px #e2e2e2;
   }
   
   .form-container:hover {
      box-shadow: 0px 8px 60px 0px #e2e2e2;
   }



input[type="text"],input[type="date"],
select.form-control {
  background: transparent;
  border: none;
  border-bottom: 1px solid #e6e6e6;
  -webkit-box-shadow: none;
  box-shadow: none;
  border-radius: 0;
}

input[type="text"]:focus, input[type="date"]:focus,
select.form-control:focus {
  -webkit-box-shadow: none;
  box-shadow: none;
}

.btn:focus {
  box-shadow: none;
}

   .text-field {
        position: relative;
        margin: 10px 2.5px 20px 2.5px;
    }

input {
        display: inline-block;
        border-bottom: solid medium #999;
        color: #444;
        background-color: #fafafa;
        padding: 10px 0px 10px 0px;
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
    }

    input:focus {
        border-bottom: solid medium #32cd32;
        background-color:#fff;
    }

label {
        color: #999;
        position: absolute;
        pointer-events: none;
        left: 0px;
        top: 10px;
        bottom: 5px;
        transition: 0.2s;
        font-size: 12px;
    }

input:focus ~ label, input:valid ~ label {
        top: -10px;
        left: 0px;
        font-size: small;
        background-color: #fff;
        padding:0 5px 0 5px;
        height: 18px;
    }

.v-line {
  border-left: 1px solid #b6b6b6;
}

.center {
  display: block;
  margin-left: auto;
  margin-right: auto;
  width: 50%;
  height: 100%;
}

.flex-container {
    display: flex;
}
</style>


<script>
    function bookAircraft(e, id)
    {
       e.preventDefault();

       var fname = document.getElementById("first_name" + id).value;
       var lname = document.getElementById("last_name" + id).value;
       var email = document.getElementById("email" + id).value;
       var contact = document.getElementById("contact" + id).value;
       var techstop = document.getElementById("techstop" + id).value;
       var departure_icao = "<?php echo $_SESSION['departure_icao']; ?>";
       var arrival_icao = "<?php echo $_SESSION['arrival_icao']; ?>";
       var departure_date = "<?php echo $_SESSION['departure_date']; ?>";
       var return_date = "<?php echo $_SESSION['return_date']; ?>";
       var passengers = "<?php echo $_SESSION['passengers']; ?>";
       var trip_type = "<?php echo $_SESSION['trip_type']; ?>";
       var aircraft_id = id;

       if (fname == "" || lname == "" || email == "" || contact == "") {
          alert("Please enter all required fields")
          return;
       }

       var ajaxurl = '<?php echo admin_url('admin-ajax.php');?>';

       var data = {
            action: 'sky_bookings',
            type: 'add',
            fname,
            lname,
            email,
            contact,
            techstop,
            departure_icao,
            arrival_icao,
            departure_date,
            return_date,
            passengers,
            trip_type,
            aircraft_id,
            booking_status: "pending"
       }

        $.ajax({ 
            url:ajaxurl,
            type:"POST",
            data: data,
            success : function( response ){
                if ($.trim(response) == "success") {
                    alert("Booking details submitted successfully.")
                    location.reload(true);

                } else if($.trim(response) == "failed") {
                    alert("An error occured.")

                }  else if($.trim(response) == "not found") {
                    alert("Flight estimates not found.");
                }
            },

        });
    }
</script>


<div>
   <div style = "display: flex; align-items: center; justify-content: center;">
    <div style = "text-align: center; display: flex; flex-direction: column; align-items: center; justify-content: center;">
        <h3>TRIP INFORMATION</h3>
        <?php 
            global $wpdb; 
            $table_name = $wpdb->prefix."sky_airports";

            $departure = $wpdb->get_results("SELECT name FROM $table_name WHERE ICAO = '$_SESSION[departure_icao]'");
            $arrival = $wpdb->get_results("SELECT name FROM $table_name WHERE ICAO = '$_SESSION[arrival_icao]'");
            
            foreach ($departure as $dep_airport) {
                $departure_airport = $dep_airport->name;
            }

            foreach ($arrival as $arr_airport) {
                $arrival_airport = $arr_airport->name;
            }
        ?>
        <span>Departure Airport: <?php echo str_repeat("&nbsp;", 2).$departure_airport; ?></span>
        <span style = "margin-top: 5px;">Arrival Airport: <?php echo str_repeat("&nbsp;", 2).$arrival_airport; ?></span>
        <span style = "margin-top: 5px;">Departure Date: <?php echo str_repeat("&nbsp;", 2).$_SESSION['departure_date']; ?></span>
        <?php 
           if($_SESSION['trip_type'] == 'round trip') {
        ?>
            <span style = "margin-top: 5px;">Return Date: <?php echo str_repeat("&nbsp;", 2).$_SESSION['return_date']; ?></span>
        <?php
        }
        ?>
        <span style = "margin-top: 5px;">Passengers: <?php echo str_repeat("&nbsp;", 2).$_SESSION['passengers']; ?></span>
    </div>
   </div>

   <hr>
  <?php
    if($aircrafts){
    $i = 1;
    $visibility = false;

    foreach ($aircrafts as $aircraft) {
        
        if($aircraft->aircraft_status == "1") {
          
            global $wpdb; 
            $minimum_price = 0;
            $maximum_price = 0;
            $techstop = "";
            $array = array();
            $array["departure_airport"] = $_SESSION['departure_icao'];
            $array["arrival_airport"] = $_SESSION['arrival_icao'];
            $array["aircraft"] = $aircraft->aircraft_name;
            $array["pax"] = $_SESSION['passengers'];
            $array["airway_time"] = true;
            $array["airway_fuel"] = true;
            $array["airway_distance"] = true;
            $array["advise_techstops"] = true;

            $response = post_data($array);
            
            if(isset($response->time->airway) || isset($response->airport->techstop)) {
              
                if(isset($response->airport->techstop)) {

                    $techstops = array();
                    $techstops = $response->airport->techstop;
                    $techstop = $techstops[sizeof($techstops)-1];
                    $distance = $response->distance->airway;

                    $array["arrival_airport"] = $techstop;          
                    $response = post_data($array);
                    
                    $first_leg_airway = $response->time->airway;

                    $array["departure_airport"] = $techstop;
                    $array["arrival_airport"] = $_SESSION['arrival_icao'];
                    $response = post_data($array);
    
                    $second_leg_airway = $response->time->airway;

                    $total_airway = $first_leg_airway + $second_leg_airway;

                    if($distance <= $aircraft->flight_range){
                        $techstop = "";
                    }

                } else {
                    $total_airway = $response->time->airway;
                }

                $hours = floor($total_airway / 60);
                $minutes = ($total_airway % 60);

                $table_name = $wpdb->prefix."sky_pricing";
                $pricing = $wpdb->get_results("SELECT * FROM $table_name WHERE aircraft_id = $aircraft->id");
                
                foreach ($pricing as $price) {
                    $minimum_price = ceil(($price->minimum_range*$hours) + ($price->minimum_range*($minutes / 60)));
                    $maximum_price = ceil(($price->maximum_range*$hours) + ($price->maximum_range*($minutes / 60)));
                }
    ?>

    <div id="<?php echo "book".$aircraft->id; ?>" class="modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div style = "width: 100%; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                    <h4 class="modal-title">Booking Information</h4>
                    <h6 class="modal-title"><?php echo $aircraft->aircraft_name; ?></h6>
                    </div>
                </div>

                <form>
                    <div style = "padding: 15px;">
                        <div class = "row" style = "margin-top:5px;">
                            <div class="col-md-6">
                                <div class="text-field">
                                    <input type="text" class = "form-control" id = "<?php echo "first_name".$aircraft->id; ?>" required>
                                    <label>First Name:</label>
                                </div>     
                            </div>
                            
                            <div class="col-md-6">
                                <div class="text-field">
                                    <input type="text" class = "form-control" id = "<?php echo "last_name".$aircraft->id; ?>" required>
                                    <label>Last Name:</label>
                                </div>                         
                            </div>
                        </div>

                        <div class = "row" style = "margin-top:5px;">
                            <div class="col-md-6">
                                <div class="text-field">
                                    <input type="text" class = "form-control" id = "<?php echo "email".$aircraft->id; ?>" required>
                                    <label>Email:</label>
                                </div>     
                            </div>
                            
                            <div class="col-md-6">
                                <div class="text-field">
                                    <input type="text" class = "form-control" id = "<?php echo "contact".$aircraft->id; ?>" required>
                                    <label>Contact:</label>
                                </div>                         
                            </div>
                        </div>
                    </div>
                </form>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-primary"  id = "<?php echo "book".$aircraft->id; ?>" style = "margin-right: 10px;" onclick ='bookAircraft(event, <?php echo $aircraft->id; ?>)'>Submit</button>
                    <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class = "card form-container">
        <div class= "card-body">
            <div class = "row">
                <div class ="col-md-6">
                    <span  class = "name" style = "color: white; background-color: #0cb3d7;"><b><?php echo $aircraft->aircraft_name; ?></b></span><br>
                    <div>
                        <img style = "margin-top: 10px; width: 100%; height: 100%" src = "<?php echo $aircraft->aircraft_photo; ?>" alt = "aircraft"/>
                    </div>
                </div>
                <div class="col-md-1 v-line"></div>
                <div class = "col-md-5 details">
                    <div class = "flex-container">
                       <div>
                            <img width=30 height=30 style = "margin-top: 6px; margin-right: 10px;" src = "<?php echo plugin_dir_url( __FILE__ ) .'../../images/dollar.png';?>" alt = "price"/>
                       </div>
                       <div>
                            <span style = "font-size: 18px;"><b>AVERAGE PRICE</b></span><br>
                            <span style = "font-size: 15px; ">$<?php echo number_format($minimum_price); ?> to $<?php echo number_format($maximum_price); ?></span>
                        </div>
                    </div>
                    <div class = "flex-container">
                       <div>
                            <img width=30 height=30 style = "margin-top: 12px; margin-right: 10px;" src = "<?php echo plugin_dir_url( __FILE__ ) .'../../images/clock.png';?>" alt = "clock"/>
                       </div>
                       <div style = "margin-top: 10px;">
                            <span style = "font-size: 18px;"><b>AVERAGE FLIGHT TIME</b></span><br>
                            <span style = "font-size: 15px;"><?php echo $hours; ?>hr <?php echo $minutes; ?>min</span>
                        </div>
                    </div>

                    <div class = "flex-container">
                        <div>
                            <img width=30 height=30 style = "margin-top: 12px; margin-right: 10px;" src = "<?php echo plugin_dir_url( __FILE__ ) .'../../images/distance.png';?>" alt = "range"/>
                        </div>
                        <div style = "margin-top: 10px;">
                            <span style = "font-size: 18px;"><b>FLIGHT RANGE</b></span><br>
                            <span style = "font-size: 15px;"><?php echo $aircraft->flight_range; ?> Kilometres</span>
                        </div>
                    </div>

                    <div class = "flex-container">
                        <div>
                            <img width=30 height=30 style = "margin-top: 12px; margin-right: 10px;" src = "<?php echo plugin_dir_url( __FILE__ ) .'../../images/box.png';?>" alt = "cargo"/>
                        </div>
                        <div style = "margin-top: 10px;">
                            <span style = "font-size: 18px;"><b>CARGO CAPACITY</b></span><br>
                            <span style = "font-size: 15px;"><?php echo $aircraft->cargo_carrying_capacity; ?> Metric Tonnes</span>
                        </div>
                    </div>
                  
                    <div class = "flex-container">
                        <div>
                           <img width=30 height=30 style = "margin-top: 12px; margin-right: 10px;" src = "<?php echo plugin_dir_url( __FILE__ ) .'../../images/travel.png';?>" alt = "travel"/>
                        </div>
                        <div style = "margin-top: 10px;">
                            <span style = "font-size: 18px; margin-left: 10px;"><b>FULL JOURNEY</b></span><br>
                            <div>
                                <ul>
                                    <li>Departure Airport: <?php echo str_repeat("&nbsp;", 2).$departure_airport; ?></li>
                                    <?php 
                                    if ($techstop != "") {
                                        global $wpdb; 
										$table_name = $wpdb->prefix."sky_airports";

                                        $tech_stop = $wpdb->get_results("SELECT name FROM $table_name WHERE ICAO = '$techstop'");
                
                                        foreach ($tech_stop as $stop) {
                                            $fueling_airport = $stop->name;
                                        }
                                    ?>
                                    <li>Fueling Stop: <?php echo str_repeat("&nbsp;", 2).$fueling_airport; ?></li>
                                    <?php
                                    }
                                    ?>
                                    <li>Destination Airport: <?php echo str_repeat("&nbsp;", 2).$arrival_airport; ?></li>
                                </ul>
                                <input type = "hidden" id = "<?php echo "techstop".$aircraft->id; ?>" value = "<?php echo $techstop; ?>"/>
                                <?php 
                                if ($_SESSION['return_date'] != "") {
                                ?>
                                <hr>

                                <ul>
                                    <li>Return Departure Airport: <?php echo str_repeat("&nbsp;", 2).$arrival_airport; ?></li>
                                    <?php 
                                    if ($techstop != "") {
                                    ?>
                                    <li>Return Fueling Stop: <?php echo str_repeat("&nbsp;", 2).$fueling_airport; ?></li>
                                    <?php
                                    }
                                    ?>
                                    <li>Return Destination Airport: <?php echo str_repeat("&nbsp;", 2).$departure_airport; ?></li>
                                </ul>
                                <?php
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                
                    <div style = "margin-top: 10px;">
                        <button class = "btn btn-primary" style = "background-color: #0cb3d7; width: 100%;"  data-toggle = "modal" data-target = "#<?php echo "book".$aircraft->id; ?>" data-backdrop = "true">BOOK NOW</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
        $i++;

        $visibility = true;

       }
    }
   }

   if ($visibility == false) {
   ?>
     <p style = "text-align: center;">No estimates found</p>
   <?php
   }
  }
  ?>
</div>