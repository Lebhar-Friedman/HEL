<?php

use yii\helpers\BaseUrl;

$this->registerCssFile('@web/css/results.css');

if ($company['logo'] === NULL || $company['logo'] === '' || !isset($company['logo'])) {

    $img_url = BaseUrl::base() . '/images/upload-logo.png';
} else {
    $img_url = IMG_URL . $company['logo'];
}
?>
<?php if (empty($company)) { ?>
    <p class="text-center"><b>Record Not found</b></p>    
    <?php
} else {
    ?>
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-5">
                <div class="search-result-content">
                    <div class="search-nav">
                        <h1>Search <a href="#" class="nav-cros"><img src="images/crose-btn.png" alt="" /></a></h1>
                        <div class="zip-code">
                            <span><b>Zip Code</b></span>
                            <div><input type="text" class="zip-textbox" value="94903" /></div>
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
                            <input type="checkbox"  />Diabetes Care
                        </div>
                        <div class="filter-box active">
                            <input type="checkbox"  checked="checked" />Diabetes Care
                        </div>
                        <div class="filter-box">
                            <input type="checkbox"  />Diabetes Care
                        </div>
                        <div class="filter-box">
                            <input type="checkbox"  />Diabetes Care
                        </div>
                        <div class="filter-box">
                            <input type="checkbox"  />Diabetes Care
                        </div>
                        <div class="filter-box">
                            <input type="checkbox"  />Diabetes Care
                        </div>
                        <div class="filter-box">
                            <input type="checkbox"  />Diabetes Care
                        </div>
                        <div><a href="#" class="go-btn">GO</a></div>
                    </div>
                    <div class="add-box"><img src="images/result-img7.png" alt="" /></div>

                </div>
            </div>
            <div class="col-lg-8 col-md-8 col-sm-7">
                <div class="event-near event-margin-r">
                    <div class="row">
                        <div class="col-lg-8 col-md-8 col-sm-8">
                            <h1><a class="search-filter" href=""><img src="images/filter-btn.png" alt="" /></a> Events <span>(103 results)</span> </h1>
                            <i>Diabetes Care, Flu Shots</i>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4">
                            <div class="event-img-box">
                                <img src="images/event-img.png" alt="" />
                            </div>
                        </div>
                    </div>

                </div>
                <div class="event-multi-service">
                    <h1>Immunizations/Vaccinationes</h1>
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
                    <div class="event-location-text">
                        <img src="images/result-img1.png" alt="" /> 1.2 m
                    </div>
                </div>
                <div class="event-multi-service">
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
                        <div class="table-cust">
                            <i>HPV</i>
                        </div>
                    </div>
                    <div class="event-location-text ">
                        <img src="images/result-img1.png" alt="" /> 4.2 m
                    </div>
                </div>
                <div class="event-multi-service">
                    <h1>Immunizations/Vaccinationes</h1>
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
                    <div class="event-location-text">
                        <img src="images/result-img1.png" alt="" /> 1.2 m
                    </div>
                </div>
                <div class="event-multi-service">
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
                        <div class="table-cust">
                            <i>HPV</i>
                        </div>
                    </div>
                    <div class="event-location-text ">
                        <img src="images/result-img1.png" alt="" /> 4.2 m
                    </div>
                </div>
                <div class="event-multi-service">
                    <h1>Immunizations/Vaccinationes</h1>
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
                    <div class="event-location-text">
                        <img src="images/result-img1.png" alt="" /> 1.2 m
                    </div>
                </div>
                <div class="event-multi-service mobile-border-none">
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
                        <div class="table-cust">
                            <i>HPV</i>
                        </div>
                    </div>
                    <div class="event-location-text ">
                        <img src="images/result-img1.png" alt="" /> 4.2 m
                    </div>
                </div>

            </div>
        </div>

    </div>
<?php } ?>