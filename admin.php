<?php
// Admin Dashboard Page
$page_title = 'Admin Home Page';
require_once 'includes/load.php';

// Check if the user has permission to access this page
page_require_level(1);

// Fetch statistics
$c_categorie = count_by_id('categories');
$c_product = count_by_id('products');
$c_sale = count_by_id('sales');
$c_user = count_by_id('users');

$products_sold = find_higest_saleing_product(10);
$recent_products = find_recent_product_added(5);
$recent_sales = find_recent_sale_added(5);

include_once 'layouts/header.php';
?>

<div class="row">
    <div class="col-md-6">
        <?php echo display_msg($msg); ?>
    </div>
</div>

<div class="row">
    <?php 
    $stats = [
        ["link" => "users.php", "icon" => "glyphicon-user", "bg" => "bg-secondary1", "count" => $c_user['total'], "label" => "Users"],
        ["link" => "categorie.php", "icon" => "glyphicon-th-large", "bg" => "bg-red", "count" => $c_categorie['total'], "label" => "Categories"],
        ["link" => "product.php", "icon" => "glyphicon-shopping-cart", "bg" => "bg-blue2", "count" => $c_product['total'], "label" => "Products"],
        ["link" => "sales.php", "icon" => "glyphicon-usd", "bg" => "bg-green", "count" => $c_sale['total'], "label" => "Sales"]
    ];

    foreach ($stats as $stat): ?>
        <a href="<?php echo $stat['link']; ?>" style="color:black;">
            <div class="col-md-3">
                <div class="panel panel-box clearfix">
                    <div class="panel-icon pull-left <?php echo $stat['bg']; ?>">
                        <i class="glyphicon <?php echo $stat['icon']; ?>"></i>
                    </div>
                    <div class="panel-value pull-right">
                        <h2 class="margin-top"> <?php echo $stat['count']; ?> </h2>
                        <p class="text-muted"> <?php echo $stat['label']; ?> </p>
                    </div>
                </div>
            </div>
        </a>
    <?php endforeach; ?>
</div>

<div class="row">
    <!-- Highest Selling Products -->
    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                <strong><span class="glyphicon glyphicon-th"></span> Highest Selling Products</strong>
            </div>
            <div class="panel-body">
                <table class="table table-striped table-bordered table-condensed">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Total Sold</th>
                            <th>Total Quantity</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products_sold as $product): ?>
                            <tr>
                                <td><?php echo remove_junk(first_character($product['name'])); ?></td>
                                <td><?php echo (int)$product['totalSold']; ?></td>
                                <td><?php echo (int)$product['totalQty']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Recent Sales -->
    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                <strong><span class="glyphicon glyphicon-th"></span> Latest Sales</strong>
            </div>
            <div class="panel-body">
                <table class="table table-striped table-bordered table-condensed">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Product Name</th>
                            <th>Date</th>
                            <th>Total Sale</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recent_sales as $sale): ?>
                            <tr>
                                <td class="text-center"> <?php echo count_id(); ?> </td>
                                <td>
                                    <a href="edit_sale.php?id=<?php echo (int)$sale['id']; ?>">
                                        <?php echo remove_junk(first_character($sale['name'])); ?>
                                    </a>
                                </td>
                                <td><?php echo remove_junk(ucfirst($sale['date'])); ?></td>
                                <td>$ <?php echo remove_junk(first_character($sale['price'])); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Recently Added Products -->
    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                <strong><span class="glyphicon glyphicon-th"></span> Recently Added Products</strong>
            </div>
            <div class="panel-body">
                <div class="list-group">
                    <?php foreach ($recent_products as $product): ?>
                        <a class="list-group-item clearfix" href="edit_product.php?id=<?php echo (int)$product['id']; ?>">
                            <h4 class="list-group-item-heading">
                                <img class="img-avatar img-circle" src="uploads/products/<?php echo $product['media_id'] === '0' ? 'no_image.png' : $product['image']; ?>" alt="">
                                <?php echo remove_junk(first_character($product['name'])); ?>
                                <span class="label label-warning pull-right">
                                    $ <?php echo (int)$product['sale_price']; ?>
                                </span>
                            </h4>
                            <span class="list-group-item-text pull-right">
                                <?php echo remove_junk(first_character($product['categorie'])); ?>
                            </span>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once 'layouts/footer.php'; ?>
