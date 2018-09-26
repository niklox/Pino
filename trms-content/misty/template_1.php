<!-- START template 1 -->

<div id="content">
		<div id="leftcolumn">
			<div id="fpleftbox">
				<div id="<?php $thiscontent->getTextID(1) ?>"><?php $thiscontent->getText(1) ?> </div>
			</div>

		</div>
		<div id="rightcolumn">
			<div class="fpsmallbox_1">
				<?php ImageGet($thiscontent->getID(), 1, "") ?>
			</div>
			<div id="<?php $thiscontent->getTextID(2) ?>" class="fpsmallbox_2"><?php $thiscontent->getText(2) ?></div>
			<div id="<?php $thiscontent->getTextID(3) ?>"  class="fpsmallbox_3"><?php $thiscontent->getText(3) ?></div>
			<div class="fpsmallbox_4">
				<?php ImageGet($thiscontent->getID(), 2, "") ?>
			</div>
		</div>
</div>

<!--  END template 1  -->
