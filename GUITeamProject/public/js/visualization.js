$(document).ready(function() {

	var current = null;

	var url = BASE_URL+"/getJSONCamp";

	/*
		When they try to delete a camp from the view
	*/
	$("#deleteCampForm").submit(function(e) {
		e.preventDefault();

		if (current) {
			$.post(
				BASE_URL + "/camps/"+current.id+"/remove/",
				{},
				function(data) {
					if (data.success == 'success') {
						window.location = BASE_URL + "/camps";
					} else {
						alert('Server error: ' + data.error);
					}
				}
			).fail(function() {
				alert("Ajax call failed [deleteCamp[camps]]");
			});
		}
	});

	/*
		When they try to edit a camp from the view
	*/
	$("#editCampForm").submit(function(e) {
		e.preventDefault();
		if (current) {
			var prevName = current.name;
			var prevLocat = current.location;
			var newName = $("#editCampName").val();
			var newLocat = $("#editCampLocation").val();
			if (newName.length) { // if length > 0
				prevName = newName;
			}
			if (newLocat.length) {
				prevLocat = newLocat;
			}
			$.post(
				BASE_URL + "/camps/"+current.id+"/update/LocationAndName/",
				{locate: prevLocat, nme: prevName},
				function(data) {
					if (data.success == 'success') {
						$("#campNameList").text(prevName);
						$("#campLocationList").text(prevLocat);
					} else {
						alert('Server error: ' + data.error);
					}
				}
			).fail(function() {
				alert("Ajax call failed [editCamps[camps]]");
			});
		}
	});

	$("#createActivityFromCamps").submit(function(e) {
		e.preventDefault();
		if (current) {
			var nme = $("#activityType").val();
			var desc = $("#activityDescription").val();
			if (nme.length && desc.length) {
				//if they have fields that are filled out...
				$.post(
					BASE_URL + "/camps/"+current.id+"/add/Activity/",
					{title: nme, description: desc},
					function(data) {
						var myObj = JSON.parse(data);
						if (myObj.success == 'success') {
							window.location = BASE_URL + "/camps";
						} else {
							alert("Server error: " + myObj.error);
						}
					}
				).fail(function() {
					alert("Ajax call failed [createActivity[camps]]")
				});
			}
		}
	});

	var margin = {top: 30, right: 20, bottom: 30, left: 20},
		width = 960,
		barHeight = 20,
		barWidth = (width - margin.left - margin.right) * 0.8;

	var i = 0,
		duration = 400,
		root;

	var diagonal = d3.linkHorizontal()
		.x(function(d) { return d.y; })
		.y(function(d) { return d.x; });

	var svg = d3.select("#campsHolder").append("svg")
		.attr("width", width) // + margin.left + margin.right)
		.attr("height", 500)
		.append("g")
		.attr("transform", "translate(" + margin.left + "," + margin.top + ")");

	d3.json(url, function(error, data) {
		if (error) throw error;
		console.log(data);
		root = d3.hierarchy(data);
		root.x0 = 0;
		root.y0 = 0;
		update(root);
	});

	function update(source) {

		// Compute the flattened node list.
		var nodes = root.descendants();

		var height = Math.max(500, nodes.length * barHeight + margin.top + margin.bottom);

		d3.select("svg").transition()
			.duration(duration)
			.attr("height", height);

		d3.select(self.frameElement).transition()
			.duration(duration)
			.style("height", height + "px");

		// Compute the "layout". TODO https://github.com/d3/d3-hierarchy/issues/67
		var index = -1;
		root.eachBefore(function(n) {
			n.x = ++index * barHeight;
			n.y = n.depth * 20;
		});

		// Update the nodes…
		var node = svg.selectAll(".node")
			.data(nodes, function(d) { return d.id || (d.id = ++i); });

		var nodeEnter = node.enter().append("g")
			.attr("class", "node")
			.attr("transform", function(d) { return "translate(" + source.y0 + "," + source.x0 + ")"; })
			.style("opacity", 0);

		// Enter any new nodes at the parent's previous position.
		nodeEnter.append("rect")
			.attr("y", -barHeight / 2)
			.attr("height", barHeight)
			.attr("width", barWidth)
			.style("fill", color)
			.on("click", click);

		nodeEnter.append("text")
			.attr("dy", 3.5)
			.attr("dx", 5.5)
			.text(function(d) { var img = d.data.type; var str = ""; if (img != null) { str = "[ACTIVITY]"; }return str + " " + d.data.name; });

		// Transition nodes to their new position.
		nodeEnter.transition()
			.duration(duration)
			.attr("transform", function(d) { return "translate(" + d.y + "," + d.x + ")"; })
			.style("opacity", 1);

		node.transition()
			.duration(duration)
			.attr("transform", function(d) { return "translate(" + d.y + "," + d.x + ")"; })
			.style("opacity", 1)
			.select("rect")
			.style("fill", color);

		// Transition exiting nodes to the parent's new position.
		node.exit().transition()
			.duration(duration)
			.attr("transform", function(d) { return "translate(" + source.y + "," + source.x + ")"; })
			.style("opacity", 0)
			.remove();

		// Update the links…
		var link = svg.selectAll(".link")
			.data(root.links(), function(d) { return d.target.id; });

		// Enter any new links at the parent's previous position.
		link.enter().insert("path", "g")
			.attr("class", "link")
			.attr("d", function(d) {
				var o = {x: source.x0, y: source.y0};
				return diagonal({source: o, target: o});
			})
			.transition()
			.duration(duration)
			.attr("d", diagonal);

		// Transition links to their new position.
		link.transition()
			.duration(duration)
			.attr("d", diagonal);

		// Transition exiting nodes to the parent's new position.
		link.exit().transition()
			.duration(duration)
			.attr("d", function(d) {
				var o = {x: source.x, y: source.y};
				return diagonal({source: o, target: o});
			})
			.remove();

		// Stash the old positions for transition.
		root.each(function(d) {
			d.x0 = d.x;
			d.y0 = d.y;
		});
	}

	// Toggle children on click.
	function click(dnode) {
		d = dnode.data;
		if (d.imageUrl != null) {
			if (current && d == current) {
				current = null;
				$("#pinnedCampCard").slideUp();
			} else {
				current = d;
				$("#pinnedCampCard").hide();
				$("#pinnedCampCard").removeClass("d-none");
				$("#pinnedCampCard").slideDown();
				$("#campImgList").attr("src",current.imageUrl);
				$("#campNameList").text(current.name);
				$("#campLocationList").text(current.location);
				$("#viewCampButton").attr("href", BASE_URL + "/camps/" + current.id);
			}
		} else if (d.type != null) { // it is an activity
			current = dnode.parent.data;
			$("#pinnedCampCard").hide();
			$("#pinnedCampCard").removeClass("d-none");
			$("#pinnedCampCard").slideDown();
			$("#campImgList").attr("src",current.imageUrl);
			$("#campNameList").text(current.name);
			$("#campLocationList").text(current.location);
			$("#viewCampButton").attr("href", BASE_URL + "/camps/" + current.id);
		}

		//console.log(dnode._children);
		//$("#pinnedActivityCard").show(); // for creation of activity
		if (dnode.children) {
			dnode._children = dnode.children;
			dnode.children = null;
		} else {
			dnode.children = dnode._children;
			dnode._children = null;
		}
		update(dnode);
	}

	function color(d) {
		return d._children ? "#3182bd" : d.children ? "#c6dbef" : "#fd8d3c";
	}
});
