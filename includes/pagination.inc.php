<?php

$pg = 1;
$per_page = 9;
$page_counter = 1;
$next = $page_counter + 1;
$previous = $page_counter - 1;


	if (isset($_GET['pg'])) {
		$start = $_GET['pg'];
		$start = $start - 1;
		$page_counter = $_GET['pg'];
		$start = $start * $per_page; // starts as 0, then 10, then 20, etc;
		$next = $page_counter + 1;
		$previous = $page_counter - 1;
	} else {
		$start = 0;
		$pg = 1;
		$per_page = 9;
		$page_counter = 1;
		$next = $page_counter + 1;
		$previous = $page_counter - 1;
	}