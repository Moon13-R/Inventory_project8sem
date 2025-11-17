<?php
$page_title = 'Vendor Sales Report';
require_once('includes/load.php');
// Checkin What level user has permission to view this page
page_require_level(3);
$all_vendors = find_all('vendors');

$results = [];
$vendor_id = '';
$start_date = '';
$end_date = '';
if (isset($_POST['submit'])) {
    $req_dates = ['start-date', 'end-date'];
    validate_fields($req_dates);

    if (empty($errors)) {
        $vendor_id = remove_junk($db->escape($_POST['vendor_id']));
        $start_date = remove_junk($db->escape($_POST['start-date']));
        $end_date = remove_junk($db->escape($_POST['end-date']));
        $results = find_sale_by_vendor_dates($vendor_id, $start_date, $end_date);
    } else {
        $session->msg("d", $errors);
    }
}
?>
<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-6">
    <?php echo display_msg($msg); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-12">
    <div class="panel">
      <div class="panel-heading">
        <strong>Vendor Sales Report</strong>
      </div>
      <div class="panel-body">
          <form class="clearfix" method="post" action="vendor_sales.php" id="reportForm">
            <input type="hidden" name="download" id="downloadField" value="">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label class="form-label">Vendor</label>
                  <select class="form-control" name="vendor_id" required>
                    <option value="">Select Vendor</option>
                    <?php foreach ($all_vendors as $vendor): ?>
                      <option value="<?php echo (int)$vendor['id']; ?>" <?php if ($vendor_id == (int)$vendor['id']) echo 'selected'; ?>><?php echo remove_junk($vendor['name']); ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="form-label">Date Range</label>
                    <div class="input-group">
                      <input type="text" class="datepicker form-control" name="start-date" placeholder="From" value="<?php echo htmlspecialchars($start_date); ?>" required>
                      <span class="input-group-addon"><i class="glyphicon glyphicon-menu-right"></i></span>
                      <input type="text" class="datepicker form-control" name="end-date" placeholder="To" value="<?php echo htmlspecialchars($end_date); ?>" required>
                    </div>
                </div>
              </div>
            </div>
            <div class="form-group">
                 <button type="submit" name="submit" class="btn btn-primary">Generate Report</button>
                 <button type="button" class="btn btn-success" onclick="download('excel')">Download Excel</button>
                 <button type="button" class="btn btn-info" onclick="download('pdf')">Download PDF</button>
            </div>
          </form>

          <?php if (!empty($results)): ?>
          <div class="page-break" style="margin-top: 30px;">
              <div class="sale-head">
                  <h1>Inventory Management System - Vendor Sales Report</h1>
                  <strong>Vendor: <?php echo find_by_id('vendors', $vendor_id)['name']; ?> | <?php echo htmlspecialchars($start_date); ?> TILL DATE <?php echo htmlspecialchars($end_date); ?></strong>
              </div>
             <table class="table table-border">
               <thead>
                 <tr>
                     <th>Date</th>
                     <th>Product Title</th>
                     <th>Buying Price</th>
                     <th>Selling Price</th>
                     <th>Total Qty</th>
                     <th>TOTAL</th>
                 </tr>
               </thead>
               <tbody>
                 <?php foreach($results as $result): ?>
                  <tr>
                     <td><?php echo remove_junk($result['date']);?></td>
                     <td class="desc">
                       <h6><?php echo remove_junk(ucfirst($result['name']));?></h6>
                     </td>
                     <td class="text-right"><?php echo remove_junk($result['buy_price']);?></td>
                     <td class="text-right"><?php echo remove_junk($result['sale_price']);?></td>
                     <td class="text-right"><?php echo remove_junk($result['total_sales']);?></td>
                     <td class="text-right"><?php echo remove_junk($result['total_saleing_price']);?></td>
                 </tr>
               <?php endforeach; ?>
               </tbody>
               <tfoot>
                <tr class="text-right">
                  <td colspan="4"></td>
                  <td colspan="1">Grand Total</td>
                  <td> $
                  <?php echo number_format(total_price($results)[0], 2);?>
                 </td>
                </tr>
                <tr class="text-right">
                  <td colspan="4"></td>
                  <td colspan="1">Profit</td>
                  <td> $<?php echo number_format(total_price($results)[1], 2);?></td>
                </tr>
               </tfoot>
             </table>
           </div>
          <?php elseif (isset($_POST['submit'])): ?>
            <div class="alert alert-warning" style="margin-top: 20px;">
              Sorry, no sales have been found for this vendor and dates.
            </div>
          <?php endif; ?>
      </div>
    </div>
  </div>
</div>
<script>
function download(type) {
    var form = document.getElementById('reportForm');
    var vendor = form.vendor_id.value;
    var start = form['start-date'].value;
    var end = form['end-date'].value;
    if(!vendor || !start || !end) {
        alert('Please fill all fields');
        return;
    }
    // Redirect to vendor_sale_process.php with download parameters
    var url = 'vendor_sale_process.php?download=' + type + '&vendor_id=' + vendor + '&start_date=' + start + '&end_date=' + end;
    window.open(url, '_blank');
}
</script>
<?php include_once('layouts/footer.php'); ?>
