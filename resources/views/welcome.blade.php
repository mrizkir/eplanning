<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Bintan E-Gemilang Suite v1.0">  
    <meta name="author" content="TIM IT Bintan 2019">  
    <title>e-Gemilang Kabupaten Bintan</title>
    <!-- Bootstrap core CSS -->
    <link href="{!!asset('/themes/frontend/css/bootstrap.min.css')!!}" rel="stylesheet">
    <!-- Custom fonts for this template -->
    <link href="{!!asset('/themes/frontend/fontawesome-free/css/all.min.css')!!}" rel="stylesheet">  
    <link href="{!!asset('/themes/frontend/simple-line-icons/css/simple-line-icons.css')!!}" rel="stylesheet" type="text/css">  
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic" rel="stylesheet" type="text/css">  
    <!-- Custom styles for this template -->
    <link href="{!!asset('/themes/frontend/css/landing-page.min.css')!!}" rel="stylesheet"> 
    <style>
        img {
            border-radius: 50%;
            -webkit-transition: -webkit-transform .8s ease-in-out;
            transition: transform .8s ease-in-out;
        }
        img:hover {
            -webkit-transform: rotate(360deg);
            transform: rotate(360deg);
        }
    </style>
</head>
<body>  
<!-- Navigation -->
<nav class="navbar navbar-light bg-light static-top">    
    <div class="container">
        <a class="navbar-brand" href="#"><img src="{!!asset('/themes/frontend/img/testimonials-4.jpg')!!}">      
            Pemerintah Kabupaten Bintan
        </a>     
    </div>
</nav>
<!-- Masthead -->  
<header class="masthead text-white text-center">
    <div class="overlay"></div>    
    <div class="container">      
        <div class="row">        
            <div class="col-xl-9 mx-auto">          
                <h1 class="mb-5">Aplikasi e-Gemilang Kabupaten Bintan</h1>
            </div>
            <div class="col-md-10 col-lg-8 col-xl-7 mx-auto">
                <form>            
                    <div class="form-row">                                
                        <div class="col-12 col-md-9 mb-2 mb-md-0">
                        </div>              
                        <div class="col-12 col-md-3">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</header>
<!-- Icons Grid -->
<section class="features-icons bg-light text-center">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-6 text-center">
                <div class="mt-5">
                    <a href="{{route('login')}}">
                        <i class="fas fa-7x fa-a text-primary mb-4">
                            <img src="{!!asset('/themes/frontend/img/portfolio/pen.png')!!}" alt="">
                        </i>
                        <h3 class="h4 mb-2">e-Planning</h3>
                    </a>           
                    <p class="text-muted mb-0">Permendagri No. 86 Tahun 2017</p>						
                </div>			   		
            </div>         
            <div class="col-lg-3 col-md-6 text-center">
                <div class="mt-5">
                    <a href="https://ebudgeting-bintan.id">
                        <i class="fas fa-4x fa-usd text-primary mb-4">
                            <img src="{!!asset('/themes/frontend/img/portfolio/money.png')!!}" alt="">
                        </i>
                        <h3 class="h4 mb-2">e-Budgeting</h3>
                    </a>            
                    <p class="text-muted mb-0">Sistem Informasi Penganggaran</p>
                </div>
            </div>  
            <div class="col-lg-3 col-md-6 text-center">
                <div class="mt-5">
                    <a href="https://simdaperencanaan.bintankab.go.id">
                        <i class="fas fa-4x fa-a text-primary mb-4">
                            <img src="{!!asset('/themes/frontend/img/portfolio/handshake.png')!!}" alt="">
                        </i>
                        <h3 class="h4 mb-2">SIMDA INTEGRATED</h3>
                    </a>            
                    <p class="text-muted mb-0">Simda Integrated</p>
                </div>
            </div>  
            <div class="col-lg-3 col-md-6 text-center">						
                <div class="mt-5">
                    <a href="https://simonev.bintankab.go.id">
                        <i class="fas fa-4x fa-a text-primary mb-4">
                            <img src="{!!asset('/themes/frontend/img/portfolio/magni.png')!!}" alt="">
                        </i>
                        <h3 class="h4 mb-2">e-Monev</h3>
                    </a>           
                    <p class="text-muted mb-0">Deskripsi e-Monev</p>
                </div>					
            </div>
        </div>
    </div>
</section>
<!-- Testimonials -->
<section class="testimonials text-center bg-light">
    <div class="container">
        <h2 class="mb-5">Sambutan</h2>      
        <div class="row">
            <div class="col-lg-4">
                <div class="testimonial-item mx-auto mb-5 mb-lg-0">            
                    <img class="img-fluid rounded-circle mb-3" src="{!!asset('/themes/frontend/img/bupati.jpg')!!}" alt="">                                
                    <h5>Bupati Kab. Bintan</h5>            
                    <p class="font-weight-light mb-0">H. Apri Sujadi S.Sos.</p>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="testimonial-item mx-auto mb-5 mb-lg-0">                        
                    <img class="img-fluid rounded-circle mb-3" src="{!!asset('/themes/frontend/img/wabup.jpg')!!}" alt="">                        
                    <h5>Wakil Bupati Kab. Bintan</h5>                        
                    <p class="font-weight-light mb-0">Drs. H. Dalmasri Syam, MM</p>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="testimonial-item mx-auto mb-5 mb-lg-0">
                    <img class="img-fluid rounded-circle mb-3" src="{!!asset('/themes/frontend/img/sekda.jpg')!!}" alt="">
                    <h5>Sekretaris Daerah Kab. Bintan</h5>
                    <p class="font-weight-light mb-0">Drs. Adi Prihantara, MM</p>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Call to Action -->  
<section class="call-to-action text-white text-center">    
    <div class="overlay"></div>        
    <div class="container">
        <div class="row">
            <div class="col-xl-9 mx-auto">
                <h2 class="mb-4">Peta Rancangan Ibukota Bintan</h2>
            </div>
            <div class="col-md-10 col-lg-8 col-xl-7 mx-auto">
                <img src="{!!asset('/themes/frontend/img/bintan-masterplan.jpg')!!}">          
                <form>
                    <div class="form-row">
                        <div class="col-12 col-md-9 mb-2 mb-md-0">
                    
                        </div>
                        <div class="col-12 col-md-3">
                        
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
  <!-- Footer -->
<footer class="footer bg-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 h-100 text-center text-lg-left my-auto">
                <ul class="list-inline mb-2">
                    <li class="list-inline-item">
                        <a href="#">Tentang</a>
                    </li>
                    <li class="list-inline-item">&sdot;</li>
                    <li class="list-inline-item">
                        <a href="#">Kontak</a>
                    </li>
                    <li class="list-inline-item">&sdot;</li>
                    <li class="list-inline-item"></li>
                    <li class="list-inline-item">&sdot;</li>
                    <li class="list-inline-item"></li>
                </ul>
                <p class="text-muted small mb-4 mb-lg-0">Copyright 2019</p>
            </div>
            <div class="col-lg-6 h-100 text-center text-lg-right my-auto">
                <ul class="list-inline mb-0">
                    <li class="list-inline-item mr-3">
                        <a href="#">
                        <i class="fab fa-facebook fa-2x fa-fw"></i>
                        </a>
                    </li>
                    <li class="list-inline-item mr-3">
                        <a href="#">
                        <i class="fab fa-twitter-square fa-2x fa-fw"></i>
                        </a>
                    </li>
                    <li class="list-inline-item">
                        <a href="#">
                        <i class="fab fa-instagram fa-2x fa-fw"></i>
                        </a>
                    </li>
                 </ul>
            </div>
        </div>
    </div>
</footer>  
<script src="{!!asset('/themes/frontend/js/jquery.min.js')!!}"></script>
<script src="{!!asset('/themes/frontend/js/bootstrap.bundle.min.js')!!}"></script>
</body>
</html>