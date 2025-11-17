<?php
$page_title = 'Add Product';
require_once('includes/load.php');
page_require_level(2);

$all_categories = find_all('categories');
$all_vendors = find_all('vendors');

// Save uploaded media to DB
function save_media_to_db($file_name, $file_type) {
    global $db;
    $query = "INSERT INTO media (file_name, file_type) VALUES ('{$db->escape($file_name)}', '{$db->escape($file_type)}')";
    if($db->query($query)){
        return $db->insert_id();
    }
    return 0;
}

// Handle form submission
if(isset($_POST['add_product']) || isset($_POST['returned_product'])){
    $req_fields = ['product-title','product-categorie','product-vendor'];
    validate_fields($req_fields);

    if(empty($errors)){
        $p_name        = remove_junk($db->escape($_POST['product-title']));
        $p_upc         = remove_junk($db->escape($_POST['product-upc']));        // NEW
        $p_value_size  = remove_junk($db->escape($_POST['value-size']));        // NEW
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
        $media_id      = 0;
        $photo_name    = "";

        // Determine table
        $table = isset($_POST['returned_product']) ? "returned_products" : "products";

        // ===== UPC UNIQUE CHECK =====
        if(!empty($p_upc)){
            $check_query = "SELECT id FROM {$table} WHERE upc='{$p_upc}' LIMIT 1";
            $result = $db->query($check_query);
            if($db->num_rows($result) > 0){
                $session->msg('d', 'UPC already exists in this table. Please enter a unique UPC.');
                redirect('add_product.php', false);
            }
        }

        // Handle file upload
        if(!empty($_FILES['product-photo']['name'])){
            $target_dir = isset($_POST['returned_product']) ? "uploads/returned_products/" : "uploads/products/";
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
                    redirect('add_product.php', false);
                }
            } else {
                $session->msg('d','Invalid file type. Allowed: jpg, jpeg, png, gif.');
                redirect('add_product.php', false);
            }
        }

        // ===== INSERT QUERY =====
        $query  = "INSERT INTO {$table} 
                  (name, upc, value_size, quantity, units_in_case, unit_cost, case_cost, unit_retail, case_retail, gpm, categorie_id, vendor_id, media_id, photo, date)
                  VALUES 
                  ('{$p_name}','{$p_upc}','{$p_value_size}','{$p_qty}','{$units_in_case}','{$unit_cost}','{$case_cost}','{$unit_retail}','{$case_retail}','{$gpm}','{$p_cat}','{$p_vendor}','{$media_id}','{$photo_name}','{$date}')";

        if($db->query($query)){
            $msg_type = isset($_POST['returned_product']) ? 'Returned product saved successfully.' : 'Product added successfully.';
            $session->msg('s', $msg_type);
            redirect('add_product.php', false);
        } else {
            $session->msg('d','Failed to save product.');
            redirect('add_product.php', false);
        }

    } else {
        $session->msg("d", $errors);
        redirect('add_product.php', false);
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
                <strong><span class="glyphicon glyphicon-plus"></span> Add New Product / Returned Product</strong>
            </div>
            <div class="panel-body">
                <form method="post" action="add_product.php" enctype="multipart/form-data">

                    <!-- Product Name -->
                    <div class="form-group">
                        <label>Product Title</label>
                        <input type="text" class="form-control" name="product-title" required>
                    </div>

                    <!-- UPC and Value Size -->
                    <div class="row">
                        <div class="col-md-6">
                            <label>UPC (Numbers Only)</label>
                            <input type="text" class="form-control" name="product-upc" pattern="[0-9]*" inputmode="numeric" maxlength="20" placeholder="Enter UPC e.g. 8901234567890">
                        </div>
                        <div class="col-md-6">
                            <label>Value Size (e.g. 1kg, 500ml, 2lb)</label>
                            <input type="text" class="form-control" name="value-size" maxlength="20" placeholder="Enter size e.g. 1kg, 500ml, 2lb">
                        </div>
                    </div>
                    <hr>

                    <!-- Category & Vendor -->
                    <div class="row">
                        <div class="col-md-6">
                            <label>Category</label>
                            <select class="form-control" name="product-categorie" required>
                                <option value="">Select Category</option>
                                <?php foreach ($all_categories as $cat): ?>
                                    <option value="<?php echo (int)$cat['id'] ?>"><?php echo $cat['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label>Vendor</label>
                            <select class="form-control" name="product-vendor" required>
                                <option value="">Select Vendor</option>
                                <?php foreach ($all_vendors as $vendor): ?>
                                    <option value="<?php echo (int)$vendor['id'] ?>"><?php echo $vendor['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <hr>

                    <!-- Case and Unit Fields -->
                    <div class="row">
                        <div class="col-md-3">
                            <label>Case Quantity</label>
                            <input type="number" min="0" name="product-quantity" class="form-control" id="caseQty">
                        </div>
                        <div class="col-md-3">
                            <label>Units in Case</label>
                            <input type="number" min="1" name="units-in-case" class="form-control" id="unitsInCase" oninput="recalculateAll()">
                        </div>
                        <div class="col-md-3">
                            <label>Case Cost</label>
                            <input type="number" step="0.01" name="case-cost" class="form-control" id="caseCost" oninput="updateFromCaseCost()">
                        </div>
                        <div class="col-md-3">
                            <label>Unit Cost</label>
                            <input type="number" step="0.01" name="unit-cost" class="form-control" id="unitCost" oninput="updateFromUnitCost()">
                        </div>
                    </div>

                    <div class="row" style="margin-top:10px;">
                        <div class="col-md-3">
                            <label>Case Retail</label>
                            <input type="number" step="0.01" name="case-retail" class="form-control" id="caseRetail" oninput="updateFromCaseRetail()">
                        </div>
                        <div class="col-md-3">
                            <label>Unit Retail</label>
                            <input type="number" step="0.01" name="unit-retail" class="form-control" id="unitRetail" oninput="updateFromUnitRetail()">
                        </div>
                        <div class="col-md-3">
                            <label>GPM (%)</label>
                            <input type="number" step="0.01" name="gpm" class="form-control" id="gpm" readonly>
                        </div>
                        <div class="col-md-3">
                            <label>Product Image</label>
                            <input type="file" name="product-photo" class="form-control" id="product-photo" onchange="previewImage(event)">
                        </div>
                    </div>

                    <div class="row" style="margin-top:15px;">
                        <div class="col-md-3"></div>
                        <div class="col-md-6 text-center">
                            <img id="preview" src="#" alt="Image Preview" style="display:none; max-width:100%; border:1px solid #ccc; padding:5px; border-radius:4px;">
                        </div>
                    </div>

                    <hr>

                    <!-- Buttons -->
                    <div class="form-group text-right">
                        <button type="submit" name="add_product" class="btn btn-success">Add Product</button>
                        <button type="submit" name="returned_product" class="btn btn-warning">Returned Product</button>
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
</script>

<?php include_once('layouts/footer.php'); ?>
