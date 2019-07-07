$(document).ready(function(){

	//addCamp
		//nothing to add, form post handled by php

	//addPrisoner
		//nothing to add, form post handled by php

	//camp
		//edit button for camp name, lcoation, operation,
			//and demographic send a form to php
		//needs to have for-each loop for activities.
		//add new activity also sends new form to php.
		//delete camp also sends a form to php.


		//editing the warden and purpose
		//FORM SENT TO /update/wardenAndPurpose/
		$("#editCampForm1").submit(function(e) {
			e.preventDefault();
			var wdn = $("#campHeadInput").val();
			var prpse = $("#campPurposeInput").val();
			var nme = $("#campNameInput").val();
			var u = $("#campUrl").val();

			var ht = $("#wardenAndPurpose").html();
			var splt = ht.split("<br>");

			for (i = 0; i < splt.length; i++) {
				splt[i] = splt[i].trim();
			}

			$.post(
				window.location.href + '/update/wardenAndPurpose/',
				{warden: wdn, purpose: prpse, name: nme, url: u},
				function(data) {
					if (data.success == 'success') {
						//set warden and purpose

						if (wdn.length == 0) {
							wdn = splt[0];
						}
						if (prpse.length == 0) {
							prpse = splt[1];
						}
						if (nme.length != 0) {
							$("#campName").text(nme);
						}
						if (u.length != 0) {
							$("#imageUrl").html(`<img class="no-aspect-t h-400" src="`+u+`" alt="photo of Camp">`);
						}

						$("#wardenAndPurpose").html(wdn + "<br>" + prpse);
					} else {
						alert('Server error: ' + data.error);
					}
				}
			).fail(function() {
				alert("Ajax call failed [wardenAndPurpose]");
			});
		});

		//editing the camp location.
		//FORM SENT TO /update/Location/
		$("#editLocationForm").submit(function(e){
			e.preventDefault();
			var loc = $("#campLocationInput").val();

			$.post(
				window.location.href + '/update/Location/',
				{locate: loc},
				function(data) {
					if (data.success == 'success') {
						$("#campLocation").text(loc);
					} else {
						alert('Server error: ' + data.error);
					}
				}
			).fail(function() {
				alert("Ajax call failed [campLocation]");
			});
		});

		//editing the camp start/end.
		//FORM SENT TO /update/Uptime/
		//MUST RETURN IN JSON: json.begin, and json.end
		$("#dateBeginAndEndForm").submit(function(e){
			e.preventDefault();
			var begin = $("#dateBegin").val();
			var end = $("#dateEnd").val();

			var originalDates = $('#dateBeginAndEnd').text().trim();
			var originalStart = originalDates.substring(originalDates.indexOf(' '), originalDates.indexOf('Stop:')).trim();
			var originalEnd = originalDates.substring(originalDates.indexOf('Stop:') + 5).trim();

			$.post(
				window.location.href + '/update/Uptime/',
				{begin: begin, end: end},
				function(data) {
					if (data.success == 'success') {
						if (begin.length != 0 && end.length != 0) $("#dateBeginAndEnd").html("Start: " + begin + "<br>" + "Stop: " + end);
						else if (begin.length != 0) $("#dateBeginAndEnd").html("Start: " + begin + "<br>" + "Stop: " + originalEnd);
						else if (end.length != 0) $("#dateBeginAndEnd").html("Start: " + originalStart + "<br>" + "Stop: " + end);
						else $("#dateBeginAndEnd").html("Start: " + originalStart + "<br>" + "Stop: " + originalEnd);
					} else {
						alert('Server error: ' + data.error);
					}
				}
			).fail(function() {
				alert("Ajax call failed [campUptime]");
			});
		});

		//editing the camp demographic.
		//FORM SENT TO /update/Demographic/
		$("#demographicForm").submit(function(e){
			e.preventDefault();
			var demo = $("#demographicInput").val();

			$.post(
				window.location.href + '/update/Demographic/',
				{demographic: demo},
				function(data) {
					if (data.success == 'success') {
						$("#demographicText").text(demo);
					} else {
						alert('Server error: ' + data.error);
					}
				}
			).fail(function() {
				alert("Ajax call failed [campLocation]");
			});
		});

		//editing the type and description for adding activities
		//FORM SENT TO /add/Activity
		$("#typeAndDescriptionForm").submit(function(e) {
			e.preventDefault();
			var tit = $("#titleInput").val();
			var dec = $("#descriptionInput").val();
			$.post(
				window.location.href + '/add/Activity/',
				{title: tit, description: dec},
				function(data) {

					if (data.success == 'success') {
						var item = $('<div><h5>'+tit+'</h5><p>'+dec+'</p></div>');
						$("#appendMe").append(item);
						$('.emptyMessage').hide();
					} else {
						alert('Server error: ' + data.error);
					}
				},
				"json"
			).fail(function() {
				alert("Ajax call failed [typeAndDescriptionForm]");
			});
		});

		//removing
		$("#removeCamp").submit(function(e) {
			e.preventDefault();
			$.post(
				window.location.href + '/remove/',
				{},
				function(data){
					if (data.success == "success") {
						if (data.error) {
							alert("Error in removing camp: " + data.error);
						} else {
							window.location.href = BASE_URL + "/camps/";
						}
					}
				}
			).fail(function() {
				alert("Ajax call failed [remove]")
			});
		});

	//camps
		//needs to check if username is admin (php?)
		//the href needs to redirect to a view of the camp

	//footer
		//nothing

	//header
		//nothing

	//index
		//nothing

	//login
		//sends a post request to log in with a form

	//map

	//see mapscript.js in the /js folder.

	//prisoner
		//needs a for-each loop for events.
		//needs a post request for deleting the prisoner.

		//update the details of the prisoner
		//FORM SENT TO /update/Details/
		//MUST RETURN data.campId if camp is changed
		$("#editPersonForm").submit(function(e) {
			e.preventDefault();
			var cmps = $("#addNewCamps").val();
			var dts1 = $("#editDofBirth").val();
			var dts2 = $("#editDofDeath").val();
			var orgn = $("#editOrigin").val();
			var nme = $("#editName").val();
			var imageUrl = $('#editImage').val();

			$.post(
				window.location.href + '/update/Details/',
				{camps: cmps, dateofbirth: dts1, dateofdeath: dts2, origin: orgn, name: nme, imageUrl: imageUrl},
				function(data) {
					if (data.success == 'success') {
						if (cmps.length) {
							var item = $('<p class="side-menu-p ml-4"><a href="'+BASE_URL+'/camps/'+data.campId+'">'+cmps+'</a><br></p>');
							$("#camps-collapse").append(item);
							$('.emptyMessage').hide();
						}
						var splt = $("#dates-collapse").val().split("\n");
						if (dts1.length) {
							var tmp = splt[0];
							$("#dates-collapse").find("p").html("Date of Birth: "+dts1+"<br>"+tmp);
							splt[1] = "Date of Birth: "+dts1; // in case both birth and death are edited
						}
						if (dts2.length) {
							var tmp = splt[1];
							$("#dates-collapse").find("p").html(tmp+"<br>Date of Death: "+dts2);
						}
						if (orgn.length) {
							$("#origin-collapse").find("p").text(orgn);
						}
						if (nme.length) {
							$("#prisonerName").text(nme);
						}
						if (imageUrl.length) {
							$('#prisonerImage').attr('src', imageUrl);
						}
					} else {
						alert("Could not complete: " + data.error);
					}
				},
				'json'
			).fail(function() {
				alert("Ajax call failed [updatePrisonerDetails]")
			});
		});

		//add event for a prisoner
		//FORM SENT TO /add/Event
		$("#addEventPrisoner").submit(function(e) {
			e.preventDefault();
			var date = $("#prisonerUpdateDate").val();
			var title = $("#prisonerUpdateTitle").val();
			var desc = $("#prisonerUpdateDetails").val();

			$.post(
				window.location.href + '/add/Event',
				{date: date, title: title, description: desc},
				function(data) {
					if (data.success == 'success') {
						var item = $('<div><h5 class="mb-0">'+date+':'+title+'</h5><p>'+desc+'</p></div>');
						$("#eventsList").append(item);
						$('.emptyMessage').hide();
					} else {
						alert("Error in adding event: " + data.error);
					}
				},
				'json'
			).fail(function() {
				alert("Ajax call failed [addEventPrisoner]");
			});
		});


		//remove a prisoner
		//FORM SENT TO /remove/
		$("#removePrisoner").submit(function(e) {
			e.preventDefault();
			$.post(
				window.location.href + '/remove/',
				{},
				function(data) {
					if (data.success == "success") {
						window.location.href = BASE_URL + "/prisoners/";
					}
					else {
						alert("Error in removing prisoner: " + data.error);
					}
				},
				'json'
			).fail(function(){
				alert("Ajax call failed [removePrisoner]");
			});
		});

	//prisoners
		//needs to check if username is admin (php?)
		//needs to redirect to page of prisoner when clicked

	//signup
		//is gender and race necessary?
		//All of it is in a form request,
		//which should redirect on click

	//user
		//follow, share, report
		//promote/demote
		//reporting


		//reports user
		//POSTS TO
		$("#reportUser").submit(function(e) {
			e.preventDefault();
			var reasonForReporting = $("#reasonForReporting").val();
			$.post(
				window.location.href + '/report/',
				{reason : reasonForReporting},
				function(data) {
					if (data.success == "success") {
						$("#resultDisplay").show();
					}
					else {
						alert("Error in reporting user: " + data.error);
					}
				}, 'json'
			).fail(function(){
				alert("Ajax call failed [report]");
			});
		});

		//Promotes when clicked
		//POSTS TO /promote/
		//possibly returns json of new rank name
		$('#promote-btn').click(function() {
			$.post(
				window.location.href + '/promote/',
				{},
				function(data) {
					if (data.success == 'success') {
						//$('#rc-content').text(data.rankString); // replace
						location.reload();
						//$("#rank-collapse").load(location.href + " #rank-collapse>*", "");
					} else {
						alert('Server error: ' + data.error);
					}
				}, 'json'
			)
			.fail(function() {
				alert("Ajax call failed [promote]")
			});
		});

		//Demotes when clicked
		//POSTS TO /demote/
		//possibly returns json of new rank name
		$('#demote-btn').click(function() {
			$.post(
				window.location.href + '/demote/',
				{},
				function(data) {
					if (data.success == 'success') {
						//$('#rc-content').text(data.rankString); // replace
						location.reload();
						//$("#rank-collapse").load(location.href + " #rank-collapse>*", "");
					} else {
						alert('Server error: ' + data.error);
					}
				}, 'json'
			)
			.fail(function() {
				alert("Ajax call failed [demote]")
			});
		});

		//Reports are handled in the form in the html.

		//Follows when clicked
		//POSTS TO /toggleFollow/
		$('#followUser').click(function() {
			$.post(
				window.location.href + '/toggleFollow/',
				{},
				function(data) {
					if (data.success == 'success') {
						$('#followUser').hide();
						$('#unfollowUser').show();
					} else {
						alert('Server error: ' + data.error);
					}
				}, 'json'
			)
			.fail(function() {
				alert("Ajax call failed [follow]")
			});
		});

		$('#unfollowUser').click(function() {
			$.post(
				window.location.href + '/toggleUnfollow/',
				{},
				function(data) {
					if (data.success == 'success') {
						$('#unfollowUser').hide();
						$('#followUser').show();
					} else {
						alert('Server error: ' + data.error);
					}
				}, 'json'
			)
			.fail(function() {
				alert("Ajax call failed [unfollow]")
			});
		});

		//Shares when clicked
		//POSTS TO /shareUser/
		$('#shareUser').click(function() {
			//should it do a pop up verify?
			$.post(
				window.location.href + '/shareUser/',
				{},
				function(data) {
					if (data.success == 'success') {
						//redirect?
					} else {
						alert('Server error: ' + data.error);
					}
				}
			)
			.fail(function() {
				alert("Ajax call failed [share]")
			});
		});

	function create_comment_string(commentObject) {
		var delete_link = "";
		console.log("current username: " + SESSION_username);
		if (commentObject.profileId == SESSION_username || commentObject.username == SESSION_username) {
			delete_link = `<a href="`+BASE_URL+`/deleteComment/`+commentObject.id+`" class="message-footer">Delete<a>`;
		}
		var comment = `
			<li class="mb-3">
				<div class="message">
					<div class="message-user-img">
						<img class="img rounded-circle h-30" src="../public/img/profile-img.jpg" alt="Default user image">
					</div>
					<div class="message-main">
						<a href="<?= `+BASE_URL+` ?>/user/`+commentObject.username+`" class="message-user">
							`+commentObject.username+`
						</a>
						<p class="message-text">
							`+commentObject.content+`
							<br>
							`+delete_link+`
						</p>
					</div>
				</div>
			</li>
		`;
		return comment;
	}

	function create_activity_string(activityObject) {
		var currentUsername = '<%= Session["username"] %>';
		//link only exists if it isn't a new/edited account, or if it isn't a deletion
		//time_elapsed_string and username always exist
		//action always exist except for deletion.
		//if the type is 'otherUser' then it changes the link and entity
		var time_elapsed = activityObject.happened;
		var username = activityObject.username;
		var action = "deleted";
		var entity = "";
		var a_link = "";
		if (activityObject.entityName == "") {
			action = activityObject.getAction;
		} else {
			entity = activityObject.entity; //.entityName;
		}
		if (action != "created an account" && action != "edited their profile" && action != "deleted") {
			var type = activityObject.getDestType;
			var endTag = activityObject.otherUser;
			var body = activityObject.otherUser;
			if (type != "user") {
				endTag = activityObject.entityId;
				body = activityObject.entity;
			}
			a_link = `
				<a href="`+BASE_URL+`/`+type+`/`+endTag+`">`+body+`</a>
			`;
		}
		var content = `
			<p class="message-text">
				[`+time_elapsed+`] `+username+` `+action+``+entity+``+a_link+`
			</p>
		`;
		var activity = `
			<li class="mb-3">
				<div class="message">
					<div class="message-main">
						`+content+`
					</div>
				</div>
			</li> <!-- End of a message -->
		`;
		/*
			if($activityObject->entityName == NULL)
				if($activityObject->getAction() != 'created an account' && $activityObject->getAction() != 'edited their profile')
					$type = $activityObject->getDestType()
					if($type != 'user')
						if($activityObject->getEntity() != NULL)
							$entity = $activityObject->getEntity()
							<p class="message-text">
								[<?= time_elapsed_string($activityObject->happened) ?>] <?= $activityObject->username ?> <?= $activityObject->getAction() ?> <a href="<?= BASE_URL ?>/<?= $type ?>/<?= $activityObject->entityId ?>"><?= $entity ?></a>
							</p>
						endif
					php else
						<p class="message-text">
							[<?= time_elapsed_string($activityObject->happened) ?>] <?= $activityObject->username ?> <?= $activityObject->getAction() ?> <a href="<?= BASE_URL ?>/<?= $type ?>/<?= $activityObject->otherUser ?>"><?= $activityObject->otherUser ?></a>
						</p>
					endif
				else:
					<p class="message-text">
						[<?= time_elapsed_string($activityObject->happened) ?>] <?= $activityObject->username ?> <?= $activityObject->getAction() ?>
					</p>
				endif
			else
				<p class="message-text">
					[<?= time_elapsed_string($activityObject->happened) ?>] <?= $activityObject->username ?> deleted <?= $activityObject->entityName ?>
				</p>
			endif
		*/
		return activity;
	}

	//Check when they scrolled to the bottom of the activity feed

	var commentIndex = 10;
	var activitiyIndex = 10;
	var incrementSize = 10;
	var isBusy_activities = false;
	var isBusy_comments = false;
	var isFinished_activities = false; // when you go through all feed
	var isFinished_comments = false; // when you go through all comments

	jQuery(function($) {
		$('#activity-box').on('scroll', function() {
			if ($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight) {
				//reached the bottom of the div
				if (isBusy_activities) {
					return;
				}
				isBusy_activities = true;
				$.get(
					window.location.href + '/pullFeed/activities',
					{index : activitiyIndex, increment : incrementSize},
					function(data) {
						//load in the data and append it to the end
						var myObj = JSON.parse(data);
						if (myObj.success == "success") {
							var size = 0;
							//iterate through all of myObj.comments
							for (var i in myObj.feed) {
								size++;
								var activity = myObj.feed[i];
								var feed_string = create_activity_string(activity);
								//append it to the list.
								$("#activity-list").append(feed_string);
							}
							if (size <= incrementSize) {
								isFinished_activities = true;
							}
						} else {
							alert("Loading activity data failed, error: " + myObj.success);
						}
					}
				).done(function() {
					//if (!isFinished_activities) {
						activitiyIndex+=incrementSize;
						isBusy_activities = false;
					//}
				});
			}
		})
	});

	//reached the bottom of the comment fee

	jQuery(function($) {
		$('#comment-box').on('scroll', function() {
			if ($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight) {
				//reached the bottom of the div
				console.log("commentIndex: " + commentIndex);
				console.log("isBusy_comments: "); console.log(isBusy_comments);
				console.log("isFinished_comments: "); console.log(isFinished_comments);
				if (isBusy_comments) {
					return;
				}
				isBusy_comments = true;
				$.get(
					window.location.href + '/pullFeed/comments',
					{index : commentIndex, increment : incrementSize},
					function(data) {
						//load in the data and append it to the end
						var myObj = JSON.parse(data);
						if (myObj.success == "success") {
							var size = 0;
							//iterate through all of myObj.comments
							for (var i in myObj.comments) {
								size++;
								var comment = myObj.comments[i];
								var comment_string = create_comment_string(comment);
								//append it to the list.
								$("#comments-list").append(comment_string);
							}
							if (size < incrementSize) {
								//stop loading
								isFinished_comments = true;
							}
						} else {
							alert("Loading comment data failed, error: " + myObj.success);
						}
					}
				).done(function() {
					//if (!isFinished_comments) {
						commentIndex+=incrementSize;
						isBusy_comments = false;
					//}
				});
			}
		})
	});

	//gets the comment
	//SENDS IT TO /comment/
	$("#comment-form").submit(function(e) {
		e.preventDefault();
		var cmt = $("#submitCommentValue").val();
		//clear the comment box
		if (cmt.length == 0) {
			return;
		}
		$("#submitCommentValue").val('');
		$.post(
			window.location.href + '/comment/',
			{comment: cmt},
			function(data) {
				if (data.success == "success") {
					//refresh
					$("#comment-box").load(location.href + " #comment-box>*", "");
					//when refreshed, all other comments are removed, so reset variables
					commentIndex = 10;
					isBusy_comments = false;
					isFinished_comments = false;
				}
				else {
					alert("Error in creating comment: " + data.error);
				}
			},'json'
		).fail(function(){
			alert("Ajax call failed [comment-form]");
		});
	});

	//below is old code used for reference//

	/*
	$('#editReleaseButton').click(function() {
		$('#editReleaseButton').hide();
		$('#editReleaseForm').show();
		$('#releaseDate').focus();
		$('#editReleaseForm').find('.text').each(function(){
			$(this).val(''); // clear
		});
	});

	$('#addEventButton').click(function(){
		//alert('clicked');
		//console.log("add event clicked");
		$('#addEventButton').hide();
		$('#addEventForm').show(); // show the form
		$('#eventTitle').focus(); // position cursor at event year
		// clear the text boxes
		$('#addEventForm').find('.text').each(function(){
			$(this).val('');
		});
	});

	$('#submitReleaseEditButton').click(function(){
		//update the release date
		var date = $('#releaseDate').val();
		$.post(
			window.location.href + '/release/update/process/',
			{
				release: date
			},
			function(data) {
				if (data.success == 'success') {
					var fmt = data.format;
					var txt = 'Released ' + fmt;
					$('#releaseHeader').text(txt);
					$('#editReleaseForm').hide();
					$('#editReleaseButton').show();
				} else {
					alert('Server error: ' + data.error);
				}
			}
		)
		.fail(function() {
			alert("Ajax call failed [release edit]")
		});
	});

	$('#submitEventButton').click(function(){
		// build the title
		var year = $('#eventYear').val();
		var title = $('#eventTitle').val();

		//now for Ajax stuff
		$.post(
			window.location.href + '/award/add/process/',
			{
				year: year,
				title: title
			},
			function(data) {
				if (data.success == 'success') {
					var fullTitle = $('<h4>' + year + ' - ' + title + '</h4>');
					$('#events').append(fullTitle);
					$('#addEventForm').hide();
					$('#addEventButton').show();
				} else {
					alert('Server error: ' + data.error);
				}
			}
		)
		.fail(function() {
			alert("Ajax call failed [new award]");
		});
	});

	$('#removeGameButton').click(function() {
		$('#removeGameButton').hide();
		$('#confirmDiv').show();
	});

	$('#removeNoButton').click(function(){
		$('#confirmDiv').hide();
		$('#removeGameButton').show();
	});

	$('#removeYesButton').click(function(){
		$('#confirmDiv').hide();
		$('#removeGameButton').show();
		window.location.href = window.location.href + '/remove/process/';
	});

	$('.removeLinkButton').click(function() {
		console.log("clicked");
		if (confirm("Are you sure you want to remove this item?")) {
			//href="<?= BASE_URL ?>/game/view/<?= $game->id ?>/remove/process"
			window.location.href = window.location.href + '/view/'+$(this).attr('name')+'/remove/process';
		} else {

		}
	});
	*/

});
