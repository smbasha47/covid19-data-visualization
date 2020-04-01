<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="COVID-19/coronavirus  effected data visualization">
    <meta name="robots" content="index, follow">
    <title>Interactive Visualisation of Weekly and daily change in Covid-19 cases by country</title>
    <meta property="og:image" content="./images/home.png" />
    <!-- base:css -->
    <link href='./css/bootstrap.css?<?php echo time();?>' rel='stylesheet' type='text/css'>
    <link href='./css/dc.css?<?php echo time();?>' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="./css/materialdesignicons.min.css?<?php echo time();?>">
    <link rel="stylesheet" href="./css/vendor.bundle.base.css?<?php echo time();?>">
    <!-- endinject -->
    <!-- plugin css for this page -->
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="./css/style.css?<?php echo time();?>">
    <style type="text/css">
        div.dc-chart {
                   float: left;
               }
               #daily-volume-chart g.y, #weekly-volume-chart g.y {
                   display: none;
               }
       .dc-chart text, #country text{
             fill: black;
             font-family: 'Helvetica Neue', 'Noto Sans', 'Droid Sans', Helvetica, sans-serif;
           }
           .dc-chart {
             margin: 0;
             padding: 0;
           }
           div.dc-chart {
             float: none;
           }
           svg {
             display: block;
           }
           #country {
             overflow-y: auto;
             /* width: 20%; */
             height: 600px;
           }
         </style>
    <!-- endinject -->
    <script src='./js/d3.js?<?php echo time();?>' type='text/javascript'></script>
  <script src='./js/crossfilter.js?<?php echo time();?>' type='text/javascript'></script>
  <script src='./js/dc.js?<?php echo time();?>' type='text/javascript'></script>
  <script src='./js/jquery-3.4.1.min.js?<?php echo time();?>' type='text/javascript'></script>
  </head>
  <body>
    <div class="container-scroller">
        <div class="horizontal-menu">
            <nav class="navbar top-navbar col-lg-12 col-12 p-0">
                <div class="container-fluid">
                    <div class="navbar-menu-wrapper d-flex align-items-center justify-content-between">
                        <h3 class="text-dark font-weight-bold mb-2">COVID-19</h3>
                        <h5 class="text-danger font-weight-bold" >
                          Cases: <span id="filterCases"></span><br>
                          Deaths: <span id="filterDeaths"></span>
                         </h5>
                        <span><a href="https://ourworldindata.org/coronavirus-source-data" target="black">[Data Source]</a>
                          <br>
                          <h10 class="text-dark"> Last Updated on:  <span id="lastUpdated"></span> </h10>
                        </span>
                    </div>
                </div>
            </nav>
        </div>
    <!-- partial -->
		<div class="container-fluid page-body-wrapper">
			<div class="main-panel">
				<div class="content-wrapper">
                    <div class="row">
                        <div class="col-lg-4 stretch-card">
                            <div class="card">
								<div class="card-body">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <h3>Country</h3>
                                        <h5><a href="javascript:dc.filterAll(); dc.redrawAll();">Reset All</a></h5>
                                    </div>
									<section>
                                        <div id="country"></div>
                                        <div id="country-axis"></div>
                                      </section>
								</div>
							</div>
                        </div>
                        <div class="col-lg-8">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <h3>Daily</h3>
                                            </div>
                                            <div id="covid19-daily" style="width:100%;">
                                                <span class="reset" style="display: none;">range: <span class="filter"></span></span>
                                                <a class="reset" href="javascript:covid19Daily.filterAll();dailyVolumeChart.filterAll();dc.redrawAll();"
                                                  style="display: none;">reset</a>
                                                <div class="clearfix"></div>
                                              </div>
                                              <div class="row">
                                                <div id="daily-volume-chart" style="width:100%;">
                                                </div>
                                                <p class="muted pull-right" style="margin-right: 15px;">select a time range to zoom in</p>
                                              </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12 " style="margin-top: 15px !important;">
                                    <div class="card">
                                        <div class="card-body">
                                            <div id="covid19-weekly" style="width:100%;">
                                                <h5 class="chart-title">Weekly</h5>
                                                <span class="reset" style="display: none;">range: <span class="filter"></span></span>
                                                <a class="reset" href="javascript:covid19Weekly.filterAll();weeklyVolumeChart.filterAll();dc.redrawAll();"
                                                  style="display: none;">reset</a>
                                                <div class="clearfix"></div>
                                              </div>
                                              <div class="row">
                                                <div id="weekly-volume-chart">
                                                </div>
                                                <p class="muted pull-right" style="margin-right: 15px;">select a time range to zoom in</p>
                                              </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

					
				</div>
				<!-- content-wrapper ends -->
				<!-- partial:partials/_footer.html -->
				<footer class="footer">
          <div class="footer-wrap">
              <div class="w-100 clearfix">
                <span class="d-block text-center text-sm-left d-sm-inline-block">Copyright © 2020 . All rights reserved.</span>
              </div>
          </div>
        </footer>
				<!-- partial -->
			</div>
			<!-- main-panel ends -->
		</div>
		<!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <script src='./js/preparecharts.js?version=<?php echo time();?>' type='text/javascript'></script>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-162177579-1"></script>
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'UA-162177579-1');
    </script>
  </body>
</html>