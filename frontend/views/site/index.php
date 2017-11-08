<?php
$baseUrl = Yii::$app->request->baseUrl;
$this->title = 'Health Events Live: Home';
$this->registerCss(
        "#homelayout-header{
        display: none;
    }");
?>
<style>
    .slider{
        position: absolute;
        top: 0px;
        bottom: 0px;
        width: 100%;
        z-index: -1;
        overflow: hidden;
    }
    .slider .slide{
        width: 100%;
        height: 100%;
        background-size: cover;
    }
    .slide-0{background: url(<?= $baseUrl ?>/images/banner-img.jpg) top center no-repeat;}
    .slide-1{background: url(<?= $baseUrl ?>/images/hero1.jpg) top center no-repeat;}
    .slide-2{background: url(<?= $baseUrl ?>/images/hero2.jpg) top center no-repeat;}
    .slide-3{background: url(<?= $baseUrl ?>/images/hero3.jpg) top center no-repeat;}
    @media screen and (max-width: 480px){
        .slide-0 {background-position-x: 21%;}
        .slide-1 {background-position-x: 38%;}
        .slide-2 {background-position-x: 40%;}
        .slide-3 {background-position-x: 50%;}
    }
</style>
<header>
    <div class="slider">
        <div class="slide slide-0"></div>
        <div class="slide slide-1"></div>
        <div class="slide slide-2"></div>
        <div class="slide slide-3"></div>
        <!--<img class="slide" src="<?= $baseUrl ?>/images/banner-img.jpg" alt="" />-->
    </div>
    <div class="container" id="home">
        <div class="signUp-btns clearfix">
            <?php
            if (Yii::$app->user->isGuest) {
                ?>
                <a href="<?= $baseUrl ?>/site/signup" class="border" style="font-weight: bold;">Sign Up</a>
                <a href="<?= $baseUrl ?>/site/login" style="font-weight: bold;">Log In</a>
            <?php } else {
                ?>
                <a href="<?= $baseUrl ?>/site/logout" class="active show_menu">Logout</a>
                <a href="<?= \yii\helpers\Url::to(['user/profile']) ?>">My Account</a>
                <!--<a class="active show_menu" style="font-weight: bold;"><?= Yii::$app->user->identity->first_name ?></a>-->
            <?php } ?>
        </div>
        <div class="logo-container">
            <a href="<?= $baseUrl ?>"><img src="<?= Yii::$app->getHomeUrl(); ?>images/home-logo.png" alt=""></a>
            <div class="logo-text">
                Find free and low-cost health <br> services at trusted stores near you.
            </div>
            <div class="search-content">
                <input type="text" class="search-txtbx" placeholder="Enter your zip code"  onkeyup="checkEnterPress(event, this.value)" id="zipcode_input" value="<?= $zip_code ?>"/>
                <a href="javascript:;" onclick="getZipCodeForSearch()" class="search-btn" ></a>
                <img src="<?= $baseUrl ?>/images/loader-mini.gif" width="24" height="24" hidden="" alt="">

            </div>
        </div>
    </div>
</header>
<div class="company-content">
    <div class="container">
        <i>Find health events from companies like</i>
        <div>
            <img src="<?= $baseUrl ?>/images/event-img1.png" alt="" />
            <img src="<?= $baseUrl ?>/images/event-img2.png" alt="" />
            <img src="<?= $baseUrl ?>/images/event-img3.png" alt="" />
            <img src="<?= $baseUrl ?>/images/event-img4.png" alt="" />
            <img src="<?= $baseUrl ?>/images/event-img5.png" alt="" />
            <img src="<?= $baseUrl ?>/images/event-img6.png" alt="" />
            <img src="<?= $baseUrl ?>/images/event-img7.png" alt="" />
        </div>
    </div>
</div>
<div class="container">
    <h1 class="health-h">Health comes to your local store</h1>
    <div class="row">
        <div class="col-lg-4 col-md-4 col-sm-4">
            <div class="health-box">
                <span><img src="<?= $baseUrl ?>/images/map.png" alt="" /></span>
                More access to   <br />quality health care
            </div>
            <p class="custom-text">
                Who says health care has to be so complex? Health clinics are now open in 
                many of your favorite local stores — and the doctor is ready to see you.  
                Take advantage of a wide range of free and low-cost health services right 
                in the heart of your community.
            </p>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4">
            <div class="health-box">
                <span><img src="<?= $baseUrl ?>/images/timmer.png" alt="" /></span>
                Save money and time  <br />with ease
            </div>
            <p class="custom-text">
                Not only are many health services available for free, but it’s as easy as 
                walking into a store you already know and trust. Combine it with a shopping 
                trip for prescriptions or groceries and double-up on the convenience. 
            </p>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4">
            <div class="health-box">
                <span><img src="<?= $baseUrl ?>/images/heart.png" alt="" class="margin-t" /></span>
                Keep your whole <br />family healthy!
            </div>
            <p class="custom-text">
                Calling all patients, young and young-at-heart!  Health services include everything 
                from checkups, health screenings, and shots to preventative care and support for chronic 
                conditions. Everyone is welcome and anyone can benefit! 
            </p>
        </div>
    </div>
    <h1 class="from-h">Regular health care shouldn’t be a luxury</h1>
    <div class="row">
        <div class="col-lg-7 col-md-7 col-sm-7">
            <div class="health-care-text">
                <h2 class="heading-blue">200,000+ events and growing everyday<br>
                    40, 000+ locations across the U.S.
                </h2>
                <span>
                    It’s no secret that millions of Americans are without health insurance, with millions more underinsured. Even with good coverage, you may find it difficult to afford doctor’s co-pays and the prescriptions you need.
                </span>

                <span>
                    It’s time to take your heath into your own hands!
                </span>

                <span>
                    HealthEventsLive.com gives you comprehensive, up-to-date listings of free health screenings and low-cost health services at trusted stores near you.  Check HealthEventsLive.com and keep your health in check!
                </span>
            </div>

        </div>
        <div class="col-lg-5 col-md-5 col-sm-5">
            <div class="debate-txt">

                <span>Heart health</span>
                <span>Diabetes</span>
                <span>Immunizations & vaccinations</span>
                <span>Lung health</span>
                <span>Wellness & diet</span>
                <span>Women’s health</span>
                <span>Senior health</span>
                <span>Cancer screenings</span>
                <span>Mental health</span>
                <span>And more …</span>
                <a href="#home"><img src="<?= $baseUrl ?>/images/search-btn.png" alt="" />Find events near me!</a>
            </div>
        </div>
    </div>
</div>
