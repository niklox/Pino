<!-- START Template 15 -->
<!-- Markup your HTML here please! -->
<div id="content">
	<div id="pressleft">
	<div id="<?php $thiscontent->getTextID(1) ?>"><?php $thiscontent->getText(1) ?> </div>
	</div>

	<div  id="pressright">
<?php
	for($i=1; $i<36; $i++ ){

		if(ContentHasImageAtPosition($thiscontent->getID(), $i))
			ImageGet($thiscontent->getID(), $i, "downloadthumb");

	}
?>
	</div>
</div>
<!-- END Template 15 -->
