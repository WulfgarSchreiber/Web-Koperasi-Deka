<?php

include "func.php";
require "session.php";
$username = $_SESSION['admin'];

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

      <title>About Us</title>

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

                            <a class="nav-item nav-link" href="aboutus.html">About Us</a>

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

   	<!-- contact section start -->

    <div class="collection_text">About Us</div>

    <div class="layout_padding contact_section">
    	<div class="layout_padding contact_section">
    <div class="container">
        <h1 class="text-center">Tentang Kami</h1>
        <p class="text-center"><strong>PT Dua Kelinci</strong> adalah perusahaan makanan ringan ternama di Indonesia yang dikenal dengan produk kacang berkualitas tinggi. Sejak berdiri, kami telah berkomitmen untuk menyediakan produk terbaik bagi konsumen dengan cita rasa khas.</p>
        
        <h2 class="text-center">Visi</h2>
        <p class="text-center">Menjadi produsen makanan yang terpercaya di pasar lokal dan global, unggul
dalam kualitas produk dan layanan melalui inovasi yang berkelanjutan.</p>
        
        <h2 class="text-center">Misi</h2>
        <ul class="text-center">
            <li>Meningkatkan daya saing dengan fokus pada peningkatan kualitas, efisiensi
dan teknologi.</li>
            <li>Bekerja secara konsisten untuk meningkatkan kinerja dan memperkuat merek
korporat dengan memanfaatkan dan memperluas jaringan distribusi global.</li>
            <li>Bersaing dalam kualitas dengan menjadi efisien dan menerapkan teknologi
baru, serta tetap responsif terhadap kebutuhan dan keinginan konsumen di
Indonesia dan Internasional. </li>
        </ul>
        <br>

        <h2 class="text-center">Kontak</h2>
        <p class="text-center">Alamat: Jl. Raya Pati-Kudus KM 6, Pati, Jawa Tengah, Indonesia</p>
        <p class="text-center">Email: pati@duakelinci.co.id</p>
        <p class="text-center">Telepon:</p>
        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr class="text-center">
                                                <th scope="col">No.</th>
                                                <th scope="col">Nama</th>
                                                <th scope="col">No. Telepon</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        <?php
                                        $q=$db->prepare("select * from admin order by adminname desc");
                                        $q->execute();
                                        $no=0;
                                        while($r=$q->fetch()){ $no++;
                                        ?>
                                        <tr>
                                            <td class="tab-col" scope="row"><?php echo $no;?></td>
                                            <td><?php echo $r["nama"];?></td>
                                            <td class="tab-col"><?php echo $r["telepon"];?></td>
                                            </tr>
                                        <?php } ?>

                                        </tbody>
        </table>
    </div>
</div>


    </div>

   	<!-- contact section end -->

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
    
	<div class="copyright">PT. Dua Kelinci Pati&nbsp<a href="https://html.design">| Koperasi  Dua Kelinci.</a></div>





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

