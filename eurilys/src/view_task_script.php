<?php
	/* Configuring Server & Database */
	$host        =    'localhost';
	$user        =    'root';
	$password    =    '';
	$database    =    'progin_405_13510086';
	$con        =    mysql_connect($host,$user,$password) or die('Server information is not correct.');
	mysql_select_db($database,$con) or die('Database information is not correct');
	
	$response = "";
	
	/* Get the task id we're going to generate to the HTML page */
	$q	= $_GET["q"];
	
	/* Searching for Task */
	$query 	= "SELECT * FROM task WHERE task_id='$q';";
	$result	= mysql_query($query);
	while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {		
		$taskID = $row['task_id'];

		//Get 'tag'
		$tag_query = "SELECT * from tag WHERE task_id='$taskID'";
		$tag_result = mysql_query($tag_query);
		$tagResponse = "";
		while ($tag_row = mysql_fetch_array($tag_result, MYSQL_ASSOC)) { 
			$tagResponse = $tagResponse.$tag_row['tag_name']." , ";
		}
		
		//Get 'comment'		
		unset($commentContent);
		$commentContent = array();
		unset($commentCreator);
		$commentCreator = array();
		
		$comment_query = "SELECT comment_content, comment_creator from comment WHERE task_id='$taskID'";
		$comment_result = mysql_query($comment_query);
		while ($comment_row = mysql_fetch_array($comment_result, MYSQL_ASSOC)) {
			$commentContent[] = $comment_row['comment_content'];
			$commentCreator[] = $comment_row['comment_creator'];
		}
		
		//Generate response
		$response = $response. 
		"
		<div class='taskDetail'>
			<div id='edit_task_header' class='left top30 dynamic_content_head darkBlue'>
				".$row['task_name']."
			</div>
			
			<input id='edit_task_button' class='left top30 link_blue_rect' onclick='edit_task()' type='button' value='Edit Task'>
			
			<input id='save_button_td' class='left top30 link_blue_rect' onclick='save_edit_task()' type='button' value='Save'>
						
			<div class='left top30 dynamic_content_row'>
				<div id='task_name_ltd' class='left dynamic_content_left'> Task Name </div>
				<div id='task_name_rtd' class='left dynamic_content_right'>".$row['task_name']."</div>
			</div>
			
			<div class='left top20 dynamic_content_row'>
				<div id='attachment_ltd' class='left dynamic_content_left'>Attachment</div>
				<div id='attachment_rtd' class='left dynamic_content_right'>
					<!-- <img id='taskdetail_img' src='../img/taskdetail_img.jpg' alt='Rikka-chan'> -->
					??? Belum ada attachment
				</div>
			</div>
			
			<div class='left top20 dynamic_content_row'>
				<div id='deadline_ltd' class='left dynamic_content_left'>Deadline</div>
				<div id='deadline_rtd' class='left dynamic_content_right'>".$row['task_deadline']."</div>
			</div>
			
			<div class='left top20 dynamic_content_row'>
				<div id='assignee_ltd' class='left dynamic_content_left'>Assignee</div>
				<div id='assignee_rtd' class='left dynamic_content_right'> ??? Belum ada DB utk task assignee </div>
			</div>
		
			<div class='left top20 dynamic_content_row'>
				<div id='tag_ltd' class='left dynamic_content_left'>Tag</div>
				<div id='tag_rtd' class='left dynamic_content_right'>".$tagResponse."</div>
			</div>
			<div class='left top20 dynamic_content_row'>
					<div id='comment_ltd' class='left dynamic_content_left'> Comment </div>
					<div id='comment_rtd' class='left dynamic_content_right'> </div>
			</div>
		";
		
		if (count($commentContent) > 0) {
			for($i=0; $i<count($commentContent); $i++) {
				$response = $response.
				"
				<div class='left top20 dynamic_content_row'>
					<div id='comment_ltd' class='left dynamic_content_left darkBlueItalic'> ".$commentCreator[$i]." </div>
					<div id='comment_rtd' class='left dynamic_content_right'>".$commentContent[$i]."</div>
				</div>";
			}
		}	
		$response = $response.
		"	<div class='left top20 dynamic_content_row'>
				<div id='addcomment_ltd' class='left dynamic_content_left'> &nbsp; </div>
				<div id='addcomment_rtd' class='left dynamic_content_right'>
					<form autocomplete='off' method='POST' action='add_comment.php'>
						<textarea id='comment_textarea' rows='5' cols='50' name='CommentBox'>
						</textarea> 
						<br>
						<input type='hidden' name='comment_task_id' value='".$taskID."'>
						<input type='submit' value='Add Comment' name='add_comment_button' class='link_red'>
						<br><br><br>
					</form>
				</div>
			</div> 
		</div>";
	}
	//output the response
	echo $response;
?>