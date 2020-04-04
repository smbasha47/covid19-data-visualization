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
        <div class="navbar-right-content">
          <a href="https://ourworldindata.org/coronavirus-source-data" target="black">[Data Source]</a> <br />
          Last Updated on:  <span id="lastUpdated"></span>
        </div>
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
                <div id="covid19-daily">
                <div style="height: 20px;">
                  <span class="reset" style="display: none;">range: <span class="filter"></span></span>
                  <a class="reset" href="javascript:covid19Daily.filterAll();dailyVolumeChart.filterAll();dc.redrawAll();" style="float: right;display: none;">reset</a>
                </div>
                </div>
              </div>
              <div class="row">
                <div id="daily-volume-chart" style="width:100%;">
                </div>
                <p class="muted pull-right" style="margin-right: 60px;">select a time range to zoom in</p>
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
                <div id="covid19-weekly">
                <div  style="height: 20px;">
                  <span class="reset" style="display: none;">range: <span class="filter"></span></span>
                  <a class="reset" href="javascript:covid19Weekly.filterAll();weeklyVolumeChart.filterAll();dc.redrawAll();" style="display: none; float: right;">reset</a>
                </div>
                </div>
              </div>
              <div class="row">
                <div id="weekly-volume-chart" style="width:100%;">
                </div>
                <p class="muted pull-right" style="margin-right: 60px;">select a time range to zoom in</p>
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
                <a style="float:right;" href="javascript:dc.filterAll(); dc.redrawAll();">Reset All</a>
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
                <div id="worldmap" style="width: 700px; height: 540px"></div>
              </div>
            </div>
          </div>
          <!-- Map -->  
        </div>
        
      </div>
    </div>
  </div>
  <hr>
  <div class="container-fluid">
    <footer>
        <div style="float: right;"> 
          <a href="https://github.com/smbasha47/covid19-data-visualization" target="blank" >
            <svg class="octicon octicon-mark-github v-align-middle" height="24" viewBox="0 0 16 16" version="1.1" width="32" aria-hidden="true"><path fill-rule="evenodd" d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82.64-.18 1.32-.27 2-.27.68 0 1.36.09 2 .27 1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0 .21.15.46.55.38A8.013 8.013 0 0016 8c0-4.42-3.58-8-8-8z"></path></svg>
          </a> </div>
          <div style="float: right;"> LICENSE: Apache 2.0</div>
        <div style="float: left;"> Contributors: Mahaboob Basha, Srini PNV, Nithin Raju Devaraju </div>
        <div style="width: 250px;margin:0 auto;">Copyright &copy; 2020 . All rights reserved.</div>
        <div style="clear: both;"></div>
    </footer>
  </div>
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