<?php
/*
Plugin Name: Sky Bullion
Plugin URI: https://
Description: Sky Bullion.  
Version: 1.0.0
Author: Asterisk
Author URI: https://
License: 
Text Domain: sky bullion
*/


/*

operational, email editing


airport names,
return leg, 
summary info, 
departure be fourdays earlier
Airport type -> Ajax fetch from database (multi select)
Location-> from database(Airports)
Operating days -> (multiselect)
Aircraft -> Live location not mandatory
Codes -> not all are required
-Aircraft name missing.
-Registration number
Flight range -> KM
Fuel consumption -> KM/Litre
Cargo -> Metric tonnes
Add navigation for Aircraft types.

Pricing
Minimum price per flying hour (USD)
*/


if(!defined('ABSPATH')){
   exit;
}


register_activation_hook( __FILE__, 'add_capabilities' );

function add_capabilities()
{    
    $capabilities = array('manage_sky_bullion', 'manage_airports',
                        'manage_aircraft_types', 'manage_aircrafts', 
                        'manage_pricing', 'manage_bookings', 'manage_dashboard');
    
    $user_role = get_role('administrator');

    foreach($capabilities as $cap) 
    {
        if(!$user_role->has_cap($cap)) {
            $user_role->add_cap( $cap );
        }
    }
}



register_activation_hook( __FILE__, 'add_user_role' );

function add_user_role()
{    
    //remove_role('charter_sales_representative');

    $user_role = $GLOBALS['wp_roles']->is_role( 'charter_sales_representative' );
    
    if($user_role == false) {
        add_role('charter_sales_representative', __(
            'Charter Sales Representative'),
            array(
                'read' => true,
                'manage_sky_bullion' => true,
                'manage_airports' => true,
                'manage_bookings' => true,
                'manage_aircraft_types' => true,
                'manage_aircrafts' => true,
                'manage_pricing' => true,
                'manage_dashboard' => true
            )
        );
    }
}



register_activation_hook( __FILE__, 'add_pages' );

function add_pages()
{

    if (get_page_by_title('TRIP DETAILS') == NULL) {
        $trip_details = array(
            'post_title' => __('TRIP DETAILS'),
            'post_content' => '[trip_details]',
            'post_status' => 'publish',
            'post_type' => 'page',
        );
        wp_insert_post($trip_details);
    }

    
    if (get_page_by_title('JET CHARTER') == NULL) {
        $jet_charter = array(
            'post_title' => __('JET CHARTER'),
            'post_content' => '[jet_charter]',
            'post_status' => 'publish',
            'post_type' => 'page',
        );
        wp_insert_post($jet_charter);
    }

}


register_activation_hook( __FILE__, 'add_tables' );

//creating table in wordpress database
function add_tables()
{
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();
    
    //Create airports table
    $airports = $wpdb->prefix . "sky_airports"; 
    $airports_query = "CREATE TABLE IF NOT EXISTS $airports (
     id int NOT NULL AUTO_INCREMENT,
     name LONGTEXT NOT NULL
     aircraft_type LONGTEXT NOT NULL,
     operating_status LONGTEXT NOT NULL,
     operating_days LONGTEXT NOT NULL,
     IATA LONGTEXT,
     ICAO LONGTEXT,
     FAA LONGTEXT,
     PRIMARY KEY  (id)
   ) $charset_collate";
   require_once( ABSPATH .'wp-admin/includes/upgrade.php' );
   dbDelta( $airports_query );


   //Create aircrafts table
   $aircrafts = $wpdb->prefix . "sky_aircrafts";

    $aircrafts_query = "CREATE TABLE IF NOT EXISTS $aircrafts (
     id int NOT NULL AUTO_INCREMENT,
     aircraft_name LONGTEXT NOT NULL,
     reg_number LONGTEXT NOT NULL,
     aircraft_type LONGTEXT NOT NULL,
     aircraft_photo LONGTEXT NOT NULL,
     flight_range LONGTEXT NOT NULL,
     fuel_consumption LONGTEXT NOT NULL,
     cargo_carrying_capacity LONGTEXT NOT NULL,
     live_location LONGTEXT,
     service_records LONGTEXT NOT NULL,
     aircraft_status LONGTEXT NOT NULL,
     PRIMARY KEY  (id)
   ) $charset_collate";
   require_once( ABSPATH .'wp-admin/includes/upgrade.php' );
   dbDelta( $aircrafts_query );


   //Create bookings table
   $bookings = $wpdb->prefix . "sky_bookings";

    /*$addcolumn = "ALTER TABLE $bookings ADD COLUMN minimum_price TEXT NOT NULL";
    $wpdb->query($addcolumn);

    $addcolumn = "ALTER TABLE $bookings ADD COLUMN maximum_price TEXT NOT NULL";
    $wpdb->query($addcolumn);

    $addcolumn = "ALTER TABLE $bookings ADD COLUMN flight_hours TEXT NOT NULL";
    $wpdb->query($addcolumn);

    $addcolumn = "ALTER TABLE $bookings ADD COLUMN flight_minutes TEXT NOT NULL";
    $wpdb->query($addcolumn);

    $addcolumn = "ALTER TABLE $bookings ADD COLUMN fueling_stop TEXT NOT NULL";
    $wpdb->query($addcolumn);*/


   $bookings_query = "CREATE TABLE IF NOT EXISTS $bookings (
     id int NOT NULL AUTO_INCREMENT,
     first_name LONGTEXT NOT NULL,
     last_name LONGTEXT NOT NULL,
     email LONGTEXT NOT NULL,
     contact LONGTEXT NOT NULL,
     departure_airport LONGTEXT NOT NULL,
     arrival_airport LONGTEXT NOT NULL,
     departure_date LONGTEXT NOT NULL,
     return_date LONGTEXT,
     passengers LONGTEXT NOT NULL,
     trip_type LONGTEXT NOT NULL,
     aircraft_id TEXT NOT NULL,
     booking_status TEXT NOT NULL
     PRIMARY KEY  (id)
   ) $charset_collate";
   require_once( ABSPATH .'wp-admin/includes/upgrade.php' );
   dbDelta( $bookings_query );


   //Create pricing table
   $pricing = $wpdb->prefix . "sky_pricing"; 
    $pricing_query = "CREATE TABLE IF NOT EXISTS $pricing (
     id int NOT NULL AUTO_INCREMENT,
     aircraft_id LONGTEXT NOT NULL,
     minimum_range LONGTEXT NOT NULL,
     maximum_range LONGTEXT NOT NULL,

     PRIMARY KEY  (id)
   ) $charset_collate";
   require_once( ABSPATH .'wp-admin/includes/upgrade.php' );
   dbDelta( $pricing_query );


   //Create aircrafts types table
   $aircraft_types = $wpdb->prefix . "sky_aircraft_types"; 
    $types_query = "CREATE TABLE IF NOT EXISTS $aircraft_types (
     id int NOT NULL AUTO_INCREMENT,
     aircraft_type LONGTEXT NOT NULL,

     PRIMARY KEY  (id)
   ) $charset_collate";
   require_once( ABSPATH .'wp-admin/includes/upgrade.php' );
   dbDelta( $types_query );
}



//managing aircrafts
add_action('wp_ajax_sky_aircrafts', 'sky_aircrafts');
add_action( 'wp_ajax_nopriv_sky_aircrafts', 'sky_aircrafts' );

function sky_aircrafts()
{
    global $wpdb; 
    $wpdb->show_errors();
    $table_name = $wpdb->prefix.'sky_aircrafts'; 

    if ($_POST["type"] == "add" ) {

        $aircraft = $wpdb->get_results("SELECT * FROM $table_name WHERE aircraft_name = '$_POST[name]' OR reg_number = '$_POST[reg_number]'");
        if ($aircraft) {
           return;
        }

        $filename = $_FILES["photo"]["name"];
        $tempname = $_FILES["photo"]["tmp_name"];    
        $folder = plugin_dir_path( __FILE__ )."pages/admin/aircrafts/".$filename;
        
    
        if (move_uploaded_file($tempname, $folder)) {
            $image = plugin_dir_url( __FILE__ ) ."/pages/admin/aircrafts/".$filename;

            $run = $wpdb->insert($table_name, array('aircraft_name'=>$_POST["name"], 'reg_number'=>$_POST["reg_number"], 'aircraft_type'=>$_POST["aircraft_type"], 'flight_range'=> $_POST["flight_range"],
                    'fuel_consumption'=> $_POST["fuel_consumption"], 'cargo_carrying_capacity'=>$_POST["cargo_carrying_capacity"],
                    'live_location'=>$_POST["live_location"], 'service_records'=>$_POST["service_records"],'aircraft_status'=>$_POST["status"], 'aircraft_photo'=>$image), 
                    array('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s'));
        } else {
            echo "failed";
        }

        //$wpdb->print_error();

        if ($run) {
            echo "success";

        } else {
            echo "failed";
        }

    } else if ($_POST["type"] == "delete" ) {
        $run = $wpdb->delete($table_name, array("id" => $_POST["id"]));
        
        if ($run) {
            echo "success";

        } else {
            echo "failed";
        }

    } else if ($_POST["type"] == "update" ) {

        if ($_POST["name"] != $_POST["original_name"]) {
            $aircraft = $wpdb->get_results("SELECT * FROM $table_name WHERE aircraft_name = '$_POST[name]'");
            if ($aircraft) {
                return;
            }
        } else if ($_POST["reg_number"] != $_POST["original_reg_number"]) {
            $aircraft = $wpdb->get_results("SELECT * FROM $table_name WHERE reg_number = '$_POST[reg_number]'");
            if ($aircraft) {
                return;
            }
        }


        if(isset($_FILES["photo"]["name"])) {
            $filename = $_FILES["photo"]["name"];
            $tempname = $_FILES["photo"]["tmp_name"];    
            $folder = plugin_dir_path( __FILE__ )."pages/admin/aircrafts/".$filename;
            
            if (move_uploaded_file($tempname, $folder)) {
                $image = plugin_dir_url( __FILE__ ) ."/pages/admin/aircrafts/".$filename;
    
                $run = $wpdb->update($table_name, array('aircraft_name'=>$_POST["name"], 'reg_number'=>$_POST["reg_number"], 'aircraft_type'=>$_POST["aircraft_type"], 'flight_range'=> $_POST["flight_range"],
                'fuel_consumption'=> $_POST["fuel_consumption"], 'cargo_carrying_capacity'=>$_POST["cargo_carrying_capacity"],
                'live_location'=>$_POST["live_location"],'service_records'=>$_POST["service_records"],'aircraft_status'=>$_POST["status"], 'aircraft_photo'=>$image), 
                 array("id" => $_POST["id"]));
            } else {
                echo "failed";
            }
    
        } else {
            $run = $wpdb->update($table_name, array('aircraft_name'=>$_POST["name"], 'reg_number'=>$_POST["reg_number"], 'aircraft_type'=>$_POST["aircraft_type"], 'flight_range'=> $_POST["flight_range"],
            'fuel_consumption'=> $_POST["fuel_consumption"], 'cargo_carrying_capacity'=>$_POST["cargo_carrying_capacity"],
            'live_location'=>$_POST["live_location"],'service_records'=>$_POST["service_records"],'aircraft_status'=>$_POST["status"]), 
            array("id" => $_POST["id"]));
        }

        if ($run) {
            echo "success";

        } else {
            echo "failed";
        }
    }
    
    wp_die(); 
}


//managing aircraft types
add_action('wp_ajax_sky_aircraft_types', 'sky_aircraft_types');
add_action( 'wp_ajax_nopriv_sky_aircraft_types', 'sky_aircraft_types' );

function sky_aircraft_types()
{
    global $wpdb; 
    //$wpdb->show_errors();
    $table_name = $wpdb->prefix.'sky_aircraft_types'; 


    if ($_POST["type"] == "add" ) {

        $aircraft_type = $wpdb->get_results("SELECT * FROM $table_name WHERE aircraft_type = '$_POST[aircraft_type]'");
        if ($aircraft_type) {
           return;
        }

        $run = $wpdb->insert($table_name, array('aircraft_type'=>$_POST["aircraft_type"]), 
                array('%s'));
        
        //$wpdb->print_error();

        if ($run) {
            echo "success";

        } else {
            echo "failed";
        }

    } else if ($_POST["type"] == "delete" ) {
        $run = $wpdb->delete($table_name, array("id" => $_POST["id"]));
        
        if ($run) {
            echo "success";

        } else {
            echo "failed";
        }

    } else if ($_POST["type"] == "update" ) {
        if ($_POST["aircraft_type"] != $_POST["original_type"]) {
            $aircraft_type = $wpdb->get_results("SELECT * FROM $table_name WHERE aircraft_type = '$_POST[aircraft_type]'");
            if ($aircraft_type) {
                return;
            }
        }

        $run = $wpdb->update($table_name, array('aircraft_type'=>$_POST["aircraft_type"]), 
         array("id" => $_POST["id"]));
          
        if ($run) {
            echo "success";

        } else {
            echo "failed";
        }
    }
    
    wp_die(); 
}



//managing airports
add_action('wp_ajax_sky_airports', 'sky_airports');
add_action( 'wp_ajax_nopriv_sky_airports', 'sky_airports' );

function sky_airports()
{
    global $wpdb; 
    $table_name = $wpdb->prefix.'sky_airports'; 
    $operating_days = serialize($_POST["operating_days"]);
    $aircraft_types = serialize($_POST["aircraft_types"]);

    //$wpdb->show_errors();

    if ($_POST["type"] == "add" ) {
           
        $airport = $wpdb->get_results("SELECT * FROM $table_name WHERE name = '$_POST[name]'");
        
        if ($airport) {
           return;
        }

        $run = $wpdb->insert(
            $table_name,
            array('name'=>$_POST["name"], 'aircraft_type'=>$aircraft_types, 'operating_status'=> $_POST["operating_status"],
                 'operating_days'=> $operating_days, 'IATA'=>$_POST["iata"], 'ICAO'=>$_POST["icao"],'FAA'=>$_POST["faa"]),
            array('%s', '%s', '%s', '%s', '%s', '%s', '%s')
        );

        //$wpdb->print_error();

        if ($run) {
            echo "success";

        } else {
            echo "failed";
        }

    } else if ($_POST["type"] == "delete" ) {
        $run = $wpdb->delete($table_name, array("id" => $_POST["id"]));
        
        if ($run) {
            echo "success";

        } else {
            echo "failed";
        }

    } else if ($_POST["type"] == "update" ) {

        if ($_POST["name"] != $_POST["original_name"]) {
            $airport = $wpdb->get_results("SELECT * FROM $table_name WHERE name = '$_POST[name]'");
            if ($airport) {
                return;
            }
        }

        $operating_days = serialize($_POST["operating_days"]);
        $aircraft_types = serialize($_POST["aircraft_types"]);

        $run = $wpdb->update(
            $table_name, 
            array('name'=>$_POST["name"], 'aircraft_type'=>$aircraft_types, 'operating_status'=> $_POST["operating_status"],
                 'operating_days'=> $operating_days, 'IATA'=>$_POST["iata"], 'ICAO'=>$_POST["icao"], 'FAA'=>$_POST["faa"]), 
             array("id" => $_POST["id"])
        );

        if ($run) {
            echo "success";

        } else {
            echo "failed";
        }
    }

    wp_die(); 
}


//get locations
add_action('wp_ajax_sky_locations', 'sky_locations');
add_action( 'wp_ajax_nopriv_sky_locations', 'sky_locations' );

function sky_locations()
{
    if (!empty($_POST["keyword"])) {
        global $wpdb; 
        $table_name = $wpdb->prefix.'sky_airports';
        $keyword = $_POST["keyword"]; 
        $locations = $wpdb->get_results("SELECT * FROM $table_name WHERE name like '" . $keyword . "%' OR IATA like '" . $keyword . "%'");

        if ($locations) {
        ?>
            <div style="padding: 10px;  width: 100%;">
        <?php foreach ($locations as $location) {?>  
            <p style="margin-bottom: 2px;" onClick="<?php echo "selectNumber".$_POST["id"]?>(<?php echo $location->id; ?>, '<?php echo $location->name; ?>');"><?php echo $location->name; ?></p>
            <hr style="margin-top: 5px; margin-bottom: 5px;"/>
        <?php } ?>
            </div>
        <?php 
            } else {
        ?>
            <div style="padding: 10px; width: 100%;">No search results</div>
        <?php
            } 
    }
    wp_die(); 
}


//aircrafts lists page
add_action('wp_ajax_sky_aircrafts_list', 'sky_aircrafts_list');
add_action( 'wp_ajax_nopriv_sky_aircrafts_list', 'sky_aircrafts_list' );

function sky_aircrafts_list()
{    
    $_SESSION['trip_type'] = $_POST['trip_type'];
    $_SESSION['departure_airport'] = $_POST['departure_airport'];
    $_SESSION['departure_icao'] = $_POST['departure_icao']; 
    $_SESSION['arrival_airport'] = $_POST['arrival_airport']; 
    $_SESSION['arrival_icao'] = $_POST['arrival_icao']; 
    $_SESSION['departure_date'] = $_POST['departure_date']; 
    $_SESSION['return_date'] = $_POST['return_date']; 
    $_SESSION['passengers'] = $_POST['passengers']; 

    wp_die(); 
}


//get locations
add_action('wp_ajax_sky_airport_locations', 'sky_airport_locations');
add_action( 'wp_ajax_nopriv_sky_airport_locations', 'sky_airport_locations' );

function sky_airport_locations()
{
    if (!empty($_POST["keyword"])) {
        global $wpdb; 
        $table_name = $wpdb->prefix.'sky_airports';
        $keyword = $_POST["keyword"]; 
        $locations = $wpdb->get_results("SELECT * FROM $table_name WHERE name like '" . $keyword . "%' OR IATA like '" . $keyword . "%'");

        if ($locations) {
        ?>
            <div style="padding: 10px;  width: 100%;">
        <?php foreach ($locations as $location) {
                 if ($_POST['icao'] == "icao_departure") {
        ?>  
            <a href = "#" style="text-decoration: none; margin-bottom: 2px;" onClick="selectDeparture('<?php echo $location->ICAO; ?>', '<?php echo $location->name; ?>');"><?php echo $location->name; ?></a>
        <?php 
                 } else if($_POST['icao'] == "icao_arrival") {
        ?>
            <a href = "#" style="text-decoration: none;  margin-bottom: 2px;" onClick="selectArrival('<?php echo $location->ICAO; ?>', '<?php echo $location->name; ?>');"><?php echo $location->name; ?></a>
        <?php
                 }
        ?>    
            <hr style="margin-top: 5px; margin-bottom: 5px;"/>
        <?php
        }
        ?>
            </div>
        <?php 
            } else {
        ?>
            <div style="padding: 10px; width: 100%;">No search results</div>
            <script>
                $("#<?php echo $_POST['icao'];?>").val("");
            </script>
        <?php
            } 
    }
    wp_die(); 
}


//get booking locations
add_action('wp_ajax_sky_booking_locations', 'sky_booking_locations');
add_action( 'wp_ajax_nopriv_sky_booking_locations', 'sky_booking_locations' );

function sky_booking_locations()
{
    if (!empty($_POST["keyword"])) {
        global $wpdb; 
        $table_name = $wpdb->prefix.'sky_airports';
        $keyword = $_POST["keyword"]; 
        $locations = $wpdb->get_results("SELECT * FROM $table_name WHERE name like '" . $keyword . "%' OR IATA like '" . $keyword . "%'");

        if ($locations) {
        ?>
            <div style="padding: 10px;  width: 100%;">
        <?php foreach ($locations as $location) {
                 if ($_POST['icao'] == "departure_icao") {
        ?>  
            <a href = "#" style="text-decoration: none; margin-bottom: 2px;" onClick="selectDeparture<?php echo $_POST['id']; ?>('<?php echo $location->ICAO; ?>', '<?php echo $location->name; ?>');"><?php echo $location->name; ?></a>
        <?php 
                 } else if($_POST['icao'] == "arrival_icao") {
        ?>
            <a href = "#" style="text-decoration: none;  margin-bottom: 2px;" onClick="selectArrival<?php echo $_POST['id']; ?>('<?php echo $location->ICAO; ?>', '<?php echo $location->name; ?>');"><?php echo $location->name; ?></a>
        <?php
                 }
        ?>    
            <hr style="margin-top: 5px; margin-bottom: 5px;"/>
        <?php
        }
        ?>
            </div>
        <?php 
            } else {
        ?>
            <div style="padding: 10px; width: 100%;">No search results</div>
            <script>
                $("#<?php echo $_POST['icao'].$_POST['id'];?>").val("");
            </script>
        <?php
            } 
    }
    wp_die(); 
}


//managing pricing
add_action('wp_ajax_sky_pricing', 'sky_pricing');
add_action( 'wp_ajax_nopriv_sky_pricing', 'sky_pricing' );

function sky_pricing()
{
    global $wpdb; 
    //$wpdb->show_errors();
    $table_name = $wpdb->prefix.'sky_pricing'; 

    if ($_POST["type"] == "add" ) {
                
        $pricing = $wpdb->get_results("SELECT * FROM $table_name WHERE aircraft_id = '$_POST[aircraft_id]'");
        
        if ($pricing) {
           return;
        }

        $run = $wpdb->insert(
            $table_name, 
            array('aircraft_id'=>$_POST["aircraft_id"], 'minimum_range'=> $_POST["minimum_range"],
                 'maximum_range'=> $_POST["maximum_range"]),
            array('%s', '%s', '%s')
        );
        
        //$wpdb->print_error();

        if ($run) {
            echo "success";

        } else {
            echo "failed";
        }

    } else if ($_POST["type"] == "delete" ) {
        $run = $wpdb->delete($table_name, array("id" => $_POST["id"]));
        
        if ($run) {
            echo "success";

        } else {
            echo "failed";
        }

    } else if ($_POST["type"] == "update" ) {

        if ($_POST["aircraft_id"] != $_POST["original_aircraft_id"]) {
            $pricing = $wpdb->get_results("SELECT * FROM $table_name WHERE aircraft_id = '$_POST[aircraft_id]'");
            if ($pricing) {
                return;
            }
        }

        $run = $wpdb->update(
            $table_name, 
            array('aircraft_id'=>$_POST["aircraft_id"], 'minimum_range'=> $_POST["minimum_range"],
                 'maximum_range'=> $_POST["maximum_range"]), 
            array("id" => $_POST["id"])
        );

        if ($run) {
            echo "success";

        } else {
            echo "failed";
        }
    }

    wp_die(); 
}



//add bookings
add_action('wp_ajax_sky_bookings', 'sky_bookings');
add_action( 'wp_ajax_nopriv_sky_bookings', 'sky_bookings' );

function post_data_to_url($data)
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


function sky_bookings()
{
    global $wpdb; 
    global $current_user; 
    $admins = get_users('role=Administrator');

    $wpdb->show_errors();

    $table_name = $wpdb->prefix.'sky_bookings'; 

    if ($_POST["type"] == "add" ) {

        $aircrafts_table = $wpdb->prefix."sky_aircrafts";
        $aircraft = $wpdb->get_results("SELECT * FROM $aircrafts_table WHERE id = $_POST[aircraft_id]");
            
        foreach ($aircraft as $craft) {
            $aircraft_name = $craft->aircraft_name;
            $flight_range = $craft->flight_range;
        }

        $minimum_price = 0;
        $maximum_price = 0;
        $techstop = "";
        $array = array();
        $array["departure_airport"] = $_POST['departure_icao'];
        $array["arrival_airport"] = $_POST['arrival_icao'];
        $array["aircraft"] = $aircraft_name;
        $array["pax"] = $_POST['passengers'];
        $array["airway_time"] = true;
        $array["airway_fuel"] = true;
        $array["airway_distance"] = true;
        $array["advise_techstops"] = true;

        $response = post_data_to_url($array);

        if(isset($response->time->airway) || isset($response->airport->techstop)) {
                        
            if(isset($response->airport->techstop)) {
                
                $techstops = array();
                $techstops = $response->airport->techstop;
                $techstop = $techstops[sizeof($techstops)-1];
                $distance = $response->distance->airway;

                $array["arrival_airport"] = $techstop;          
                $response = post_data_to_url($array);
                
                $first_leg_airway = $response->time->airway;

                $array["departure_airport"] = $techstop;
                $array["arrival_airport"] = $_POST['arrival_icao'];
                $response = post_data_to_url($array);

                $second_leg_airway = $response->time->airway;

                $total_airway = $first_leg_airway + $second_leg_airway;

                if($distance <= $flight_range){
                    $techstop = "";
                }

            } else {
                $total_airway = $response->time->airway;
            }

            $hours = floor($total_airway / 60);
            $minutes = ($total_airway % 60);

            $pricing_table = $wpdb->prefix."sky_pricing";
            $pricing = $wpdb->get_results("SELECT * FROM $pricing_table WHERE aircraft_id = $_POST[aircraft_id]");
            
            foreach ($pricing as $price) {
                $minimum_price = ceil(($price->minimum_range*$hours) + ($price->minimum_range*($minutes / 60)));
                $maximum_price = ceil(($price->maximum_range*$hours) + ($price->maximum_range*($minutes / 60)));
            }

            $booking = $wpdb->insert(
                $table_name, 
                array('first_name'=>$_POST["fname"], 'last_name'=> $_POST["lname"], 'email'=> $_POST["email"],
                        'contact'=> $_POST["contact"], 'departure_airport'=> $_POST["departure_icao"],
                        'arrival_airport'=> $_POST["arrival_icao"], 'departure_date'=> $_POST["departure_date"],
                        'return_date'=> $_POST["return_date"], 'passengers'=> $_POST["passengers"], 'trip_type'=> $_POST["trip_type"],
                        'aircraft_id'=> $_POST["aircraft_id"], 'booking_status'=> $_POST["booking_status"], 'minimum_price'=> $minimum_price,
                        'maximum_price'=> $maximum_price, 'flight_hours'=> $hours, 'flight_minutes'=> $minutes,
                        'fueling_stop'=> $techstop),
                array('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s')
            );
            
            //$wpdb->print_error();
            
            if($booking) {
                echo "success";

                $headers  = "From: " ."noreply@flight.skybullion.net". "\r\n";
                $headers .= "MIME-Version: 1.0\r\n";
                $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

                $user_email = $_POST["email"];
                $user_subject = "Flight Booking";
                $user_message = "Your booking details have been submitted successfully. You will be informed once it has been processed.";
                $user_status = mail($user_email, $user_subject, $user_message, $headers);
            

                foreach ($admins as $admin) 
                {
                    $headers  = "From: " ."noreply@flight.skybullion.net". "\r\n";
                    $headers .= "MIME-Version: 1.0\r\n";
                    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        
                    $admin_email = $admin->user_email;
                    $admin_subject = "New Flight Booking";
                    
                    if($current_user->roles[0] == 'charter_sales_representative' || $current_user->roles[0] == 'administrator') {
                        $admin_message = "A new flight booking for ".$_POST["first_name"]."  ".$_POST["last_name"]."  "."has been made by ".$current_user->user_email;
                
                    } else {
                        $admin_message = "A new flight booking by ".$_POST["first_name"]."  ".$_POST["last_name"]."  "."has been made.";
        
                    }

                    $admin_status = mail($admin_email, $admin_subject, $admin_message, $headers);
                }  


                if($current_user->roles[0] == 'charter_sales_representative') {

                    $headers  = "From: " ."noreply@flight.skybullion.net". "\r\n";
                    $headers .= "MIME-Version: 1.0\r\n";
                    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

                    $rep_email = $current_user->user_email;
                    $rep_subject = "New Flight Booking";
                    $rep_message = "A new flight booking for ".$_POST["first_name"]."  ".$_POST["last_name"]."  "." has been made by you.";
                    $rep_status = mail($rep_email, $rep_subject, $rep_message, $headers);
                }

            } else {
                echo "failed";

            }

        } else {
            echo "not found";
        }


    } else if ($_POST["type"] == "delete" ) {
        $run = $wpdb->delete($table_name, array("id" => $_POST["id"]));
        
        if($run) {
            echo "success";
            
            foreach ($admins as $admin) 
            {
                $headers  = "From: " ."noreply@flight.skybullion.net". "\r\n";
                $headers .= "MIME-Version: 1.0\r\n";
                $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

                $admin_email = $admin->user_email;
                $admin_subject = "Booking Deletion.";
                
                if($current_user->roles[0] == 'charter_sales_representative' || $current_user->roles[0] == 'administrator') {
                    $admin_message = "A flight booking been deleted by  ".$current_user->user_email;
                    $admin_status = mail($admin_email, $admin_subject, $admin_message, $headers);
                }

            }  


            if($current_user->roles[0] == 'charter_sales_representative') {

                $headers  = "From: " ."noreply@flight.skybullion.net". "\r\n";
                $headers .= "MIME-Version: 1.0\r\n";
                $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

                $rep_email = $current_user->user_email;
                $rep_subject = "Booking Deletion.";
                $rep_message = "A flight booking been deleted by you";
                $rep_status = mail($rep_email, $rep_subject, $rep_message, $headers);
            }

        } else {
            echo "failed";

        }


    } else if ($_POST["type"] == "update" ) {

        if($_POST["departure_icao"] != $_POST["original_departure_icao"] || $_POST["arrival_icao"] != $_POST["original_arrival_icao"] ||
          $_POST["passengers"] != $_POST["original_passengers"] || $_POST["aircraft_id"] != $_POST["original_aircraft_id"]) {
            
            $aircrafts_table = $wpdb->prefix."sky_aircrafts";
            $aircraft = $wpdb->get_results("SELECT * FROM $aircrafts_table WHERE id = $_POST[aircraft_id]");
                
            foreach ($aircraft as $craft) {
                $aircraft_name = $craft->aircraft_name;
                $flight_range = $craft->flight_range;
            }
    
            $minimum_price = 0;
            $maximum_price = 0;
            $techstop = "";
            $array = array();
            $array["departure_airport"] = $_POST['departure_icao'];
            $array["arrival_airport"] = $_POST['arrival_icao'];
            $array["aircraft"] = $aircraft_name;
            $array["pax"] = $_POST['passengers'];
            $array["airway_time"] = true;
            $array["airway_fuel"] = true;
            $array["airway_distance"] = true;
            $array["advise_techstops"] = true;
    
            $response = post_data_to_url($array);
                
            if(isset($response->time->airway) || isset($response->airport->techstop)) {
                  
                if(isset($response->airport->techstop)) {
                    
                    $techstops = array();
                    $techstops = $response->airport->techstop;
                    $techstop = $techstops[sizeof($techstops)-1];
                    $distance = $response->distance->airway;

                    $array["arrival_airport"] = $techstop;          
                    $response = post_data_to_url($array);
                    
                    $first_leg_airway = $response->time->airway;
    
                    $array["departure_airport"] = $techstop;
                    $array["arrival_airport"] = $_POST['arrival_icao'];
                    $response = post_data_to_url($array);
    
                    $second_leg_airway = $response->time->airway;
    
                    $total_airway = $first_leg_airway + $second_leg_airway;
    
                    if($distance <= $flight_range){
                        $techstop = "";
                    }

                } else {
                    $total_airway = $response->time->airway;
                }
    
                $hours = floor($total_airway / 60);
                $minutes = ($total_airway % 60);
    
                $pricing_table = $wpdb->prefix."sky_pricing";
                $pricing = $wpdb->get_results("SELECT * FROM $pricing_table WHERE aircraft_id = $_POST[aircraft_id]");
                
                foreach ($pricing as $price) {
                    $minimum_price = ceil(($price->minimum_range*$hours) + ($price->minimum_range*($minutes / 60)));
                    $maximum_price = ceil(($price->maximum_range*$hours) + ($price->maximum_range*($minutes / 60)));
                }

                $run = $wpdb->update(
                    $table_name, 
                    array('first_name'=>$_POST["fname"], 'last_name'=> $_POST["lname"], 'email'=> $_POST["email"],
                    'contact'=> $_POST["contact"], 'departure_airport'=> $_POST["departure_icao"],
                    'arrival_airport'=> $_POST["arrival_icao"], 'departure_date'=> $_POST["departure_date"],
                    'return_date'=> $_POST["return_date"], 'passengers'=> $_POST["passengers"], 'trip_type'=> $_POST["trip_type"],
                    'aircraft_id'=> $_POST["aircraft_id"], 'booking_status'=> $_POST["booking_status"], 'minimum_price'=> $minimum_price,
                    'maximum_price'=> $maximum_price, 'flight_hours'=> $hours, 'flight_minutes'=> $minutes,
                    'fueling_stop'=> $techstop), 
                    array("id" => $_POST["id"])
                );

            } else {
                return;
            }
    
        } else {
            $run = $wpdb->update(
                $table_name, 
                array('first_name'=>$_POST["fname"], 'last_name'=> $_POST["lname"], 'email'=> $_POST["email"],
                        'contact'=> $_POST["contact"], 'departure_airport'=> $_POST["departure_icao"],
                        'arrival_airport'=> $_POST["arrival_icao"], 'departure_date'=> $_POST["departure_date"],
                        'return_date'=> $_POST["return_date"], 'passengers'=> $_POST["passengers"], 'trip_type'=> $_POST["trip_type"],
                        'aircraft_id'=> $_POST["aircraft_id"], 'booking_status'=> $_POST["booking_status"]), 
                array("id" => $_POST["id"])
            );    
        }

        if($run) {
            echo "success";

            if($_POST["return_date"] == "") {
                $return_date = "None";

            } else {
                $return_date = $_POST["return_date"];
            }

            $headers  = "From: " ."noreply@flight.skybullion.net". "\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

            $user_email = $_POST["email"];
            $user_subject = "Flight Booking";

            if($_POST['booking_status'] != 'completed') {
                $user_message = "Your booking has been modified and the status is <b>".$_POST["booking_status"]."</b>. ";
            
            } else {
                $user_message = "<p>Your booking has been processed successfully.</p>
                            <table\>
                               <tr>
                                  <td colspan = '2'>Booking information</td>
                               </tr>
                               <tr>
                                  <td>Name:</td>
                                  <td>".$_POST["fname"]."  ".$_POST["lname"]."</td>
                               </tr>
                               <tr>
                                  <td>Email:</td>
                                  <td>".$_POST["email"]."</td>
                               </tr>
                               <tr>
                                   <td>Contact:</td>
                                   <td>".$_POST["contact"]."</td>
                               </tr>
                               <tr>
                                   <td>Departure Airport ICAO Code: </td>
                                   <td>".$_POST["departure_airport"]."</td>
                               </tr>
                               <tr>
                                    <td>Departure Date:</td>
                                    <td>".$_POST["departure_date"]."</td>
                               </tr>
                               <tr>
                                    <td>Destination Airport ICAO Code:</td>
                                    <td>".$_POST["arrival_airport"]."</td>
                               </tr>
                               <tr>
                                    <td>Return Date:</td>
                                    <td>".$return_date."</td>
                               </tr>
                               <tr>
                                    <td>Passengers:</td>
                                    <td>".$_POST["passengers"]."</td>
                               </tr>
                            </table>";

            }

            $user_status = mail($user_email, $user_subject, $user_message, $headers);




            foreach ($admins as $admin) 
            {
                $headers  = "From: " ."noreply@flight.skybullion.net". "\r\n";
                $headers .= "MIME-Version: 1.0\r\n";
                $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

                $admin_email = $admin->user_email;
                $admin_subject = "Flight Booking Update.";
                
                if($current_user->roles[0] == 'charter_sales_representative' || $current_user->roles[0] == 'administrator') {
                    $admin_message = "A flight booking for ".$_POST["first_name"]."  ".$_POST["last_name"]."  "."has been updated by ".$current_user->user_email;
                    $admin_status = mail($admin_email, $admin_subject, $admin_message, $headers);
                }

            }  


            if($current_user->roles[0] == 'charter_sales_representative') {

                $headers  = "From: " ."noreply@flight.skybullion.net". "\r\n";
                $headers .= "MIME-Version: 1.0\r\n";
                $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

                $rep_email = $current_user->user_email;
                $rep_subject = "Flight Booking Update.";
                $rep_message = "You have updated a flight booking for ".$_POST["first_name"]."  ".$_POST["last_name"];
                $rep_status = mail($rep_email, $rep_subject, $rep_message, $headers);
            }

        } else {
            echo "failed";

        }
    }

    wp_die(); 
}




if(!class_exists('SkyBullion'))
{
    class SkyBullion
    {
        public function __construct()
        {
            //adding css and javascript files.
            add_action('admin_enqueue_scripts', array($this, 'files')); 
            add_action('wp_enqueue_scripts', array($this, 'files')); 

            add_action('admin_menu', array($this, 'admin_menu')); 

            add_action('init',  array($this, 'initialize'));

            //adding trip details
            add_shortcode('trip_details', array($this, 'trip_details'));

            //adding 
            add_shortcode('jet_charter', array($this, 'aircrafts'));

        }
        

        //intiailizing session
        public function initialize()
        {
           ob_start();
           session_start();
        }

    
        //Adding menu and submenus in the admin dashboard
        public function admin_menu()
        {
            global $submenu;
            
            add_menu_page('Sky Bullion', 'Sky Bullion', 'manage_sky_bullion', 'sky_bullion', 'carers_func', plugin_dir_url( __FILE__ ) .'/images/plane.png', 2);
            add_submenu_page('sky_bullion', 'Dashboard' , 'Dashboard' , 'manage_dashboard', 'dashboard', array($this, 'dashboard'));
            add_submenu_page('sky_bullion', 'Airports' , 'Airports' , 'manage_airports', 'airports', array($this, 'airports_management'));
            add_submenu_page('sky_bullion', 'Aircraft Types' , 'Aircraft Types' , 'manage_aircraft_types', 'aircraft_types', array($this, 'aircraft_types_management'));
            add_submenu_page('sky_bullion', 'Aircrafts' , 'Aircrafts' , 'manage_aircrafts', 'aircrafts', array($this, 'aircrafts_management'));
            add_submenu_page('sky_bullion', 'Pricing' , 'Pricing' , 'manage_pricing', 'pricing', array($this, 'pricing_management'));
            add_submenu_page('sky_bullion', 'Bookings' , 'Bookings' , 'manage_bookings', 'bookings', array($this, 'bookings_management'));

            unset($submenu['sky_bullion'][0]);
        }
           

        //returns the dashboard
        public function dashboard()
        {
            global $wpdb; 
            $table_name1 = $wpdb->prefix."sky_aircrafts";
            $table_name2 = $wpdb->prefix."sky_airports";
            $table_name3 = $wpdb->prefix."sky_bookings";

            $aircrafts = $wpdb->get_results("SELECT COUNT(*) as total_aircrafts FROM $table_name1");
            $airports = $wpdb->get_results("SELECT COUNT(*) as total_airports FROM $table_name2");
            $bookings = $wpdb->get_results("SELECT COUNT(*) as total_bookings FROM $table_name3");

            include "pages/admin/dashboard.php";
        }
        

        //return booking form
        public function trip_details()
        {
           include "pages/user/trip_details.php";
        }


        //returns aircrafts page
        public function aircrafts()
        {
            global $wpdb; 
            $table_name = $wpdb->prefix."sky_aircrafts";
            $aircrafts = $wpdb->get_results("SELECT * FROM $table_name");

            include "pages/user/aircrafts.php";
        }
        

        //returns aircraft types page
        public function aircraft_types_management()
        {
            global $wpdb; 
            $table_name = $wpdb->prefix."sky_aircraft_types";
            $aircraft_types = $wpdb->get_results("SELECT * FROM $table_name");

           include "pages/admin/aircraft_types.php";
        }


        //returns aircrafts page
        public function aircrafts_management()
        {
            global $wpdb; 
            $table_name1 = $wpdb->prefix."sky_aircrafts";
            $aircrafts = $wpdb->get_results("SELECT * FROM $table_name1");

            $table_name2 = $wpdb->prefix."sky_airports";
            $airports = $wpdb->get_results("SELECT * FROM $table_name2");
           include "pages/admin/aircrafts.php";
        }

        //returns airports page
        public function airports_management()
        {
            global $wpdb; 
            $table_name1 = $wpdb->prefix."sky_airports";
            $table_name2 = $wpdb->prefix."sky_aircraft_types";

            $airports = $wpdb->get_results("SELECT * FROM $table_name1");
            $aircraft_types = $wpdb->get_results("SELECT * FROM $table_name2");

            include "pages/admin/airports.php";
        }

        //returns pricing
        public function pricing_management()
        {
            global $wpdb; 
            $table_name1 = $wpdb->prefix."sky_pricing";
            $table_name2 = $wpdb->prefix."sky_aircrafts";

            $pricings = $wpdb->get_results("SELECT * FROM $table_name1");
            $aircrafts = $wpdb->get_results("SELECT * FROM $table_name2");
            include "pages/admin/pricing.php";
        }


        //returns bookings
        public function bookings_management()
        {
            global $wpdb; 
            $table_name1 = $wpdb->prefix."sky_bookings";
            $table_name2 = $wpdb->prefix."sky_aircrafts";

            $bookings = $wpdb->get_results("SELECT * FROM $table_name1");
            $aircrafts = $wpdb->get_results("SELECT * FROM $table_name2");

            include "pages/admin/bookings.php";
        }
        

        //add css and javascript files.
        public function files()
        {
            wp_enqueue_style(
                'styles',
                plugin_dir_url( __FILE__ ) . '/css/styles.css'
            );


            wp_enqueue_script(
                'javascript',
                plugin_dir_url( __FILE__ ) . '/js/main.js'
            );
        }        
    }

    $SkyBullion = new SkyBullion();
}

//Request Quote onclick => xtenderSearchFlight.modal_visibility = ! xtenderSearchFlight.modal_visibility;

//Home Empty space => [xtd-search-flight url="http://flight.skybullion.net/private-jet/book-jet/" modal=true modal_text="BOOK NOW" modal_desc="+1 323 833 0257"]

//Private jet Empty space => [xtd-search-flight]

//Business jet Empty space => [xtd-search-flight]