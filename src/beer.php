<?php
session_start();
require_once "model/db_functions.php";
require "model/cartItems.php";

define('BEER', 1);
define('ALE', 3);
define('LAGER', 4);
define('STOUT', 5);
define('IPA', 6);

if (isset($_POST['addItem']) && (isset($_GET['itemType']) || isset($_GET['prodType']))) {
	if ($_GET['itemType'] == ALE) {
		header("Location: beer.php?itemType=" . ALE);
	} elseif ($_GET['itemType'] == LAGER) {
		header('Location: beer.php?itemType=' . LAGER);
	} elseif ($_GET['itemType'] == STOUT) {
		header('Location: beer.php?itemType=' . STOUT);
	} elseif ($_GET['itemType'] == IPA) {
		header('Location: beer.php?itemType=' . IPA);
	} elseif ($_GET['prodType'] == BEER) {
	    header('Location: beer.php?prodType=' . BEER);
	}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

	<title>the beer shoppe - Camosun ICS Year One Project</title>


	<!-- General CSS scripts
	<link rel="stylesheet" type="text/css" href="css/basecss.css">  -->

	<!-- Bootstrap Core CSS -->
    <!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

    <!-- Individual product display CSS  -->
    <link href="../css/individual_product.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="../css/shop-homepage.css" rel="stylesheet">

    <!-- For shopping_cart.js -->
    <script data-require="jquery" data-semver="2.1.4" src="http://code.jquery.com/jquery-2.1.4.min.js"></script>
    <script src="../shopping_cart.js"></script>


    <!-- Link for font -->
    <link href="https://fonts.googleapis.com/css?family=Share" rel="stylesheet">

	<style>

	</style>


</head>

<!-- The stript for the header -->
<?php require 'view/header.php'; ?>



<div class="container-fluid text-center">
  <div class="row">
    <div class="col-sm-3">
          <!-- could put a search form here is wanted -->
              <form action="" method="get">
              <button class="btn btn-info btn-lg" name = "itemType" type="submit" value="<?php echo ALE ?>" style="width: 175px;"> Ale </button>
              </form><br>
              <form action="" method="get">
              <button class="btn btn-info btn-lg" name = "itemType" type="submit" value="<?php echo LAGER ?>"" style="width: 175px;"> Lager </button>
              </form><br>
              <form action="" method="get">
              <button class="btn btn-info btn-lg" name = "itemType" type="submit" value="<?php echo STOUT ?>"" style="width: 175px;"> Stout </button>
              </form><br>
              <form action="" method="get">
              <button class="btn btn-info btn-lg" name = "itemType" type="submit" value="<?php echo IPA ?>"" style="width: 175px;"> IPA </button>
              </form>


    </div>

    <div class="col-sm-9">
      <?php
        if (isset($_GET['itemType'])){
      		$items = getProdInfo($_GET['itemType']);
      	} elseif (isset($_GET['prodType'])) {
      		$items = getProdInfoWithType ($_GET['prodType']);
      	}
      	    $count = 0;
      		foreach ($items as $item) {
      			if ($count % 3 == 0) echo '<div class="row">';
  		?>
  				<div class="col-sm-4">

  					<div class="product-boarder">
  						<form action = "" method = "post">
      						<fieldset>

                                <div class="prod-name"><?php echo $item['company_name'] . "<br>" . $item['prod_name']; ?> </div>
                  				<br>
                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#<?php echo $item['prod_id']; ?>">
                                        <img class="img-thumbnail" style = "width:200px; height:200px;" src="<?php echo $item[prod_picture]; ?>">
                  				    </a>
                  				<br><br>
                  				    <div class="prod-price"> <?php if ($item['prod_package'] != null) { echo $item['prod_package'] . " --- ";} echo "$" . $item['prod_price']; ?> </div>
                                <br>

                  				<div id="<?php echo $item['prod_id']; ?>" class="collapse">
                  					<?php echo $item['prod_description'] . "<br><br>" ?>
                  					Quantity:
                  					<input type="number" name="quantity" min="1" max="10">
                  					<input type="hidden" name="prod_id" value="<?php echo $item['prod_id']; ?>">
                  					<input type="hidden" name="price" value="<?php echo $item['prod_price']; ?>">
                  					<button class="btn btn-success" name = "addItem" type="submit"> Add </button>
              					</div>
              				</fieldset>
          				</form>
          			</div>
  				</div>

  		<?php
  			$count++;
  			if ($count % 3 == 0) echo '</div>';
  			}
  		?>
      <?php
      //}
      ?>
  	  </div>
	</div>
</div>

          <?php
              $getUserID = 0;

                  if (isset($_POST['seshReset'])){
                      session_unset();
                      exit();
                  }
                  if (isset($_POST['addItem'])) {

              	    $quantity = filter_input(INPUT_POST, 'quantity', FILTER_VALIDATE_INT);
  	            	$product = filter_input(INPUT_POST, 'prod_id', FILTER_VALIDATE_INT);
  	            	$price = filter_input(INPUT_POST, 'price', FILTER_VALIDATE_FLOAT);

              	    cartItems($quantity, $product, $price);
  	        	}




              /*

                      	//print_r($_SESSION['user']);

                          	if (isset($_POST['prod_id'])) {
                              	$quantity = filter_input(INPUT_POST, 'quantity', FILTER_SANITIZE_SPECIAL_CHARS);
              	            	$product = filter_input(INPUT_POST, 'prod_id', FILTER_SANITIZE_SPECIAL_CHARS);


                              	if (!isset($_SESSION['cart'])) {
                                  	$_SESSION['cart'] = array ();
                              	}
              					//If an order has already been created, add the product to the order
                                  //if (isset($_SESSION['order_created'])){
                                    // Get the order id
                                    //$ordID = getOrderID($_SESSION['user']['user_id']);
                                    // Add item call here
                                  //}

                                  //If an order has not been created yet, create one and add the item to the order
                                  if (!isset($_SESSION['order_created'])){
                                    $_SESSION['order_created'] = true;

                                    if(isset($_SESSION['user']['user_id'])){
                                      $getUserID = $_SESSION['user']['user_id'];
                                      createOrder($getUserID);
                                      //Get order ID
                                      //$ordID = getOrderID($_SESSION['user']['user_id']);
                                      //Add item call here
                                    }
                                  }
               	            	if (array_key_exists($product, $_SESSION['cart']))
               	            	{
               		            	$quantity += $_SESSION['cart'][$product];
               		            	$_SESSION['cart'][$product] = $quantity;
               	            	}
               	            	else
               	            	{
               		            	$_SESSION['cart'][$product] = $quantity;
               	            	}


               	            	foreach($_SESSION['cart'] as $key => $value) {
              	                	echo "The product id is $key <br>";
              	                	echo "The quantity is $value <br><hr>";
              	            	}
              	        	}
              */
                      ?>

      </div> <!-- closes single body row -->
    </div> <!-- closes body container-fluid -->


  </div>
</div>







<?php require 'view/footer.php'; ?>

<!-- Script for hover over products for accordian to drop -->
<script>

$(function() {
    $(document).on('mouseenter.collapse', '[data-toggle=collapse]', function(e) {
        var $this = $(this),
            href,
            target = $this.attr('data-target')
                     || e.preventDefault()
                     || (href = $this.attr('href'))
                     && href.replace(/.*(?=#[^\s]+$)/, ''), //strip for ie7
            option = $(target).hasClass('in') ? 'hide' : "show"

            $('.panel-collapse').not(target).collapse("hide")
            $(target).collapse(option);
    })
});
</script>

<!-- jQuery -->
<script src="js/jquery.js"></script>

<!-- Bootstrap Core JavaScript -->
<script src="js/bootstrap.min.js"></script>

<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

<!-- Latest compiled JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>


</body>


</html>
