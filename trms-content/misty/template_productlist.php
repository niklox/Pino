<?php
$products = ContentGetAllInNode(ContentGetList($thiscontent->getID(), 0));
while($products = ContentGetNext($products)){
?>
		 <div id="item_<?php print $products->getID() ?>" class="productrow">
			<div class="item_left">
				<?php ImageGetWithID($products->getID(), 1, "productimg", "more_" . $products->getID()) ?>
			</div>
<?php
/* If it is the product 262 Teckensöd
if($products->getID() == 262):
?>

			<div class="item_right_spec" >
				<div id="<? $products->getTextID(1) ?>">
					<? $products->getText(1) ?>
				</div>
<?php
$handsign_support = ContentGetAllInNode(87);
			while($handsign_support = ContentGetNext($handsign_support)){
				print	'<div class="tecken">';
				print	'	<span class="tecken_intro">Teckenstöd:</span><h6 class="tecken_header">'.MTextGet($handsign_support->getContentTextID()).'</h6>';
				if($handsign_support->getFlag() == 1)
					print '<div style="font-size:14px;margin:1px;color:#CC0000;float:left;padding:9px 2px">'.MTextGet("soldout").'</div>';
				else
					print '	<input class="buy_tecken_button" title="Antal i varukorgen" id="prodid_'.$handsign_support->getID().'" type="button" />';

				print	'   <input class="basketbutton" title="Antal i varukorgen" id="orderquant_'.$handsign_support->getID().'" type="button"  value="' . (isset($_SESSION['orderid']) ? number_format(OrderItemGetQuantity($handsign_support->getID(),$_SESSION['orderid'])) : "0" ) . '"/>' .
						"</div>\n";
			}
?>
			</div>

<?php
//else: */
?>
			<div class="item_mid" id="<?php $products->getTextID(1) ?>">
				<?php $products->getText(1) ?>
			</div>
			<div class="item_right">
<?php
			print	$products->getTitle() . "<br/>\n".
					$products->getExternalID() . "<br/>\n";
?>
				<span class="price"><?php print $products->getValue() ?> kr</span><br/>

				<input class="basketbutton" title="Antal i varukorgen" id="orderquant_<?php print $products->getID() ?>" type="button" value="<?php if(isset($_SESSION['orderid']))print number_format(OrderItemGetQuantity($products->getID(),$_SESSION['orderid'])); else print "0"; ?>" /><br/>
				<?php
				if($products->getFlag() == 1)
				print '<div style="font-size:18px;margin:5px;color:#CC0000">'.MTextGet("soldout").'</div>';
				else
				print '<img class="buybutton" title="Lägg i varukorgen" id="prodid_'.$products->getID().'" src="/images/buybutton_I000404_-1.png"/>';
				?>
			</div>
		</div>
<?php
//endif;
}
?>
