<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>

<link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.0/css/bootstrap.min.css">
<link href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css">

<script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>

<script type ="text/javascript">
   //Adding an aircraft
   function addAirport()
   {
      var days_options = document.getElementById("days").options;
      var operating_days = [];

      for (var option of days_options) {
         if (option.selected) {
            operating_days.push(option.value);
         }
      }


      var type_options = document.getElementById("aircraft_types").options;
      var aircraft_types = [];
      var all = true;

      for (var option of type_options) {
        if (!option.selected) {
           all = false;
         }

         if (option.selected) {
           aircraft_types.push(option.value);
         }
      }

      var name = document.getElementById("name").value;
      var operating_status = document.getElementById("operating_status").value;
      var iata = document.getElementById("iata").value;
      var icao = document.getElementById("icao").value;
      var faa = document.getElementById("faa").value;

      if ( name == '' || aircraft_types.length == 0 || operating_status == '' || operating_days.length == 0
         || (iata == '' && icao == '' && faa == '') ) {
        alert('Please enter all fields');

      } else {

        var save = document.getElementById("save").innerHTML ="saving...";

        var ajaxurl = '<?php echo admin_url('admin-ajax.php');?>';
        var data = {
            action: 'sky_airports',
            type: 'add',
            name,
            aircraft_types: all == true ? ["All"] : aircraft_types,
            operating_status,
            operating_days,
            iata,
            icao,
            faa
        };

        $.post(ajaxurl, data, function(response) {
            if ($.trim(response) == "success") {
               alert("Airport added successfully.")
               location.reload(true);

            } else if($.trim(response) == "0") {
                alert("The airport already exists.")
                var save = document.getElementById("save").innerHTML ="save";

            }  else if($.trim(response) == "failed") {
                alert("An error occured.");
                var save = document.getElementById("save").innerHTML ="save";

            }        
        }); 
      } 
   } 

   
   //Deleting an airport
   function deleteAirport(id)
   {
      var ajaxurl = '<?php echo admin_url('admin-ajax.php');?>';
      var data = {
          action: 'sky_airports',
          type: 'delete',
          id: id
      };

      var del = document.getElementById("delete"+id).innerHTML ="Deleting...";

      $.post(ajaxurl, data, function(response) {
          if ($.trim(response) == "success") {
              alert("Airport deleted successfully.")
              location.reload(true);

          } else if($.trim(response) == "failed") {
              alert("An error occured.");
              var del = document.getElementById("delete"+id).innerHTML ="Delete";
          }      
      }); 
   }


   //Updating an airport
   function updateAirport(id, name, aircraft_type, operating_status, operating_days, iata, icao, faa)
   {
      var days_options = document.getElementById(operating_days).options;
      var operating_days = [];

      for (var option of days_options) {
         if (option.selected) {
            operating_days.push(option.value);
         }
      }


      var type_options = document.getElementById(aircraft_type).options;
      var aircraft_types = [];
      var all = true;

      for (var option of type_options) {
         if (!option.selected) {
           all = false;
         }

         if (option.selected) {
            aircraft_types.push(option.value);
         }
      }

      var ajaxurl = '<?php echo admin_url('admin-ajax.php');?>';
      var name = document.getElementById(name).value;
      var original_name = document.getElementById("original_name"+id).value;
      var operating_status = document.getElementById(operating_status).value;
      var iata = document.getElementById(iata).value;
      var icao = document.getElementById(icao).value;
      var faa = document.getElementById(faa).value;

      if ( name == '' || aircraft_types.length == 0 || operating_status == '' || (iata == '' && icao == '' && faa == '') ) {
        alert('Please enter all fields');

      } else {
        var edit = document.getElementById("update" + id).innerHTML = "Updating...";

        var data = {
            action: 'sky_airports',
            type: 'update',
            id: id,
            name,
            original_name,
            aircraft_types: all == true ? ["All"] : aircraft_types,
            operating_status,
            operating_days,
            iata,
            icao,
            faa
        };

        $.post(ajaxurl, data, function(response) {
          if ($.trim(response) == "success") {
              alert("Airport updated successfully.")
              location.reload(true);

            } else if($.trim(response) == "0") {
                alert("The airport already exists.")
                var edit = document.getElementById("update" + id).innerHTML = "Update";

            }  else if($.trim(response) == "failed") {
                alert("An error occured.");
                var edit = document.getElementById("update" + id).innerHTML = "Update";

            }           
        }); 
      }
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

</style>

<div class = "main">
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Add New Airport</h4>
      </div>

      <form method="POST" id = "add">
        <div class="input">
          <div class = "row" style = "margin-top:8px;">
            <div class="form-group col-md-12">
              <label>Airport Name:</label>
              <input type="text" class="form-control" id = "name" name = "name" placeholder = "Enter airport name..">
            </div>
          </div>
          <div class = "row" style = "margin-top:8px;">
            <div class="form-group col-md-6">
              <label>Aircraft Type:</label>
              <select id="aircraft_types" name="aircraft_types[]" onchange ="disableOptions('aircraft_types')" multiple required>
              <?php
              if($aircraft_types){
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
              <label>Operating status:</label>
              <select class="form-control" id = "operating_status" name ="operating_status">
                <option value = "1">Operational</option>
                <option value = "0">Not Operational</option>
              </select>
            </div>
          </div>

          <div class = "row" style = "margin-top:8px;">
            <div class="form-group col-md-6">
              <label>Operating Days:</label><br>
              <select id="days" name="days[]" multiple required>  
                <option value="Sun">Sun</option>              
                <option value="Mon">Mon</option> 
                <option value="Tues">Tues</option>          
                <option value="Wed">Wed</option>          
                <option value="Thurs">Thur</option>          
                <option value="Fri">Fri</option>          
                <option value="Sat">Sat</option>             
              </select>
            </div>
            <div class="form-group col-md-6">
              <label>IATA Code:</label>
              <input type="text" class="form-control" id = "iata" name = "iata" placeholder = "Enter IATA code..">
            </div>
          </div>

          <div class = "row" style = "margin-top:8px; margin-bottom:8px;">
            <div class="form-group col-md-6">
              <label>ICAO code:</label>
              <input type="text" class="form-control" id = "icao" name = "icao" placeholder = "Enter ICAO code..">
            </div>
            <div class="form-group col-md-6">
              <label>FAA code:</label>
              <input type="text" class="form-control" id = "faa" name = "faa" placeholder = "Enter FAA code..">
            </div>
          </div>
        </div>
      </form>

      <div class="modal-footer">
        <button type="button" class="button" id = "save" onclick ="addAirport()" style = "margin-right:8px;">Save</button>
        <button type="button" class="button" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div class = "card">
  <div class = "card-header" style = "height: 35px; display: flex;">
      <h6><b>Airports</b></h6>
      <img width=20 height=20 style = "float:right; margin-left: auto;" src = "<?php echo plugin_dir_url( __FILE__ ) .'../../images/airport.png';?>" alt = "airport image"/>
  </div>

  <div>
    <div class = "card-body table-responsive">
        <table id = "table" class="table table-striped table-hover" border = "0">
            <thead>
                <tr class = "header" style = "background-color: white !important;">
                    <td class = "text">Id</td>
                    <td class = "text">Airport Name</td>
                    <td class = "text">Aircraft Type</td>
                    <td class = "text">Operating Status</td>
                    <td class = "text">Operating Days</td>
                    <td class = "text">IATA Code</td>
                    <td class = "text">ICAO Code</td>
                    <td class = "text">FAA Code</td>
                    <td class = "text notexport">Actions</td>
                </tr>
            </thead>
            <tbody>
            <?php
            if($airports){
              $i = 1;
              foreach ($airports as $airport) {
            ?>

                <div id="<?php echo $airport->id."delete"; ?>" class="modal fade" role="dialog">
                  <div class="modal-dialog modal-dialog-centered">
                    <!-- Modal content-->
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title">Delete Airport</h4>
                      </div>
                      <div class="modal-body">
                        <p>Are sure you want to delete this airport?</p>
                        <button class="button" id = "<?php echo "delete".$airport->id; ?>" style = "width: 100%;" onclick ="deleteAirport(<?php echo $airport->id; ?>)">Delete</button>
                      </div>                      
                    </div>
                  </div>
                </div>


                <div id="<?php echo $airport->id."update"; ?>" class="modal fade" role="dialog">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title">Update Airport Information</h4>
                      </div>

                      <form>
                        <div class="input">
                          <div class = "row" style = "margin-top:8px;">
                            <div class="form-group col-md-12">
                              <label>Airport Name:</label>
                              <input type="text" class="form-control" id = "<?php echo "name".$airport->id; ?>" name = "name" placeholder = "Enter airport name.." value = "<?php echo $airport->name; ?>">
                              <input type="hidden" id = "<?php echo "original_name".$airport->id; ?>" value = "<?php echo $airport->name; ?>">
                            </div>
                          </div>
                          <div class = "row" style = "margin-top:8px;">
                            <div class="form-group col-md-6">
                              <label>Aircraft Type:</label>

                              <?php
                                $types = unserialize($airport->aircraft_type); 
                              ?>
                               
                              <select id="<?php echo "aircraft_types".$airport->id; ?>" name="aircraft_types[]" multiple required>  
                              <?php                               
                              if($aircraft_types) {
                                foreach ($aircraft_types as $type) {
                              ?>  
                                <option value = "<?php echo $type->aircraft_type; ?>" <?php echo in_array($type->aircraft_type, $types) ? "selected" : ""; ?>><?php echo $type->aircraft_type; ?></option>
                              <?php
                                }
                              }
                              ?>        
                              </select>
                            </div>
                            
                              
                            <div class="form-group col-md-6">
                              <label>Operating status:</label>
                              <select class="form-control" id = "<?php echo "operating_status".$airport->id; ?>" name ="operating_status">
                                <option value = "1" <?php echo $airport->operating_status == "1" ? "selected" : ""; ?>>Operational</option>
                                <option value = "0" <?php echo $airport->operating_status == "0" ? "selected" : ""; ?>>Not Operational</option>
                              </select>
                            </div>
                          </div>

                          <div class = "row" style = "margin-top:8px;">
                            <div class="form-group col-md-6">
                              <label>Operating Days:</label><br>

                              <?php $days =  unserialize($airport->operating_days);?>

                              <select id="<?php echo "operating_days".$airport->id; ?>" name="days[]" class="form-control" style = "width: 60%; border-style: solid;" multiple required>   
                                <option value="Sun" <?php echo in_array("Sun", $days) ? "selected" : ""; ?>>Sun</option>              
                                <option value="Mon" <?php echo in_array("Mon", $days) ? "selected" : ""; ?>>Mon</option> 
                                <option value="Tues" <?php echo in_array("Tues", $days) ? "selected" : ""; ?>>Tues</option>          
                                <option value="Wed" <?php echo in_array("Wed", $days) ? "selected" : ""; ?>>Wed</option>          
                                <option value="Thurs" <?php echo in_array("Thurs", $days) ? "selected" : ""; ?>>Thurs</option>          
                                <option value="Fri" <?php echo in_array("Fri", $days) ? "selected" : ""; ?>>Fri</option>          
                                <option value="Sat" <?php echo in_array("Sat", $days) ? "selected" : ""; ?>>Sat</option>                   
                              </select>
                              <script>
                                  $(document).ready(function () {
                                      $('#<?php echo "operating_days".$airport->id; ?>').multiselect({		
                                        nonSelectedText: 'Select days',
                                        buttonWidth: '100%',
                                        includeSelectAllOption: true,
                                        enableFiltering: true		
                                      });

                                      $('#<?php echo "aircraft_types".$airport->id; ?>').multiselect({		
                                        nonSelectedText: 'Select types',
                                        buttonWidth: '100%',
                                        includeSelectAllOption: true,
                                        enableFiltering: true		
                                      });

                                  });
                              </script>

                              <?php                                
                                if(in_array("All", $types)) {
                                  echo "<script>".
                                  "$('#aircraft_types".$airport->id."').multiselect('selectAll', false);".
                                  "$('#aircraft_types".$airport->id."').multiselect('updateButtonText');".
                                  "</script>";
                              }
                              ?>
                            </div>
                            <div class="form-group col-md-6">
                              <label>IATA Code:</label>
                              <input type="text" class="form-control" id = "<?php echo "iata".$airport->id; ?>" name = "iata" placeholder = "Enter IATA code.." value = "<?php echo $airport->IATA; ?>">
                            </div>
                          </div>

                          <div class = "row" style = "margin-top:8px; margin-bottom:8px;">
                            <div class="form-group col-md-6">
                              <label>ICAO code:</label>
                              <input type="text" class="form-control" id = "<?php echo "icao".$airport->id; ?>" name = "icao" placeholder = "Enter ICAO code.." value = "<?php echo $airport->ICAO; ?>">
                            </div>
                            <div class="form-group col-md-6">
                              <label>FAA code:</label>
                              <input type="text" class="form-control" id = "<?php echo "faa".$airport->id; ?>" name = "faa" placeholder = "Enter FAA code.." value = "<?php echo $airport->FAA; ?>">
                            </div>
                          </div>
                        </div>
                      </form>
                      
                      <div class="modal-footer">
                        <button type="button" class="button"  id = "<?php echo "update".$airport->id; ?>" style = "margin-right: 10px;" onclick ='updateAirport(
                          <?php echo $airport->id; ?>,
                          "<?php echo "name".$airport->id; ?>",
                          "<?php echo "aircraft_types".$airport->id; ?>",
                          "<?php echo "operating_status".$airport->id; ?>",
                          "<?php echo "operating_days".$airport->id; ?>",
                          "<?php echo "iata".$airport->id; ?>",
                          "<?php echo "icao".$airport->id; ?>",
                          "<?php echo "faa".$airport->id; ?>"
                        )'>Update</button>
                        <button type="button" class="button" data-dismiss="modal">Close</button>
                      </div>
                    </div>
                  </div>
                </div>
                <tr>
                    <td class = "data"><?php echo $i; ?></td>
                    <td class = "data"><?php echo $airport->name; ?></td> 
                    <td class = "data"> 
                      <?php 
                        $array = unserialize($airport->aircraft_type);
                        foreach($array as $type)
                        {
                          if($type == end($array)) {
                            echo $type;
                          } else {
                            echo $type.',  ';
                          }
                        } 
                      ?>
                    </td> 
                    <td class = "data"><?php echo $airport->operating_status == "1" ? "Operational" : "Not Operational"; ?></td>                  
                    <td class = "data">
                      <?php 
                        $array = unserialize($airport->operating_days);
                        foreach($array as $day)
                        {
                          if($day == end($array)) {
                            echo $day;
                          } else {
                            echo $day.',  ';
                          }
                        } 
                      ?>
                    </td>                  
                    <td class = "data"><?php echo $airport->IATA == "" ? "N/A" : $airport->IATA; ?></td>                  
                    <td class = "data"><?php echo $airport->ICAO == "" ? "N/A" : $airport->ICAO; ; ?></td>                  
                    <td class = "data"><?php echo $airport->FAA == "" ? "N/A" : $airport->FAA; ; ?></td>  
                    <td class = "action">
                    <?php global $current_user; 
                       if($current_user->roles[0] == 'administrator') {
                    ?>
                        <div style = "display: flex;">
                          <button type = "button" class = "button" data-toggle = "modal" data-target = "#<?php echo $airport->id."update"; ?>" data-backdrop = "true">Update</button>
                          <button type = "button" class = "button" style = "margin-left: 5px;" data-toggle = "modal" data-target = "#<?php echo $airport->id."delete"; ?>" data-backdrop = "true">Delete</button>
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
      <button type = "button" style = "margin-left : 5px;" class = "button" data-toggle = "modal" data-target = "#myModal" data-backdrop = "true"><span class = "add">&plus;</span>Add Airport</button>
  </div>
  <?php
  }
  ?>
</div>
</div>


<script>
    function disableOptions(id)
    {      
      var options = document.getElementById(id).options;

      for (var option of options) {
        if (!option.selected) {
          $(id).prop('disabled', false);
        }
      }
    }


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

        $('#days').multiselect({		
          nonSelectedText: 'Select days',	
          buttonWidth: '100%',
          includeSelectAllOption: true,
          enableFiltering: true
        });


        $('#aircraft_types').multiselect({		
          nonSelectedText: 'Select type',	
          buttonWidth: '100%',
          includeSelectAllOption: true,
          enableFiltering: true
        });
    });
</script>