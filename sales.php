<?php
$page_title = 'Sales List';
require_once('includes/load.php');
page_require_level(3);

// Fetch all sales
$sales = $db->query("SELECT s.*, p.name as product_name_from_inventory
                     FROM sales s
                     LEFT JOIN products p ON s.product_id = p.id
                     ORDER BY s.created_at DESC");
$sales = $sales->fetch_all(MYSQLI_ASSOC);
?>

<?php include_once('layouts/header.php'); ?>

<div class="row">
  <div class="col-md-12">
    <?php echo display_msg($msg); ?>
    <div class="panel panel-default">
      <div class="panel-heading clearfix">
        <strong>
          <span class="glyphicon glyphicon-list"></span>
          <span>Sales Records</span>
        </strong>
      </div>
      <div class="panel-body">
        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>#</th>
              <th>Product Name</th>
              <th>UPC</th>
              <th>Value / Size</th>
              <th>Qty Sold</th>
              <th>Unit Cost</th>
              <th>Unit Retail</th>
              <th>Total</th>
              <th>GPM (%)</th>
              <th>Sale Date</th>
              <th>Created At</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php if(!empty($sales)): ?>
              <?php foreach($sales as $sale): ?>
                <tr>
                  <td><?php echo $sale['id']; ?></td>
                  <td><?php echo $sale['product_name'] ?: $sale['product_name_from_inventory']; ?></td>
                  <td><?php echo $sale['upc']; ?></td>
                  <td><?php echo $sale['value_size']; ?></td>
                  <td><?php echo $sale['qty']; ?></td>
                  <td><?php echo number_format($sale['unit_cost'], 2); ?></td>
                  <td><?php echo number_format($sale['unit_retail'], 2); ?></td>
                  <td><?php echo number_format($sale['total'], 2); ?></td>
                  <td><?php echo number_format($sale['gpm'], 2); ?></td>
                  <td><?php echo $sale['date']; ?></td>
                  <td><?php echo $sale['created_at']; ?></td>
                  <td>
                    <a href="delete_sale.php?id=<?php echo $sale['id']; ?>" 
                       class="btn btn-danger btn-sm" 
                       onclick="return confirm('Are you sure you want to delete this sale?');">
                       Delete
                    </a>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="12" class="text-center">No sales found.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>
