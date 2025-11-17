<?php
$page_title = 'Returned Products';
require_once('includes/load.php');
page_require_level(2);

// Fetch returned products with category and vendor info
// ⚠️ You can create a function like join_returned_product_table() in your includes/functions.php
// For now, we’ll just query directly:

$sql  = "SELECT r.*, c.name AS categorie, v.name AS vendor 
         FROM returned_products r
         LEFT JOIN categories c ON r.categorie_id = c.id
         LEFT JOIN vendors v ON r.vendor_id = v.id
         ORDER BY r.id DESC";

$returned_products = find_by_sql($sql);
?>

<?php include_once('layouts/header.php'); ?>

<div class="row">
  <div class="col-md-12">
    <?php echo display_msg($msg); ?>
  </div>

  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading clearfix">
        <strong><span class="glyphicon glyphicon-repeat"></span> Returned Products</strong>
        <div class="pull-right">
          <a href="add_product.php" class="btn btn-primary">Add New</a>
          <a href="product.php" class="btn btn-success">All Products</a>
        </div>
      </div>

      <div class="panel-body">
        <div class="table-responsive">
          <table class="table table-bordered table-striped table-hover">
            <thead>
              <tr class="info">
                <th class="text-center" style="width: 50px;">#</th>
                <th class="text-center" style="width: 80px;">Photo</th>
                <th>Product Title</th>
                <th class="text-center">Category</th>
                <th class="text-center">Vendor</th>
                <th class="text-center">UPC</th>
                <th class="text-center">Value Size</th>
                <th class="text-center">Qty (Cases)</th>
                <th class="text-center">Units/Case</th>
                <th class="text-center">Unit Cost</th>
                <th class="text-center">Case Cost</th>
                <th class="text-center">Unit Retail</th>
                <th class="text-center">Case Retail</th>
                <th class="text-center">GPM (%)</th>
                <th class="text-center" style="width: 120px;">Returned On</th>
                <th class="text-center" style="width: 100px;">Actions</th>
              </tr>
            </thead>

            <tbody>
              <?php if ($returned_products): ?>
                <?php foreach ($returned_products as $product): ?>
                  <tr>
                    <td class="text-center"><?php echo count_id(); ?></td>

                    <!-- Product Photo -->
                    <td class="text-center">
                      <?php if(empty($product['photo'])): ?>
                        <img src="uploads/products/no_image.png" class="img-thumbnail" width="60" height="60" alt="No image">
                      <?php else: ?>
                        <img src="uploads/products/<?php echo $product['photo']; ?>" class="img-thumbnail" width="60" height="60" alt="<?php echo remove_junk($product['name']); ?>">
                      <?php endif; ?>
                    </td>

                    <!-- Product Info -->
                    <td><?php echo remove_junk($product['name']); ?></td>
                    <td class="text-center"><?php echo remove_junk($product['categorie']); ?></td>
                    <td class="text-center"><?php echo remove_junk($product['vendor']); ?></td>
                    <td class="text-center"><?php echo remove_junk($product['upc']); ?></td>
                    <td class="text-center"><?php echo remove_junk($product['value_size']); ?></td>
                    <td class="text-center"><?php echo remove_junk($product['quantity']); ?></td>
                    <td class="text-center"><?php echo remove_junk($product['units_in_case']); ?></td>
                    <td class="text-center"><?php echo number_format($product['unit_cost'], 2); ?></td>
                    <td class="text-center"><?php echo number_format($product['case_cost'], 2); ?></td>
                    <td class="text-center"><?php echo number_format($product['unit_retail'], 2); ?></td>
                    <td class="text-center"><?php echo number_format($product['case_retail'], 2); ?></td>
                    <td class="text-center"><?php echo number_format($product['gpm'], 2); ?></td>
                    <td class="text-center"><?php echo read_date($product['return_date']); ?></td>

                    <!-- Actions -->
                    <td class="text-center">
                      <div class="btn-group">
                        <a href="edit_returned_product.php?id=<?php echo (int)$product['id']; ?>" class="btn btn-info btn-xs" title="Edit" data-toggle="tooltip">
                          <span class="glyphicon glyphicon-edit"></span>
                        </a>
                        <a href="delete_returned_product.php?id=<?php echo (int)$product['id']; ?>" class="btn btn-danger btn-xs" title="Delete" data-toggle="tooltip">
                          <span class="glyphicon glyphicon-trash"></span>
                        </a>
                      </div>
                    </td>

                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr><td colspan="16" class="text-center">No returned products found.</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>

    </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>
