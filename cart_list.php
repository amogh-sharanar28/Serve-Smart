<?php 
// Turn off warnings and notices (for production-friendly output)
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
ini_set('display_errors', 0);

// Start session and DB connection
session_start();
require_once 'admin/db_connect.php'; 
?>

<!-- Masthead -->
<header class="masthead">
  <div class="container h-100">
    <div class="row h-100 align-items-center justify-content-center text-center">
      <div class="col-lg-10 align-self-center mb-4 page-title">
        <h1 class="text-white">Shopping Cart</h1>
        <hr class="divider my-4 bg-dark" />
      </div>
    </div>
  </div>
</header>

<section class="page-section" id="menu">
  <div class="container">
    <div class="row">

      <!-- Cart Items -->
      <div class="col-lg-8">
        <div class="sticky">
          <div class="card">
            <div class="card-body">
              <div class="row">
                <div class="col-md-8"><b>Items</b></div>
                <div class="col-md-4 text-right"><b>Total</b></div>
              </div>
            </div>
          </div>
        </div>

        <?php
        $user_id = $_SESSION['login_user_id'] ?? 0;
        $ip = $_SERVER['REMOTE_ADDR'];
        $data = "WHERE c.user_id = '$user_id' OR c.client_ip = '$ip'";
        $total = 0;
        
        $get = $conn->query("SELECT *, c.id as cid FROM cart c 
                             INNER JOIN product_list p ON p.id = c.product_id 
                             $data");
        
        while ($row = $get->fetch_assoc()):
            $total += ($row['qty'] * $row['price']);
            // your display logic here...
        endwhile;
        ?>

        <div class="card mt-2">
          <div class="card-body">
            <div class="row">
              <!-- Product Image -->
              <div class="col-md-4 text-center">
                <img src="assets/img/<?php echo $row['img_path'] ?>" alt="" class="img-fluid" style="max-height:120px;">
              </div>

              <!-- Product Info -->
              <div class="col-md-5">
                <p><b><?php echo $row['name'] ?></b></p>
                <p><small>Desc: <?php echo $row['description'] ?></small></p>
                <p><small>Unit Price: ₹<?php echo number_format($row['price'], 2) ?></small></p>
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <button class="btn btn-outline-secondary qty-minus" type="button" data-id="<?php echo $row['cid'] ?>"><i class="fa fa-minus"></i></button>
                  </div>
                  <input type="number" readonly value="<?php echo $row['qty'] ?>" class="form-control text-center">
                  <div class="input-group-append">
                    <button class="btn btn-outline-secondary qty-plus" type="button" data-id="<?php echo $row['cid'] ?>"><i class="fa fa-plus"></i></button>
                  </div>
                </div>
              </div>

              <!-- Total + Remove -->
              <div class="col-md-3 text-right">
                <b>₹<?php echo number_format($row['qty'] * $row['price'], 2) ?></b><br>
                <a href="admin/ajax.php?action=delete_cart&id=<?php echo $row['cid'] ?>" class="btn btn-sm btn-outline-danger mt-2"><i class="fa fa-trash"></i></a>
              </div>
            </div>
          </div>
        </div>
        <?php endwhile; ?>
      </div>

      <!-- Total Section -->
      <div class="col-md-4">
        <div class="sticky">
          <div class="card">
            <div class="card-body">
              <p><b>Total Amount</b></p>
              <hr>
              <p class="text-right"><b>₹<?php echo number_format($total, 2) ?></b></p>
              <div class="text-center">
                <button class="btn btn-block btn-outline-dark" id="checkout">Proceed to Checkout</button>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>

<style>
  .card p {
    margin: 0;
  }
  .card img {
    max-width: 100%;
    max-height: 120px;
  }
  .sticky {
    position: sticky;
    top: 4.7em;
    background: white;
    z-index: 10;
  }
</style>

<script>
  $('.qty-minus').click(function () {
    var qty = $(this).closest('.input-group').find('input').val();
    if (qty == 1) return false;
    update_qty(parseInt(qty) - 1, $(this).data('id'));
  });

  $('.qty-plus').click(function () {
    var qty = $(this).closest('.input-group').find('input').val();
    update_qty(parseInt(qty) + 1, $(this).data('id'));
  });

  function update_qty(qty, id) {
    $.post('admin/ajax.php?action=update_cart_qty', { id: id, qty: qty }, function (resp) {
      if (resp == 1) location.reload();
    });
  }

  $('#checkout').click(function () {
    <?php if (isset($_SESSION['login_user_id'])): ?>
      location.href = 'checkout.php';
    <?php else: ?>
      uni_modal('Login First', 'login.php?redirect=checkout.php');
    <?php endif; ?>
  });
</script>
