dc.config.defaultColors(d3.schemeSet1);

// const dateFormatSpecifier = '%m/%d/%Y';
const dateFormatSpecifier = '%Y-%m-%d';
const dateFormat = d3.timeFormat(dateFormatSpecifier);
const formatWeek = d3.timeFormat("%b %d");
const fullDateFormat = d3.timeFormat("%B %d %Y");
const dateFormatParser = d3.timeParse(dateFormatSpecifier);
const numberFormat = d3.format('.2f');
var country = new dc.RowChart("#country");
const covid19Daily = new dc.LineChart('#covid19-daily');
const covid19Weekly = new dc.LineChart('#covid19-weekly');
const dailyVolumeChart = new dc.BarChart('#daily-volume-chart');
const weeklyVolumeChart = new dc.BarChart('#weekly-volume-chart');
var mapChart = new dc.GeoChoroplethChart("#worldmap");
const minDate= new Date(2020, 0, 1), maxDate= new Date(2020, 03, 05);
const ordinalColors = ['#e41a1c','#377eb8','#4daf4a','#984ea3','#ff7f00','#ffff33','#a65628'];
var dynamic_for_width = document.getElementById("row_for_width").offsetWidth;
var max = 0
var ndx, counryDim, countryGroup, moveDays, indexAvgByDayGroup, volumeByDayGroup,
indexAvgByDayDeathsGroup, moveWeeks, volumeByWeeksGroup, indexAvgByWeeksGroup,
indexAvgByWeeksDeathsGroup,
    dayNewCasesGroupData, dayNewDeathsGroupData,
    weekNewCasesGroupData, weekNewDeathsGroupData;

Promise.all([d3.json("./data/worldgeo.json"), d3.csv("https://covid.ourworldindata.org/data/ecdc/full_data.csv")])
    .then(function([worldgeojson, data]) {

        data = data.filter(function(d) {
            return d.location != "World";
        });

        data.forEach(function(d) {
            d.dd = dateFormatParser(d.date);
            d.day = d3.timeDay(d.dd);
            d.week = d3.timeWeek(d.dd);
            d.new_cases = +d.new_cases;
            d.new_deaths = +d.new_deaths;
            d.total_cases = +d.total_cases;
            d.total_deaths = +d.total_deaths;
            d.pos = {key:d.location};
        });

        updateDimAndGroups(data, worldgeojson);

        var maxDate = counryDim.top(1)[0]['dd'];
        $("#lastUpdated").html(fullDateFormat(maxDate));

        drawCountryRowChart();
        drawDailyChart();
        drawWeeklyChart();
        drawWorldMap(worldgeojson);

        dc.renderAll();
        updateCounts();
  });

function updateCounts(){
    let filterData = ndx.allFiltered();
    let filterNoOfCases = 0 ;
    let filterNoOfDeaths = 0;
    filterData.forEach(function(d){ filterNoOfCases +=d.new_cases; });
    filterData.forEach(function(d){ filterNoOfDeaths +=d.new_deaths; });
    $("#filterCases").html(filterNoOfCases);
    $("#filterDeaths").html(filterNoOfDeaths);
}

function updateDimAndGroups(data, geojson){
    
    ndx = crossfilter(data);

    counryDim = ndx.dimension(function(d) { return d.location; });
    countryGroup = counryDim.group().reduceSum(function(d) {return d.new_cases;});

    moveDays = ndx.dimension(d => d.day);
    volumeByDayGroup = moveDays.group().reduceSum(d => d.new_cases);
    indexAvgByDayGroup = moveDays.group().reduce(
        (p, v) => {
            ++p.days;
            p.total += v.new_cases;
            p.g_total += v.total_cases;
            p.type = "newCases";
            return p;
        },
        (p, v) => {
            --p.days;
            p.total -= v.new_cases;
            p.g_total -= v.total_cases;
            p.avg = p.days ? Math.round(p.total / p.days) : 0;
            p.type = "newCases";
            return p;
        },
        () => ({days: 0, total: 0, g_total:0, type:"newCases"})
    );
     indexAvgByDayDeathsGroup = moveDays.group().reduce(
        (p, v) => {
            ++p.days;
            p.total += v.new_deaths;
            p.g_total += v.total_deaths;
            p.type = "newDeaths";
            return p;
        },
        (p, v) => {
            --p.days;
            p.total -= v.new_deaths;
            p.g_total -= v.total_deaths;
            p.type = "newDeaths";
            return p;
        },
        () => ({days: 0, total: 0, g_total:0, type: "newDeaths"})
    );
    dayNewCasesGroupData = indexAvgByDayGroup.all();
    dayNewDeathsGroupData = indexAvgByDayDeathsGroup.all();
    // console.log(dayNewCasesGroupData, dayNewDeathsGroupData);
     moveWeeks = ndx.dimension(d => d.week);
     volumeByWeeksGroup = moveWeeks.group().reduceSum(d => d.new_cases);
     indexAvgByWeeksGroup = moveWeeks.group().reduce(
        (p, v) => {
            ++p.weeks;
            p.total += v.new_cases;
            p.g_total += v.total_cases;
            p.type = "newCases";
            return p;
        },
        (p, v) => {
            --p.weeks;
            p.total -= v.new_cases;
            p.g_total -= v.total_cases;
            p.type = "newCases";
            return p;
        },
        () => ({weeks: 0, total: 0, g_total: 0, type:"newCases"})
    );
    indexAvgByWeeksDeathsGroup = moveWeeks.group().reduce(
        (p, v) => {
            ++p.weeks;
            p.total += v.new_deaths;
            p.g_total += v.total_deaths;
            p.type = "newDeaths";
            return p;
        },
        (p, v) => {
            --p.weeks;
            p.total -= v.new_deaths;
            p.g_total -= v.total_deaths;
            p.type = "newDeaths";
            return p;
        },
        () => ({weeks: 0, total: 0, g_total: 0, type:"newDeaths"})
    );
    weekNewCasesGroupData = indexAvgByWeeksGroup.all();
    weekNewDeathsGroupData = indexAvgByWeeksDeathsGroup.all();
    

}

function drawCountryRowChart(){
    country
            .height( 5000 )
            // .width( 800 )
            .margins({ left: 10, top: 0, right: 10, bottom: 0 })
            .x(d3.scaleLinear())
            .elasticX(true)
            .dimension(counryDim)
            .group(countryGroup)
            .on("filtered", function (chart) {
               updateCounts();
           })
            .controlsUseVisibility( true )
            .render();
}

function drawDailyChart(){
    dailyVolumeChart.width(dynamic_for_width)
            .height(40)
            .margins({top: 0, right: 35, bottom: 20, left: 40})
            .dimension(moveDays)
            .group(volumeByDayGroup)
            .centerBar(true)
            .gap(1)
            .x(d3.scaleTime().domain([minDate, maxDate]))
            .round(d3.timeDay.round)
            .alwaysUseRounding(true)
            .xUnits(d3.timeDays);
            covid19Daily /* dc.lineChart('#monthly-move-chart', 'chartGroup') */
            .renderArea(true)
            // .width(990)
            .height(200)
            .transitionDuration(1000)
            .margins({top: 30, right: 50, bottom: 25, left: 40})
            .dimension(moveDays)
            .mouseZoomable(true)
            .on("filtered", function (chart) {
                updateCounts();
            })
        // Specify a "range chart" to link its brush extent with the zoom of the current "focus chart".
            .rangeChart(dailyVolumeChart)
            .x(d3.scaleTime().domain([minDate, maxDate]))
            .round(d3.timeDay.round)
            .xUnits(d3.timeDays)
            .elasticY(true)
            .renderDataPoints(true)
            .ordinalColors(ordinalColors)
            // .y(d3.scaleLog().clamp(true).domain([.5, 1000000]))
            .renderHorizontalGridLines(true)
            //##### Legend

            // Position the legend relative to the chart origin and specify items' height and separation.
            .legend(new dc.Legend().horizontal(true).x(500).y(10).gap(10))
            .brushOn(false)
            // Add the base layer of the stack with group. The second parameter specifies a series name for use in the
            // legend.
            // The `.valueAccessor` will be used for the base layer
            .group(indexAvgByDayDeathsGroup, 'Deaths')
            .valueAccessor(d => d.value.total)
            .stack(indexAvgByDayGroup, 'New cases', d => d.value.total)
            //.y(d3.scaleSymlog())
            // Title can be called by any stack layer.
            .title((d, i) => {
                let data = d.value.type=="newCases"? dayNewCasesGroupData:dayNewDeathsGroupData;
                let value = d.value.total ? d.value.total : d.value;
                if (isNaN(value)) {
                    value = 0;
                }
                let per = 0;
                if(i>0){
                    let p = data[i];
                    try{
                        per = ((d.value.total - p.value.total)/p.value.total) * 100;
                    }catch(e){

                    }
                }
                return `${dateFormat(d.key)}\n${value}\n${numberFormat(per)}%`;
            })
            .yAxis().tickFormat(d3.format('.2s'));
}

function drawWeeklyChart(){
    weeklyVolumeChart.width(dynamic_for_width)
            .height(40)
            .margins({top: 0, right: 35, bottom: 20, left: 40})
            .dimension(moveWeeks)
            .group(volumeByWeeksGroup)
            .centerBar(true)
            .gap(1)
            .x(d3.scaleTime().domain([minDate, maxDate]))
            .round(d3.timeWeek.round)
            .alwaysUseRounding(true)
            .xUnits(d3.timeWeek);
    covid19Weekly /* dc.lineChart('#monthly-move-chart', 'chartGroup') */
            .renderArea(true)
            // .width(990)
            .height(200)
            .transitionDuration(1000)
            .margins({top: 30, right: 50, bottom: 25, left: 40})
            .dimension(moveWeeks)
            .mouseZoomable(true)
            .renderDataPoints(true)
            .on("filtered", function (chart) {
                updateCounts();
            })
        // Specify a "range chart" to link its brush extent with the zoom of the current "focus chart".
            .rangeChart(weeklyVolumeChart)
            .x(d3.scaleTime().domain([minDate, maxDate]))
            .round(d3.timeWeek.round)
            .xUnits(d3.timeWeek)
            .elasticY(true)
            .ordinalColors(ordinalColors)
            .renderHorizontalGridLines(true)
        //##### Legend

            // Position the legend relative to the chart origin and specify items' height and separation.
            .legend(new dc.Legend().horizontal(true).x(500).y(10).gap(10))
            .brushOn(false)
            // Add the base layer of the stack with group. The second parameter specifies a series name for use in the
            // legend.
            // The `.valueAccessor` will be used for the base layer
            .group(indexAvgByWeeksDeathsGroup, 'Deaths')
            .valueAccessor(d => d.value.total)
            .stack(indexAvgByWeeksGroup, 'New cases', d => d.value.total)
            // Title can be called by any stack layer.
            .title((d, i)=> {
                let value = d.value.total ? d.value.total : d.value;
                let data = d.value.type=="newCases"? weekNewCasesGroupData:weekNewDeathsGroupData;
                if (isNaN(value)) {
                    value = 0;
                }
                let per = 0;
                if(i>0){
                    let p = data[i];
                    try{
                        per = ((d.value.total - p.value.total)/p.value.total) * 100;
                    }catch(e){

                    }
                }
                return `${formatWeek(d.key)}\n${value}\n${numberFormat(per)}%`;
            }).yAxis().tickFormat(d3.format('.2s'));
}

function drawWorldMap(worldgeojson) {
    
    var facilities = ndx.dimension(function(d) { return d.location; });
    var facilitiesGroup = facilities.group().reduceSum(function(d) { return d.new_cases;});  

    mapChart
       .dimension(facilities)
       .group(facilitiesGroup)
       .colors(d3.scaleQuantize().range(["#E2F2FF", "#C4E4FF", "#9ED2FF", "#81C5FF", "#6BBAFF",
             "#51AEFF", "#36A2FF", "#1E96FF", "#0089FF", "#0061B5"]))
       .colorDomain([0, 200])
       .colorCalculator(function (d) { return d ? mapChart.colors()(d) : '#ccc'; })
       .overlayGeoJson(worldgeojson.features, "countries", function (d) {
           return d.properties.name;
       })
   .projection(d3.geoAlbers())
   .valueAccessor(function(kv) {
    //    console.log(kv);
       return kv.value;
   })
       .title(function (d) {
           return "Country: " + d.key + "\nTotal Amount Raised: " + numberFormat(d.value ? d.value : 0);
       });

}