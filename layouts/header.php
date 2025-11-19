<?php $user = current_user(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>
    <?php 
      if (!empty($page_title)) echo remove_junk($page_title);
      elseif(!empty($user)) echo ucfirst($user['name']);
      else echo "Inventory Management System";
    ?>
  </title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css"/>
  <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker3.min.css" />
  <link rel="stylesheet" href="libs/css/main.css" />
  <style>
    /* Optional: Add some shadow and scrollbar style */
    #notificationWindow::-webkit-scrollbar {
      width: 6px;
    }
    #notificationWindow::-webkit-scrollbar-thumb {
      background: #ccc;
      border-radius: 3px;
    }
  </style>
</head>
<body>
<?php if ($session->isUserLoggedIn(true)): ?>
<header id="header">
  <div class="logo pull-left"> Inventory System</div>
  <div class="header-content">
    <div class="header-date pull-left">
      <strong><?php echo date("F j, Y, g:i a");?></strong>
    </div>
    <div class="pull-right clearfix">
      <ul class="info-menu list-inline list-unstyled">

        <!-- 🔔 Low-stock Notifications -->
        <?php
        // Get count of products with quantity less than 1
        $low_stock_result = $db->query("SELECT COUNT(id) AS total FROM products WHERE quantity < 1");
        $low_stock_count = $db->fetch_assoc($low_stock_result);

        // Fetch those products (latest 10 for display)
        $low_stock_products = find_by_sql("SELECT name, quantity FROM products WHERE quantity < 1 ORDER BY quantity ASC LIMIT 10");
        ?>

        <?php if($low_stock_count['total'] > 0): ?>
        <li class="dropdown">
          <a href="javascript:void(0);" id="notificationToggle" style="position: relative;">
            <i class="glyphicon glyphicon-bell" style="font-size:18px; color:red;"></i>
          </a>
        </li>
        <?php endif; ?>

        <!-- User Profile -->
        <li class="profile">
          <a href="#" data-toggle="dropdown" class="toggle" aria-expanded="false">
            <img src="uploads/users/<?php echo $user['image'];?>" alt="user-image" class="img-circle img-inline">
            <span><?php echo remove_junk(ucfirst($user['name'])); ?> <i class="caret"></i></span>
          </a>
          <ul class="dropdown-menu">
            <li>
              <a href="profile.php?id=<?php echo (int)$user['id'];?>">
                <i class="glyphicon glyphicon-user"></i> Profile
              </a>
            </li>
            <li>
              <a href="edit_account.php" title="edit account">
                <i class="glyphicon glyphicon-cog"></i> Settings
              </a>
            </li>
            <li class="last">
              <a href="logout.php">
                <i class="glyphicon glyphicon-off"></i> Logout
              </a>
            </li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</header>

<!-- 🔔 Notification Window -->
<?php if($low_stock_count['total'] > 0): ?>
<div id="notificationWindow" style="display:none; position:fixed; top:60px; left:50%; transform:translateX(-50%); z-index:9999; width:350px; background:#fff; border:1px solid #ccc; border-radius:5px; box-shadow:0 2px 8px rgba(0,0,0,0.2);">
  <div style="padding:10px; font-weight:bold; border-bottom:1px solid #eee;">Out of Stock Alerts</div>
  <div style="max-height:300px; overflow-y:auto;">
    <?php foreach($low_stock_products as $product): ?>
      <div style="padding:8px; border-bottom:1px solid #eee;">
        <?php echo remove_junk($product['name']); ?> - Qty: <?php echo (int)$product['quantity']; ?>
      </div>
    <?php endforeach; ?>
  </div>
</div>
<?php endif; ?>

<script>
  // Toggle notification window
  const toggleBtn = document.getElementById('notificationToggle');
  if (toggleBtn) {
    toggleBtn.addEventListener('click', function() {
      const win = document.getElementById('notificationWindow');
      if (win) win.style.display = (win.style.display === 'none' || win.style.display === '') ? 'block' : 'none';
    });
  }

  // Hide notification when clicking outside
  document.addEventListener('click', function(e){
    const win = document.getElementById('notificationWindow');
    const toggle = document.getElementById('notificationToggle');
    if(win && toggle && !win.contains(e.target) && !toggle.contains(e.target)){
      win.style.display = 'none';
    }
  });
</script>

<div class="sidebar">
  <?php if($user['user_level'] === '1'): ?>
    <?php include_once('admin_menu.php');?>
  <?php elseif($user['user_level'] === '2'): ?>
    <?php include_once('special_menu.php');?>
  <?php elseif($user['user_level'] === '3'): ?>
    <?php include_once('user_menu.php');?>
  <?php endif;?>
</div>

<div class="page">
  <div class="container-fluid">
<?php endif; ?>
