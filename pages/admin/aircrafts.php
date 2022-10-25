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
   //Adding an aircraft
   function addAircraft()
   {
      var name = document.getElementById("name").value;
      var reg_number = document.getElementById("reg_number").value;
      var aircraft_type = document.getElementById("aircraft_type").value;
      var flight_range = document.getElementById("flight_range").value;
      var fuel_consumption = document.getElementById("fuel_consumption").value;
      var cargo_carrying_capacity = document.getElementById("cargo_carrying_capacity").value;
      var live_location = document.getElementById("live_location").value;
      var service_records = document.getElementById("service_records").value;
      var status = document.getElementById("status").value;
      var photo = $('#photo').prop('files')[0];

      if ( name == '' || reg_number == '' || aircraft_type == '' || flight_range == '' || fuel_consumption == '' || cargo_carrying_capacity == ''
         || service_records == '' || status == '' || !photo ) {
        alert('Please enter all fields');

      } else {

        var form_data = new FormData();
        form_data.append("action", 'sky_aircrafts');
        form_data.append("type", 'add');
        form_data.append("name", name);
        form_data.append("reg_number", reg_number);
        form_data.append("aircraft_type", aircraft_type);
        form_data.append("flight_range", flight_range);
        form_data.append("fuel_consumption", fuel_consumption);
        form_data.append("cargo_carrying_capacity", cargo_carrying_capacity);
        form_data.append("live_location", live_location);
        form_data.append("service_records", service_records);
        form_data.append("status", status);
        form_data.append("photo", photo);

        var save = document.getElementById("save").innerHTML ="saving...";

        var ajaxurl = '<?php echo admin_url('admin-ajax.php');?>';

        $.ajax({ 
          url:ajaxurl,
          type:"POST",
          processData: false,
          contentType: false,
          cache: false,
          enctype: 'multipart/form-data',
          data: form_data,
          success : function( response ) {
            if ($.trim(response) == "success") {
              alert("Aircraft added successfully.")
              location.reload(true);

            } else if($.trim(response) == "0") {
                alert("The aircraft already exists.")
                var save = document.getElementById("save").innerHTML ="save";

            }  else if($.trim(response) == "failed") {
                alert("An error occured.");
                var save = document.getElementById("save").innerHTML ="save";

            }
          },
        });
      } 
   } 

   //Deleting an airport
   function deleteAircraft(id)
   {
      var ajaxurl = '<?php echo admin_url('admin-ajax.php');?>';
       //alert(id);
      var data = {
          action: 'sky_aircrafts',
          type: 'delete',
          id: id
      };

      var del = document.getElementById("delete"+id).innerHTML ="Deleting...";

      $.post(ajaxurl, data, function(response) {
          if ($.trim(response) == "success") {
              alert("Aircraft deleted successfully.")
              location.reload(true);

          } else if($.trim(response) == "failed") {
              alert("An error occured.");
              var del = document.getElementById("delete"+id).innerHTML ="Delete";
          }
      }); 
   }

   //Updating an airport
   function updateAircraft(id, name, reg_number, aircraft_type, flight_range, fuel_consumption, cargo_carrying_capacity, live_location, photo, service_records, status)
   {
      var name = document.getElementById(name).value;
      var original_name = document.getElementById("original_name"+id).value;
      var reg_number = document.getElementById(reg_number).value;
      var original_reg_number = document.getElementById("original_reg_number"+id).value;
      var aircraft_type = document.getElementById(aircraft_type).value;
      var flight_range = document.getElementById(flight_range).value;
      var fuel_consumption = document.getElementById(fuel_consumption).value;
      var cargo_carrying_capacity = document.getElementById(cargo_carrying_capacity).value;
      var live_location = document.getElementById(live_location).value;
      var service_records = document.getElementById(service_records).value;
      var status = document.getElementById(status).value;
      var photo = $("#"+photo).prop('files')[0];
      
      if ( name == '' || reg_number == '' || aircraft_type == '' || flight_range == '' || fuel_consumption == '' || cargo_carrying_capacity == ''
        || service_records == '' || status == '' ) {
        alert('Please enter all fields');

      } else {
        var ajaxurl = '<?php echo admin_url('admin-ajax.php');?>';
        var edit = document.getElementById("update" + id).innerHTML = "Updating...";

        var form_data = new FormData();
        form_data.append("action", 'sky_aircrafts');
        form_data.append("type", 'update');
        form_data.append("id", id);
        form_data.append("name", name);
        form_data.append("original_name", original_name);
        form_data.append("reg_number", reg_number);
        form_data.append("original_reg_number", original_reg_number);
        form_data.append("aircraft_type", aircraft_type);
        form_data.append("flight_range", flight_range);
        form_data.append("fuel_consumption", fuel_consumption);
        form_data.append("cargo_carrying_capacity", cargo_carrying_capacity);
        form_data.append("live_location", live_location);
        form_data.append("service_records", service_records);
        form_data.append("status", status);
        form_data.append("photo", photo);

        $.ajax({ 
          url:ajaxurl,
          type:"POST",
          processData: false,
          contentType: false,
          cache: false,
          enctype: 'multipart/form-data',
          data: form_data,
          success : function( response ){
            if ($.trim(response) == "success") {
              alert("Aircraft updated successfully.")
              location.reload(true);

            } else if($.trim(response) == "0") {
                alert("The aircraft already exists.")
                var edit = document.getElementById("update" + id).innerHTML = "Update";

            }  else if($.trim(response) == "failed") {
                alert("An error occured.");
                var edit = document.getElementById("update" + id).innerHTML = "Update";

            }          
        },
        });
      }
   }

</script>


<style>
  tr:nth-child(odd) {
    background-color: rgba(150, 212, 212, 0.4) !important;
  }

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

  button.multiselect {
    background-color: initial;
    border: 1px solid #8c8c8c;
  }

  ul.multiselect-container {height:200px;overflow-y:scroll;}
  input.multiselect-search {margin-top: 5px;}

</style>

<div class = "main">
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Add New Aircraft</h4>
      </div>

      <form method="POST" id = "add">
        <div class="input">
          <div class = "row" style = "margin-top:5px;">
            <div class="form-group col-md-6">
              <label>Aircraft Name:</label>
              <input type="text" class="form-control" id = "name" name = "name" placeholder = "Enter aircraft name..">
            </div>
            
            <div class="form-group col-md-6">
              <label>Aircraft Registration Number:</label>
              <input type="text" class="form-control" id = "reg_number" name = "reg_number" placeholder = "Enter registration number..">
            </div>
          </div>
          <div class = "row" style = "margin-top:5px;">
            <div class="form-group col-md-6">
              <label>Aircraft Type:</label>                
              <select id="aircraft_type" name="aircraft_type" required>  
              <?php          
              global $wpdb; 
              $table_name = $wpdb->prefix."sky_aircraft_types";

              $aircraft_types = $wpdb->get_results("SELECT * FROM $table_name");
                     
              if($aircraft_types) {
                foreach ($aircraft_types as $type) {
              ?>  
                <option value = "<?php echo $type->aircraft_type; ?>"><?php echo $type->aircraft_type; ?></option>
              <?php
                }
              }
              ?>        
              </select>
            </div>

            <div class="form-group col-md-6">
              <label>Flight Range (Kilometres):</label>
              <input type="text" class="form-control" id = "flight_range" name = "flight_range" placeholder = "Enter flight range..">
            </div>
          </div>

          <div class = "row" style = "margin-top:8px;">
            <div class="form-group col-md-6">
              <label>Fuel Consumption (Kilometres per litre):</label>
              <input type="text" class="form-control" id = "fuel_consumption" name = "fuel_consumption" placeholder = "Enter fuel consumption..">
            </div>
            <div class="form-group col-md-6">
              <label>Cargo Carrying Capacity (Metric tonnes):</label>
              <input type="text" class="form-control" id = "cargo_carrying_capacity" name = "cargo_carrying_capacity" placeholder = "Enter cargo carrying capacity..">
            </div>
          </div>

          <div class = "row" style = "margin-top:8px;">
            <div class="form-group col-md-6">
              <label>Live Location:</label>
              <input type = "text" name = "live_location" autocomplete="off" id = "live_location" class="form-control"  placeholder = "Enter live location.." required/>
              <input type = "hidden" name = "l_id" id = "l_id" required/>

              <div class="dropdown-menu" style = "margin-top: 3px;" id="suggestion-box"></div>
            </div>
            <div class="form-group col-md-6">
              <label>Status:</label>
              <select class="form-control" id = "status" name ="status">
                <option value = "1">Operational</option>
                <option value = "0">Not Operational</option>
              </select>
            </div>
          </div>
          <div class = "row" style = "margin-top:5px;">
            <div class="form-group col-md-12">
              <label>Aircraft Photo:</label>
              <input type="file" class="form-control" id = "photo" name = "photo">
            </div>
          </div>
          <div class="form-group col-md-12" style = "margin-top:8px; margin-bottom:8px;">
            <label>Service Records:</label>
            <textarea class="form-control" rows = "5"id = "service_records" name = "service_records"></textarea>
          </div>
          <input type="hidden" name = "add" value = "add">
        </div>
      </form>

      <div class="modal-footer">
        <button type="button" class="button" id = "save" onclick ="addAircraft()" style = "margin-right:8px;">Save</button>
        <button type="button" class="button" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div class = "card">
  <div class = "card-header" style = "height: 35px; display: flex;">
      <h6><b>Aircrafts</b></h6>
      <img width=20 height=20 style = "float:right; margin-left: auto;" src = "<?php echo plugin_dir_url( __FILE__ ) .'../../images/aeroplane.png';?>" alt = "airplane"/>
  </div>

  <div>
    <div class = "card-body table-responsive">
        <table id = "table" class="table table-striped table-hover" border = "0">
            <thead>
                <tr class = "header" style = "background-color: white !important;">
                    <td class = "text">Id</td>
                    <td class = "text">Name</td>
                    <td class = "text">Registration Number</td>
                    <td class = "text">Aircraft Type</td>
                    <td class = "text">Aircraft Photo</td>
                    <td class = "text">Flight Range (Kilometres)</td>
                    <td class = "text">Fuel Consumption (Kilometres per litre)</td>
                    <td class = "text">Cargo Capacity (Metric tonnes)</td>
                    <td class = "text">Live Location</td>
                    <td class = "text">Service Records</td>
                    <td class = "text">Status</td>
                    <td class = "text notexport">Actions</td>
                </tr>
            </thead>
            <tbody>
            <?php
            if($aircrafts){
              $i = 1;
              foreach ($aircrafts as $aircraft) {
            ?>
                <div id="<?php echo $aircraft->id."delete"; ?>" class="modal fade" role="dialog">
                  <div class="modal-dialog modal-dialog-centered">
                    <!-- Modal content-->
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title">Delete Aircraft</h4>
                      </div>
                      <div class="modal-body">
                        <p>Are sure you want to delete this aircraft?</p>
                        <button class="button" id = "<?php echo "delete".$aircraft->id; ?>" style = "width: 100%;" onclick ="deleteAircraft(<?php echo $aircraft->id; ?>)">Delete</button>
                      </div>                      
                    </div>
                  </div>
                </div>


                <div id="<?php echo $aircraft->id."update"; ?>" class="modal fade" role="dialog">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title">Update Aircraft Information</h4>
                      </div>

                      <form>
                        <div class="input">
                          <div class = "row" style = "margin-top:5px;">
                            <div class="form-group col-md-6">
                              <label>Aircraft Name:</label>
                              <input type="text" class="form-control" id = "<?php echo "name".$aircraft->id; ?>" name = "name" placeholder = "Enter aircraft name.." value = "<?php echo $aircraft->aircraft_name; ?>">
                              <input type="hidden" class="form-control" id = "<?php echo "original_name".$aircraft->id; ?>" value = "<?php echo $aircraft->aircraft_name; ?>">
                            </div>
                            
                            <div class="form-group col-md-6">
                              <label>Aircraft Registration Number:</label>
                              <input type="text" class="form-control" id = "<?php echo "reg_number".$aircraft->id; ?>" name = "reg_number" placeholder = "Enter registration number.." value = "<?php echo $aircraft->reg_number; ?>">
                              <input type="hidden" class="form-control" id = "<?php echo "original_reg_number".$aircraft->id; ?>" value = "<?php echo $aircraft->reg_number; ?>">
                            </div>
                          </div>
                          <div class = "row" style = "margin-top:5px;">
                            <div class="form-group col-md-6">
                              <label>Aircraft Type:</label>   
                              <select id="<?php echo "aircraft_type".$aircraft->id; ?>" name="aircraft_type"  required>  
                              <?php                               
                              if($aircraft_types) {
                                foreach ($aircraft_types as $type) {
                              ?>  
                                <option value = "<?php echo $type->aircraft_type; ?>" <?php echo $type->aircraft_type == $aircraft->aircraft_type ? "selected" : ""; ?>><?php echo $type->aircraft_type; ?></option>
                              <?php
                                }
                              }
                              ?>        
                              </select>
                            </div>
                            
                            <div class="form-group col-md-6">
                              <label>Flight Range (Kilometres):</label>
                              <input type="text" class="form-control" id = "<?php echo "flight_range".$aircraft->id; ?>" name = "flight_range" placeholder = "Enter flight range.." value = "<?php echo $aircraft->flight_range; ?>">
                            </div>
                          </div>

                          <script>
                              $(document).ready(function () {
                                  $('#<?php echo "aircraft_type".$aircraft->id; ?>').multiselect({		
                                    nonSelectedText: 'Select type',
                                    buttonWidth: '100%',
                                    //includeSelectAllOption: true,
                                    enableFiltering: true		
                                  });
                              });
                          </script>

                          <div class = "row" style = "margin-top:8px;">
                            <div class="form-group col-md-6">
                              <label>Fuel Consumption (Kilometres per litre):</label>
                              <input type="text" class="form-control" id = "<?php echo "fuel_consumption".$aircraft->id; ?>" name = "fuel_consumption" placeholder = "Enter fuel consumption.." value = "<?php echo $aircraft->fuel_consumption; ?>">
                            </div>
                            <div class="form-group col-md-6">
                              <label>Cargo Carrying Capacity (Metric tonnes):</label>
                              <input type="text" class="form-control" id = "<?php echo "cargo_carrying_capacity".$aircraft->id; ?>" name = "cargo_carrying_capacity" placeholder = "Enter cargo carrying capacity.." value = "<?php echo $aircraft->cargo_carrying_capacity; ?>">
                            </div>
                          </div>

                          <div class = "row" style = "margin-top:8px;">
                            <div class="form-group col-md-6">
                              <label>Live Location:</label>
                              <input type = "text" name = "live_location" autocomplete="off" id = "<?php echo "live_location".$aircraft->id; ?>" class="form-control"  placeholder = "Enter live location.." required/>

                              <div class="dropdown-menu" style = "margin-top: 3px;" id="<?php echo "suggestion-box".$aircraft->id; ?>"></div>

                              <script>
                                // AJAX call for autocomplete 
                                $(document).ready(function(){
                                    $("#<?php echo "live_location".$aircraft->id; ?>").keyup(function(){
                                        if ($(this).val()) {
                                            var ajaxurl = '<?php echo admin_url('admin-ajax.php');?>';

                                            var data = {
                                              action: 'sky_locations',
                                              keyword: $(this).val(),
                                              id: <?php echo $aircraft->id; ?>
                                            };
                                            $.ajax({
                                            type: "POST",
                                            url: ajaxurl,
                                            data: data,
                                            beforeSend: function(){
                                                $("#<?php echo "live_location".$aircraft->id; ?>").css("background","#e2e2e2 url(facebook.png) no-repeat 165px");
                                            },
                                            success: function(data){
                                                $("#<?php echo "suggestion-box".$aircraft->id; ?>").show();
                                                $("#<?php echo "suggestion-box".$aircraft->id; ?>").html(data);
                                                $("#<?php echo "live_location".$aircraft->id; ?>").css("background","#FFF");
                                            }
                                            });
                                        } else {
                                            $("#<?php echo "suggestion-box".$aircraft->id; ?>").hide(); 
                                        }
                                    });
                                });

                                <?php 
                                 echo '
                                    function selectNumber'.$aircraft->id.'(val1, val2) {
                                        $("#live_location'.$aircraft->id.'").val(val2);
                                        $("#suggestion-box'.$aircraft->id.'").hide();
                                    }';
                                ?>
                              </script>

                            
                            </div>
                            <div class="form-group col-md-6">
                              <label>Status:</label>
                              <select class="form-control" id = "<?php echo "status".$aircraft->id; ?>" name ="status">
                                <option value = "1" <?php echo $aircraft->aircraft_status == '1' ? "selected" : ""; ?>>Operational</option>
                                <option value = "0" <?php echo $aircraft->aircraft_status == '0' ? "selected" : ""; ?>>Not Operational</option>
                              </select>
                            </div>
                          </div>
                          <div class = "row" style = "margin-top:5px;">
                            <div class="form-group col-md-12">
                              <label>Aircraft Photo:</label>
                              <input type="file" class="form-control" id = "<?php echo "photo".$aircraft->id; ?>" name = "photo">
                            </div>
                          </div>
                          <div class="form-group col-md-12" style = "margin-top:8px; margin-bottom:8px;">
                            <label>Service Records:</label>
                            <textarea class="form-control" rows = "5" id = "<?php echo "service_records".$aircraft->id; ?>" name = "service_records"><?php echo $aircraft->service_records; ?></textarea>
                          </div>
                        </div>
                      </form>
                      
                      <div class="modal-footer">
                        <button type="button" class="button"  id = "<?php echo "update".$aircraft->id; ?>" style = "margin-right: 10px;" onclick ='updateAircraft(
                          <?php echo $aircraft->id; ?>,
                          "<?php echo "name".$aircraft->id; ?>",
                          "<?php echo "reg_number".$aircraft->id; ?>",
                          "<?php echo "aircraft_type".$aircraft->id; ?>",
                          "<?php echo "flight_range".$aircraft->id; ?>",
                          "<?php echo "fuel_consumption".$aircraft->id; ?>",
                          "<?php echo "cargo_carrying_capacity".$aircraft->id; ?>",
                          "<?php echo "live_location".$aircraft->id; ?>",
                          "<?php echo "photo".$aircraft->id; ?>",
                          "<?php echo "service_records".$aircraft->id; ?>",
                          "<?php echo "status".$aircraft->id; ?>"
                        )'>Update</button>
                        <button type="button" class="button" data-dismiss="modal">Close</button>
                      </div>
                    </div>
                  </div>
                </div>

                <div id="<?php echo $aircraft->id."view"; ?>" class="modal fade" role="dialog">
                    <div class="modal-dialog modal-dialog-centered">
                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-body">
                                <img src= "<?php echo $aircraft->aircraft_photo; ?>" width = 100% height = 100% alt = "aircraft"/>
                            </div>                      
                        </div>
                    </div>
                </div>

                <tr>
                    <td class = "data"><?php echo $i; ?></td>
                    <td class = "data"><?php echo $aircraft->aircraft_name; ?></td> 
                    <td class = "data"><?php echo $aircraft->reg_number; ?></td> 
                    <td class = "data"><?php echo $aircraft->aircraft_type; ?></td>
                    <td class = "data"><img height = 50 width = 50 src = "<?php echo $aircraft->aircraft_photo; ?>" alt = "aircraft" data-toggle = "modal" data-target = "#<?php echo $aircraft->id."view"; ?>" data-backdrop = "true"/></td>  
                    <td class = "data"><?php echo $aircraft->flight_range; ?></td>                  
                    <td class = "data"><?php echo $aircraft->fuel_consumption; ?></td>                  
                    <td class = "data"><?php echo $aircraft->cargo_carrying_capacity; ?></td>                  
                    <td class = "data"><?php echo $aircraft->live_location; ?></td>                  
                    <td class = "data"><?php echo $aircraft->service_records; ?></td>  
                    <td class = "data"><?php echo $aircraft->aircraft_status == "1" ? "Operational" : "Not Operational"; ?></td>                                                                    
                    <td class = "action">
                    <?php global $current_user; 
                       if($current_user->roles[0] == 'administrator') {
                    ?>
                        <div style = "display: flex;">
                          <button type = "button" class = "button" data-toggle = "modal" data-target = "#<?php echo $aircraft->id."update"; ?>" data-backdrop = "true">Update</button>
                          <button type = "button" class = "button" style = "margin-left: 5px;" data-toggle = "modal" data-target = "#<?php echo $aircraft->id."delete"; ?>" data-backdrop = "true">Delete</button>
                        </div>
                    <?php
                     } else {
                    ?>
                       <span>Not permitted</p>
                    <?php
                     }
                    ?>
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
  
  <?php global $current_user; 
    if ($current_user->roles[0] == 'administrator') {
  ?>
  <div class = "card-footer">
      <button type = "button" style = "margin-left : 5px;" class = "button" data-toggle = "modal" data-target = "#myModal" data-backdrop = "true"><span class = "add">&plus;</span>Add Aircraft</button>
  </div>
  <?php
  }
  ?>
</div>
</div>

<script>
    $(document).ready(function () {
      $('#aircraft_type').multiselect({		
          nonSelectedText: 'Select type',	
          buttonWidth: '100%',
          //includeSelectAllOption: true,
          enableFiltering: true
        });

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
    });
</script>


<script>
      // AJAX call for autocomplete 
      $(document).ready(function(){
          $("#live_location").keyup(function(){
              if ($(this).val()) {
                  var ajaxurl = '<?php echo admin_url('admin-ajax.php');?>';

                  var data = {
                    action: 'sky_locations',
                    keyword: $(this).val(),
                    id: ''
                  };
                  $.ajax({
                  type: "POST",
                  url: ajaxurl,
                  data: data,
                  beforeSend: function(){
                      $("#live_location").css("background","#e2e2e2 url(facebook.png) no-repeat 165px");
                  },
                  success: function(data){
                      $("#suggestion-box").show();
                      $("#suggestion-box").html(data);
                      $("#live_location").css("background","#FFF");
                  }
                  });
              } else {
                  $("#suggestion-box").hide(); 
              }
          });
      });
      //To select location
      function selectNumber(val1, val2) {
          $("#live_location").val(val2);
          $("#l_id").val(val1);
          $("#suggestion-box").hide();
      }

  </script>

