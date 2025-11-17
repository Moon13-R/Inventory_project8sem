<?php
  $page_title = 'Edit Vendor';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(1);

  if (!isset($_GET['id']) || empty($_GET['id'])) {
    $session->msg("d", "Missing vendor ID.");
    redirect('vendors.php', false);
  }

  $vendor_id = (int)$_GET['id'];
  $vendor = find_by_id('vendors', $vendor_id);

  if (!$vendor) {
    $session->msg("d", "Vendor not found.");
    redirect('vendors.php', false);
  }

  if (isset($_POST['update_vendor'])) {
    $req_fields = array('vendor-name');
    validate_fields($req_fields);

    $vendor_name = remove_junk($db->escape($_POST['vendor-name']));
    $vendor_contact = remove_junk($db->escape($_POST['vendor-contact']));
    $vendor_address = remove_junk($db->escape($_POST['vendor-address']));

    if (empty($errors)) {
      $sql = "UPDATE vendors SET name='{$vendor_name}', contact='{$vendor_contact}', address='{$vendor_address}' WHERE id='{$vendor_id}'";
      if ($db->query($sql)) {
        $session->msg("s", "Vendor updated successfully.");
        redirect('vendors.php', false);
      } else {
        $session->msg("d", "Failed to update vendor.");
        redirect('edit_vendor.php?id={$vendor_id}', false);
      }
    } else {
      $session->msg("d", $errors);
      redirect('edit_vendor.php?id={$vendor_id}', false);
    }
  }
?>
<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-6">
    <?php echo display_msg($msg); ?>
    <form method="post" action="edit_vendor.php?id=<?php echo (int)$vendor_id; ?>">
      <div class="form-group">
        <label for="vendor-name">Vendor Name</label>
        <input type="text" class="form-control" name="vendor-name" value="<?php echo remove_junk($vendor['name']); ?>" required>
      </div>
      <div class="form-group">
        <label for="vendor-contact">Contact</label>
        <input type="text" class="form-control" name="vendor-contact" value="<?php echo remove_junk($vendor['contact']); ?>">
      </div>
      <div class="form-group">
        <label for="vendor-address">Address</label>
        <textarea class="form-control" name="vendor-address"><?php echo remove_junk($vendor['address']); ?></textarea>
      </div>
      <button type="submit" name="update_vendor" class="btn btn-primary">Update Vendor</button>
    </form>
  </div>
</div>
<?php include_once('layouts/footer.php'); ?>
