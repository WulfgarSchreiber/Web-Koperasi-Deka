<?php

//Koneksi ke database
require 'config.php';
// Mengambil sesi dari login
require 'session.php';

// Query untuk mengambil data
$sql = "SELECT p.id, p.nama_produk, p.stok, p.gambar, p.harga, 
       COALESCE(SUM(d.jumlah), 0) AS total_terjual
		FROM produk p
		LEFT JOIN detail_pemesanan d ON p.id = d.idproduk
        WHERE p.stok > 0
		GROUP BY p.id, p.nama_produk, p.stok, p.gambar, p.harga
		ORDER BY total_terjual DESC
		LIMIT 6;";
$result = $conn->query($sql);
$username = $_SESSION['admin'];

?>

<!DOCTYPE html>
<html lang="en">
<link rel="stylesheet" href="css/invoice.css">
   <head>
      <!-- basic -->
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <!-- mobile metas -->
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta name="viewport" content="initial-scale=1, maximum-scale=1">
      <!-- site metas -->
      <title>Koperasi Dua Kelinci</title>
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
	<div class="header_section">
		<div class="container">
			<div class="row">
				<div class="col-sm-3">
					<div class="company">Koperasi Kelinci</div>
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
		<div class="banner_section">
			<div class="container-fluid">
				<section class="slide-wrapper">
    <div class="container-fluid">
	    <div id="myCarousel" class="carousel slide">

            <!-- Wrapper for slides -->
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <div class="row">
						<div class="col-sm-2 padding_0">
					</div>
					<div class="col-sm-5">
						<div class="banner_taital">
							<h1 class="banner_text">Koperasi Dua Kelinci </h1>
							<h1 class="mens_text"><strong>Buy Your Stuff</strong></h1>
							<p class="lorem_text">Menyediakan berbagai macam produk, dari produk dua kelinci, peralatan sekolah, serta alat rumah tangga dengan harga yang sangat terjangkau.</p>
						</div>
					</div>
					<div class="col-sm-5">
						<div class="shoes_img"><img src="images/logg.png"></div>
					</div>
				</div>
                <div class="carousel-item">
                    <div class="row">
					<div class="col-sm-5">
						<div class="banner_taital">
							<h1 class="banner_text">Koperasi Dua Kelinci </h1>
							<h1 class="mens_text"><strong>Buy Your Stuff</strong></h1>
							<p class="lorem_text">Menyediakan berbagai macam produk, dari produk dua kelinci, peralatan sekolah, serta alat rumah tangga dengan harga yang sangat terjangkau.</p>
						</div>
					</div>
					<div class="col-sm-5">
						<div class="shoes_img"><img src="images/logg.png"></div>
					</div>
				</div>
            </div>
        </div>
    </div>
</section>			
			</div>
		</div>
	</div>
	<!-- header section end -->
	
	<!-- New Arrivals section start -->
	<div class="collection_section layout_padding">
    	<div class="container">
    		<h1 class="new_text"><strong>Produk Paling Sering Dibeli</strong></h1>
    	    <p class="consectetur_text">Berikut adalah produk dari kami yang sering dibeli:</p>
    	</div>
    </div>
    <div class="layout_padding gallery_section">
    	<div class="container">
    		<div class="row">
			<!-- Menampilkan data yang sudah diambil pada query sebelumnya -->
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
												<a type="button" href="action.cart.home.php?action=add&id=<?= $data['id'] ?>" onclick="return confirm('Apakah anda ingin menambahkan produk ini?');"><img src="images/buynow.jpg"></a>
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
   	<!-- New Arrivals section end -->
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
 