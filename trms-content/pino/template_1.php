<div class="headarea">
	<div class="figures">
		<img src="trms-content/pino/images/pino-friends.png" alt="Pino" />
	</div>
</div>


<div class="shop">
	<ul class="productlist">

<?php

function trim_text($input, $length, $ellipses = true, $strip_html = true) {
    //strip tags, if desired
    if ($strip_html) {
        $input = strip_tags($input);
    }

    //no need to trim, already shorter than trim length
    if (strlen($input) <= $length) {
        return $input;
    }

    //find last space within length
    $last_space = strrpos(substr($input, 0, $length), ' ');
    $trimmed_text = substr($input, 0, $last_space);

    //add ellipses (...)
    if ($ellipses) {
        $trimmed_text .= '...';
    }

    return $trimmed_text;
}


$products = ContentGetAllInNode(ContentGetList(219, 0));
while($products = ContentGetNext($products)){

	print '<li>';
	$text = ContentTextGet($products->getID(), 1);

	ImageGetWithIDNoSize($products->getID(), 2, "productimg", "more_" . $products->getID());
	print trim_text(MTextGet($text->getTextID()), 200, true, false);


	print '</li>';



}



?>

	</ul>



</div>
<!--
<div class="middle">
	<img src="trms-content/pino/images/pino-hemsida.png" alt="Pino"/>
	<div id="topleft"><img src="/trms-content/pino/images/pino-boat.png"/></div>
	<div id="topmiddle"><img src="/trms-content/pino/images/pino-firedep.png"/></div>
	<div id="topright"><img src="/images/pino_flygplan_I000399_-1.png" alt="Pino"/></div>
	<div id="botleft"><img src="/images/krampino_I000547_-1.png" alt="Pino"/></div>
	<div id="botright"><img src="/images/orderinfo_I000518_-1.png" alt="Orderinfo" /></div>
</div>

<div class="middle-mobile">
	<img src="trms-content/pino/images/pino-friends.png" alt="Pino"/>
</div>
-->
