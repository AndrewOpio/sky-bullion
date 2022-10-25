<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>


<style>
  .card {
      box-shadow: 0px 8px 20px 0px #e2e2e2;
   }
   
   .card:hover {
      box-shadow: 0px 8px 40px 0px #e2e2e2;
   }

</style>

<div class = "main">
  <div class = "row">
    <div class = "col-md-4">
        <div class = "card">
            <img width=80 height=80 style = "margin: auto;" src = "<?php echo plugin_dir_url( __FILE__ ) .'../../images/globe.png';?>" alt = "airport"/>
            <h4 style = "margin: auto; margin-top: 20px;">Airports: 
            <?php 
            foreach ($airports as $total) {
                echo str_repeat("&nbsp;", 2).$total->total_airports;
            }
            ?>
            </h4>
        </div>
    </div>
    <div class = "col-md-4">
        <div class = "card">
            <img width=85 height=85 style = "margin: auto;" src = "<?php echo plugin_dir_url( __FILE__ ) .'../../images/airplane.png';?>" alt = "plane"/>
            <h4 style = "margin: auto; margin-top: 20px;">Aircrafts:
            <?php 
            foreach ($aircrafts as $total) {
                echo str_repeat("&nbsp;", 2).$total->total_aircrafts;
            }
            ?>
            </h4>
        </div>
    </div>
    <div class = "col-md-4">
        <div class = "card">
            <img width=80 height=80 style = "margin: auto;" src = "<?php echo plugin_dir_url( __FILE__ ) .'../../images/bookings.png';?>" alt = "booking"/>
            <h4 style = "margin: auto; margin-top: 20px;">Bookings:
            <?php 
            foreach ($bookings as $total) {
                echo str_repeat("&nbsp;", 2).$total->total_bookings;
            }
            ?>
            </h4>
        </div>
    </div>
  </div>
</div>
