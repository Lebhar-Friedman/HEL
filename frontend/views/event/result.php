<?php $this->registerCssFile('@web/css/results.css'); ?>
<?php $img_url= yii\helpers\BaseUrl::base().'/images/'; ?>

<!--<body class="reult-body">-->
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-5">
                <div class="search-result-content">
                    <div class="search-nav">
                        <h1>Search <a href="#" class="nav-cros"><img src="<?= $img_url ?>crose-btn.png" alt="" /></a></h1>
                        <div class="zip-code">
                            <span><b>Zip Code</b></span>
                            <div><input type="text" class="zip-textbox" value="<?= $zip_code ?>" /></div>
                        </div>
                        <div class="zip-code">
                            <span><b>Keyword</b> (optional)</span>
                            <div class="optional">
                                <div class="full-shot">Flu shots <a href="#">X</a></div>
                            </div>
                        </div>
                        <div class="zip-code">
                            <span><b>Sort By</b></span>
                            <div>
                                <select  class="zip-textbox">
                                    <option>Closest</option>
                                </select>
                            </div>
                        </div>
                        <div><a href="#" class="go-btn">GO</a></div>
                        <h1>Filters</h1>
                        <div class="filter-box">
                            <span></span>Diabetes Care
                        </div>
                        <div class="filter-box active">
                            <span></span>Diabetes Care
                        </div>
                        <div class="filter-box">
                            <span></span>Diabetes Care
                        </div>
                        <div class="filter-box">
                            <span></span>Diabetes Care
                        </div>
                        <div class="filter-box">
                            <span></span>Diabetes Care
                        </div>
                        <div class="filter-box">
                            <span></span>Diabetes Care
                        </div>
                        <div class="filter-box">
                            <span></span>Diabetes Care
                        </div>
                        <div><a href="#" class="go-btn">GO</a></div>
                    </div>
                    <div class="add-box"><img src="<?= $img_url ?>result-img7.png" alt="" /></div>

                </div>
            </div>
            <div class="col-lg-8 col-md-8 col-sm-7">
                <div class="event-near">
                    <h1>Events near <?= $zip_code ?> <span>(by distance)</span> 
                    <a class="search-filter" href=""><img src="<?= $img_url ?>filter-btn.png" alt="" /></a></h1>
                    <i>Heart Health, Flu Shots</i>
                </div>
                <div class="multi-service">
                    <h1>Multiple Services</h1>
                    <h2>Jun 1 - 10</h2>
                    <span>FREE</span>
                    <div class="clearfix">
                        <div class="table-cust">
                            <i>Flu Shots</i>
                            <i>Meningitis</i>
                        </div>
                        <div class="table-cust">
                            <i>Hepititis A</i>
                            <i>MMR</i>
                        </div>
                        <div class="table-cust">
                            <i>Hepititis B</i>
                            <i>Pnumonia</i>
                        </div>
                        <div class="table-cust">
                            <i>HPV</i>
                            <i>Shingles</i>
                        </div>

                    </div>
                    <div class="location-text">
                        <img src="images/result-img.png" alt="" />
                        <div class="text">10 locations</div>
                        <img src="images/result-img1.png" alt="" /> 1.2 m
                    </div>
                </div>
                <div class="multi-service mobile-border-none">
                    <h1>Vaccination Screenings</h1>
                    <h2>Jun 1 - 10</h2>
                    <span>$25 - $50</span>
                    <div class="clearfix">
                        <div class="table-cust">
                            <i>Flu Shots</i>
                        </div>
                        <div class="table-cust">
                            <i>Hepititis A</i>
                        </div>
                        <div class="table-cust">
                            <i>Hepititis B</i>
                        </div>

                    </div>
                    <div class="location-text ">
                        <img src="images/result-img2.png" alt="" />
                        <div class="text">2 locations</div>
                        <img src="images/result-img1.png" alt="" /> 1.7 m
                    </div>
                </div>
                <div class="map-content">
                    <img src="images/result-img3.png" alt="" />
                    <a href="#" class="view-all-btn">View all event locations</a>
                </div>
                <div class="email-content">
                    <div class="row">
                        <div class="col-lg-6 col-md-8">
                            <h1>Alert me when more health events like this get added!</h1>
                            <div class="email-conatiner">
                                <input type="text" class="email-textbox" placeholder="Email" />
                                <input type="submit" value="Go" class="submitbtn" />
                            </div>
                        </div>
                    </div>

                </div>
                <div class="event-near">
                    <h1>More health events</h1>
                </div>
                <div class="multi-service">
                    <h1>Multiple Services</h1>
                    <h2>Jun 1 - 10</h2>
                    <span>FREE</span>
                    <div class="clearfix">
                        <div class="table-cust">
                            <i>Flu Shots</i>
                            <i>Meningitis</i>
                        </div>
                        <div class="table-cust">
                            <i>Hepititis A</i>
                            <i>MMR</i>
                        </div>
                        <div class="table-cust">
                            <i>Hepititis B</i>
                            <i>Pnumonia</i>
                        </div>
                        <div class="table-cust">
                            <i>HPV</i>
                            <i>Shingles</i>
                        </div>

                    </div>
                    <div class="location-text">
                        <img src="images/result-img4.png" alt="" />
                        <div class="text">10 locations</div>
                        <img src="images/result-img1.png" alt="" /> 1.2 m
                    </div>
                </div>
                <div class="multi-service">
                    <h1>Vaccination Screenings</h1>
                    <h2>Jun 1 - 10</h2>
                    <span>$25 - $50</span>
                    <div class="clearfix">
                        <div class="table-cust">
                            <i>Flu Shots</i>
                        </div>
                        <div class="table-cust">
                            <i>Hepititis A</i>
                        </div>
                        <div class="table-cust">
                            <i>Hepititis B</i>
                        </div>

                    </div>
                    <div class="location-text">
                        <img src="images/result-img5.png" alt="" />
                        <div class="text">2 locations</div>
                        <img src="images/result-img1.png" alt="" /> 1.7 m
                    </div>
                </div>
            </div>
       	</div>
        <div class="row">
            <div class="col-lg-1 col-md-1"></div>
            <div class="col-lg-10 col-md-10">
                <div class="add-box2">
                    <img src="images/result-img6.png" alt=""  />
                </div>
            </div>
        </div>
    </div>

