<style>

.axis {
  font: 10px sans-serif;
  -webkit-user-select: none;
  -moz-user-select: none;
  user-select: none;
}

.axis .domain {
  fill: none;
  stroke: #000;
  stroke-opacity: .3;
  stroke-width: 10px;
  stroke-linecap: round;
}

.axis .halo {
  fill: none;
  stroke: #ddd;
  stroke-width: 8px;
  stroke-linecap: round;
}

.slider .handle {
  fill: #fff;
  stroke: #000;
  stroke-opacity: .5;
  stroke-width: 1.25px;
  pointer-events: none;
}

</style>
<body>
<script src="http://d3js.org/d3.v3.min.js"></script>
<script>

var margin = {top: 200, right: 50, bottom: 200, left: 50},
    width = 960 - margin.left - margin.right,
    height = 500 - margin.bottom - margin.top;

var x = d3.scale.linear()
    .domain([0, 180])
    .range([0, width])
    .clamp(true);

var brush = d3.svg.brush()
    .x(x)
    .extent([0, 0])
    .on("brush", brushed);

var svg = d3.select("body").append("svg")
    .attr("width", width + margin.left + margin.right)
    .attr("height", height + margin.top + margin.bottom)
  .append("g")
    .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

svg.append("g")
    .attr("class", "x axis")
    .attr("transform", "translate(0," + height / 2 + ")")
    .call(d3.svg.axis()
      .scale(x)
      .orient("bottom")
      .tickFormat(function(d) { return d + "°"; })
      .tickSize(0)
      .tickPadding(12))
  .select(".domain")
  .select(function() { return this.parentNode.appendChild(this.cloneNode(true)); })
    .attr("class", "halo");

var slider = svg.append("g")
    .attr("class", "slider")
    .call(brush);

slider.selectAll(".extent,.resize")
    .remove();

slider.select(".background")
    .attr("height", height);

var handle = slider.append("circle")
    .attr("class", "handle")
    .attr("transform", "translate(0," + height / 2 + ")")
    .attr("r", 9);

slider
    .call(brush.event)
  .transition() // gratuitous intro!
    .duration(750)
    .call(brush.extent([70, 70]))
    .call(brush.event);

function brushed() {
  var value = brush.extent()[0];

  if (d3.event.sourceEvent) { // not a programmatic event
    value = x.invert(d3.mouse(this)[0]);
    brush.extent([value, value]);
  }

  handle.attr("cx", x(value));
  d3.select("body").style("background-color", d3.hsl(value, .8, .8));
}

</script>
