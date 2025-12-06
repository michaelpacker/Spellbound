<?php



if (isset($_POST['product-create'])) {

	

	// Set up token in database

	require 'dbh.inc.php';

	

	$product_number 	= $_POST["product_number"];

	$product_name 		= $_POST["product_name"];

	$product_desc 		= $_POST["product_desc"];

	$product_desc_sp 	= $_POST["product_desc_sp"];

	

	$product_status		= $_POST["product_status"];

	

	$wholesale_price 	= $_POST["wholesale_price"];

	$retail_price 		= $_POST["retail_price"];

	

	$image_path 		= $_POST["image_path"];

	$inventory_count 	= $_POST["inventory_count"];

	

	$product_type 		= $_POST["product_type"];

	$product_material 	= $_POST["product_material"];

	$product_supplier 	= $_POST["product_supplier"];

	$supplier_url 		= $_POST["supplier_url"];

	

	

	

	// See if product already exists

	$sql = "SELECT prod_num FROM products WHERE prod_num=?";

	$stmt = mysqli_stmt_init($conn);

	

	if (!mysqli_stmt_prepare($stmt, $sql)) { 

		// sql error

		header("Location: ../adminProductCreate?createStatus=error&message=sql");

	} else {

		mysqli_stmt_bind_param($stmt, "s", $product_number);

		mysqli_stmt_execute($stmt);

		

		mysqli_stmt_store_result($stmt);

		$resultCheck = mysqli_stmt_num_rows($stmt);

		

		if ($resultCheck > 0) {

			// That product exists

			header("Location: ../adminProductCreate?createStatus=error&message=exists");

			exit();

		}

	}

	

	if (!mysqli_stmt_prepare($stmt, $sql)) {

		// sql error

		header("Location: ../adminProductCreate?createStatus=error&message=sql");

	} else {

	

		mysqli_stmt_bind_param($stmt, "s", $product_name);

		mysqli_stmt_execute($stmt);

		

		mysqli_stmt_store_result($stmt);

		$result_check = mysqli_stmt_num_rows($stmt);

		

		if ($result_check > 0) {

			// Product with that name exists

			header("Location: ../adminProductCreate.php?createStatus=error&message=name");

		} else {

			// Insert the product into the database

			$sql = "INSERT INTO products 

				(prod_num,name,description,desc_sp,price_wholesale,price_retail,file_name,count,type,material,supplier,status,url) 

				VALUES 

				(?,?,?,?,?,?,?,?,?,?,?,?,?)";

			

			$stmt = mysqli_stmt_init($conn);

			if (!mysqli_stmt_prepare($stmt, $sql)) {

				header("Location: ../adminProductCreate.php?error=sqlError");

				exit();

			} else {

				mysqli_stmt_bind_param($stmt, "ssssddsiisisi", $product_number,$product_name,$product_desc,$product_desc_sp,$wholesale_price,$retail_price,$image_path,$inventory_count,$product_type,$product_material,$product_supplier,$supplier_url,$product_status);

				mysqli_stmt_execute($stmt);

				header("Location: ../adminProducts.php?createStatus=success&createdName=$product_name&createdProductType=$product_type");

				exit();

			}

			

			

			

		}

	}



} else {

	// product-create was not pressed

	header("Location: ../index.php");

	exit();

}