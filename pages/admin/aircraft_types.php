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

<script type ="text/javascript">
   //Adding aircraft type
   function addAircraftType()
   {
      var aircraft_type = document.getElementById("aircraft_type").value;

      if ( aircraft_type == '') {
        alert('Please enter all fields');

      } else {

        var save = document.getElementById("save").innerHTML ="saving...";

        var ajaxurl = '<?php echo admin_url('admin-ajax.php');?>';
        var data = {
            action: 'sky_aircraft_types',
            type: 'add',
            aircraft_type
        };

        $.post(ajaxurl, data, function(response) {
          if ($.trim(response) == "success") {
              alert("Aircraft type added successfully.")
              location.reload(true);

          } else if($.trim(response) == "0") {
              alert("The aircraft type already exists.")
              var save = document.getElementById("save").innerHTML ="save";

          }  else if($.trim(response) == "failed") {
              alert("An error occured.");
              var save = document.getElementById("save").innerHTML ="save";

          }
        }); 
      } 
   } 

   //Deleting an aircraft type
   function deleteAircraftType(id)
   {
      var ajaxurl = '<?php echo admin_url('admin-ajax.php');?>';
       //alert(id);
      var data = {
          action: 'sky_aircraft_types',
          type: 'delete',
          id: id
      };

      var del = document.getElementById("delete"+id).innerHTML ="Deleting...";

      $.post(ajaxurl, data, function(response) {
          if ($.trim(response) == "success") {
              alert("Aircraft type deleted successfully.")
              location.reload(true);

          } else if($.trim(response) == "failed") {
              alert("An error occured.");
              var del = document.getElementById("delete"+id).innerHTML ="Delete";
          }
      }); 
   }



   //Updating an aircraft type
   function updateAircraftType(id, aircraft_type)
   {
      var ajaxurl = '<?php echo admin_url('admin-ajax.php');?>';
      var aircraft_type = document.getElementById(aircraft_type).value;
      var original_type = document.getElementById("original_type"+id).value;

      if ( aircraft_type == '') {
        alert('Please enter all fields');

      } else {

        var edit = document.getElementById("update" + id).innerHTML = "Updating...";

        var data = {
            action: 'sky_aircraft_types',
            type: 'update',
            id: id,
            aircraft_type,
            original_type
        };

        $.post(ajaxurl, data, function(response) {
          if ($.trim(response) == "success") {
              alert("Aircraft type updated successfully.")
              location.reload(true);

          } else if($.trim(response) == "0") {
              alert("The aircraft type already exists.")
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
</style>

<div class = "main">
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Add New Aircraft Type</h4>
      </div>

      <form method="POST" id = "add">
        <div class="input">
          <div class = "row" style = "margin-top:8px; margin-top:8px;">
            <div class="form-group col-md-12">
              <label>Aircraft Type:</label>
              <input type="text" class="form-control" id = "aircraft_type" name = "aircraft_type" placeholder = "Enter aircraft type..">
            </div>
          </div>
          <input type="hidden" name = "add" value = "add">
        </div>
      </form>

      <div class="modal-footer">
        <button type="button" class="button" id = "save" onclick ="addAircraftType()" style = "margin-right:8px;">Save</button>
        <button type="button" class="button" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div class = "card">
  <div class = "card-header" style = "height: 35px; display: flex;">
      <h6><b>Aircraft Types</b></h6>
      <img width=20 height=20 style = "float:right; margin-left: auto;" src = "<?php echo plugin_dir_url( __FILE__ ) .'../../images/aeroplane.png';?>" alt = "airplane"/>
  </div>

  <div>
    <div class = "card-body table-responsive">
        <table id = "table" class="table table-striped table-hover" border = "0">
            <thead>
                <tr class = "header" style = "background-color: white !important;">
                    <td class = "text">Id</td>
                    <td class = "text">Aircraft Type</td>
                    <td class = "text notexport">Actions</td>
                </tr>
            </thead>
            <tbody>
            <?php
            if($aircraft_types){
              $i = 1;
              foreach ($aircraft_types as $aircraft_type) {
            ?>

                <div id="<?php echo $aircraft_type->id."delete"; ?>" class="modal fade" role="dialog">
                  <div class="modal-dialog modal-dialog-centered">
                    <!-- Modal content-->
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title">Delete Aircraft Type</h4>
                      </div>
                      <div class="modal-body">
                        <p>Are sure you want to delete this aircraft type?</p>
                        <button class="button" id = "<?php echo "delete".$aircraft_type->id; ?>" style = "width: 100%;" onclick ="deleteAircraftType(<?php echo $aircraft_type->id; ?>)">Delete</button>
                      </div>                      
                    </div>
                  </div>
                </div>


                <div id="<?php echo $aircraft_type->id."update"; ?>" class="modal fade" role="dialog">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title">Update Aircraft Type Information</h4>
                      </div>

                      <form>
                        <div class="input">
                          <div class = "row" style = "margin-top:8px; margin-bottom:8px;">
                            <div class="form-group col-md-12">
                              <label>Aircraft Type:</label>
                              <input type="text" class="form-control" id = "<?php echo "aircraft_type".$aircraft_type->id; ?>" name = "aircraft_type" placeholder = "Enter aircraft type.." value = "<?php echo $aircraft_type->aircraft_type; ?>">
                              <input type="hidden" class="form-control" id = "<?php echo "original_type".$aircraft_type->id; ?>" name = "aircraft_type" value = "<?php echo $aircraft_type->aircraft_type; ?>">
                            </div>
                          </div>
                        </div>
                      </form>
                      
                      <div class="modal-footer">
                        <button type="button" class="button"  id = "<?php echo "update".$aircraft_type->id; ?>" style = "margin-right: 10px;" onclick ='updateAircraftType(
                          <?php echo $aircraft_type->id; ?>,
                          "<?php echo "aircraft_type".$aircraft_type->id; ?>",
                        )'>Update</button>
                        <button type="button" class="button" data-dismiss="modal">Close</button>
                      </div>
                    </div>
                  </div>
                </div>

                <tr>
                    <td class = "data"><?php echo $i; ?></td>
                    <td class = "data"><?php echo $aircraft_type->aircraft_type; ?></td> 
                    <td class = "action">
                    <?php global $current_user; 
                       if($current_user->roles[0] == 'administrator') {
                    ?>
                        <div style = "display: flex;">
                          <button type = "button" class = "button" data-toggle = "modal" data-target = "#<?php echo $aircraft_type->id."update"; ?>" data-backdrop = "true">Update</button>
                          <button type = "button" class = "button" style = "margin-left: 5px;" data-toggle = "modal" data-target = "#<?php echo $aircraft_type->id."delete"; ?>" data-backdrop = "true">Delete</button>
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