<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="COVID-19/coronavirus  effected data visualization">
    <meta name="robots" content="index, follow">
    <title>Interactive visualization  of weekly and daily change in Covid-19 cases by country</title>
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
                        <h5 class="text-dark font-weight-bold mb-2">Interactive visualization of 
                        weekly and daily change in covid-19 cases by country
                        <br><br>
                        <span class="text-danger font-weight-bold"> Cases: <span id="filterCases"></span> &nbsp; &nbsp;  Deaths: <span id="filterDeaths"></span> </span>
                       </h5>
                        <h5 class="text-danger font-weight-bold" >
                        
                         </h5>
                        <span style="font-size: 10px;"><a href="https://ourworldindata.org/coronavirus-source-data" target="black">[Data Source]</a>
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
                                            <div id="row_for_width" class="d-flex align-items-center justify-content-between">
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
            <div style="float: right;"> 
              <a href="https://github.com/smbasha47/covid19-data-visualization" target="blank" >
              <svg class="octicon octicon-mark-github v-align-middle" height="32" viewBox="0 0 16 16" version="1.1" width="32" aria-hidden="true"><path fill-rule="evenodd" d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82.64-.18 1.32-.27 2-.27.68 0 1.36.09 2 .27 1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0 .21.15.46.55.38A8.013 8.013 0 0016 8c0-4.42-3.58-8-8-8z"></path></svg>
              </a> </div>
              <div style="float: right;"> LICENSE: Apache 2.0</div>
            <div style="float: left;"> Contributors: Mahaboob Basha, Srini PNV, Nithin Raju Devaraju </div>
            <div style="width: 250px;margin:0 auto;">Copyright &copy; 2020 . All rights reserved.</div>
            <div style="clear: both;"></div>
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