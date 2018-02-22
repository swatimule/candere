<?php $_menu = $this->getHtml('level-top', '') ?>
<?php if($_menu): ?>
<!-- Pushy Menu -->
<nav id="nav" class="nav" role="navigation">
	<div class="scrollMenuItem">
		<div class="block">
			<a class="block-title" href="<?php echo $this->getUrl('') ?>">
                <img src="<?php echo $this->getSkinUrl('images/custom/bw2-live-logo.png'); ?>" width="158" height="auto" alt="Candere.com">
			</a>
			<a class="close-btn" id="nav-close-btn">X</a>        
			<?php if(Mage::getSingleton('customer/session')->isLoggedIn()) { ?>
				<h3 class="logged_in_name">
					<?php 
						$customer = Mage::getSingleton('customer/session')->getCustomer();
						$customerData = Mage::getModel('customer/customer')->load($customer->getId());
						echo 'Welcome '.$customerData->getFirstname() ;
					?>
				</h3> 
			<?php } ?>
		</div>
		<ul class="mobileMenuUl">
            <?php echo $_menu ?>
		</ul>		
		<div class="links">
			<ul> 
				<?php if(!$this->helper('customer')->isLoggedIn()){ ?> 
					<li class="m_RL">
						<span class="link-wrap">
							<a href="<?php echo $this->getBaseUrl() ; ?>customer/account/login/" title="Log In"><span class="rl_ico"><i class="fa fa-user" aria-hidden="true"></i></span>Register / Log In</a>
						</span>
					</li>		
					<li class="m_str">
						<span class="link-wrap">
							<a href="<?php echo $this->getBaseUrl() ; ?>my-store" title="Log In"><span class="my_str_ico"><i class="fa fa-shopping-bag" aria-hidden="true"></i></span>My Store</a>
						</span>
					</li>
					<?php }else{ ?>
					<li class="my_Acc">
						<span class="link-wrap">
							<a href="<?php echo $this->getBaseUrl() ; ?>sales/order/history/" title="My Account"><span class="rl_ico"><i class="fa fa-user" aria-hidden="true"></i></span>My Account</a>
						</span>
					</li>
					<li class="m_store">
						<span class="link-wrap">
							<a href="<?php echo $this->getBaseUrl() ; ?>my-store" title="My Store"><span class="my_str_ico"><i class="fa fa-shopping-bag" aria-hidden="true"></i></span>My Store</a>
						</span>
					</li>
					<li class="l_out">
						<span class="link-wrap">
							<a href="<?php echo $this->getBaseUrl() ; ?>customer/account/logout/" title="Log Out"><span class="rl_ico"><i class="fa fa-sign-out" aria-hidden="true"></i></span>Log Out</a>
						</span>
					</li>
				<?php }?>
			</ul>
		</div>
	</div>
	<?php //echo $this->getChildHtml('offCanvasLinks') ?> 
</nav>
<?php endif ?>


<?php
	//$startTime = microtime(true);
	try {
		$identity="";
		$customer_id="";
		$customer_no = "";
		if (Mage::getSingleton('customer/session')->isLoggedIn()) {
			
			$c = Mage::getSingleton('customer/session')->getCustomer();
			$customer = Mage::getModel('customer/customer')->load($c->getId());
			$identity = $customer->getEmail();
			$customer_id = $customer->getId();
			if($customer->getPrimaryBillingAddress())
			{
				$customer_no  = $customer->getPrimaryBillingAddress()->getTelephone();
			}
		}
		else {
			$identity= Mage::getModel('core/cookie')->get('amplify_email');
		}
		
	?>
	<script type="text/javascript">
		var _bout = _bout || [];
		var _boutAKEY = '<?php echo Mage::getStoreConfig("betaout_amplify_options/settings/amplify_key"); ?>';
		var _boutPID = '<?php echo Mage::getStoreConfig("betaout_amplify_options/settings/amplify_projectId"); ?>';
		
		
		var d = document, f = d.getElementsByTagName("script")[0], _sc = d.createElement("script");
		_sc.type = "text/javascript";
		_sc.async = true;
		_sc.src = "//d22vyp49cxb9py.cloudfront.net/jal-v2.min.js";
		f.parentNode.insertBefore(_sc, f);
		_bout.push(["identify", {
			"customer_id": "<?php echo $customer_id;?>",
			"email": "<?php echo $identity; ?>",
			"phone": "<?php echo $customer_no ; ?>",
			"device_id": ""
		}
		]);
	</script>
	
	<?php
		
		} catch (Exception $ex) {
		
	}
	//$endTime = microtime(true);
	//echo "total Execution time ==" . ($endTime - $startTime);
?>

<?php
	$request=$this->getRequest();
	$moduleName=$request->getModuleName();
	$controllerName=$request->getControllerName();
	$actionName = $request->getActionName();

	$routeName = Mage::app()->getRequest()->getRouteName(); 
	$identifier = Mage::getSingleton('cms/page')->getIdentifier();
		if(($moduleName == 'catalog' && $controllerName == 'category' && $actionName == 'view')||($moduleName == 'catalogsearch' && $controllerName == 'result' && $actionName == 'index')||($routeName == 'cms' && $identifier == 'home')||($moduleName == 'catalog' && $controllerName == 'product' && $actionName == 'view')||($moduleName == 'tsearch' && $controllerName == 'index' && $actionName == 'index')){
				if(!$this->helper('customer')->isLoggedIn()){
					$all_products=Mage::helper('all/wishlist')->getProductIntoWishlistCookies();
					$all_wishlist_itmes_arr=explode(',',$all_products['products']);
					
				}else{
					$customerData = Mage::getSingleton('customer/session')->getCustomer();
              		$customerId = $customerData->getId();
              		$wishlist = Mage::getModel('wishlist/wishlist')->loadByCustomer($customerId, true);
              		$wishListItemCollection = $wishlist->getItemCollection();
              		$all_wishlist_itmes_arr=array();
              		foreach ($wishListItemCollection as $item){
              			$all_wishlist_itmes_arr[]=$item->getProductId();
              		}
				}
		?>
			<script type="text/javascript">
				var  wishlist_json_arr ='<?php echo json_encode($all_wishlist_itmes_arr); ?>';
				var arr_wishlist = new Array();
				arr_wishlist= JSON.parse(wishlist_json_arr);				
				function wishlistUpdateList(){
					jQuery(function($){
						jQuery.each( arr_wishlist, function( key, value ) {
							var updatedWishlist=jQuery('#add-to-links-'+value+' a.link-wishlist').attr("data-updatedWishlist");
							if(updatedWishlist==''){
						    	jQuery('#add-to-links-'+value+' a.link-wishlist').html('');				    	
						    	jQuery('#add-to-links-'+value+' a.link-wishlist').html('<span class="listing-wishlist-stock-icon" title="Already in Wishlist"><i class="fa fa-heart fa-custom-icon-red1"></i></span>');
						    	jQuery('#add-to-links-'+value+' a.link-wishlist').attr("data-updatedWishlist", 1);
					      }
				  		});
					});
				}
				//setTimeout(wishlistUpdateList,1000);
				setInterval(
					function(){ wishlistUpdateList() }, 1000
					);
			</script>
		<?php	
		}
	if($moduleName == 'catalog' && $controllerName == 'product' && $actionName == 'view'){
		$_in_wishlist_check = 0;
		$customer_wishlist_login = 0;
		$current_wishlist_product_url = "#";
		$wishlist_hover_title ="Add to Wishlist";
		$current_registery_product = Mage::registry('current_product');
		if(Mage::getSingleton('customer/session')->isLoggedIn()){
			$customer_wishlist_login = 1;
			$current_wishlist_product_url = $this->helper('wishlist')->getAddUrl(Mage::registry('current_product'));
		}
		if ($this->helper('wishlist')->isAllow() && $customer_wishlist_login ==true){
			$current_product_page=Mage::registry('current_product')->getId();
			foreach (Mage::helper('wishlist')->getWishlistItemCollection() as $_wishlist_item){
				if($current_product_page == $_wishlist_item->getProduct()->getId()){
					$_in_wishlist_check = 1;
					$wishlist_hover_title ="Already in Wishlist";
					break;
				}
			}
		}
		if(!Mage::getSingleton('customer/session')->isLoggedIn()){
			$current_wishlist_product_url = $this->helper('wishlist')->getAddUrl(Mage::registry('current_product'));
			$all_products=Mage::helper('all/wishlist')->getProductIntoWishlistCookies();
			$product_id = Mage::registry('current_product')->getId();
			if($all_products['products']!= ''){
				$all_wishlist_itmes_arr=explode(',',$all_products['products']);
				if (in_array($product_id, $all_wishlist_itmes_arr)){
			  		$_in_wishlist_check = 1;
					$wishlist_hover_title ="Already in Wishlist";

			  	}
			}
		}
	?>
	
	<script type="text/javascript">
		var is_in_wishlist_check = <?php echo $_in_wishlist_check ?>;
		var isCustomerLoginWishlist = <?php echo $customer_wishlist_login ?>;
		var currentWishlistProductUrl = '<?php echo $current_wishlist_product_url; ?>';
		var wishlistHoveTitle ='<?php echo $wishlist_hover_title; ?>';
		jQuery( document ).ready(function($) {  
			
			
			if(is_in_wishlist_check==1){
				jQuery('#addTowishlist a ').removeClass('product'); 
				// jQuery('#outer-wrap').removeClass('wishClose_Close_product');
				// jQuery("body").removeClass('openMenu');	 
				jQuery('#addTowishlist a i').addClass('fa-custom-icon-red1');
				jQuery('#addTowishlist a').attr("data-updatedWishlist", 1);
				
				
				
				
				}else{
				jQuery('#addTowishlist a ').addClass('product'); 
				
				$('#addTowishlist a i').addClass('fa-custom-icon-green1');
			}
			
			
		});
	</script>
<?php } ?>
<!--mobile fixed footer-->
<div class="fixedFooter">
	<div class="fi">		
		<?php if(!$this->helper('customer')->isLoggedIn()){ ?> 	
            <a href="<?php echo $this->getBaseUrl() ; ?>customer/account/login/" title="Log In" class="fw"><i class="fa fa-sign-in" aria-hidden="true"></i></a>
			<?php }else{ ?>
            <a href="<?php echo $this->getBaseUrl() ; ?>customer/account/logout/" title="Log Out"><i class="fa fa-sign-out" aria-hidden="true"></i></a>   
		<?php }?>
	</div>
	<div class="fi">
		<?php if(!$this->helper('customer')->isLoggedIn()){ ?> 
			<?php 
					$count_cookies_products_wishlist = Mage::helper('all/wishlist')->countCoookiesWishlistProducts();
					$count_cookies_wishlist_str='';
					if($count_cookies_products_wishlist != ''){
						$count_cookies_wishlist_str=$count_cookies_products_wishlist;
					}
			?>
				<?php if($count_cookies_wishlist_str != ''): ?>
					<a href="<?php echo $this->getBaseUrl()."wishlist" ?>" class="fw wishlist-icon">
						<i class="fa fa-heart" aria-hidden="true"></i>
						<div class="wishlist_count"><span class="mobile-cart-count"><?php echo $count_cookies_wishlist_str; ?></span></div>
					</a>	
				<?php else: ?>
					<a href="#" class="fs wis wishlist-icon"><i class="fa fa-heart-o" aria-hidden="true"></i></a>
				<?php endif; ?>
			<?php }else{ ?>
			<?php if($this->helper('customer')->isLoggedIn())
				{   
					$customer = Mage::getSingleton('customer/session')->getCustomer(); 
					$wishlist = Mage::getModel('wishlist/wishlist')->loadByCustomer($customer, true); 
					$itemcount = $wishlist->getItemsCount() ; 
				?>			       
				<a href="<?php echo $this->getBaseUrl()."wishlist" ?>" class="fw wishlist-icon"><i class="fa fa-heart" aria-hidden="true"></i>        
					<?php if($itemcount>0)
						{
						?>
						<div class="wishlist_count">
							<?php echo '<span class="mobile-cart-count">' . $this->__('%s', $itemcount) . '</span>';    ?>
							</div><?php     
						} 
					?>
				</a>
				<?php 
				}
				else 
				{ ?> 
				<li class="fi wis">
					<a class="fw wishlist-icon"><i class="fa fa-heart" aria-hidden="true"></i></a>
				</li>
				<?php 
				}
			?>
		<?php }?>
	</div>
	
	<div class="fi">
		<a href="<?php echo $this->getUrl('') ?>" class="fh"><span class="fa_home"><i class="fa fa-home" aria-hidden="true"></i><span></a>
		</div>
		<div class="fi">
			<a class="fc" href="tel:+912261066262" ><i class="fa fa-phone" aria-hidden="true"></i></a>
		</div>	
        <div class="fi">
			<a href="<?php echo $this->getBaseUrl() ; ?>my-store" title="My Store"><i class="fa fa-shopping-bag" aria-hidden="true"></i></a>
		</div>
		</div>
		<!--end fixed footer-->
		<div id="mobile_cart">
			<div class="close_cart"><i class="fa fa-times" aria-hidden="true"></i></div>
			<div class="cart_item">
				<div class="bagIcon"><i class="fa fa-shopping-bag list_fa" aria-hidden="true"></i></div>
				<p class="sce">Your shopping bag is empty</p>
				<a href="https://www.candere.com"><button class="cont_button">Continue Shopping</button></a>
			</div>
		</div>
		<div id="mobile_wishlist">
			<div class="close_cart"><i class="fa fa-times" aria-hidden="true"></i></div>
			<div class="wish_item">
				<div class="bagIcon"><i class="fa fa-heart-o list_fa" aria-hidden="true"></i></div>
				<div class="userFormRight">
					<span id="wishlistloginpass" style="color:#FF0000"></span>
					<span id="text-varemail_none1" style="color:#FF0000"></span>
					<span id="wishlistemail_userLogin" style="color:#FF0000"></span>
					<span id="wishlisttexterrorlogin" style="color:#FF0000"></span>
					<span id="texterrorlogin1" style="color:#FF0000"></span>
					<span id="texterrorloginwrongLogin" style="color:#FF0000"></span>
					<span id="varemail_nonewish" style="color:#FF0000"></span>
					<input type="text" name="email_wishlistLogin" id="email_wishlistLogin"  class="userRegisterName" placeholder="Enter your email Id">
					<input type="password" name="userPassword_wishlistLogin" id="userPassword_wishlistLogin" class="userRegisterName" placeholder="Enter password"> 
					<div id="wishlistuserloginHeader" class="cont_button" style="margin-top:25px;" >SIGN IN</div> 
					<div id="wishlistutm_newsletter_spinner_wish" style="display:none;">
						<img width="16" height="16" alt="loading" src="<?php echo $this->getSkinUrl('images/lazy_image_loader/loader.gif');?>">
					</div>
				</div>		
			</div>
		</div>
		<!--mobile cart and wishlist close-->
		<!-- back-to-top-->
		<div id="back-top" style="display: block;"><a href="#top" class="b_t_t"><i class="fa fa-angle-up" aria-hidden="true"></i></a></div>
		<!-- back-to-top-->
		<script>
			jQuery(document).ready(function(){
				//back-to-top
				// hide #back-top first
				jQuery("#back-top").hide();
				// fade in #back-top
				jQuery(function () {
					jQuery(window).scroll(function () {
						if (jQuery(this).scrollTop() > 100) {
							jQuery('#back-top').fadeIn();
							} else {
							jQuery('#back-top').fadeOut();
						}
					});
					
					// scroll body to 0px on click
					jQuery('#back-top a').click(function () {
						jQuery('body,html').animate({
							scrollTop: 0
						}, 800);
						return false;
					});
				});	
				// user onclick		
				jQuery('.fu').click(function() {
					jQuery('#outer-wrap').toggleClass('leftIconOpen');
					jQuery("#outer-wrap").toggleClass('sideIconOpen');
					jQuery('body').toggleClass('openMenu');
					jQuery('#outer-wrap').removeClass('cart_Close');
					jQuery('body').removeClass('openMenu2');
					jQuery("body").removeClass('openMenu3');
					jQuery('#outer-wrap').removeClass('wishClose_Close');
					jQuery('#nav').removeClass('sideOpen');	
					jQuery("#outer-wrap").removeClass('menuOpen');	
					jQuery('body').removeClass('search_2');
					jQuery('body').removeClass('openMenu4');
				});
				jQuery('.sideWrapper').click(function() {
					jQuery("#outer-wrap").removeClass('leftIconOpen');
					jQuery("#outer-wrap").removeClass('sideIconOpen');
					jQuery("body").removeClass('openMenu');			
					
				});	
				jQuery('.fu a , .wis a , .fsi a .for_search a' ).click(function(event) {
					event.preventDefault();
				});
				// cart cclose		
				jQuery('.car').click(function() {
					jQuery('body').removeClass('openMenu');	
					jQuery("body").removeClass('openMenu3');			
					jQuery('#outer-wrap').toggleClass('cart_Close');
					jQuery('#outer-wrap').removeClass('leftIconOpen');
					jQuery("#outer-wrap").removeClass('sideIconOpen');
					jQuery('body').removeClass('search_2');
					jQuery('#outer-wrap').removeClass('wishClose_Close');
					jQuery('body').toggleClass('openMenu2');
					jQuery("body").removeClass('openMenu4');
					jQuery('#nav').removeClass('sideOpen');	
					jQuery("#outer-wrap").removeClass('menuOpen');				
					
					
				});
				
				// wish cclose		
				jQuery('.wis').click(function() {
					jQuery('#outer-wrap').toggleClass('wishClose_Close');
					jQuery("body").toggleClass('openMenu3');
					jQuery("body").removeClass('openMenu');			
					jQuery('#outer-wrap').removeClass('leftIconOpen');
					jQuery("#outer-wrap").removeClass('sideIconOpen');
					jQuery('#nav').removeClass('sideOpen');	
					jQuery("#outer-wrap").removeClass('menuOpen');	
				});
				
				jQuery(".close_cart").click(function() {			
					jQuery('#outer-wrap').removeClass('wishClose_Close');
					jQuery("body").removeClass('openMenu');
					jQuery("body").removeClass('openMenu3');
					jQuery('#outer-wrap').removeClass('leftIconOpen');
					jQuery('#outer-wrap').removeClass('cart_Close');
					jQuery("body").removeClass('openMenu2');
				});	
				//mobile cart and wishlist close		
				jQuery( "#wishlistuserloginHeader" ).click(function() 
				{
					var email=jQuery("#email_wishlistLogin").val();		   
					var userPassword_wishlistLogin=jQuery("#userPassword_wishlistLogin").val();		   
					var atsymb = email.indexOf("@");
					var dotsymb = email.lastIndexOf(".");
					var base_url = '<?php echo Mage::getBaseUrl() ;?>';			
					if (email == null || email == "" )
					{
						//alert("Please Enter the valid Email ID");
						jQuery("#wishlistemail_userLogin").css('display','block');
						jQuery("#varemail_nonewish").css('display','none');
						jQuery("#wishlistemail_userLogin").text("Please Enter the Email ID!");
						return false;
						} else{			
						jQuery("#wishlistemail_userLogin").css('display','none'); 			  
					}
					if (atsymb < 1 || dotsymb < atsymb + 2 || dotsymb + 2 >= email.length)
					{
						// alert("Please Enter the valid Email ID");
						jQuery("#wishlistemail_userLogin").css('display','none'); 
						jQuery("#varemail_nonewish").css('display','block');
						jQuery("#varemail_nonewish").text("Please Enter the valid Email ID!");
						return false;
						} else{			
						jQuery("#varemail_nonewish").css('display','none');			  
					} 		  
					if(userPassword_wishlistLogin==null || userPassword_wishlistLogin==""){			 
						jQuery("#wishlistloginpass").css('display','block');
						jQuery("#wishlistloginpass").text("Please Enter the password!");
						return false;
						}else {			  
						jQuery("#wishlistloginpass").css('display','none');			  
					}		  
					jQuery( "#wishlistutm_newsletter_spinner_wish").css('display', 'block');
					jQuery.ajax
					({
						url: base_url + 'customer/account/addtowishlistLogin/', 
						type: "POST", 
						data: {'email': email,'userPassword_wishlistLogin':userPassword_wishlistLogin},	
						success: function(data){
							if(data=='true'){					
								window.location.href = base_url + 'wishlist';
							}
							if(data=='none'){					
								jQuery("#wishlisttexterrorlogin").text("Email Id does not Exist,Please Register new Account!");
								jQuery("#wishlisttexterrorlogin").css('display','block');
								jQuery( "#wishlistutm_newsletter_spinner_wish").css('display', 'none');							
								}else {						
								jQuery("#wishlisttexterrorlogin").css('display','none');						
							}					
							if(data=='wrong_user'){					
								jQuery("#texterrorloginwrongLogin").text("Email Id OR Password Wrong!");
								jQuery( "#wishlistutm_newsletter_spinner_wish").css('display', 'none');
								jQuery("#texterrorloginwrongLogin").css('display','block');
								} else{						
								jQuery("#texterrorloginwrongLogin").css('display','none');					
							}
						}
					});
				});	
				//footer search click		
				jQuery('.cms-home .fsi').click(function() {
					if(jQuery('#outer-wrap').hasClass('wishClose_Close')){				
					}
					else{
						jQuery('#mobile_search').focus();
					}
					jQuery('#outer-wrap').removeClass('leftIconOpen');
					jQuery("#outer-wrap").removeClass('sideIconOpen');
					jQuery("body").removeClass('openMenu');
					jQuery('#outer-wrap').removeClass('wishClose_Close');
					jQuery('#outer-wrap').removeClass('cart_Close');
					jQuery("body").removeClass('openMenu2');
					jQuery("body").removeClass('openMenu3');
					jQuery('#nav').removeClass('sideOpen');	
					jQuery("#outer-wrap").removeClass('menuOpen');	
				});
				//remove href from footer home btn
				/*if(jQuery('body').hasClass('canderecheckout-index-address') || jQuery('body').hasClass('canderecheckout-index-payment')){
					jQuery('.fh').removeAttr("href");
				}*/
				
			});
		</script>
		
		<!--google marketing-->
		<script>
			window['GoogleAnalyticsObject'] = 'ga';
			window['ga'] = window['ga'] || function() {
				(window['ga'].q = window['ga'].q || []).push(arguments)
			};
		</script>
		<?php
			
			$page_type = Mage::app()->getFrontController()->getRequest()->getControllerName(); 
			if($page_type == 'index')
			{
			?>
			<script type="text/javascript">
				var google_tag_params = {
					ecomm_pagetype: 'home',
					ecomm_prodid: '', 
					ecomm_totalvalue: 0
				};
			</script>
			<script>
				try {
					ga('set', 'dimension1', window.google_tag_params.ecomm_prodid.toString());
				} catch (e) {}
				try {
					ga('set', 'dimension2', window.google_tag_params.ecomm_pagetype.toString()); 
				} catch (e) {}
				try {
					ga('set', 'dimension3', window.google_tag_params.ecomm_totalvalue.toString()); 
				} catch (e) {}
				ga('send', 'event', 'page', 'visit', window.google_tag_params.ecomm_pagetype.toString(), {
					'nonInteraction': 1
				});
			</script>
			<?php
			}
			elseif($page_type == 'category')
			{
			?>
			<script type="text/javascript">
				var google_tag_params = {
					ecomm_pagetype: 'category',
					ecomm_prodid: '',
					ecomm_totalvalue: 0
				};
			</script>
			<script>
				try {
					ga('set', 'dimension1', window.google_tag_params.ecomm_prodid.toString());
				} catch (e) {}
				try {
					ga('set', 'dimension2', window.google_tag_params.ecomm_pagetype.toString()); 
				} catch (e) {}
				try {
					ga('set', 'dimension3', window.google_tag_params.ecomm_totalvalue.toString()); 
				} catch (e) {}
				ga('send', 'event', 'page', 'visit', window.google_tag_params.ecomm_pagetype.toString(), {
					'nonInteraction': 1
				});
			</script>
			<?php
			}
			elseif($page_type == 'product')
			{
				$product_id = Mage::registry('current_product')->getId();
				$product_id = Mage::getModel('catalog/product')->load($product_id)->getSku();
				$product_price = Mage::registry('current_product')->getNewPrice();
			?>
			<script type="text/javascript">
				var google_tag_params = {
					ecomm_pagetype: 'product',
					ecomm_prodid: '<?php echo $product_id; ?>',
					ecomm_totalvalue: parseFloat('<?php echo $product_price; ?>')
				};
			</script>
			<script>
				try {
					ga('set', 'dimension1', window.google_tag_params.ecomm_prodid.toString());
				} catch (e) {}
				try {
					ga('set', 'dimension2', window.google_tag_params.ecomm_pagetype.toString()); 
				} catch (e) {}
				try {
					ga('set', 'dimension3', window.google_tag_params.ecomm_totalvalue.toString()); 
				} catch (e) {}
				ga('send', 'event', 'page', 'visit', window.google_tag_params.ecomm_pagetype.toString(), {
					'nonInteraction': 1
				});
			</script>
			<?php 
			}
			elseif($page_type == 'cart')
			{
			?>
			<script>
				var id = new Array();
				var price = new Array();
			</script>
			<?php
				$cart = Mage::getModel('checkout/session')->getQuote();
				foreach ($cart->getAllItems() as $item)
				{
					$product_id = $item->getProductId();
					$product_id = $item->getSku();
					$product_id_all = $product_id_all.','.$product_id ;
					$productPrice = $item->getPrice();
					$productPrice_all = $productPrice_all.','.$productPrice;
				?>
				<Script>
					id.push('<?php echo $product_id; ?>');
					price.push(parseFloat('<?php echo $productPrice; ?>'));
				</Script>
				<?php
				}
				$product_id_all=substr($product_id_all, 1);
				$productPrice_all=substr($productPrice_all, 1);
				
			?>
			<script type="text/javascript">
				
				var google_tag_params = {
					ecomm_pagetype: 'cart',
					ecomm_prodid: id,
					ecomm_totalvalue: price
				};
			</script>
			<script>
				try {
					ga('set', 'dimension1', window.google_tag_params.ecomm_prodid.toString());
				} catch (e) {}
				try {
					ga('set', 'dimension2', window.google_tag_params.ecomm_pagetype.toString()); 
				} catch (e) {}
				try {
					ga('set', 'dimension3', window.google_tag_params.ecomm_totalvalue.toString()); 
				} catch (e) {}
				ga('send', 'event', 'page', 'visit', window.google_tag_params.ecomm_pagetype.toString(), {
					'nonInteraction': 1
				});
			</script>
			<?php
			}
		?>
		<!-- google marketing-->
		<?php
			$baseUrl = Mage::getBaseUrl();
			$currentUrl = Mage::helper('core/url')->getCurrentUrl();
			if (!$this->helper('customer')->isLoggedIn() && $baseUrl==$currentUrl) 
			{ 
			?>
			<style>
				.popContainerbg {
				position: fixed;
				top: 0;
				right: 0;
				bottom: 0;
				left: 0;
				width: 100%;
				height: 100%;
				overflow: auto;
				z-index:99;
				box-sizing: border-box;
				background-color: rgb(0,0,0);
				background-color: rgba(0,0,0,0.6);
				text-align: center;
				opacity:0;
				visibility: hidden;
				transition: all 1s ease-in-out;
				}
				#popWrapper{
				display:block;
				width:300px;
				margin:0 auto;
				margin-top:40px;
				background-color:#f5f6f6;
				color:#000000;
				box-sizing:border-box;
				-moz-box-sizing:border-box;
				overflow:hidden;
				position:relative;	
				border:1px solid #babbbb;
				-moz-transform:translateY(-1000px);
				-webkit-transform:translateY(-1000px);
				transform:translateY(-1000px);
				-moz-transition: transform .3s ease-in-out;
				-webkit-transition: transform .3s ease-in-out;
				transition: transform 1s ease-in-out;
				}
				.popupCloseButton{
				position:absolute;
				top:3px;
				right:3px;
				font-size:12px;
				background-color:#2fbccc;
				height:20px;
				width:20px;
				line-height:20px;
				text-align:center;
				border-radius:50%;
				cursor:pointer;
				color:#fff;
				}
				#popWrapper .rightHolder{
				display:inline-block;
				width:100%;
				padding:5px 10px;
				box-sizing: border-box;
				float:right;
				border-left:2px solid #fff;
				}
				.popHeading{
				font-size:24px;
				font-weight:normal;
				line-height:31px;
				text-transform:uppercase;
				letter-spacing:2px;
				color:#343339;
				margin-bottom:28px;
				text-align:center;
				}
				.popHeading span{
				color:#df6061;
				}
				.emailHolder{
				position: relative;
				display:block;
				overflow: hidden;
				}
				.popupErrorname{
				position: absolute;	
				bottom:3px;
				display:none;
				text-align: left;
				left:0;
				}
				.pop_mobile{
				position: relative;
				}
				.popInputText{
				font-size:13px;
				font-weight:normal;
				padding-top:8px;
				padding-bottom:16px;
				}
				.popTandC{
				font-size:13px;
				font-weight:normal;
				display:inline-block;
				}
				.popSubC{
				font-size:13px;
				font-weight:normal;
				display:inline-block;
				line-height:19px;
				}
				.popSignup{
				font-size:15px;
				font-weight:normal;
				width:100%;
				height:30px;
				text-align: center;
				line-height:30px;
				background-color:#2fbccc;
				border:none;
				color:#fff;
				text-transform:uppercase;
				cursor:pointer;
				margin-top:8px;
				position: relative;
				}
				.popSignup:focus{
				border:none;
				outline: none;
				}
				.popFooter{
				font-size:10px;
				line-height:18px;
				font-weight:normal;
				text-align:center;
				display:block;
				clear: both;
				padding:5px 0
				}
				.emailHolder{
				position: relative;
				display:block;
				overflow: hidden;
				}
				.userNameSymbol{
				width:40px;
				height:40px;
				display:block;
				background-color:#d5d7d6;
				overflow: hidden;
				line-height:40px;
				position: absolute;
				text-align: center;
				font-size: 20px;
				color:#fff;
				}
				.popHomeEmail{
				width:100%;
				display:block;
				padding: 0 10px;
				box-sizing:border-box;
				-moz-box-sizing:border-box;
				margin-bottom:20px!important;
				height:30px;
				line-height:30px;
				border: 1px solid #d5d7d6!important;
				}
				.popHomeEmail:hover{
				focus:none;
				}
				.popHomeEmailFName{
				width:50%;
				display:inline-block;
				padding: 0 10px;
				box-sizing:border-box;
				-moz-box-sizing:border-box;
				margin-bottom:30px;
				height:40px;
				line-height: 40px;
				border: 1px solid #d5d7d6!important;
				float:left;
				margin-right:5%;
				}
				.popHomeEmailLName{
				width:45%;
				display:inline-block;
				padding: 0 10px;
				box-sizing:border-box;
				-moz-box-sizing:border-box;
				margin-bottom:30px;
				height:40px;
				line-height: 40px;
				border: 1px solid #d5d7d6!important;
				
				}
				.popCheckBox{
				vertical-align:middle;
				display:inline-block;
				width:15px;
				height:15px;
				margin-right:8px;
				}
				.popupError{
				display:none;				
				color:red;
				font-size:12px;
				}
				.popupErrorfname{
				position: absolute;	
				bottom:8px;
				display:none;	
				}
				.popupErroremail{
				position: absolute;	
				bottom:3px;
				display:none;
				}
				.popupErrormobile{
				position: absolute;	
				bottom:3px;
				display:none;
				}
				.socialLoginpopup{
				display: block;
				text-align: center;
				overflow: hidden;
				width:100%;
				margin-top:5px;
				}
				.popupSocialBTN {
				display: inline-block;
				vertical-align: middle;
				text-align: center;
				width:45%;
				height:40px;
				line-height:40px;
				margin-right:5%;
				color: #fff;
				cursor: pointer;
				}
				.popupSocialBTN:last-child{
				margin-right:0;
				}
				.popupgpl {
				background-color: #e44120;
				font-size: 15px;
				}
				.popupfbl {
				background-color: #3b589c;
				font-size:15px;
				}
				.popupgpl .fa {
				margin-right:10px;
				}
				.popupgpl span{
				font-size:16px
				}
				.popupfbl span{
				font-size:16px
				}
				.popupfbl .fa{
				margin-right:10px;
				}
				.tcp{
				font-size: 10px;
				text-align: center;
				display:block;
				margin-top:8px;
				}
				.popMobile{
				display:block;
				width:100%;
				}
				.popMobile img{
				display:block;
				width:100%;
				}
				.successmessage
				{
				margin-top:25% ;
				}
				@media(max-width:370px){
				#popWrapper{
				margin-top:45px;
				}
				}
				.pop_name{
				display: inline-block;
				width:49%;
				}
				.pop_mobile{
				display: inline-block;
				width:49%;
				}
				/* loader*/
				.spinner1 {
				margin:0 auto;
				width: 50px;
				height: 30px;
				text-align: center;
				font-size: 10px;
				}
				.spinner1 > div{
				background-color: #fff;
				height: 100%;
				width: 6px;
				display: inline-block;
				
				-webkit-animation: sk-stretchdelay 1.2s infinite ease-in-out;
				animation: sk-stretchdelay 1.2s infinite ease-in-out;
				}
				.spinner1 .rect2 {
				-webkit-animation-delay: -1.1s;
				animation-delay: -1.1s;
				}
				.spinner1 .rect3 {
				-webkit-animation-delay: -1.0s;
				animation-delay: -1.0s;
				}
				.spinner1 .rect4{
				-webkit-animation-delay: -0.9s;
				animation-delay: -0.9s;
				}
				.spinner1 .rect5 {
				-webkit-animation-delay: -0.8s;
				animation-delay: -0.8s;
				}
				
				@-webkit-keyframes sk-stretchdelay {
				0%, 40%, 100% { -webkit-transform: scaleY(0.4) }  
				20% { -webkit-transform: scaleY(1.0) }
				}
				
				@keyframes sk-stretchdelay {
				0%, 40%, 100% { 
				transform: scaleY(0.4);
				-webkit-transform: scaleY(0.4);
				}  20% { 
				transform: scaleY(1.0);
				-webkit-transform: scaleY(1.0);
				}
				}
				.socialLoginwithFBgmail{
				display:block;
				text-align: center;
				overflow: hidden;
				}
				.newgpBTN{
				vertical-align: middle;
				text-align: center;
				height: 30px;
				width: 30px;
				line-height:30px;
				border-radius: 50%;
				margin-right:5px;
				color: #fff;
				cursor: pointer;
				background-color: #e44120;
				position: relative;
				display: inline-block;
				}
				.newfbBTN {
				vertical-align: middle;
				text-align: center;
				height: 30px;
				width: 30px;
				line-height: 30px;
				border-radius: 50%;
				color: #fff;
				cursor: pointer;
				background-color:  #4476b1;
				position: relative;
				display: inline-block;
				}
				.gm{
				background-color: #e44120;
				color: #fff;
				}
				.fbi{
				background-color: #3b589c;
				color: #fff;
				}
				#popContainer .socialFB_BTN{
				text-align: center;
				background-color: #4476b1;
				color: #fff;
				height: 30px;
				width: 30px;
				line-height: 30px;
				border-radius: 50%;
				display: inline-block;
				padding-left:0;
				}
				#popContainer .newgpBTN >a li.socialGP_BTN span {
				display: none;
				}
				#popContainer .newfbBTN > li.socialFB_BTN span {
				display: none;
				}
				#popContainer .socialGP_BTN{
				color:#fff!important;
				}
				.signinWith{
				margin: 10px 0!important;
				}
			</style> 
			<!--homepage popup-->
			<div id="popContainer" class="popContainerbg">
				<div id="popWrapper">
					<div class="popupCloseButton" id="popupcloseBTN">X</div>
					<div class="popMobile"><img src="<?php echo $this->getSkinUrl() ;?>images/mobile_popup.jpg"></div>
					<div class="rightHolder">
						<div class="emailHolder">
							<div class="pop_name">                                                  	
								<input type="text" name="name" class="popHomeEmail" placeholder="Name" id="name" required="">
								<p class="popupError popupErrorname">Please enter Name</p>
							</div>
							<div class="pop_mobile">                                                  	
								<input type="text" name="mobile" class="popHomeEmail" placeholder="Moblie No" id="mobile" required="">
								<p class="popupError popupErrormobile">Please enter Mobile no</p>
							</div>
						</div>
						
						<div class="emailHolder">                            	            		
							<input type="email" required="" name="email" placeholder="Email Address" id="email" autocomplete="off"  class="popHomeEmail" required="" >
							<p class="popupError popupErroremail">Please enter a valid email</p>
						</div>  
						
						<input type="checkbox" name="terms" class="popCheckBox" value="" checked><span class="popTandC">I agree to the terms and Conditions.</span>
						<button class="popSignup" title="SUBMIT" id="submitPopup" type="submit">Sign Me Up!
							
							<div id="utm_newsletter_spinner_r1" style="display:none; position: absolute; top:0; right:5px;">
								<div class="spinner1">
									<div class="rect1"></div>
									<div class="rect2"></div>
									<div class="rect3"></div>
									<div class="rect4"></div>
									<div class="rect5"></div>
								</div>
							</div></button>			
							<div class="signinWith">
								<span>OR</span>
							</div>
							<div class="socialLoginwithFBgmail"> 
								<div class="newgpBTN gm">
									<?php echo $this->getChildHtml('inchoo_googleconnect_checkout')?>						
								</div>
								<div class="newfbBTN fbi">
									<?php echo $this->getChildHtml('ced_checkout_facebook_button')?>					
								</div>   
							</div>  
							<p class="tcp">Already a member with us? <a style="text-decoration: underline;" href="<?php echo Mage::getBaseUrl(); ?>customer/account/login/">Sign in</a></p>
							
							<!--fb-->	
							
							<!--/fb-->
					</div>
				</div>
			</div>
		
			
			<script>	
				$jsq = jQuery.noConflict();
				
				function createCookie(name, value, days) {
					
					var expires;
					var date = new Date();
					
					date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
					
					expires = "; expires=" + date.toGMTString();
					
					document.cookie = encodeURIComponent(name) + "=" + encodeURIComponent(value) + expires + "; path=/";
					
					
				}
				
				function readCookie(name) {
					var nameEQ = encodeURIComponent(name) + "=";
					var ca = document.cookie.split(';');
					for (var i = 0; i < ca.length; i++) {
						var c = ca[i];
						while (c.charAt(0) === ' ') c = c.substring(1, c.length);
						if (c.indexOf(nameEQ) === 0) return decodeURIComponent(c.substring(nameEQ.length, c.length));
					}
					return null;
				}
				
				
				
				
				//for appear popup function
				jQuery(function () {
					jQuery(window).on('load', function () {
						
						if(!readCookie('default_pop_up_outside_click_close') && !readCookie('default_pop_up_close')) {
							setTimeout(function(){
								jQuery('#popWrapper').css('transform', 'translateY(0)');
								jQuery('.popContainerbg').css('opacity', '1');						 
								jQuery('.popContainerbg').css('visibility', 'visible');	
								window.addEventListener('click', function(e){   
									if (document.getElementById('popWrapper').contains(e.target)){
										// Clicked in box
										} else{
										createCookie('default_pop_up_outside_click_close','xyz','0.5');
										jQuery('#popContainer').css('display','none');
									}
								});
								jQuery('#popupcloseBTN').click(function(){
									createCookie('default_pop_up_outside_click_close','xyz','0.5');
									jQuery('#popContainer').css('display','none');
								});
							},10000);
							
							
						}
						
						
					});
				});		
				//form validation function			  
				jQuery(document).ready(function() {	
					
					var base_url = '<?php echo Mage::getBaseUrl() ;?>';	 
					if (window.location.protocol == "https:") {
						base_url = base_url.replace("http://","https://");      
					}  	 
					var name_error 				= 0;
					var email_error 				= 0;
					
					var mobile_number_error 		= 0;
					jQuery('#popContainer #submitPopup, #popContainer .emailHolder input').keydown(function(event){
						var keyCode = (event.keyCode ? event.keyCode : event.which);
						if (keyCode == 13) {jQuery('#popContainer #submitPopup').trigger('click');}
					});
					jQuery('#submitPopup').click(function(e) {		
						var name = jQuery('#name').val();
						var email = jQuery('#email').val();
						var mobile = jQuery('#mobile').val();	
						
						
						if(name.length==0){
							jQuery('.popupErrorname').css("display","block");
							jQuery("#name").focus();
							name_error = 1;	
							return false;
						}
						
						else
						{
							name_error = 0;	
						}
						
						// Validating Mobile Field.
						//else if (rmobile == null || rmobile == "" ||  (rmobile.length>1 && rmobile.length<=9) || rmobile.length>10) {
						var pattern = /^\d{10}$/;
						if( (!mobile) || (!pattern.test(mobile)) || (mobile.length!=10)  ){
							jQuery('.popupErrorname').css("display","none");
							jQuery('.popupErrormobile').css("display","block");
							jQuery("#mobile").focus();
							mobile_number_error = 1;	
							return false;
						}
						
						else
						{
							mobile_number_error = 0;	
						}
						var email_regex = /^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/;			
						
						// Validating Email Field.
						if (!email.match(email_regex) || email.length == 0) {
							jQuery('.popupErrorname').css("display","none");
							jQuery('.popupErrormobile').css("display","none");
							jQuery('.popupErroremail').css("display","block"); 
							jQuery("#email").focus();
							email_error = 1;	
							return false;
						}
						else
						{
							email_error = 0;	
						}			
						
						
						if ( name_error == 1 || email_error == 1 || mobile_number_error == 1) 
						{
							
							return false ;
						}
						else
						{
							jQuery('.popupErrorname').css("display","none");
							jQuery('.popupErrormobile').css("display","none");
							jQuery('.popupErroremail').css("display","none"); 
							
							$jsq('#utm_newsletter_spinner_r1').css('display', 'block');				
							$jsq.post(base_url + 'customer/account/pushuserdata/', {'firstname':name , 'email': email,  'mobile_number':mobile}, function(resp) {
								if(resp=='Email_Exists')
								{
									jQuery('.popupErroremail').css("display","block").html('email id already used'); 
								}
								else
								{
									jQuery('.rightHolder').html('Thank you for signing up! We have sent account details to your registered email address.');
									
									createCookie("default_pop_up_close","xyz","365") ;
									
									setTimeout(function(){
										jQuery('.popContainerbg').css('display', 'none'); 
										window.location = base_url ;
									},7000);
								}
								
								
								
								
							});
							
							
							
							
							
							
							
						}				
					});
				});
				
			</script> 
			<?php 
			} 
		?>
		
		
		<!--call back-->
		<?php 
			
			$page_type = Mage::app()->getFrontController()->getRequest()->getControllerName(); 
			if($page_type == 'product')
			{ 
				$callback_name =  '' ;
				$callback_email = '' ;
				$callback_phone_no = '' ;
				$callback_id = Mage::getModel('core/cookie')->get('callback_id');
				$callback_product_id = Mage::registry('current_product')->getId();
				$callback_product_sku = Mage::getModel('catalog/product')->load($callback_product_id)->getSku();
				$callback_product_name = Mage::getModel('catalog/product')->load($callback_product_id)->getName();
				
				
				if (Mage::getSingleton('customer/session')->isLoggedIn()) {
					
					$c = Mage::getSingleton('customer/session')->getCustomer();
					$customer = Mage::getModel('customer/customer')->load($c->getId());
					$callback_email = $customer->getEmail();
					$callback_name = $customer->getFirstname().' '.$customer->getLastname();
					if($customer->getPrimaryBillingAddress())
					{
						$callback_phone_no  = $customer->getPrimaryBillingAddress()->getTelephone();
					}
					
				}
				else 
				{
					
					
					
					
					$callback_name = Mage::getModel('core/cookie')->get('callback_name');
					$callback_email = Mage::getModel('core/cookie')->get('callback_email');
					$callback_phone_no = Mage::getModel('core/cookie')->get('callback_contactno');
				}
				
			?>
			
	 
			
			<div id="mob_popContainerbg" class="mob_popContainerbg">
				<div class="mobile_threeMin_wrapper" id="callback_popup_wrapper">
					<img src="<?php echo $this->getSkinUrl() ; ?>images/callback.jpg">
					<div class="mobile_popupCloseButton" id="popupcloseBTN">X</div>		
					<div class="d_g_input_area">	
						<div class="emailHolder">                            	            		
							<input type="text"  name="name" placeholder="Name" id="name" autocomplete="off"   value= "<?php echo $callback_name?$callback_name:''  ;  ?>"  class="popEmail">
							<p class="popupErrorname popup_Erroremail">Please enter a valid Name</p>
						</div>  			
						<div class="emailHolder">                            	            		
							<input type="email"  name="email123" placeholder="Email ID" id="email123" autocomplete="off" value= "<?php echo $callback_email?$callback_email:'' ;  ?>" class="popEmail">
							<p class="popupErroremail popup_Erroremail">Please enter a valid email</p>
						</div>  
						<div class="emailHolder">                                                  	
							<input type="text" name="mobile" class="popEmail" placeholder="Moblie No" value= "<?php echo $callback_phone_no?$callback_phone_no:'' ;  ?>" id="mobile" >
							<p class="popupErrormobile popup_Erroremail">Please enter your mobile number</p>
						</div>
						<button class="popSignup" title="Call Me" id="submitPopup" type="submit">Call Me Back
							<div id="utm_newsletter_spinner_r1" style="display:none; position: absolute; top:0; right:5px;">
								<div class="spinner1">
									<div class="rect1"></div>
									<div class="rect2"></div>
									<div class="rect3"></div>
									<div class="rect4"></div>
									<div class="rect5"></div>
								</div>
							</div></button>
							<p class="orText">Or</p>
							<a target="_blank" href="https://api.whatsapp.com/send?phone=918291416421&text=Hi!%20I%20had%20some%20queries%20regarding%20shopping%20at%20Candere.%20Could%20you%20help%20me%20with%20it?"><img src="<?php echo $this->getSkinUrl() ; ?>images/whatsapp.jpg"></a>
					</div>
				</div>
			</div>
			
			<script type = "text/javascript">
				function createCookie(name, value, days) {
					
					var expires;
					var date = new Date();
					
					date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
					
					expires = "; expires=" + date.toGMTString();
					
					document.cookie = encodeURIComponent(name) + "=" + encodeURIComponent(value) + expires + "; path=/";
					
					
				}
				
				function readCookie(name) {
					var nameEQ = encodeURIComponent(name) + "=";
					var ca = document.cookie.split(';');
					for (var i = 0; i < ca.length; i++) {
						var c = ca[i];
						while (c.charAt(0) === ' ') c = c.substring(1, c.length);
						if (c.indexOf(nameEQ) === 0) return decodeURIComponent(c.substring(nameEQ.length, c.length));
					}
					return null;
				}
				
				
				jQuery('document').ready(function(){
					var callback_id  = '<?php echo $callback_id ;  ?>' ;	
					var callback_product_id  = '<?php echo $callback_product_id ;  ?>' ;	
					if(callback_id==callback_product_id){
						
						jQuery('.d_g_input_area').css('margin','65px auto').html('Your callback request for this product has already been registered');
					}
					
					jQuery('#rcb').on('click', function(){
						
							jQuery('#outer-wrap').addClass('popupAdded');
							if(jQuery('#outer-wrap').hasClass('popupAdded')){	
								jQuery('body').addClass('openMenu');
							} 
						jQuery('.mobile_threeMin_wrapper').css('transform', 'translateY(0)');
						jQuery('.mob_popContainerbg').css('opacity', '1');						 
						jQuery('.mob_popContainerbg').css('visibility', 'visible');	
						
						
						jQuery(window).on('click', function (event) {
							if (event.target == mob_popContainerbg) {
								jQuery('.mob_popContainerbg').css('opacity', '0');						 
								jQuery('.mob_popContainerbg').css('visibility', 'hidden');
								jQuery('body').removeClass('openMenu');
							}
						});	
						
						
						try {
							jQuery("#popupcloseBTN").on("click",function() {
								jQuery('.mob_popContainerbg').css('opacity', '0');						 
								jQuery('.mob_popContainerbg').css('visibility', 'hidden');	
								jQuery('body').removeClass('openMenu');
								
							});
							
						}
						catch(e){}	
						
						
						//form validation function			  
						jQuery(document).ready(function() {	
							
							var callback_product_name = '<?php echo $callback_product_name ;  ?>' ;
							var callback_product_id = '<?php echo $callback_product_id ;  ?>' ;
							var callback_product_sku = '<?php echo $callback_product_sku ;  ?>' ;
							var base_url = '<?php echo Mage::getBaseUrl() ;?>';	 
							if (window.location.protocol == "https:") {
								base_url = base_url.replace("http://","https://");      
							}  	 
							var name_error 				= 0;
							var email_error 				= 0;
							
							var mobile_number_error 		= 0;
							jQuery('#mob_popContainerbg #submitPopup, #mob_popContainerbg .emailHolder input').keydown(function(event){
								var keyCode = (event.keyCode ? event.keyCode : event.which);
								if (keyCode == 13) {jQuery('#mob_popContainerbg #submitPopup').trigger('click');}
							});
							jQuery('#submitPopup').click(function(e) {		
								var name = jQuery('#name').val();
								var email = jQuery('#email123').val();
								var mobile = jQuery('#mobile').val();	
								
								
								if(name.length==0){
									jQuery('.popupErrorname').css("display","block");
									jQuery("#name").focus();
									name_error = 1;	
									return false;
								}
								
								else
								{
									name_error = 0;	
								}
								
								var email_regex = /^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/;			
								
								// Validating Email Field.
								if (!email.match(email_regex) || email.length == 0) {
									
									jQuery('.popupErrorname').css("display","none");
									
									jQuery('.popupErroremail').css("display","block"); 
									jQuery("#email123").focus();
									email_error = 1;	
									return false;
								}
								else
								{
									email_error = 0;	
								}
								
								
								
								// Validating Mobile Field.
								//else if (rmobile == null || rmobile == "" ||  (rmobile.length>1 && rmobile.length<=9) || rmobile.length>10) {
								var pattern = /^\d{10}$/;
								if( (!mobile) || (!pattern.test(mobile)) || (mobile.length!=10)  ){
									jQuery('.popupErrorname').css("display","none");
									jQuery('.popupErroremail').css("display","none"); 
									jQuery('.popupErrormobile').css("display","block");
									jQuery("#mobile").focus();
									mobile_number_error = 1;	
									return false;
								}
								
								else
								{
									mobile_number_error = 0;	
								}
								
								
								
								if ( name_error == 1 || email_error == 1 || mobile_number_error == 1) 
								{
									
									return false ;
								}
								else
								{
									jQuery('.popupErrorname').css("display","none");
									jQuery('.popupErrormobile').css("display","none");
									jQuery('.popupErroremail').css("display","none"); 
									
									$jsq('#utm_newsletter_spinner_r1').css('display', 'block');				
									$jsq.post(base_url + 'newsletter/subscriber/callback/', {'name':name , 'email': email,'contact_no':mobile ,'callback_product_id':callback_product_id,'callback_product_name':callback_product_name,'callback_product_sku':callback_product_sku}, function(resp) {
										if(resp=='success')
										{
											clevertap.event.push("product_callback", {
									    "name": name,
									    "email": email,
									    "contact_no": mobile,
									    "callback_product_name": callback_product_name,
									    "callback_product_sku": callback_product_sku,
									   
									});	

											jQuery('.d_g_input_area').css('margin','65px auto').html('Thank you for sharing your contact details. Our Jewellery Expert will contact you soon.');
											
											
											
											setTimeout(function(){
												jQuery('.mob_popContainerbg').css('opacity', '0');						 
												jQuery('.mob_popContainerbg').css('visibility', 'hidden');	
												
											},7000);
										}
										
										
										
										
										
									});
									
									
									
									
									
									
									
								}				
							});
						});
						
						
					}); 
					
				});
				
				
				
			</script>		
<!--ring sizer-->
<link rel="stylesheet" href="<?php echo $this->getSkinUrl();?>css/rs/rangeslider.min.css" />
<script src="<?php echo $this->getSkinUrl();?>js/rs/rangeslider.min.js"></script>
<script src="<?php echo $this->getSkinUrl();?>js/rs/rs.js"></script>
<div class="ring_sizer_mobile" id="ring_sizer_mobile">
            <div class="r_t_btn">RING SIZE GUIDE<span class="rs_close">X</span></div>
        <div class="mobile_range_slider">
            <form>
                <p class="r_C_C">Select Country</p>
                <select id="select_country">
                    <option value="in_ring" selected="selected">India</option>
                   <!-- <option value="us_ring">US</option>
                    <option value="uk_ring">UK</option>-->
                </select>           
            </form> 
    <!--for India-->                    
            <div id="in_ring" class="slidecontainer current">
                    <div id="in_carat-image-assets">
                            <div class="ring_absolute">
                                <div class="dy_img"><p class="in_r_text">1</p></div>
                            </div>  
                    </div>

                <input type="range" min="1" max="30" value="1" class="r_slider" id="indian_myRange" data-highlight="true">                    
            
                <p class="r_r_size">Ring Size : <span id="indian_ring">1</span></p>
                <div class="details">Detsils :</div>
                <p class="perimeter">Diameter is <span id="in_p_val">13.06</span> mm</p>
                <p class="diameter">Perimeter is <span id="in_d_val">40.99</span> mm</p>                        
            </div>
    <!--for US-->
            <div id="us_ring" class="slidecontainer">
                    <div id="us_carat-image-assets">
                            <div class="ring_absolute">
                                <div class="dy_img"><p class="in_r_text">1¾</p></div>
                            </div>  
                    </div>
                <input type="range" min="1" max="30" value="1" class="r_slider" name="us_myRange" id="us_myRange" data-highlight="true" step="1">                     
            
                <p class="r_r_size">Ring Size : <span id="us_Rings">1¾</span></p>
                <div class="details">Detsils :</div>
                <p class="perimeter">Diameter is <span id="us_p_val">13.06</span> mm</p>
                <p class="diameter">Perimeter is <span id="us_d_val">40.99</span> mm</p>                        
            </div>
    <!--for UK-->
            <div id="uk_ring" class="slidecontainer">
                    <div id="uk_carat-image-assets">
                            <div class="ring_absolute">
                                <div class="dy_img"><p class="in_r_text">C½</p></div>
                            </div>  
                    </div>
                <input type="range" min="1" max="30" value="1" class="r_slider" name="uk_myRange" id="uk_myRange" data-highlight="true">                     
                
                <p class="r_r_size">Ring Size : <span id="uk_Rings">C½</span></p>
                <div class="details">Detsils :</div>
                <p class="perimeter">Diameter is <span id="uk_p_val">13.06</span> mm</p>
                <p class="diameter">Perimeter is <span id="uk_d_val">40.99</span> mm</p>                        
            </div>
        </div> 
        <div class="row">
            <div class="r_l_btn"><a target="_blank" href="<?php echo $this->getBaseUrl();?>media/documents/ring-sizer-candere-India.pdf">Download Chart</a></div>
            <div class="r_r_btn">Continue Shopping</div>
        </div> 
    </div>  
<script>
	jQuery(window).scroll(function (event) {
		var scroll = jQuery(window).scrollTop();
		jQuery(".ring_sizer_mobile").css("top", scroll);
	});
    jQuery(function() {
       const cssClasses = [
         'rangeslider--is-lowest-value',
         'rangeslider--is-highest-value'
       ];
       
    jQuery('input[type=range]')
         .rangeslider({
           polyfill: false
         })
    .on('input', function() {
           const fraction = (this.value - this.min) / (this.max - this.min);
           if (fraction === 0) {
             this.nextSibling.classList.add(cssClasses[0]);
           } else if (fraction === 1) {
             this.nextSibling.classList.add(cssClasses[1]);
           } else {
             this.nextSibling.classList.remove(...cssClasses)
           }
	});
			var fullHeightMinusHeader, sideScrollHeight = 0;	
			function calcHeights() {	
				fullHeightMinusHeader = jQuery(window).height();
				jQuery(".ring_sizer_mobile").height(fullHeightMinusHeader);								
			} 
			calcHeights();
	jQuery(window).resize(function() {
		calcHeights();
	});			

		jQuery(".what-is-my-ring-size").click(function(e){
			e.preventDefault();			
			jQuery(".ring_sizer_mobile").css("display","block");
			jQuery(".ring_sizer_mobile").addClass("sizer_open");
			if (jQuery(".ring_sizer_mobile").hasClass("sizer_open")) 
			{ 
				jQuery("body").addClass("ring_sizer_open");
			}  	 
				
		});
		jQuery(".r_r_btn").click(function(){
			jQuery(".ring_sizer_mobile").css("display","none");	
			jQuery("body").removeClass("ring_sizer_open");	
			var ring_size_value =  jQuery("#indian_ring").html(); 
			jQuery('#extra_options_select option').each(function(){
				    if (ring_size_value == jQuery(this).attr('data-ring-val')) {
		        	jQuery(this).prop('selected', true).trigger('change');
			    }
		    });			
		});

		jQuery(".rs_close").click(function(){
			jQuery(".ring_sizer_mobile").css("display","none");	
			jQuery("body").removeClass("ring_sizer_open");		
		});
		 
		//jQuery(".step_Back").click(function(){
			//history.back();			
		//});
     });
</script>  
<!--ring sizer-->	
			<style type="text/css">
				.mob_popContainerbg {
				position: fixed;
				top: 0;
				right: 0;
				bottom: 0;
				left: 0;
				width: 100%;
				height: 100%;
				overflow: auto;
				z-index:99;
				box-sizing: border-box; 
				background-color: rgba(255,255,255,0.6);
				text-align: center;
				opacity:0;
				visibility: hidden;
				transition: all 1s ease-in-out;
				z-index:9999999 ;
				
				}
				.mobile_threeMin_wrapper{
				-webkit-box-shadow: -2px 8px 47px -13px rgba(0,0,0,0.42);
-moz-box-shadow: -2px 8px 47px -13px rgba(0,0,0,0.42);
box-shadow: -2px 8px 47px -13px rgba(0,0,0,0.42);
				display:block;
				max-width:320px;
				width:90%;
				height:470px;
				margin:0 auto;
				margin-top:45px;
				background-color:#fff;
				color:#000000;
				box-sizing:border-box;
				-moz-box-sizing:border-box;
				overflow:hidden;
				position:relative;	
				-moz-transform:translateY(0px);
				-webkit-transform:translateY(0px);
				transform:translateY(0px);
				-moz-transition: transform .3s ease-in-out;
				-webkit-transition: transform .3s ease-in-out;
				transition: transform 1s ease-in-out;	
				}
				@media(max-width:375px){
				.mobile_threeMin_wrapper{
					margin-top:45px;
				}
				}
				@media(max-width:320px){
				.mobile_threeMin_wrapper{
				margin-top:45px;
				height: 440px;
				}
				}
				img {
				max-width: 100%;
				}
				.mobile_popupCloseButton {
				position: absolute;
				top:0px;
				right:0px;
				font-size: 12px;
				background-color: #2fbccc;
				height: 20px;
				width: 20px;
				line-height: 20px;
				text-align: center;
				border-radius: 50%;
				cursor: pointer;
				color: #fff;
				}
				.emailHolder {
				position: relative;
				display: block;
				overflow: hidden;
				}
				.list_poup{
				display: block;
				background: url(../images/pop_bg.jpg) center center no-repeat;
				max-width:600px;
				width:100%;
				height:343px;
				margin:10px auto;
				position: relative;
				padding: 10px 15px;
				box-sizing: border-box;
				}
				.d_g_input_area{
				display:block;
				width:280px;
				box-sizing: border-box;
				margin:0 auto;
				background-color:#fff;
				}
				.list_head{
				font-size: 27px;
				text-align: left;
				font-weight: bold;
				padding: 6px 0 10px 0;
				}
				.list_help{
				font-size: 20px;
				display: inline-block;
				}
				.list_call{
				font-size: 18px;
				text-align: center;
				margin-top: 5px;
				margin-bottom:65px;
				}
				.mobile_threeMin_wrapper .popHomeEmail{
				margin-bottom: 28px;
				height: 35px;
				line-height:35px;
				background-color:#fff;
				}
				.mobile_threeMin_wrapper .popSignup{
				width: 100%;
				text-align: center;
				margin-top:0;
				font-weight: bold;
				font-size:17px;
				height: 35px;
				line-height: 38px;
				background-color: #2fbccc;
				border: none;
				color: #fff;
				text-transform: uppercase;
				cursor: pointer;
				position: relative;
				}
				.popEmail {
				width: 100%;
				display: block;
				padding: 0 10px!important;
				box-sizing: border-box;
				-moz-box-sizing: border-box;
				margin-bottom:20px!important;
				height:28px!important;
				line-height:28px!important;
				border: 1px solid #d5d7d6!important;
				}
				.popup_Erroremail {
				position: absolute;
				bottom: 3px;
				display: none;
				color: red;
				font-size: 13px;
				}
				.orText{
				text-align: center;
				font-size: 13px;
				margin: 12px 0 8px 0;
				}
				/* loader*/
				.spinner1 {
				margin:0 auto;
				width: 50px;
				height: 30px;
				text-align: center;
				font-size: 10px;
				}
				.spinner1 > div{
				background-color: #fff;
				height: 100%;
				width: 6px;
				display: inline-block;
				
				-webkit-animation: sk-stretchdelay 1.2s infinite ease-in-out;
				animation: sk-stretchdelay 1.2s infinite ease-in-out;
				}
				.spinner1 .rect2 {
				-webkit-animation-delay: -1.1s;
				animation-delay: -1.1s;
				}
				.spinner1 .rect3 {
				-webkit-animation-delay: -1.0s;
				animation-delay: -1.0s;
				}
				.spinner1 .rect4{
				-webkit-animation-delay: -0.9s;
				animation-delay: -0.9s;
				}
				.spinner1 .rect5 {
				-webkit-animation-delay: -0.8s;
				animation-delay: -0.8s;
				}
				
				@-webkit-keyframes sk-stretchdelay {
				0%, 40%, 100% { -webkit-transform: scaleY(0.4) }  
				20% { -webkit-transform: scaleY(1.0) }
				}
				
				@keyframes sk-stretchdelay {
				0%, 40%, 100% { 
				transform: scaleY(0.4);
				-webkit-transform: scaleY(0.4);
				}  20% { 
				transform: scaleY(1.0);
				-webkit-transform: scaleY(1.0);
				}
				}
				body.openMenu {
					overflow: hidden;
				}
			</style>
			
			<?php 
			}
		?>
	<!--call back-->	
	<?php if(Mage::getStoreConfig('systemfieldsgroupsectioncode/systemfieldsgroupcode/is_site_live', Mage::app()->getStore()) == 1){
?> 
		<!--LeadSquared Tracking Code Start-->
		<script type="text/javascript" src="https://web.mxradon.com/t/Tracker.js"></script>
		<script type="text/javascript">pidTracker('8559');</script>
		<!--LeadSquared Tracking Code End-->
		<?php } 

		$data_dropdown .='<option data-ring-val="'.$ring_size_in_no_array[0].'" value="'.$this->htmlEscape($attribute_collect['label']).'">'.$this->htmlEscape($attribute_collect['label']) .'</option>'; 