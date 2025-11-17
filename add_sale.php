<?php
$page_title = 'Add Sale';
require_once('includes/load.php');
page_require_level(3);

$all_products = find_all('products');

if (isset($_POST['add_sale'])) {
    $req_fields = ['product_id', 'quantity', 'price', 'total', 'date'];
    validate_fields($req_fields);

    if (empty($errors)) {
        $p_id    = (int)$db->escape($_POST['product_id']);
        $s_qty   = (int)$db->escape($_POST['quantity']);
        $p_price = (float)$db->escape($_POST['price']);
        $s_total = (float)$db->escape($_POST['total']);
        $s_date  = $db->escape($_POST['date']);

        // Fetch product info
        $product = find_by_id('products', $p_id);
        if (!$product) {
            $session->msg('d', "Product not found!");
            redirect('add_sale.php', false);
        }

        // Check stock
        if ($s_qty > $product['quantity']) {
            $session->msg('d', "Not enough stock available!");
            redirect('add_sale.php', false);
        }

        // Insert sale with snapshot
        $sql = "INSERT INTO sales (
                    product_id, qty, price, total, date,
                    product_name, upc, value_size, unit_cost, unit_retail, gpm
                ) VALUES (
                    '{$p_id}', '{$s_qty}', '{$p_price}', '{$s_total}', '{$s_date}',
                    '{$db->escape($product['name'])}',
                    '{$db->escape($product['upc'])}',
                    '{$db->escape($product['value_size'])}',
                    '{$db->escape($product['unit_cost'])}',
                    '{$db->escape($product['unit_retail'])}',
                    '{$db->escape($product['gpm'])}'
                )";

        if ($db->query($sql)) {
            // Deduct stock from inventory
            $new_qty = $product['quantity'] - $s_qty;
            $db->query("UPDATE products SET quantity='{$new_qty}' WHERE id='{$p_id}'");

            $session->msg('s', "Sale added successfully with product snapshot.");
            redirect('add_sale.php', false);
        } else {
            $session->msg('d', "Failed to add sale!");
            redirect('add_sale.php', false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('add_sale.php', false);
    }
}
?>

<?php include_once('layouts/header.php'); ?>

<div class="row">
  <div class="col-md-10 col-md-offset-1">
    <?php echo display_msg($msg); ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong><span class="glyphicon glyphicon-shopping-cart"></span> Add Sale (From Inventory)</strong>
      </div>
      <div class="panel-body">
        <form method="post" action="add_sale.php" autocomplete="off">

          <!-- Product Selection -->
          <div class="form-group">
            <label for="product_id">Select Product</label>
            <select class="form-control" name="product_id" id="product_id" onchange="fillProductDetails()" required>
              <option value="">Select Product</option>
              <?php foreach ($all_products as $p): ?>
                <option 
                  value="<?php echo $p['id']; ?>"
                  data-upc="<?php echo $p['upc']; ?>"
                  data-size="<?php echo $p['value_size']; ?>"
                  data-qty="<?php echo $p['quantity']; ?>"
                  data-units="<?php echo $p['units_in_case']; ?>"
                  data-unitcost="<?php echo $p['unit_cost']; ?>"
                  data-casecost="<?php echo $p['case_cost']; ?>"
                  data-unitretail="<?php echo $p['unit_retail']; ?>"
                  data-caseretail="<?php echo $p['case_retail']; ?>"
                  data-gpm="<?php echo $p['gpm']; ?>"
                  data-cat="<?php echo $p['categorie_id']; ?>"
                  data-vendor="<?php echo $p['vendor_id']; ?>"
                  data-photo="<?php echo $p['photo']; ?>">
                  <?php echo $p['name']; ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <!-- Product Details (read-only) -->
          <div class="row">
            <div class="col-md-4">
              <label>UPC</label>
              <input type="text" id="upc" class="form-control" readonly>
            </div>
            <div class="col-md-4">
              <label>Value/Size</label>
              <input type="text" id="value_size" class="form-control" readonly>
            </div>
            <div class="col-md-4">
              <label>Available Stock</label>
              <input type="text" id="stock" class="form-control" readonly>
            </div>
          </div>

          <div class="row" style="margin-top:10px;">
            <div class="col-md-3">
              <label>Units in Case</label>
              <input type="text" id="units_in_case" class="form-control" readonly>
            </div>
            <div class="col-md-3">
              <label>Unit Cost</label>
              <input type="text" id="unit_cost" class="form-control" readonly>
            </div>
            <div class="col-md-3">
              <label>Case Cost</label>
              <input type="text" id="case_cost" class="form-control" readonly>
            </div>
            <div class="col-md-3">
              <label>Unit Retail</label>
              <input type="text" name="price" id="unit_retail" class="form-control" readonly>
            </div>
          </div>

          <div class="row" style="margin-top:10px;">
            <div class="col-md-3">
              <label>Case Retail</label>
              <input type="text" id="case_retail" class="form-control" readonly>
            </div>
            <div class="col-md-3">
              <label>GPM (%)</label>
              <input type="text" id="gpm" class="form-control" readonly>
            </div>
            <div class="col-md-3">
              <label>Quantity Sold</label>
              <input type="number" min="1" name="quantity" id="quantity" class="form-control" oninput="calculateTotal()" required>
            </div>
            <div class="col-md-3">
              <label>Total</label>
              <input type="number" step="0.01" name="total" id="total" class="form-control" readonly>
            </div>
          </div>

          <div class="row" style="margin-top:10px;">
            <div class="col-md-6">
              <label>Date</label>
              <input type="date" name="date" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
            </div>
            <div class="col-md-6 text-center">
              <label>Product Image</label><br>
              <img id="product_photo" src="#" alt="Preview" style="max-width:150px; display:none; border:1px solid #ccc; border-radius:6px;">
            </div>
          </div>

          <hr>
          <div class="form-group text-right">
            <button type="submit" name="add_sale" class="btn btn-success">Add Sale</button>
          </div>

        </form>
      </div>
    </div>
  </div>
</div>

<script>
function fillProductDetails() {
  const select = document.getElementById('product_id');
  const selected = select.options[select.selectedIndex];
  if (!selected.value) return;

  // Fill read-only fields
  document.getElementById('upc').value = selected.dataset.upc || '';
  document.getElementById('value_size').value = selected.dataset.size || '';
  document.getElementById('stock').value = selected.dataset.qty || '';
  document.getElementById('units_in_case').value = selected.dataset.units || '';
  document.getElementById('unit_cost').value = selected.dataset.unitcost || '';
  document.getElementById('case_cost').value = selected.dataset.casecost || '';
  document.getElementById('unit_retail').value = selected.dataset.unitretail || '';
  document.getElementById('case_retail').value = selected.dataset.caseretail || '';
  document.getElementById('gpm').value = selected.dataset.gpm || '';

  const photo = selected.dataset.photo;
  const img = document.getElementById('product_photo');
  if (photo) {
    img.src = 'uploads/products/' + photo;
    img.style.display = 'block';
  } else {
    img.style.display = 'none';
  }

  calculateTotal();
}

function calculateTotal() {
  const price = parseFloat(document.getElementById('unit_retail').value) || 0;
  const qty = parseFloat(document.getElementById('quantity').value) || 0;
  document.getElementById('total').value = (price * qty).toFixed(2);
}
</script>

<?php include_once('layouts/footer.php'); ?>
