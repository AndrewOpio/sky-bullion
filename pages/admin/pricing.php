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
   //Adding pricing
   function addPricing()
   {
      var aircraft_id = document.getElementById("aircraft_type").value;
      var minimum_range = document.getElementById("minimum_range").value;
      var maximum_range = document.getElementById("maximum_range").value;

      if ( aircraft_id == '' || minimum_range == '' || maximum_range == '') {
        alert('Please enter all fields');

      } else {

        var save = document.getElementById("save").innerHTML ="saving...";

        var ajaxurl = '<?php echo admin_url('admin-ajax.php');?>';
        var data = {
            action: 'sky_pricing',
            type: 'add',
            aircraft_id,
            minimum_range,
            maximum_range
        };

        $.post(ajaxurl, data, function(response) {
          if ($.trim(response) == "success") {
              alert("Pricing added successfully.")
              location.reload(true);

          } else if($.trim(response) == "0") {
              alert("The pricing already exists.")
              var save = document.getElementById("save").innerHTML ="save";

          }  else if($.trim(response) == "failed") {
              alert("An error occured.");
              var save = document.getElementById("save").innerHTML ="save";

          }        
        }); 
      } 
   } 


   //Deleting pricing
   function deletePricing(id)
   {
      var ajaxurl = '<?php echo admin_url('admin-ajax.php');?>';
      var data = {
          action: 'sky_pricing',
          type: 'delete',
          id: id
      };

      var del = document.getElementById("delete"+id).innerHTML ="Deleting...";

      $.post(ajaxurl, data, function(response) {
          if ($.trim(response) == "success") {
              alert("Pricing deleted successfully.")
              location.reload(true);

          } else if($.trim(response) == "failed") {
              alert("An error occured.");
              var del = document.getElementById("delete"+id).innerHTML ="Delete";
          }        
      }); 
   }

   
   //Updating pricing
   function updatePricing(id, aircraft_id, minimum_range, maximum_range)
   {
      var ajaxurl = '<?php echo admin_url('admin-ajax.php');?>';
      var aircraft_id = document.getElementById(aircraft_id).value;
      var original_aircraft_id = document.getElementById("original_aircraft_id"+id).value;
      var minimum_range = document.getElementById(minimum_range).value;
      var maximum_range = document.getElementById(maximum_range).value;

      if ( aircraft_id == '' || minimum_range == '' || maximum_range == '') {
        alert('Please enter all fields');

      } else {
        var edit = document.getElementById("update" + id).innerHTML = "Updating...";

        var data = {
            action: 'sky_pricing',
            type: 'update',
            id: id,
            aircraft_id,
            original_aircraft_id,
            minimum_range,
            maximum_range
        };

        $.post(ajaxurl, data, function(response) {
            if ($.trim(response) == "success") {
              alert("Pricing updated successfully.")
              location.reload(true);

            } else if($.trim(response) == "0") {
                alert("The pricing already exists.")
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
        <h4 class="modal-title">Add New Pricing</h4>
      </div>

      <form method="POST" id = "add">
        <div class="input">
          <div class = "row" style = "margin-top:8px; margin-bottom:8px;">
            <div class="form-group col-md-6">
              <label>Minimum Price Per Flying Hour (USD):</label>
              <input type="text" class="form-control" id = "minimum_range" name = "minimum_range" placeholder = "Enter minimum price..">
            </div>
            <div class="form-group col-md-6">
              <label>Maximum Price Per Flying Hour (USD):</label>
              <input type="text" class="form-control" id = "maximum_range" name = "maximum_range" placeholder = "Enter maximum price..">
            </div>
          </div>
          <div class = "row" style = "margin-top:5px; margin-bottom:8px;">
            <div class="form-group col-md-6">
              <label>Aircraft Name:</label>
              <select class="form-control" id = "aircraft_type" name ="aircraft_type">
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
        </div>
      </form>

      <div class="modal-footer">
        <button type="button" class="button" id = "save" onclick ="addPricing()" style = "margin-right:8px;">Save</button>
        <button type="button" class="button" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div class = "card">
  <div class = "card-header" style = "height: 35px; display: flex;">
      <h6><b>Aircraft Pricing</b></h6>
      <img width=20 height=20 style = "float:right; margin-left: auto;" src = "<?php echo plugin_dir_url( __FILE__ ) .'../../images/price.png';?>" alt = "pricing image"/>
  </div>

  <div>
    <div class = "card-body table-responsive">
        <table id = "table" class="table table-striped table-hover" border = "0">
            <thead>
                <tr class = "header" style = "background-color: white !important;">
                    <td class = "text">Id</td>
                    <td class = "text">Aircraft Name</td>
                    <td class = "text">Minimum Price Per Flying Hour (USD)</td>
                    <td class = "text">Maximum Price Per Flying Hour (USD)</td>
                    <td class = "text notexport">Actions</td>
                </tr>
            </thead>
            <tbody>
            <?php
            if($pricings){
              $i = 1;
              foreach ($pricings as $pricing) {
            ?>

                <div id="<?php echo $pricing->id."delete"; ?>" class="modal fade" role="dialog">
                  <div class="modal-dialog modal-dialog-centered">
                    <!-- Modal content-->
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title">Delete Pricing</h4>
                      </div>
                      <div class="modal-body">
                        <p>Are sure you want to delete this pricing?</p>
                        <button class="button" id = "<?php echo "delete".$pricing->id; ?>" style = "width: 100%;" onclick ="deletePricing(<?php echo $pricing->id; ?>)">Delete</button>
                      </div>                      
                    </div>
                  </div>
                </div>


                <div id="<?php echo $pricing->id."update"; ?>" class="modal fade" role="dialog">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title">Update Pricing Information</h4>
                      </div>

                      <form>
                        <div class="input">
                          <div class = "row" style = "margin-top:8px;">
                            <div class="form-group col-md-6">
                              <label>Minimum Price Per Flying Hour (USD):</label>
                              <input type="text" class="form-control" id = "<?php echo "minimum_range".$pricing->id; ?>" name = "minimum_range" placeholder = "Enter minimum price.." value = "<?php echo $pricing->minimum_range; ?>">
                            </div>
                            <div class="form-group col-md-6">
                              <label>Maximum Price Per Flying Hour (USD):</label>
                              <input type="text" class="form-control" id = "<?php echo "maximum_range".$pricing->id; ?>" name = "maximum_range" placeholder = "Enter maximum price.." value = "<?php echo $pricing->maximum_range; ?>">
                            </div>
                          </div>
                          <div class = "row" style = "margin-top:8px; margin-bottom:8px;">
                            <div class="form-group col-md-6">
                              <label>Aircraft Type:</label>
                              <select class="form-control" id = "<?php echo "aircraft_id".$pricing->id; ?>" name ="aircraft_type">
                              <?php
                              if($aircrafts){
                                foreach ($aircrafts as $aircraft) {
                              ?>  
                                <option value = "<?php echo $aircraft->id; ?>" <?php echo $aircraft->id == $pricing->aircraft_id ? "selected" : ""; ?>><?php echo $aircraft->aircraft_name; ?></option>
                              <?php
                                }
                              }
                              ?>
                              </select>
                              <input type="hidden" class="form-control" id = "<?php echo "original_aircraft_id".$pricing->id; ?>" value = "<?php echo $pricing->aircraft_id; ?>">
                            </div>
                          </div>
                        </div>
                      </form>

                      <script>
                          $('#<?php echo "aircraft_id".$pricing->id?>').multiselect({		
                              nonSelectedText: 'Select Aircraft',	
                              buttonWidth: '100%',
                              includeSelectAllOption: true,
                              enableFiltering: true
                          });
                      </script>

                      <div class="modal-footer">
                        <button type="button" class="button"  id = "<?php echo "update".$pricing->id; ?>" style = "margin-right: 10px;" onclick ='updatePricing(
                          <?php echo $pricing->id; ?>,
                          "<?php echo "aircraft_id".$pricing->id; ?>",
                          "<?php echo "minimum_range".$pricing->id; ?>",
                          "<?php echo "maximum_range".$pricing->id; ?>"
                        )'>Update</button>
                        <button type="button" class="button" data-dismiss="modal">Close</button>
                      </div>
                    </div>
                  </div>
                </div>

                <tr>
                    <td class = "data"><?php echo $i; ?></td>
                    <td class = "data"><?php
                        global $wpdb; 
                        $table_name = $wpdb->prefix."sky_aircrafts";
                        $aircraft = $wpdb->get_results("SELECT * FROM $table_name WHERE id = '$pricing->aircraft_id'");
                        foreach ($aircraft as $craft) {
                            echo $craft->aircraft_name;
                        }
                      ?></td> 
                    <td class = "data"><?php echo number_format($pricing->minimum_range); ?></td>                  
                    <td class = "data"><?php echo number_format($pricing->maximum_range); ?></td>                  
                    <td class = "action">
                    <?php global $current_user; 
                       if($current_user->roles[0] == 'administrator') {
                    ?>
                        <div style = "display: flex;">
                          <button type = "button" class = "button" data-toggle = "modal" data-target = "#<?php echo $pricing->id."update"; ?>" data-backdrop = "true">Update</button>
                          <button type = "button" class = "button" style = "margin-left: 5px;" data-toggle = "modal" data-target = "#<?php echo $pricing->id."delete"; ?>" data-backdrop = "true">Delete</button>
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
      <button type = "button" style = "margin-left : 5px;" class = "button" data-toggle = "modal" data-target = "#myModal" data-backdrop = "true"><span class = "add">&plus;</span>Add Pricing</button>
  </div>
  <?php
  }
  ?>
</div>
</div>

<script>
    $(document).ready(function () {

      $('#aircraft_type').multiselect({		
          nonSelectedText: 'Select Aircraft',	
          buttonWidth: '100%',
          includeSelectAllOption: true,
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
        } );
    });
</script>