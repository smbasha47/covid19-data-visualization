dc.config.defaultColors(d3.schemeSet1);
      
var data_count = new dc.DataCount('.dc-data-count');

function static_copy_group(group) {
    var all = group.all().map(kv => ({key: kv.key, value: kv.value}));
    return {
        all: function() {
            return all;
        }
    }
}
var country = new dc.RowChart("#country");
const covid19Daily = new dc.LineChart('#covid19-daily');
const covid19Weekly = new dc.LineChart('#covid19-weekly');
const dailyVolumeChart = new dc.BarChart('#daily-volume-chart');
const weeklyVolumeChart = new dc.BarChart('#weekly-volume-chart');
const minDate= new Date(2020, 0, 1), maxDate= new Date(2020, 03, 05);
const ordinalColors = ['#e41a1c','#377eb8','#4daf4a','#984ea3','#ff7f00','#ffff33','#a65628'];
var ndx;
var all;

function updateCounts(){
    let filterData = ndx.allFiltered();
    let allData = ndx.all();
    let filterNoOfCases = 0 ;
    let filterNoOfDeaths = 0;
    filterData.forEach(function(d){ filterNoOfCases +=d.new_cases; });
    filterData.forEach(function(d){ filterNoOfDeaths +=d.new_deaths; })
    $("#filterCases").html(filterNoOfCases);
    $("#filterDeaths").html(filterNoOfDeaths);
}

d3.csv('./data/full_data.csv').then(function(data) {
    // Since its a csv file we need to format the data a bit.
    const dateFormatSpecifier = '%m/%d/%Y';
    const dateFormat = d3.timeFormat(dateFormatSpecifier);
    const formatWeek = d3.timeFormat("%b %d");
    const fullDateFormat = d3.timeFormat("%B %d %Y");
    const dateFormatParser = d3.timeParse(dateFormatSpecifier);
    const numberFormat = d3.format('.2f');

    data.forEach(function(d) {
        d.dd = dateFormatParser(d.date);
        d.day = d3.timeDay(d.dd);
        d.week = d3.timeWeek(d.dd);
        d.new_cases = +d.new_cases;
        d.new_deaths = +d.new_deaths;
        d.total_cases = +d.total_cases;
        d.total_deaths = +d.total_deaths;
    });

     ndx = crossfilter(data);
     all = ndx.groupAll();

    data_count.crossfilter(ndx)
        .groupAll(all);
    
    var breathing_room = 0.05;
    var show_unfiltered = true;
    
    var dim = ndx.dimension(function(d) { return d.location; }),
        group = dim.group().reduceSum(function(d) {return d.new_cases;});
    
    var maxDate = dim.top(1)[0]['dd'];
    $("#lastUpdated").html(fullDateFormat(maxDate));
    const moveDays = ndx.dimension(d => d.day);
    const volumeByDayGroup = moveDays.group().reduceSum(d => d.new_cases);
    const indexAvgByDayGroup = moveDays.group().reduce(
        (p, v) => {
            ++p.days;
            p.total += v.new_cases;
            p.avg = Math.round(p.total / p.days);
            return p;
        },
        (p, v) => {
            --p.days;
            p.total -= v.new_cases;
            p.avg = p.days ? Math.round(p.total / p.days) : 0;
            return p;
        },
        () => ({days: 0, total: 0, avg: 0})
    );
    const indexAvgByDayDeathsGroup = moveDays.group().reduce(
        (p, v) => {
            ++p.days;
            p.total += v.new_deaths;
            p.avg = Math.round(p.total / p.days);
            return p;
        },
        (p, v) => {
            --p.days;
            p.total -= v.new_deaths;
            p.avg = p.days ? Math.round(p.total / p.days) : 0;
            return p;
        },
        () => ({days: 0, total: 0, avg: 0})
    );
    const moveWeeks = ndx.dimension(d => d.week);
    const volumeByWeeksGroup = moveWeeks.group().reduceSum(d => d.new_cases);
    const indexAvgByWeeksGroup = moveWeeks.group().reduce(
        (p, v) => {
            ++p.weeks;
            p.total += v.new_cases;
            p.avg = Math.round(p.total / p.weeks);
            return p;
        },
        (p, v) => {
            --p.weeks;
            p.total -= v.new_cases;
            p.avg = p.weeks ? Math.round(p.total / p.weeks) : 0;
            return p;
        },
        () => ({weeks: 0, total: 0, avg: 0})
    );
    const indexAvgByWeeksDeathsGroup = moveWeeks.group().reduce(
        (p, v) => {
            ++p.weeks;
            p.total += v.new_deaths;
            p.avg = Math.round(p.total / p.weeks);
            return p;
        },
        (p, v) => {
            --p.weeks;
            p.total -= v.new_deaths;
            p.avg = p.weeks ? Math.round(p.total / p.weeks) : 0;
            return p;
        },
        () => ({weeks: 0, total: 0, avg: 0})
    );
    
        country
            .height( 5000 )
            // .width( 800 )
            .margins({ left: 10, top: 0, right: 10, bottom: 0 })
            .x(d3.scaleLinear())
            .elasticX(true)
            .dimension(dim)
            .group(group)
            .on("filtered", function (chart) {
               updateCounts();
           })
            .controlsUseVisibility( true )
            .render();

        dailyVolumeChart.width(990)
            .height(40)
            .margins({top: 0, right: 50, bottom: 20, left: 40})
            .dimension(moveDays)
            .group(volumeByDayGroup)
            .centerBar(true)
            .gap(1)
            .x(d3.scaleTime().domain([minDate, maxDate]))
            .round(d3.timeDay.round)
            .alwaysUseRounding(true)
            .xUnits(d3.timeDays);
        
        weeklyVolumeChart.width(990)
            .height(40)
            .margins({top: 0, right: 50, bottom: 20, left: 40})
            .dimension(moveWeeks)
            .group(volumeByWeeksGroup)
            .centerBar(true)
            .gap(1)
            .x(d3.scaleTime().domain([minDate, maxDate]))
            .round(d3.timeWeek.round)
            .alwaysUseRounding(true)
            .xUnits(d3.timeWeek);

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
            .ordinalColors(ordinalColors)
            // .y(d3.scaleLog().clamp(true).domain([.5, 1000000]))
            .renderHorizontalGridLines(true)
        //##### Legend

            // Position the legend relative to the chart origin and specify items' height and separation.
            .legend(new dc.Legend().x(800).y(10).itemHeight(13).gap(5))
            .brushOn(false)
            // Add the base layer of the stack with group. The second parameter specifies a series name for use in the
            // legend.
            // The `.valueAccessor` will be used for the base layer
            .group(indexAvgByDayDeathsGroup, 'No of. Deaths')
            .valueAccessor(d => d.value.total)
            .stack(indexAvgByDayGroup, 'No of. Cases', d => d.value.total)
            // Title can be called by any stack layer.
            .title(d => {
                let value = d.value.total ? d.value.total : d.value;
                if (isNaN(value)) {
                    value = 0;
                }
                return `${dateFormat(d.key)}\n${numberFormat(value)}`;
            });
        covid19Weekly /* dc.lineChart('#monthly-move-chart', 'chartGroup') */
            .renderArea(true)
            // .width(990)
            .height(200)
            .transitionDuration(1000)
            .margins({top: 30, right: 50, bottom: 25, left: 40})
            .dimension(moveWeeks)
            .mouseZoomable(true)
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
            .legend(new dc.Legend().x(800).y(10).itemHeight(13).gap(5))
            .brushOn(false)
            // Add the base layer of the stack with group. The second parameter specifies a series name for use in the
            // legend.
            // The `.valueAccessor` will be used for the base layer
            .group(indexAvgByWeeksDeathsGroup, 'No of. Deaths')
            .valueAccessor(d => d.value.total)
            .stack(indexAvgByWeeksGroup, 'No of. Cases', d => d.value.total)
            // Title can be called by any stack layer.
            .title(d => {
                let value = d.value.total ? d.value.total : d.value;
                if (isNaN(value)) {
                    value = 0;
                }
                return `${formatWeek(d.key)}\n${numberFormat(value)}`;
            });

    function ydomain_from_child1(chart) {
        chart.y().domain([0, chart.children()[1].yAxisMax()]);
        chart.resizing(true);
    }

    dc.renderAll();
    updateCounts();
});