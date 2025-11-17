<?php
$page_title = 'Edit Product';
require_once('includes/load.php');
page_require_level(2);

// Validate ID
$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if($product_id <= 0){
    $session->msg("d","Invalid product ID.");
    redirect('product.php', false);
}

// Fetch product
$sql = "SELECT * FROM products WHERE id = '{$product_id}' LIMIT 1";
$result = $db->query($sql);

if($db->num_rows($result) === 0){
    $session->msg("d","Product not found.");
    redirect('product.php', false);
}

$product = mysqli_fetch_assoc($result);

// Fetch dropdown data
$all_categories = find_all('categories');
$all_vendors = find_all('vendors');

// Handle Update
if(isset($_POST['update_product'])){
    $req_fields = ['product-title','product-categorie','product-vendor'];
    validate_fields($req_fields);

    if(empty($errors)){
        $p_name        = remove_junk($db->escape($_POST['product-title']));
        $p_upc         = remove_junk($db->escape($_POST['product-upc']));
        $p_value_size  = remove_junk($db->escape($_POST['value-size']));
        $p_cat         = remove_junk($db->escape($_POST['product-categorie']));
        $p_vendor      = remove_junk($db->escape($_POST['product-vendor']));
        $p_qty         = remove_junk($db->escape($_POST['product-quantity']));
        $units_in_case = remove_junk($db->escape($_POST['units-in-case']));
        $case_cost     = remove_junk($db->escape($_POST['case-cost']));
        $unit_cost     = remove_junk($db->escape($_POST['unit-cost']));
        $case_retail   = remove_junk($db->escape($_POST['case-retail']));
        $unit_retail   = remove_junk($db->escape($_POST['unit-retail']));
        $gpm           = remove_junk($db->escape($_POST['gpm']));
        $date          = make_date();

        // Check duplicate UPC
        $check = $db->query("SELECT id FROM products WHERE upc='{$p_upc}' AND id != '{$product['id']}' LIMIT 1");
        if($db->num_rows($check) > 0){
            $session->msg('d','UPC already exists. Enter a unique UPC.');
            redirect('edit_product.php?id='.$product['id'], false);
        }

        // Handle photo
        $photo_name = $product['photo'] ?? '';
        $media_id   = $product['media_id'] ?? 0;

        if(!empty($_FILES['product-photo']['name'])){
            $target_dir = "uploads/products/";
            if(!file_exists($target_dir)) mkdir($target_dir, 0777, true);

            $file_name = time() . "_" . basename($_FILES["product-photo"]["name"]);
            $target_file = $target_dir . $file_name;
            $file_type = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            $allowed_types = ["jpg","jpeg","png","gif"];

            if(in_array($file_type, $allowed_types)){
                if(move_uploaded_file($_FILES["product-photo"]["tmp_name"], $target_file)){
                    $photo_name = $file_name;
                    $media_id = save_media_to_db($file_name, $file_type);
                } else {
                    $session->msg('d','Failed to upload file.');
                    redirect('edit_product.php?id='.$product['id'], false);
                }
            } else {
                $session->msg('d','Invalid file type. Allowed: jpg, jpeg, png, gif.');
                redirect('edit_product.php?id='.$product['id'], false);
            }
        }

        // Update DB
        $query = "UPDATE products SET 
            name='{$p_name}',
            upc='{$p_upc}',
            value_size='{$p_value_size}',
            quantity='{$p_qty}',
            units_in_case='{$units_in_case}',
            case_cost='{$case_cost}',
            unit_cost='{$unit_cost}',
            case_retail='{$case_retail}',
            unit_retail='{$unit_retail}',
            gpm='{$gpm}',
            categorie_id='{$p_cat}',
            vendor_id='{$p_vendor}',
            media_id='{$media_id}',
            photo='{$photo_name}',
            date='{$date}'
            WHERE id='{$product['id']}'";

        if($db->query($query)){
            $session->msg('s','Product updated successfully.');
            redirect('product.php', false);
        } else {
            $session->msg('d','Failed to update product.');
            redirect('edit_product.php?id='.$product['id'], false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('edit_product.php?id='.$product['id'], false);
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
  <div class="col-md-10">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong><span class="glyphicon glyphicon-edit"></span> Edit Product</strong>
      </div>
      <div class="panel-body">
        <form method="post" action="edit_product.php?id=<?php echo $product_id; ?>" enctype="multipart/form-data">

          <div class="form-group">
            <label>Product Title</label>
            <input type="text" name="product-title" class="form-control" 
                   value="<?php echo $product['name'] ?? ''; ?>" required>
          </div>

          <div class="row">
            <div class="col-md-6">
              <label>UPC (Numbers Only)</label>
              <input type="text" name="product-upc" class="form-control"
                     value="<?php echo $product['upc'] ?? ''; ?>" 
                     pattern="[0-9]*" inputmode="numeric" maxlength="20" placeholder="Enter UPC e.g. 8901234567890">
            </div>
            <div class="col-md-6">
              <label>Value Size (e.g. 1kg, 500ml, 2lb)</label>
              <input type="text" name="value-size" class="form-control" 
                     value="<?php echo $product['value_size'] ?? ''; ?>" maxlength="20" placeholder="Enter size e.g. 1kg, 500ml, 2lb">
            </div>
          </div>

          <hr>

          <div class="row">
            <div class="col-md-6">
              <label>Category</label>
              <select name="product-categorie" class="form-control" required>
                <option value="">Select Category</option>
                <?php foreach($all_categories as $cat): ?>
                  <option value="<?php echo $cat['id']; ?>" 
                    <?php echo (($product['categorie_id'] ?? '') == $cat['id']) ? 'selected' : ''; ?>>
                    <?php echo remove_junk($cat['name']); ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="col-md-6">
              <label>Vendor</label>
              <select name="product-vendor" class="form-control" required>
                <option value="">Select Vendor</option>
                <?php foreach($all_vendors as $vendor): ?>
                  <option value="<?php echo $vendor['id']; ?>" 
                    <?php echo (($product['vendor_id'] ?? '') == $vendor['id']) ? 'selected' : ''; ?>>
                    <?php echo remove_junk($vendor['name']); ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>

          <hr>

          <div class="row">
            <div class="col-md-3">
              <label>Case Quantity</label>
              <input type="number" name="product-quantity" class="form-control" id="caseQty"
                     value="<?php echo $product['quantity'] ?? 0; ?>" min="0">
            </div>
            <div class="col-md-3">
              <label>Units in Case</label>
              <input type="number" name="units-in-case" class="form-control" id="unitsInCase"
                     value="<?php echo $product['units_in_case'] ?? 1; ?>" min="1" oninput="recalculateAll()">
            </div>
            <div class="col-md-3">
              <label>Case Cost</label>
              <input type="number" step="0.01" name="case-cost" class="form-control" id="caseCost"
                     value="<?php echo $product['case_cost'] ?? 0; ?>" oninput="updateFromCaseCost()">
            </div>
            <div class="col-md-3">
              <label>Unit Cost</label>
              <input type="number" step="0.01" name="unit-cost" class="form-control" id="unitCost"
                     value="<?php echo $product['unit_cost'] ?? 0; ?>" oninput="updateFromUnitCost()">
            </div>
          </div>

          <div class="row" style="margin-top:10px;">
            <div class="col-md-3">
              <label>Case Retail</label>
              <input type="number" step="0.01" name="case-retail" class="form-control" id="caseRetail"
                     value="<?php echo $product['case_retail'] ?? 0; ?>" oninput="updateFromCaseRetail()">
            </div>
            <div class="col-md-3">
              <label>Unit Retail</label>
              <input type="number" step="0.01" name="unit-retail" class="form-control" id="unitRetail"
                     value="<?php echo $product['unit_retail'] ?? 0; ?>" oninput="updateFromUnitRetail()">
            </div>
            <div class="col-md-3">
              <label>GPM (%)</label>
              <input type="number" step="0.01" name="gpm" class="form-control" id="gpm"
                     value="<?php echo $product['gpm'] ?? 0; ?>" readonly>
            </div>
            <div class="col-md-3">
              <label>Product Image</label>
              <input type="file" name="product-photo" class="form-control" id="product-photo" onchange="previewImage(event)">
            </div>
          </div>

          <div class="row" style="margin-top:15px;">
            <div class="col-md-3"></div>
            <div class="col-md-6 text-center">
              <img id="preview"
                   src="<?php echo !empty($product['photo']) ? 'uploads/products/'.$product['photo'] : 'uploads/products/no_image.png'; ?>"
                   style="display:block; max-width:100%; border:1px solid #ccc; padding:5px; border-radius:4px;" alt="Image Preview">
            </div>
          </div>

          <hr>
          <div class="form-group text-right">
            <button type="submit" name="update_product" class="btn btn-success">Update Product</button>
          </div>

        </form>
      </div>
    </div>
  </div>
</div>

<script>
// ====== IMAGE PREVIEW ======
function previewImage(event) {
    const preview = document.getElementById('preview');
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = e => {
            preview.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(file);
    } else {
        preview.style.display = 'none';
    }
}

// ====== CALCULATIONS ======
function updateFromCaseCost() {
    const caseCost = parseFloat(document.getElementById('caseCost').value) || 0;
    const units = parseFloat(document.getElementById('unitsInCase').value) || 1;
    document.getElementById('unitCost').value = (caseCost / units).toFixed(2);
    recalculateGPM();
}

function updateFromUnitCost() {
    const unitCost = parseFloat(document.getElementById('unitCost').value) || 0;
    const units = parseFloat(document.getElementById('unitsInCase').value) || 1;
    document.getElementById('caseCost').value = (unitCost * units).toFixed(2);
    recalculateGPM();
}

function updateFromCaseRetail() {
    const caseRetail = parseFloat(document.getElementById('caseRetail').value) || 0;
    const units = parseFloat(document.getElementById('unitsInCase').value) || 1;
    document.getElementById('unitRetail').value = (caseRetail / units).toFixed(2);
    recalculateGPM();
}

function updateFromUnitRetail() {
    const unitRetail = parseFloat(document.getElementById('unitRetail').value) || 0;
    const units = parseFloat(document.getElementById('unitsInCase').value) || 1;
    document.getElementById('caseRetail').value = (unitRetail * units).toFixed(2);
    recalculateGPM();
}

function recalculateAll() {
    updateFromCaseCost();
    updateFromCaseRetail();
}

function recalculateGPM() {
    const unitCost = parseFloat(document.getElementById('unitCost').value) || 0;
    const unitRetail = parseFloat(document.getElementById('unitRetail').value) || 0;
    const gpm = unitRetail > 0 ? (((unitRetail - unitCost) / unitRetail) * 100).toFixed(2) : 0;
    document.getElementById('gpm').value = gpm;
}

// Initialize calculations on load to ensure consistency
window.onload = function() {
    recalculateAll();
    recalculateGPM();
};
</script>

<?php include_once('layouts/footer.php'); ?>