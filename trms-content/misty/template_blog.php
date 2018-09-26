	<div id="widecolumn">
<?php
		if(isset($currentuser)){

			print "<div id=\"blogbutton\"><input type=\"button\" id=\"createblogpost\" value=\"BLOGGA!\"/>För att visa fälten för ett blogginlägg kllickar du knappen \"BLOGGA!\". Ett inlägg ska innehålla rubrik, text och signatur och kan innehålla även en bild men det är valfritt.</div>\n";
			print	"<div id=\"blogform\">" .
					"<form id=\"thepost\" action=\"/trms-content/blogform.php\" method=\"post\" enctype=\"multipart/form-data\">" .
					"<input type=\"hidden\" name=\"listid\" id=\"listid\" value=\"63\"/>" .
					"<input type=\"hidden\" name=\"imgcatid\" id=\"imgcatid\" value=\"24\"/>" .
					"<input type=\"hidden\" name=\"currentuserid\" id=\"currentuserid\" value=\"".$currentuser->getID()."\"/>" .
					"<input type=\"hidden\" name=\"contentid\" id=\"contentid\" value=\"" . $thiscontent->getID() ."\"/>" .
					"<label>Rubrik</label><br/>\n" .
					"<input type=\"text\" name=\"bloghead\" id=\"bloghead\"/><br/>" .
					"<label>Text</label><br/>\n" .
					"<textarea name=\"blogtext\" id=\"blogtext\"></textarea><br/>" .
					"<label>Signatur</label><br/>\n" .
					"<input type=\"text\" name=\"blogsign\" id=\"blogsign\"/><br/>" .
					"<label>Bild</label><br/>\n" .
					"<input type=\"file\" id=\"file\" name=\"file\" size=\"83\"/>\n" .
					"<input type=\"submit\" id=\"blogsend\" value=\"SKICKA INLÄGG!\"/>" .
					"</form>" .
					"</div>\n";

		}

		$blogposts = ContentGetAllInNode(ContentGetList($thiscontent->getID(), 0));
		while($blogposts = ContentGetNext($blogposts)){
			$text = ContentTextGet($blogposts->getID(), 2);
			print	'<div class="blogpost" id="blogpost_'.$blogposts->getID().'">' .
					'<div class="bloghead">'. substr($blogposts->getCreatedDate(), 0,16) .'&nbsp; &nbsp; ' . MTextGet($text->getTextID());

			if(isset($currentuser))print ' &nbsp;<img src="/trms-admin/images/delete_mini.gif" id="deletepost_'.$blogposts->getID().'-'.$_SERVER["REQUEST_URI"].'" class="delete_post"/>';


			print	"</div>";
			print	"<div class=\"blogcontent\">";
						ImageGet($blogposts->getID(), 1, "blogimg");

			print	"<div class=\"blogsub\">" . MTextGet($blogposts->getTitleTextID()) . "</div>\n";
					$text = ContentTextGet($blogposts->getID(), 1);

			print   nl2br(MTextGet($text->getTextID()));

			print	"</div>\n";
			print	"</div>\n";

			if( $blogposts->getFlag() == 1 ){

				print	"<div class=\"discussion\" id=\"discussion_". $blogposts->getID() ."\">";
				print	"<div class=\"head\">" . MTextGet("comments") . " (". DiscussionCountAllApproved($blogposts->getID()).")";
				print	"<input class=\"commenticon\" type=\"button\" id=\"commenticon_".  $blogposts->getID()  ."\" value=\"".MTextGet("makecomment")."\"/>";
				if(DiscussionCountAllApproved($blogposts->getID()) > 0)
				print	"<input class=\"showcomments\" type=\"button\" id=\"showcomments_".  $blogposts->getID()  ."\" value=\"".MTextGet("showcomments")."\"/></div>";
				else	print	"</div>";
				print	"<div class=\"discussioninput\" id=\"discussioninput_". $blogposts->getID() ."\">";
				print	MTextGet("comment") . "<br/>";
				print	"<textarea class=\"commenttext\" id=\"commenttext_" . $blogposts->getID() ."\"></textarea>";
				print	MTextGet("name") . "<br/>";
				print	"<input type=\"text\" class=\"signature\" id=\"signature_".$blogposts->getID()."\" value=\"\"/>";
				print	"<input type=\"button\" class=\"sendcomment\"  id=\"sendcomment_". $blogposts->getID() ."\" value=\"".MTextGet("sendcomment")."\"/>";
				print	"</div>\n";
				print	"<div class=\"discussionconfirm\" id=\"discussionconfirm_".$blogposts->getID() ."\">". MTextGet("discussionconfirm") . "</div>\n";
				print	"<div class=\"discussionlist\" id=\"discussionlist_".$blogposts->getID()."\">";

					$discussion = DiscussionGetAllWithStatusForReferenceID( 1, $blogposts->getID());
					while($discussion = DiscussionGetNext($discussion)){

						$date = date_create($discussion->getCreatedDate());
						print	"<div class=\"discussionitem\">\n";
						print	"<p class=\"discussion-sign\"><span class=\"discussion-name\">" .$discussion->getAuthorName(). "</span>, ".date_format($date, 'H:i, Y-m-d')."</p>";
						print	"<p class=\"discussiontext\">" . $discussion->getText() . "</p>";
						print	"</div>\n";
					}

				print	"</div>\n";
				print	"</div>\n";

		}
	}
?>

	</div>
