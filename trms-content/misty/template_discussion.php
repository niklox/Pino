<?php	
	print	"<div id=\"discussion\">";
	print	"<div class=\"head\">" . MTextGet("comments") . " (". DiscussionCountAllApproved($thiscontent->getID()).") <input type=\"button\" id=\"commenticon\" value=\"".MTextGet("makecomment")."\"/>&nbsp;</div>";
	print	"<div id=\"discussioninput\">";
	print	MTextGet("comment") . "<br/>";
	print	"<textarea id=\"commenttext\"></textarea>";
	print	MTextGet("name") . "<br/>";
	print	"<input type=\"text\" id=\"signature\" value=\"\"/>";
	print	"<input type=\"button\" id=\"sendcomment\" value=\"".MTextGet("sendcomment")."\"/>";
	print	"</div>";
	print	"<div id=\"discussionconfirm\">". MTextGet("discussionconfirm") . "</div>";
	print	"<div id=\"discussionlist\">";

		$discussion = DiscussionGetAllWithStatusForReferenceID( 1, $thiscontent->getID());
		while($discussion = DiscussionGetNext($discussion)){

			$date = date_create($discussion->getCreatedDate());
			print	"<div class=\"discussionitem\">\n";
			print	"<p class=\"discussion-sign\"><span class=\"discussion-name\">" .$discussion->getAuthorName(). "</span>, ".date_format($date, 'H:i, d F Y')."</p>";
			print	"<p class=\"discussiontext\">" . $discussion->getText() . "</p>";
			print	"</div>\n";
		}

	print	"</div>";
	print	"</div>";

?>
