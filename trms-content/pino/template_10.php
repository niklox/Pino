
<div class="shop">
	<ul class="productlist">

<?php

$products = ContentGetAllInNode(ContentGetList($thiscontent->getID(), 0));
while($products = ContentGetNext($products)){

	print '<li>';
	ImageGetWithIDNoSize($products->getID(), 2, "productimg", "more_" . $products->getID());

	print '</li>';
}

?>
</ul>
</div>
