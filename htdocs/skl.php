<?php

require 'config.php';

$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

// Ambil data produk sesuai kategori
$sql = "SELECT id, nama_produk, stok, harga, gambar FROM produk WHERE kategori = 'Lainnya' AND stok > 0";

if (!empty($search)) {
    $sql .= " AND nama_produk LIKE '%$search%'";
}

$sql .= " ORDER BY nama_produk ASC";

$username = $_SESSION['admin'];
$result = $conn->query($sql);

?>


<!DOCTYPE html>
<html lang="en">
   <head>
      <!-- basic -->
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <!-- mobile metas -->
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta name="viewport" content="initial-scale=1, maximum-scale=1">
      <!-- site metas -->
      <title>List Produk</title>
      <meta name="keywords" content="">
      <meta name="description" content="">
      <meta name="author" content="">
      <!-- bootstrap css -->
      <link rel="stylesheet" href="css/bootstrap.min.css">
      <!-- style css -->
      <link rel="stylesheet" href="css/style.css">
      <!-- Responsive-->
      <link rel="stylesheet" href="css/responsive.css">
      <!-- fevicon -->
      <link rel="icon" href="images/fevicon.png" type="image/gif" />
      <!-- Scrollbar Custom CSS -->
      <link rel="stylesheet" href="css/jquery.mCustomScrollbar.min.css">
      <!-- Tweaks for older IEs-->
      <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css">
      <!-- owl stylesheets --> 
      <link rel="stylesheet" href="css/owl.carousel.min.css">
      <link rel="stylesheet" href="css/owl.theme.default.min.css">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.css" media="screen">
      <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script><![endif]-->
   </head>
   <!-- body -->
   <body class="main-layout">
	<!-- header section start -->
	<div class="header_section header_main">
		<div class="container">
			<div class="row">
				<div class="col-sm-3">
					<div class="logo"><a href="#"><img src="images/name.png"></a></div>
				</div>
				<div class="col-sm-9">
					<nav class="navbar navbar-expand-lg navbar-light bg-light">
                        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                        </button>
                    <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                        <div class="navbar-nav">
						<a class="nav-item nav-link" href="index.php">Home</a>
                           <a class="nav-item nav-link" href="produk.php">Produk</a>
                           <a class="nav-item nav-link" href="kategori.php">Kategori Produk</a>
                           <a class="nav-item nav-link" href="jual.php">Jual</a>
						   <a class="nav-item nav-link" href="aboutus.php">About Us</a>
						   <a class="nav-item nav-link last" href="keranjang.php"><img src="images/shop_icon.png"></a>
						   <ul class="navbar-nav ml-auto ml-md-0">
								<li class="nav-item dropdown">
									<a class="nav-link dropdown-toggle" id="userDropdown" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa-fw"></i></a>
									<div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                                        <a class="dropdown-item" href="profile.php">Profile: <?php echo $username ?></a>
										<a class="dropdown-item" href="myorder.php">Pesanan Saya</a>
										<a class="dropdown-item" href="logout.php">Logout</a>
									</div>
								</li>
							</ul>
                        </div>
                    </div>
                    </nav>
				</div>
			</div>
		</div>
	</div>
	<!-- new collection section start -->
  <div class="collection_text">Lainnya</div>
    <div class="layout_padding collection_section">
        <div class="container">
    		<h1 class="new_text"><strong>Selamat Datang di Koperasi Dua Kelinci</strong></h1>
    	    <p class="consectetur_text">Jelajahi berbagai produk pilihan dari kategori lainnya! Temukan kebutuhan unik dan menarik yang sesuai dengan gaya hidup Anda. Yuk, lihat lebih banyak dan temukan kejutan spesial!</p>
    	</div>
    </div>
    	<div class="layout_padding gallery_section">
        <form method="GET" action="" class="mb-3">
            <div class="input-group">
                <input type="text" name="search" width="50%" class="form-control" placeholder="Cari produk..." value="<?php echo htmlspecialchars($search); ?>">
                <div class="input-group-append">
                    <button type="submit" class="btn btn-success">Cari</button>
                </div>
            </div>
    	</form>
			<div class="container">
				<div class="row">
				<?php if ($result->num_rows > 0) {
								while ($data = $result->fetch_assoc()) {
									?>
									<div class="col-sm-4">
										<div class="best_shoes">
											<p class="best_text"><span style="font-family: 'Georgia'; font-size: 130%"><?php echo $data['nama_produk']; ?></span></p>
											<div class="shoes_icon">
												<img src="images/<?php echo $data['gambar']; ?>" alt="<?php echo $data['gambar']; ?>">
											</div>
											<div class="star_text">
												<div class="left_part">
													<div class="shoes_price"><span style="font-family: 'Georgia'">Rp</span> <span style="font-family: 'Georgia'; color: #04a9eb; font-size: 70%"><?php echo number_format($data['harga']); ?></span></div>
												</div>
												<div class="right_part">
													<div class="shoes_price"><span style="font-family: 'Georgia'">Stok</span> <span style="font-family: 'Georgia'; color: #04a9eb; font-size: 70%"><?php echo $data['stok']; ?></span></div>
												</div>
												<div class="buy_now_bt">
													<a type="button" href="action.cart.skl.php?action=add&id=<?= $data['id'] ?>" onclick="return confirm('Apakah anda ingin menambahkan produk ini?');"><img src="images/buynow.jpg"></a>
												</div>
											</div>
										</div>
									</div>
									<?php
								}
							} else {
								echo "Tidak ada produk yang tersedia.";
							} ?>
				</div>
			</div>
		</div>
	</div>	
	<!-- new collection section end -->
    <!-- section footer start -->
    <div class="section_footer">
    	<div class="container">
    		<div class="mail_section">
    			<div class="row">
					<div class="col-sm-6 col-lg-2">
    				</div>
    				<div class="col-sm-6 col-lg-2">
    					<div class="footer-logo"><img src="images/phone-icon.png"><span class="map_text">(0295) 381407</span></div>
    				</div>
    				<div class="col-sm-6 col-lg-3">
    					<div class="footer-logo"><img src="images/email-icon.png"><span class="map_text">pati@duakelinci.co.id</span></div>
    				</div>
    				<div class="col-sm-6 col-lg-3">
    					<div class="social_icon">
    						<ul>
    							<li><a href="https://www.facebook.com/DuaKelinciID/?locale=id_ID"><img src="images/fb-icon.png"></a></li>
    							<li><a href="https://x.com/DuaKelinci"><img src="images/twitter-icon.png"></a></li>
    							<li><a href="https://id.linkedin.com/company/duakelinci"><img src="images/in-icon.png"></a></li>
    							<li><a href="https://duakelinci.com/"><img src="images/google-icon.png"></a></li>
    						</ul>
    					</div>
    				</div>
    				<div class="col-sm-2"></div>
    			</div>
    	    </div> 
    	    <div class="footer_section_2">
		        <div class="row">
    		        <div class="col-sm-4 col-lg-2">
    		        	<p class="dummy_text"></p>
    		        </div>
    		        <div class="col-sm-4 col-lg-2">
    		        	<h2 class="shop_text">Alamat </h2>
    		        	<div class="image-icon"><img src="images/map-icon.png"><span class="pet_text">Jl. Raya Pati-Kudus KM 6, Pati, Jawa Tengah, Indonesia</span></div>
    		        </div>
    		        <div class="col-sm-4 col-md-6 col-lg-3">
    				    <h2 class="shop_text">Our Company </h2>
    				    <div class="delivery_text">
    				    	<ul>
    				    		<li>Pre-Order</li>
    				    		<li>Legal Notice</li>
    				    		<li>About Us</li>
    				    		<li>Easy Payment</li>
    				    		<li>Sell Your Product</li>
    				    	</ul>
    				    </div>
    		        </div>
    			<div class="col-sm-6 col-lg-3">
    				<h2 class="adderess_text">Produk</h2>
    				<div class="delivery_text">
    				    	<ul>
    				    		<li>Sangrai</li>
    				    		<li>Sukro</li>
    				    		<li>Tic Tac</li>
    				    		<li>Mix Nut</li>
    				    		<li>Lofet</li>
    				    	</ul>
    				    </div>
    			</div>
    			</div>
    	        </div> 
	        </div>
    	</div>
    </div>
	<!-- section footer end -->
	<div class="copyright">2025 Digawe Cah Pati. <a href="https://html.design">Okeh Sep Penting Dadi.</a></div>

      <!-- Javascript files-->
      <script src="js/jquery.min.js"></script>
      <script src="js/popper.min.js"></script>
      <script src="js/bootstrap.bundle.min.js"></script>
      <script src="js/jquery-3.0.0.min.js"></script>
      <script src="js/plugin.js"></script>
      <!-- sidebar -->
      <script src="js/jquery.mCustomScrollbar.concat.min.js"></script>
      <script src="js/custom.js"></script>
      <!-- javascript --> 
      <script src="js/owl.carousel.js"></script>
      <script src="https:cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.js"></script>
      <script>
         $(document).ready(function(){
         $(".fancybox").fancybox({
         openEffect: "none",
         closeEffect: "none"
         });
         
         
$('#myCarousel').carousel({
            interval: false
        });

        //scroll slides on swipe for touch enabled devices

        $("#myCarousel").on("touchstart", function(event){

            var yClick = event.originalEvent.touches[0].pageY;
            $(this).one("touchmove", function(event){

                var yMove = event.originalEvent.touches[0].pageY;
                if( Math.floor(yClick - yMove) > 1 ){
                    $(".carousel").carousel('next');
                }
                else if( Math.floor(yClick - yMove) < -1 ){
                    $(".carousel").carousel('prev');
                }
            });
            $(".carousel").on("touchend", function(){
                $(this).off("touchmove");
            });
        });
      </script> 
   </body>
</html>
 