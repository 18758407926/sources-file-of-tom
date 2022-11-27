<!DOCTYPE html>
<html lang="">

<head>
  <title>Gomag</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <link href="layout/styles/layout.css" rel="stylesheet" type="text/css" media="all">
</head>

<body id="top">

  <!-- Top Background Image Wrapper -->

    <div class="wrapper row1">
      <header id="header" class="hoc clear">

        <div id="logo" class="fl_left">
          <img src="images/demo/07.jpg" alt="">
          <h1><a href="index.html">Lucas</a></h1>
        </div>
        <nav id="mainav" class="fl_right">
          <ul class="clear">
            <li class="active"><a href="index.html">Home</a></li>
            <li><a class="drop" href="aboutus.html">About us</a></li>
            <li><a class="drop" href="careers.html">Careers</a></li>
            <li><a href="orderonline.php">Order online</a></li>
            <li><a href="contactus.html">Contact Us</a></li>
            <li><a href="create.php">Register</a></li>
          </ul>
        </nav>

      </header>
    </div>
<?php
session_start();
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location:login.php");
    exit;
}
require_once("dbcontroller.php");
$db_handle = new DBController();
if(!empty($_GET["action"])) {
switch($_GET["action"]) {
	case "add":
		if(!empty($_POST["quantity"])) {
			$productByCode = $db_handle->runQuery("SELECT * FROM tblproduct WHERE code='" . $_GET["code"] . "'");
			$itemArray = array($productByCode[0]["code"]=>array('name'=>$productByCode[0]["name"], 'code'=>$productByCode[0]["code"], 'quantity'=>$_POST["quantity"], 'price'=>$productByCode[0]["price"], 'image'=>$productByCode[0]["image"]));
			
			if(!empty($_SESSION["cart_item"])) {
				if(in_array($productByCode[0]["code"],array_keys($_SESSION["cart_item"]))) {
					foreach($_SESSION["cart_item"] as $k => $v) {
							if($productByCode[0]["code"] == $k) {
								if(empty($_SESSION["cart_item"][$k]["quantity"])) {
									$_SESSION["cart_item"][$k]["quantity"] = 0;
								}
								$_SESSION["cart_item"][$k]["quantity"] += $_POST["quantity"];
							}
					}
				} else {
					$_SESSION["cart_item"] = array_merge($_SESSION["cart_item"],$itemArray);
				}
			} else {
				$_SESSION["cart_item"] = $itemArray;
			}
		}
	break;
	case "remove":
		if(!empty($_SESSION["cart_item"])) {
			foreach($_SESSION["cart_item"] as $k => $v) {
					if($_GET["code"] == $k)
						unset($_SESSION["cart_item"][$k]);				
					if(empty($_SESSION["cart_item"]))
						unset($_SESSION["cart_item"]);
			}
		}
	break;
	case "empty":
		unset($_SESSION["cart_item"]);
	break;	
}
}
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<link href="default.css" rel="stylesheet" type="text/css" media="all" />
<link href="style.css" rel="stylesheet" type="text/css" media="all" />
<link href="default.css" rel="stylesheet" type="text/css" media="all" />
<link href="style.css" rel="stylesheet" type="text/css" media="all" />

</head>
<body>
<div id="shopping-cart">
<div><a href="http://localhost/7/logout.php" class="button">logout</a>
<div class="txt-heading">Shopping Cart</div>



<a id="btnEmpty" href="orderonline.php?action=empty">Empty Cart</a>
<?php
if(isset($_SESSION["cart_item"])){
    $total_quantity = 0;
    $total_price = 0;
?>	


<table class="tbl-cart" cellpadding="10" cellspacing="1">
<tbody>
<tr>
<th style="text-align:left;">Name</th>
<th style="text-align:left;">Code</th>
<th style="text-align:right;" width="5%">Quantity</th>
<th style="text-align:right;" width="10%">Unit Price</th>
<th style="text-align:right;" width="10%">Price</th>
<th style="text-align:center;" width="5%">Remove</th>
</tr>	



<?php		
    foreach ($_SESSION["cart_item"] as $item){
        $item_price = $item["quantity"]*$item["price"];
		?>
				<tr>
				<td><img src="<?php echo $item["image"]; ?>" class="cart-item-image" /><?php echo $item["name"]; ?></td>
				<td><?php echo $item["code"]; ?></td>
				<td style="text-align:right;"><?php echo $item["quantity"]; ?></td>
				<td  style="text-align:right;"><?php echo "$ ".$item["price"]; ?></td>
				<td  style="text-align:right;"><?php echo "$ ". number_format($item_price,2); ?></td>
				<td style="text-align:center;"><a href="orderonline.php?action=remove&code=<?php echo $item["code"]; ?>" class="btnRemoveAction"><img src="icon-delete.png" alt="Remove Item" /></a></td>
				</tr>
				<?php
				$total_quantity += $item["quantity"];
				$total_price += ($item["price"]*$item["quantity"]);
		}
		?>

<tr>
<td colspan="2" align="right">Total:</td>
<td align="right"><?php echo $total_quantity; ?></td>
<td align="right" colspan="2"><strong><?php echo "$ ".number_format($total_price, 2); ?></strong></td>
<td></td>
</tr>
</tbody>
</table>		
  <?php
} else {
?>
<div class="no-records">Your Cart is Empty</div>
<?php 
}
?>



</div>

<div id="product-grid">
	<div class="txt-heading">Products</div>
	<?php
	$product_array = $db_handle->runQuery("SELECT * FROM tblproduct ORDER BY id ASC");
	if (!empty($product_array)) { 
		foreach($product_array as $key=>$value){
	?>
		<div class="product-item">
			<form method="post" action="orderonline.php?action=add&code=<?php echo $product_array[$key]["code"]; ?>">
			<div class="product-image"><img src="<?php echo $product_array[$key]["image"]; ?>"></div>
			<div class="product-tile-footer">
			<div class="product-title"><?php echo $product_array[$key]["name"]; ?></div>
			<div class="product-price"><?php echo "$".$product_array[$key]["price"]; ?>
			<div class="cart-action"><input type="text" class="product-quantity" name="quantity" value="1" size="2" /><input type="submit" value="Add to Cart" class="btnAddAction" /></div>
			</div>
			<div> id=<?php echo $product_array[$key]["id"] ; ?> </div>
<br>
      <a href='get-product-info.php?id=<?php echo $product_array[$key]["id"] ; ?>' target="_black"> </a>
      <div> <a href="http://localhost"
           target="popup"   
           onclick="window.open('get-product-info.php?id=<?php echo $product_array[$key]["id"] ; ?>','popup','width=300,height=300');return false;">
           click, Open Link in Popup</a></div>
      </div>
			</form>
		</div>
	<?php
		}
	}
	?>
</div>

<!DOCTYPE html>
<html lang="">

<head>
  <title>Gomag</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <link href="layout/styles/layout.css" rel="stylesheet" type="text/css" media="all">
</head>

<body id="top">



      <a id="backtotop" href="#top"><i class="fa fa-chevron-up"></i></a>
	  <div class="copyright_section">
        <div class="container">
          <p class="copyright_text">20IT2 Design by <a href="https://html.design">Jiang Yiyi(Tom)</a></p>
        </div>
      </div>
      <!-- JAVASCRIPTS -->
      <script src="layout/scripts/jquery.min.js"></script>
      <script src="layout/scripts/jquery.backtotop.js"></script>
      <script src="layout/scripts/jquery.mobilemenu.js"></script>
</body>

</html>


</body>
</html>