<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link
        href="https://fonts.googleapis.com/css?family=Poppins:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i&display=swap"
        rel="stylesheet">

    <title>Armor Gym</title>
    <link rel="shortcut icon" href="{{ URL::asset('assets/images/logo_armor.png') }}">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/font-awesome.css">
    <link rel="stylesheet" href="css/templatemo-training-studio.css">

    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <meta name="theme-color" content="#6777ef" />
    <link rel="apple-touch-icon" href="" {{ URL::asset('assets/images/logo_armor.png') }}">
    <link rel="manifest" href="{{ asset('/manifest.json') }}">

    <style>
        .swiper-slide {
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .instructor-slide {
            position: relative;
            text-align: center;
            width: 70px;
            /* Adjust width as needed */
            height: 70px;
            /* Adjust height as needed */
        }

        .instructor-slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            /* Ensure the image covers the container without distortion */
            border-radius: 50%;
            /* Optional: add border-radius for rounded corners */
        }

        .instructor-name {
            position: absolute;
            bottom: 10px;
            /* Adjust to move text up */
            left: 50%;
            transform: translateX(-50%);
            background-color: rgba(0, 0, 0, 0.5);
            /* Semi-transparent background */
            color: white;
            padding: 2px 5px;
            border-radius: 3px;
            font-size: 20px;
            /* Adjust font size as needed */
            text-align: center;
            width: auto;
            max-width: 100%;
            /* Make sure the text is not too wide */
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
        }

        .carousel-item {
            transition: opacity 1s ease;
        }

        .carousel-item.active {
            opacity: 1;
        }

        .carousel-control-prev,
        .carousel-control-next {
            top: 50%;
            transform: translateY(-50%);
            width: 5%;
            background: none;
        }

        .carousel-control-prev svg,
        .carousel-control-next svg {
            width: 30px;
            height: 30px;
            display: block;
            margin: auto;
        }
    </style>



</head>

<body>

    <!-- ***** Preloader Start ***** -->
    <div id="js-preloader" class="js-preloader">
        <div class="preloader-inner">
            <span class="dot"></span>
            <div class="dots">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </div>
    <!-- ***** Preloader End ***** -->


    <!-- ***** Header Area Start ***** -->
    <header class="header-area header-sticky">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <nav class="main-nav">
                        <!-- ***** Logo Start ***** -->
                        <a href="{{ url('/') }}" class="logo" style="line-height: 60px;">
                            <img src="{{ URL::asset('assets/images/logo_armor.png') }}" alt="Armor Gym Logo"
                                title="Armor Gym" height="40" width="50" style="vertical-align: middle;">
                            <span style="vertical-align: middle;">Armor<em> Gym</em></span>
                        </a>



                        <!-- ***** Logo End ***** -->
                        <!-- ***** Menu Start ***** -->
                        <ul class="nav">
                            <li class="scroll-to-section"><a href="#top" class="">Home</a></li>
                            <li class="scroll-to-section"><a href="#about">About</a></li>
                            <li class="scroll-to-section"><a href="#program">Program</a></li>
                            <li class="scroll-to-section"><a href="#pricing">Pricing</a></li>
                            <li class="scroll-to-section"><a href="#trainer">Trainers</a></li>
                            <li class="scroll-to-section"><a href="#tutorial">Tutorial</a></li>
                            <li class="main-button"><a href="{{ url('login') }}">Sign Up</a></li>
                        </ul>
                        <a class='menu-trigger'>
                            <span>Menu</span>
                        </a>
                        <!-- ***** Menu End ***** -->
                    </nav>
                </div>
            </div>
        </div>
    </header>
    <!-- ***** Header Area End ***** -->

    <!-- ***** Main Banner Area Start ***** -->
    {{-- <div class="main-banner" id="top"> --}}
        <div class="main-banner" id="top" style="background-image: url('../images/gym_wallpaper.jpg');">
            {{-- <video autoplay muted loop id="bg-video">
                <source src="{{ asset('images/gym-video.mp4') }}" type="video/mp4" />
            </video> --}}


            <div class="video-overlay header-text">
                <div class="caption">
                    <h6>work harder, get stronger</h6>
                    <h2>join armor <em>gym</em></h2>
                    <div class="main-button scroll-to-section">
                        <a href="login">Become a member</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- ***** Main Banner Area End ***** -->

        <!-- ***** About Start ***** -->
        <section class="section" id="about">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 offset-lg-3">
                        <div class="section-heading">
                            <h2>About</h2>
                            <img src="images/line-dec.png" alt="waves">
                            <p>Armor Gym is a fitness training facility located in Gombong. The advantages of this gym
                                are
                                as follows :</p>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-4 text-center">
                            <div class="feature-item">
                                <img src="images/features-first-icon.png" alt="First One">
                                <p>The facilities at Armor Gym are highly supportive</p>
                            </div>
                        </div>

                        <div class="col-md-4 text-center">
                            <div class="feature-item">
                                <img src="images/features-first-icon.png" alt="Second One">
                                <p>Armor Gym provides training services with experienced trainers.</p>
                            </div>
                        </div>

                        <div class="col-md-4 text-center">
                            <div class="feature-item">
                                <img src="images/features-first-icon.png" alt="Third One">
                                <p>Armor Gym offers several programs that can assist members in achieving their various
                                    goals </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- ***** About End ***** -->

        <!-- ***** Program ***** -->
        <section class="section" id="program">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 offset-lg-3">
                        <div class="section-heading">
                            <h2>Choose <em>Program</em></h2>
                            <img src="images/line-dec.png" alt="waves">
                            <p>Armor Gym offers a variety of programs that members can choose from </p>
                        </div>
                    </div>
                </div>
                <div id="caraouselProgram" class="carousel slide" data-ride="carousel">
                    <div class="carousel-inner">
                        @foreach($programs as $key => $program)
                        <div class="carousel-item {{ $key == 0 ? 'active' : '' }} text-center">
                            <div class="card mx-auto" style="width: 18rem;">
                                <div class="left-icon">
                                    <img src="{{ $program->image() }}" class="card-img-top program-image" width="100"
                                        height="200" alt="program data Image">
                                </div>
                                <div class="right-content">
                                    <h4>{{ $program->name }}</h4>
                                    <p>{{ $program->description }}</p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <a class="carousel-control-prev" href="#caraouselProgram" role="button" data-slide="prev">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"
                            style="width: 30px; height: 30px; display: block; margin: auto;">
                            <path
                                d="M9.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l192 192c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L77.3 256 246.6 86.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-192 192z" />
                        </svg>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="carousel-control-next" href="#caraouselProgram" role="button" data-slide="next">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"
                            style="width: 30px; height: 30px; display: block; margin: auto;">
                            <path
                                d="M310.6 233.4c12.5 12.5 12.5 32.8 0 45.3l-192 192c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3L242.7 256 73.4 86.6c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0l192 192z" />
                        </svg>
                        <span class="sr-only">Next</span>
                    </a>
                </div>
        </section>
        <!-- ***** Program ***** -->

        <!-- ***** Pricing Start ***** -->
        <section class="section" id="pricing">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 offset-lg-3">
                        <div class="section-heading">
                            <h2>Pricing</h2>
                            <img src="images/line-dec.png" alt="waves">
                            <p>Armor Gym provides several services and products:</p>
                        </div>
                    </div>
                </div>
                <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                    <div class="carousel-inner">
                        @foreach($suplements as $key => $suplement)
                        <div class="carousel-item {{ $key == 0 ? 'active' : '' }} text-center">
                            <div class="card mx-auto" style="width: 18rem;">
                                <img src="{{ $suplement->image() }}" class="card-img-top suplement-image" width="100"
                                    height="200" alt="Suplement Image">

                                <div class="card-body">
                                    <h5 class="card-title">{{ $suplement->name }}</h5>
                                    <p class="card-text">{{ $suplement->description }}</p>
                                    <p class="card-text">Price: {{ $suplement->price }}</p>
                                    <a href="#tutorial" class="btn btn-primary">Buy</a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"
                            style="width: 30px; height: 30px; display: block; margin: auto;">
                            <path
                                d="M9.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l192 192c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L77.3 256 246.6 86.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-192 192z" />
                        </svg>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"
                            style="width: 30px; height: 30px; display: block; margin: auto;">
                            <path
                                d="M310.6 233.4c12.5 12.5 12.5 32.8 0 45.3l-192 192c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3L242.7 256 73.4 86.6c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0l192 192z" />
                        </svg>
                        <span class="sr-only">Next</span>
                    </a>
                </div>
        </section>
        <!-- ***** Pricing End ***** -->

        <!-- ***** Trainer ***** -->
        <section class="section" id="trainer">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 offset-lg-3">
                        <div class="section-heading">
                            <h2>Experienced <em>Trainers</em></h2>
                            <img src="images/line-dec.png" alt="">
                            <p>Armor Gym has experienced trainers who will guide you here</p>
                        </div>
                    </div>
                </div>

                <div class="swiper mySwiper">
                    <div class="swiper-wrapper">
                        @foreach($insturctors as $instructor)
                        <div class="swiper-slide">
                            {{-- <img src="{{ asset('images/instructors/' . $instructor->image) }}" height="300px"> --}}
                            <img src="{{ $instructor->image != null ? asset('storage/' . $instructor->image) : asset('images/profile-default.png') }}"
                                height="300px">
                            <div class="instructor-name">{{ ucwords($instructor->name) }}</div>
                        </div>
                        @endforeach
                    </div>
                    <div class="swiper-pagination"></div>
                </div>

            </div>
        </section>
        <!-- ***** Trainer Ends ***** -->

        <!-- ***** Tutorial Start ***** -->
        <section class="section" id="tutorial">
            <div class="container">
                <!-- Section Heading -->
                <div class="row">
                    <div class="col-lg-6 offset-lg-3">
                        <div class="section-heading dark-bg">
                            <h2 style="color:#232d39;">Tutorial<em>Transaction</em></h2>
                            <img src="images/line-dec.png" alt="">
                        </div>
                    </div>
                </div>

                <!-- Steps Row -->
                <div class="row justify-content-center">
                    <!-- Step 1 -->
                    <div class="col-md-4 text-center">
                        <div class="card">
                            <img src="images/signup.jpg" class="card-img-top mx-auto d-block" alt="Step 1 Image">
                            <div class="card-body">
                                <h5 class="card-title">Step 1: Click Sign Up</h5>
                                <p class="card-text">Click the sign up button to register a user.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Step 2 -->
                    <div class="col-md-4 text-center">
                        <div class="card">
                            <img src="images/wa.png" class="card-img-top mx-auto d-block" style="height: 300px;"
                                alt="Step 2 Image">
                            <div class="card-body">
                                <h5 class="card-title">Step 2: Chat with admin via WhatsApp admin </h5>
                                <p class="card-text">Information about WhatsApp admin can be seen in the footer of the
                                    page.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Step 3 -->
                    <div class="col-md-4 text-center">
                        <div class="card">
                            <img src="images/wa_admin.jpg" class="card-img-top mx-auto d-block"
                                style="height: 500px; alt=" Step 3 Image">
                            <div class="card-body">
                                <h5 class="card-title">Step 3: Choose transaction</h5>
                                <p class="card-text">Carry out transactions as desired which can be seen in the pricing
                                    menu
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- ***** End ***** -->

        <!-- ***** Footer Start ***** -->
        <footer class="bg-body-tertiary text-muted">
            <div class="container text-center text-md-start mt-5 py-5">
                <!-- Grid row -->
                <div class="row">
                    <!-- Grid column for Map -->
                    <div class="col-md-6 mb-4">
                        <div class="map-responsive">
                            <iframe
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3954.6909355510334!2d109.49930887580147!3d-7.608571592406585!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e654bd9d3c13643%3A0x2ee54a7dc9e67c9!2sArmor%20Gym%20Fitness%20Center!5e0!3m2!1sid!2sid!4v1715683641848!5m2!1sid!2sid"
                                width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade">
                            </iframe>
                        </div>
                    </div>
                    <!-- Grid column for Contact Info -->
                    <div class="col-md-6 col-lg-3 col-xl-3 mx-auto mb-md-0 mb-4">
                        <h6 class="text-uppercase fw-bold mb-4">Contact</h6>
                        <p><i class="fas fa-home me-3"></i> Yos Sudarso Street Number 792 B, SangkalPutung Semondo,
                            Gombong.
                        </p>
                        <p><i class="fas fa-phone me-3"></i> +62 895 1033 7817</p>
                        <a href="https://wa.link/9tkfuv" class="btn btn-success" style="color:white">WhatsApp</a>
                    </div>
                </div>
            </div>
            <!-- Copyright -->
            <div class="text-center p-4" style="background-color: rgba(0, 0, 0, 0.05);">
                © 2024 Copyright: Armor Gym
            </div>
        </footer>

        <!-- ***** Footer End ***** -->



        <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>



        <script>
            var swiper = new Swiper(".mySwiper", {
          slidesPerView: 2,
          spaceBetween: 30,
          freeMode: true,
          pagination: {
            el: ".swiper-pagination",
            clickable: true,
          },
        });

        </script>



        <!-- jQuery -->
        <script src="js/jquery-2.1.0.min.js"></script>

        <!-- Bootstrap -->
        <script src="js/popper.js"></script>
        <script src="js/bootstrap.min.js"></script>

        <!-- Plugins -->
        <script src="js/scrollreveal.min.js"></script>

        <!-- Global Init -->
        <script src="js/custom.js"></script>

</body>

</html>

<script src="{{ asset('/sw.js') }}"></script>
<script>
    if ("serviceWorker" in navigator) {
      // Register a service worker hosted at the root of the
      // site using the default scope.
      navigator.serviceWorker.register("/sw.js").then(
      (registration) => {
         console.log("Service worker registration succeeded:", registration);
      },
      (error) => {
         console.error(`Service worker registration failed: ${error}`);
      },
    );
  } else {
     console.error("Service workers are not supported.");
  }
</script>