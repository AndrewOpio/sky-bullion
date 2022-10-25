<?php 
   $page =  get_page_by_title("JET CHARTER");
   $id = $page->ID;
   $perma = get_permalink($id);
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>


<style>
   .title {
    display: flex;
    align-items: center; 
    justify-content: center;
    background-color: #fff;
   }

   .right-btn {
      margin-left: 10px;
      background-color: #0cb3d7;
      color: white;
   }
   .form-container {
      width: 80%;
      margin: auto;
      margin-top: 60px;
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

   .submit:hover {
      background-color: #0cb3d7;
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

 
   input[type="date"]::before {
      content: attr(placeholder);
      position: absolute;
      color: #999999;
   }

   input[type="date"] {
      color: #ffffff;
   }

   input[type="date"]:focus, input[type="date"]:valid {
      color: #666666;
   }

   input[type="date"]:focus::before {
      content: "";
   }

   .return_date {
      background-color: white !important;
   }

 </style>


<script>
   function visibility(status)
   {
      if(status == "block") {
         document.getElementById("round_trip").style.backgroundColor = "#0cb3d7";
         document.getElementById("round_trip").style.color = "#ffffff";
         document.getElementById("one_way").style.backgroundColor = "#ffffff";
         document.getElementById("one_way").style.color = "#000000";

         document.getElementById("trip_type").value = "round trip";


      } else {
         document.getElementById("one_way").style.backgroundColor = "#0cb3d7";
         document.getElementById("one_way").style.color = "#ffffff";
         document.getElementById("round_trip").style.backgroundColor = "#ffffff";
         document.getElementById("round_trip").style.color = "#000000";

         document.getElementById("trip_type").value = "one way";

      }
      document.getElementById("return_date").style.display = status;
      document.getElementById("return_date_label").style.display = status;
   }


   function aircraftsList(e)
   {
      e.preventDefault();

      var trip_type =  document.getElementById("trip_type").value;
      var departure_airport =  document.getElementById("departure_airport").value;
      var departure_icao =  document.getElementById("icao_departure").value;
      var arrival_airport =  document.getElementById("arrival_airport").value;
      var arrival_icao =  document.getElementById("icao_arrival").value;
      var departure_date =  document.getElementById("departure_date").value;
      var return_date =  document.getElementById("return_date").value;
      var passengers =  document.getElementById("passengers").value;

      if(trip_type == "round trip" && return_date == "") {
         alert("Please enter all required fields")
         return;
      }

      if (departure_airport == "" || departure_icao == "" || arrival_airport == "" ||
         arrival_icao == "" || departure_date == "" || passengers == "") {
         alert("Please enter all required fields")
         return;
      }

      var ajaxurl = '<?php echo admin_url('admin-ajax.php');?>';

      var data = {
         action: 'sky_aircrafts_list',
         departure_airport,
         departure_icao,
         arrival_airport,
         arrival_icao,
         departure_date,
         return_date,
         passengers,
         trip_type
      }

      $.ajax({ 
         url:ajaxurl,
         type:"POST",
         data: data,
         success : function( response ){
            location.href = "<?php echo $perma; ?>"
         },
      });
        
   }
</script>
<div>
  <div class = "card form-container">
    <div class="card-header title">
        <h4><b>Charter Flight Cost Calculator</b></h4>
    </div>
    <div class= "card-body">
       <div>
          <div>
            <button class = "btn btn-outline-primary" onclick = "visibility('none')" id ="one_way">One Way</button>
            <button class = "btn btn-outline-primary right-btn" onclick = "visibility('block')" id = "round_trip">Round Trip</button>
            <input type="hidden" id = "trip_type" value = "round trip">
          </div>
          <div style = "margin-top: 20px;">
            <form method = "POST">
               <div class = "row">
                  <div class = "col-md-4">
                     <div class="text-field">
                        <input type="text" class = "form-control"  autocomplete="off" id = "departure_airport" required>
                        <label>Departure Airport</label>
                     </div>
                     <input type = "hidden" name = "icao" id = "icao_departure" required/>
                     <div class="dropdown-menu" style="margin-left: 15px; margin-top: -13px;" id="suggestion-box1"></div>
                  </div>
                  <div class = "col-md-4">
                     <div class="text-field">
                        <input type="date" class = "form-control" id = "departure_date" onchange = "setBoundary()" required>
                        <label>Depature Date</label>
                     </div> 
                  </div>
                  <div class = "col-md-4">
                    <div class="text-field">
                        <input type="date" class = "form-control return_date"  id = "return_date" required>
                        <label id = "return_date_label">Return Date</label>
                     </div>   
                  </div>
               </div>

               <div class = "row" style = "margin-top: 20px;">
                  <div class = "col-md-6">
                     <div class="text-field">
                        <input type="text" class = "form-control"  autocomplete="off" id = "arrival_airport" required>
                        <label>Arrival Airport</label>
                     </div>  
                     <input type = "hidden" name = "icao" id = "icao_arrival" required/>
                     <div class="dropdown-menu" style="margin-left: 15px; margin-top: -13px;" id="suggestion-box2"></div>                
                  </div>
                  <div class = "col-md-6">
                     <div class="text-field">
                        <input type="text" class = "form-control" id = "passengers" required>
                        <label>Passengers</label>
                     </div>                  
                  </div>  
               </div>
               <button class = "btn btn-outline-primary submit" onclick = "aircraftsList(event)" style = "height: 55px; margin-top: 20px; width: 100%">View Estimates And Book</button>
            </form>
         </div>
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
         $("#icao_departure").val(val1);
         $("#suggestion-box1").hide();
   }

   //To select departure airport
   function selectArrival(val1, val2) {
         $("#arrival_airport").val(val2);
         $("#icao_arrival").val(val1);
         $("#suggestion-box2").hide();
   }
</script>
