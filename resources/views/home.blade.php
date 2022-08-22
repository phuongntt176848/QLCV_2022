<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <!-- <link rel="preconnect" href="https://fonts.gstatic.com" /> -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" />
  <link rel="stylesheet" href="{{asset('css/styles.css')}}" />
  <title>Todo Home</title>
</head>

<body>
  <div class="wrapper">
    <div class="container">
      <header class="header">
        <a href="{{route('home')}}" class="header-logo">
          <img src="asset('images/logo.png')" alt="" class="header-logo-image" />
        </a>
        <ul class="header-menu">
          <li class="header-menu-item">
            <a href="{{route('home')}}" class="header-menu-link"> Home </a>
          </li>
          <li class="header-menu-item">
            <a href="#product" class="header-menu-link"> Product </a>
          </li>
          <li class="header-menu-item">
            <a href="#about" class="header-menu-link"> About </a>
          </li>
        </ul>
        @auth
        <!-- TODO Them html cho logout -->
        @endauth
        @guest
        <div class="header-auth">
          <a href="{{route('login')}}" class="button">Login</a>
          <a href="{{route('signup')}}" class="button button--primary">Sign Up</a>
        </div>
        @endguest
      </header>
      <section class="banner" id="home">
        <div class="banner-info">
          <h1 class="banner-heading">
            <span>Easy task management</span>with Todo !!!
          </h1>
          <p class="banner-desc text">
            Easily build your ideal workflow with Todo building blocks.
          </p>
          <div class="banner-links">
            <a href="{{route('signup')}}" class="button button--primary">Get Started</a>
            <a href="#product" class="button button--outline">How it works</a>
          </div>
        </div>
        <img src="{{asset('images/img1.png')}}" alt="" class="banner-image" />
      </section>
      <section class="work" id="product">
        <div class="block">
          <span class="block-caption">Walk Through</span>
          <h2 class="block-heading">
            <span>How does</span> <span>Todo works?</span>
          </h2>
        </div>
        <div class="work-list">
          <div class="work-item">
            <img src="asset('images/img2.png')" alt="" class="work-image" />
            <h3 class="work-title">Check the to-do list quickly.</h3>
          
            <a href="#" class="button button--outline work-more">Learn more</a>
          </div>
          <div class="work-item">
            <img src="asset('images/img3.png')" alt="" class="work-image" />
            <h3 class="work-title">Create teams and add members easily.</h3>
            
            <a href="#" class="button button--outline work-more">Learn more</a>
          </div>
          <div class="work-item">
            <img src="asset('images/img4.png')" alt="" class="work-image" />
            <h3 class="work-title">Effective project management.</h3>
           
            <a href="#" class="button button--outline work-more">Learn more</a>
          </div>
        </div>
      </section>
      <section class="started">
        <img src="asset('images/img6.png')" alt="" class="started-comment" />
        <img src="asset('images/img7.png')" alt="" class="started-smile" />
        <span class="block-caption started-caption">Todo Studio</span>
        <h2 class="block-heading started-heading">
          <span>Get started with</span> <span>Todo today</span>
        </h2>
        <p class="text started-desc">
           Let's manage the work together effectively.
        </p>
        <a href="{{route('signup')}}" class="button button--primary">Get Started</a>
      </section>
      <footer class="footer" id="about">
        <div class="footer-column">
          <a href="index.html" class="logo">
            <img src="asset('images/logo.png')" alt="" class="logo-image" />
          </a>
          <p class="text text--dark footer-desc">
             Let's manage the work together effectively.
          </p>
          <div class="social">
            <a href="#" class="social-link bg-google">
              <i class="fa fa-google"></i>
            </a>
            <a href="#" class="social-link bg-twitter">
              <i class="fa fa-twitter"></i>
            </a>
            <a href="#" class="social-link bg-instagram">
              <i class="fa fa-instagram"></i>
            </a>
          </div>
        </div>
        <div class="footer-column">
          <h3 class="footer-heading">Product</h3>
          <ul class="footer-links">
            <li class="footer-link-item">
              <a href="#" class="footer-link">Nguyen Phuong</a>
            </li>
          </ul>
        </div>
      
        <div class="footer-column">
          <h3 class="footer-heading">Company</h3>
          <ul class="footer-links">
            <li class="footer-link-item">
              <a href="#" class="footer-link">Nguyen Phuong</a>
            </li>
          </ul>
        </div>
      </footer>
    </div>
  </div>
</body>

</html>
