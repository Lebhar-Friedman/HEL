<?php
$baseUrl = Yii::$app->request->baseUrl;
$this->title = 'Health Events';
?>
<style>
    #homelayout-header{
        display: none;
    }
</style>
<header>
    <div class="container">
        <div class="signUp-btns clearfix">
            <?php
            if (Yii::$app->user->isGuest) {
                ?>
                <a href="<?= $baseUrl ?>/site/signup" class="border">Sign Up</a>
                <a href="<?= $baseUrl ?>/site/login">Log In</a>
            <?php } else {
                ?>
                <a href="<?= $baseUrl ?>/site/logout" class="active">Logout (<?= Yii::$app->user->identity->first_name ?>)</a>
            <?php } ?>
        </div>
        <div class="logo-container">
            <a href="<?= $baseUrl ?>"><img src="<?= Yii::$app->getHomeUrl(); ?>images/home-logo.png" alt="" /></a></a>
            <div class="logo-text">
                Find free and low cost health <br />services at trusted stores near you
            </div>
            <div class="search-content">
                <input type="text" class="search-txtbx" placeholder="Enter your zip code" value="<?= $zip_code ?>" onkeyup="checkEnterPress(event,this.value)" id="zipcode_input"/>
                <a href="javascript:;" onclick="getZipCodeForSearch()" class="search-btn"></a>
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
                <span><img src="<?= $baseUrl ?>/images/search-img.png" alt="" /></span>
                Search for health <br />services near you
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4">
            <div class="health-box">
                <span><img src="<?= $baseUrl ?>/images/timmer.png" alt="" /></span>
                Save time <br />& money
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4">
            <div class="health-box">
                <span><img src="<?= $baseUrl ?>/images/heart.png" alt="" class="margin-t" /></span>
                Keep your whole <br />family healthy!
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="consider-text">
                <span>Consider the convenience of being able to get a health screening on a weekend or during a lunch break, when you don’t necessarily have to worry about racing back to your job. Consider, too, that you can get your blood pressure tested at the same place you buy your groceries. Or, you can check up on your glucose levels while picking up a prescription. Two birds, one stone, no extra trips.</span>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="consider-text">
                Common health services are now more accessible and affordable than ever, and that means you don’t have to feel pressured to put them off any longer. But how do you find out which retailer is holding what health event when, especially when so many different places are participating?

                <div class="margin-t">That’s where Health Events Live can help!</div>
            </div>
        </div>

    </div>
    <h1 class="from-h">From allergies to osteoperosis</h1>
    <div class="row">
        <div class="col-lg-7 col-md-7 col-sm-7">
            <div class="health-care-text">
                <span>Health care is experiencing a huge shift, bringing clinical services to your local retailer.</span>

                <span>Drug stores, grocery stores, big box stores and even membership clubs now offer a wide range of health screenings, checkups and consultations, many of which are free.</span>

                <span>HealthEventsLive.com was developed to provide health-conscious consumers with comprehensive listings of every free and low-cost health service available at retail stores, hospitals, community centers and more — all in one place.
                </span>
            </div>
        </div>
        <div class="col-lg-5 col-md-5 col-sm-5">
            <div class="debate-txt">
                <span>Diabetes-related screenings</span>
                <span>Heart health events</span>
                <span>Cholesterol</span>
                <span>Nutrition</span>
                <span>Osteoporosis</span>
                <span>Vaccinations and immunizations</span>
                <span>Vision and hearing</span>
                <span>Senior health</span>
                <span>And much more!</span>
                <a href="#"><img src="<?= $baseUrl ?>/images/search-btn.png" alt="" />Find events near me!</a>
            </div>
        </div>
    </div>
</div>