<?php
  $page_title = 'All Vendors';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(1);

  $all_vendors = find_all('vendors')
?>
<?php
 if(isset($_POST['add_vendor'])){
   $req_field = array('vendor-name');
   validate_fields($req_field);
   $vendor_name = remove_junk($db->escape($_POST['vendor-name']));
   $vendor_contact = remove_junk($db->escape($_POST['vendor-contact']));
   $vendor_address = remove_junk($db->escape($_POST['vendor-address']));
   if(empty($errors)){
      $sql  = "INSERT INTO vendors (name, contact, address)";
      $sql .= " VALUES ('{$vendor_name}', '{$vendor_contact}', '{$vendor_address}')";
      if($db->query($sql)){
        $session->msg("s", "Successfully Added New Vendor");
        redirect('vendors.php',false);
      } else {
        $session->msg("d", "Sorry Failed to insert.");
        redirect('vendors.php',false);
      }
   } else {
     $session->msg("d", $errors);
     redirect('vendors.php',false);
   }
 }
?>
<?php include_once('layouts/header.php'); ?>

  <div class="row">
     <div class="col-md-12">
       <?php echo display_msg($msg); ?>
     </div>
  </div>
   <div class="row">
    <div class="col-md-5">
      <div class="panel panel-default">
        <div class="panel-heading">
          <strong>
            <span class="glyphicon glyphicon-th"></span>
            <span>Add New Vendor</span>
         </strong>
        </div>
        <div class="panel-body">
          <form method="post" action="vendors.php">
            <div class="form-group">
                <input type="text" class="form-control" name="vendor-name" placeholder="Vendor Name" required>
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="vendor-contact" placeholder="Contact">
            </div>
            <div class="form-group">
                <textarea class="form-control" name="vendor-address" placeholder="Address"></textarea>
            </div>
            <button type="submit" name="add_vendor" class="btn btn-primary">Add Vendor</button>
        </form>
        </div>
      </div>
    </div>
    <div class="col-md-7">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>All Vendors</span>
       </strong>
      </div>
        <div class="panel-body">
          <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th class="text-center" style="width: 50px;">#</th>
                    <th>Vendor Name</th>
                    <th>Contact</th>
                    <th>Address</th>
                    <th class="text-center" style="width: 100px;">Actions</th>
                </tr>
            </thead>
            <tbody>
              <?php foreach ($all_vendors as $vendor):?>
                <tr>
                    <td class="text-center"><?php echo count_id();?></td>
                    <td><?php echo remove_junk(ucfirst($vendor['name'])); ?></td>
                    <td><?php echo remove_junk($vendor['contact']); ?></td>
                    <td><?php echo remove_junk($vendor['address']); ?></td>
                    <td class="text-center">
                      <div class="btn-group">
                        <a href="edit_vendor.php?id=<?php echo (int)$vendor['id'];?>"  class="btn btn-xs btn-warning" data-toggle="tooltip" title="Edit">
                          <span class="glyphicon glyphicon-edit"></span>
                        </a>
                        <a href="delete_vendor.php?id=<?php echo (int)$vendor['id'];?>"  class="btn btn-xs btn-danger" data-toggle="tooltip" title="Remove">
                          <span class="glyphicon glyphicon-trash"></span>
                        </a>
                      </div>
                    </td>

                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
       </div>
    </div>
    </div>
   </div>
  </div>
  <?php include_once('layouts/footer.php'); ?>
