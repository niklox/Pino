<!-- START Template 13 -->
<div id="content">
	<div class="templaterow">
	<?php ImageGet($thiscontent->getID(), 1, "leftimage") ?>
	<div id="<?php $thiscontent->getTextID(1) ?>"><?php $thiscontent->getText(1) ?> </div>
	</div>

	<div class="templaterow">
	<?php ImageGet($thiscontent->getID(), 2, "rightimage") ?>
	<div id="<? $thiscontent->getTextID(2) ?>"><?php $thiscontent->getText(2) ?> </div>
	</div>

</div>
<!-- END Template 13 -->
