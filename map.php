<!DOCTYPE html>
<html>
<head>
  <title>Interactive visualization  of weekly and daily change in Covid-19 cases by country</title>
  <link rel="shortcut icon" href="favicon.png">
  <link rel="stylesheet" href="./lib/css//bootstrap.min.css?v=<?php echo time();?>">
  <link rel="stylesheet" href="./css/dashboard.css?v=<?php echo time();?>">
  <link rel="stylesheet" href="./css/dc.css?v=<?php echo time();?>">
  <link rel="stylesheet" href="./css/custom.css?v=<?php echo time();?>">
</head>
<body class="application">

  <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container-fluid">
      <div class="navbar-header">
        <a class="navbar-brand" href="./">Interactive visualization  of weekly and daily change in Covid-19 cases by country</a>
      </div>
    </div>
  </div>
  <div class="container-fluid">
    <div class="row">
      <div class="col-sm-6">
        <div class="row">
          <!-- Daily change in New Cases & Deaths  --> 
          <div class="col-sm-12">
            <div class="chart-wrapper">
              <div id="row_for_width" class="chart-title">
                Daily change in New Cases & Deaths  
              </div>
              <div class="chart-stage">
                <div id="covid19-daily"></div>
              </div>
              <div class="row">
                <div id="daily-volume-chart" style="width:100%;">
                </div>
                <p class="muted pull-right" style="margin-right: 15px;">select a time range to zoom in</p>
              </div>
            </div>
          </div>
          <!-- Daily change in New Cases & Deaths  --> 
          <!-- Weekly change in New Cases & Deaths  --> 
          <div class="col-sm-12">
            <div class="chart-wrapper">
              <div class="chart-title">
                Weekly change in New Cases & Deaths 
              </div>
              <div class="chart-stage">
                <div id="covid19-weekly"></div>
              </div>
            </div>
          </div>
          <!-- Weekly change in New Cases & Deaths  --> 
          <div class="col-sm-6">
            <div class="row">
            </div>
            <div class="row">
            </div>
          </div>
        </div>
      </div>
      <div class="col-sm-2">
        <div class="row">
          <!-- Country Province -->  
          <div class="col-sm-12">
            <div class="chart-wrapper">
              <div class="chart-title">
                Country
              </div>
              <div class="chart-stage">
                <div id="country"></div>
                <div id="country-axis"></div>
              </div>
            </div>
          </div>
          <!-- Country Province -->  
        </div>
      </div>
      <div class="col-sm-4">
        <div class="row">
          <!-- counts -->  
          <div class="col-sm-12"> 
            <div class="chart-wrapper">
              <div class="chart-title">
                Count
              </div>
              <div class="chart-stage ">
                <div class="text-dark font-weight-bold">
                Total Cases: <span id="filterCases"></span><br>
                Total Deaths: <span id="filterDeaths"></span></span>
                </div>
              </div>
            </div>
          </div>
          <!-- counts -->  
        </div>
        <div class="row">
          <!-- Map -->  
          <div class="col-sm-12"> 
            <div class="chart-wrapper">
              <div class="chart-title">
                Map
              </div>
              <div class="chart-stage">
                <div id="worldmap" style="width: 700px; height: 380px"></div>
              </div>
            </div>
          </div>
          <!-- Map -->  
        </div>
        
      </div>
    </div>
  </div>
  <hr>
  <script src="./js/jquery-3.4.1.min.js?v=<?php echo time();?>"></script>
  <script src="./js/crossfilter.js?v=<?php echo time();?>"></script>
  <script src="./js/d3.js?v=<?php echo time();?>"></script>
  <script src="./js/dc.js?v=<?php echo time();?>"></script>
  <script src='./js/drawcharts.js?v=<?php echo time();?>' type='text/javascript'></script>
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