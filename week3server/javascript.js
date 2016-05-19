(function(){
	var app = {
		init: function () {
			chart.createChart();
		}
	}
	var chart = {
		createChart : function () {
			var graphData =[];
			var lastDaySleepArray = [];
			var width = document.querySelector('body').offsetWidth-150;
    		var height = 600;

			$.getJSON("http://www.jaimiederijk.nl/webofthings/light.json",function (data) {
				var threshold=parseInt(document.querySelector('#threshold').innerHTML);
				

				data.forEach(function (item){
					if(item.time.substr(4,1)==0||item.time.substr(4,1)==1) {
						
						var dateTime = moment(item.date+item.time,"YYYY-MM-DDh:mm:ss a");
						if (item.light<threshold) {
							if (moment(dateTime,"YYYY-MM-DDh:mm:ss a").isAfter(moment().subtract(20,'hours'))) { // if time is in the last 20 hours
								lastDaySleepArray.push(dateTime);
							}
						};
						graphData.push(item);
					}

				})

				var sleepMax = moment.max(lastDaySleepArray);
				var sleepMin = moment.min(lastDaySleepArray);

				var sleepTime = sleepMax.diff(sleepMin,"hours")
				console.log (sleepTime);

				$.post("http://www.jaimiederijk.nl/webofthings/index.php?light="+sleepTime)
				var timeSlept = document.querySelector("#timeslept");
				timeSlept.innerHTML=sleepTime;

				//console.log(graphData);
				var xScale = d3.scale.ordinal()
					.domain(graphData.map(function(d) { return d.time; }))
					.rangeRoundBands([0, width], .2);

				var yScale = d3.scale.linear()
					.domain([0, d3.max(graphData, function(d){ return parseInt(d.light);})])
					.range([ height,0]);

				var xAxis = d3.svg.axis()
					.scale(xScale)
					.orient("bottom")

				var yAxis = d3.svg.axis()
					.scale(yScale)
					.orient("left")
					.tickFormat(function(d) { return d ; });	
					
				var svg = d3.select("#graph").append("svg")
					.attr("width", width)
					.attr("height", height)

				svg.append("g")
					.attr("class", "x axis")
					.attr("transform", "translate(0," + height + ")")
					.call(xAxis)
					.selectAll("text")
				        .style("text-anchor", "end")
			            .attr("dx", "-.8em")
			            .attr("dy", ".15em")
			            .attr("transform", "rotate(-65)" );

				svg.append("g")
					.attr("class", "y axis")
					.call(yAxis);

				svg.selectAll("text")
					.attr("fill","red" );		


				var bars = svg.selectAll(".bar")
					.data(graphData)
						.enter();
					bars.append("rect")
						.attr("class", "bar")
						.attr("fill", "#146b9d")
	        			.attr("x", function(d) { return xScale(d.time) })
	        			.attr("width",xScale.rangeBand())
	        			.attr("y", function(d) { return yScale(parseInt(d.light)); })         
	        			.attr("height", function(d) { return height - yScale(parseInt(d.light));});
	        })
     	}
    }

	app.init();
})();