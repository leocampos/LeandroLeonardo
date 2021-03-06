<?php
global $wp_query;	
?>
<div id="default_products_page_container" class="wrap wpsc_container galeria">
	<?php if(wpsc_display_products()): ?>	
		<div class="wpsc_default_product_list">
		<?php while (wpsc_have_products()) :  wpsc_the_product(); ?>
			<div class="default_product_display product_view_<?php echo wpsc_the_product_id(); ?> <?php echo wpsc_category_class(); ?> group"> 
				<h2 class="prodtitle entry-title">
            <a class="wpsc_product_title" href="<?php echo wpsc_the_product_permalink(); ?>"><?php echo wpsc_the_product_title(); ?></a>
        </h2>
        
					<div class="imagecol" id="imagecol_<?php echo wpsc_the_product_id(); ?>">
          
            <?php if(wpsc_the_product_thumbnail()):?>
            <a rel="<?php echo wpsc_the_product_title(); ?>" class="<?php echo wpsc_the_product_image_link_classes(); ?>" href="<?php echo wpsc_the_product_image(); ?>">
              <img class="product_image" width="80" height="80" id="product_image_<?php echo wpsc_the_product_id(); ?>" alt="<?php echo wpsc_the_product_title(); ?>" title="<?php echo wpsc_the_product_title(); ?>" src="<?php echo wpsc_the_product_thumbnail(); ?>"/>
            </a>
            <?php else: ?>
              <a href="<?php echo wpsc_the_product_permalink(); ?>">
                <img class="no-image" id="product_image_<?php echo wpsc_the_product_id(); ?>" alt="No Image" title="<?php echo wpsc_the_product_title(); ?>" src="<?php echo WPSC_CORE_THEME_URL; ?>wpsc-images/noimage.png" style="width: 80px; height: 80px;"/>	
              </a>
            <?php endif; ?>	
					</div>

					<div class="productcol">
						<?php							
							do_action('wpsc_product_before_description', wpsc_the_product_id(), $wp_query->post);
							do_action('wpsc_product_addons', wpsc_the_product_id());
						?>

						<div class="wpsc_description">
							<?php echo wpsc_the_product_description(); ?>
            </div>
				
						<?php if(wpsc_the_product_additional_description()) : ?>
						<div class="additional_description_container">
							<img class="additional_description_button"  src="<?php echo WPSC_CORE_THEME_URL; ?>wpsc-images/icon_window_expand.gif" alt="Additional Description" /><a href="<?php echo wpsc_the_product_permalink(); ?>" class="additional_description_link"><?php _e('More Details', 'wpsc'); ?>
							</a>
							<div class="additional_description">
								<p><?php echo wpsc_the_product_additional_description(); ?></p>
							</div><!--close additional_description-->
						</div><!--close additional_description_container-->
						<?php endif; ?>
						
						<?php if(wpsc_product_external_link(wpsc_the_product_id()) != '') : ?>
							<?php $action =  wpsc_product_external_link(wpsc_the_product_id()); ?>
						<?php else: ?>
              <?php $action = htmlentities(wpsc_this_page_url(), ENT_QUOTES, 'UTF-8' ); ?>					
						<?php endif; ?>

						<form class="product_form" enctype="multipart/form-data" action="<?php echo $action; ?>" method="post" name="product_<?php echo wpsc_the_product_id(); ?>" id="product_<?php echo wpsc_the_product_id(); ?>" >
						
            <?php /** the variation group HTML and loop */?>
            <?php if (wpsc_have_variation_groups()): ?>
              <fieldset><legend><?php _e('Product Options', 'wpsc'); ?></legend>
                <div class="wpsc_variation_forms">
                  <table>
                <?php while (wpsc_have_variation_groups()) : wpsc_the_variation_group(); ?>
                    <tr>
                      <td class="col1"><label for="<?php echo wpsc_vargrp_form_id(); ?>"><?php echo wpsc_the_vargrp_name(); ?>:</label></td>
                    <?php /** the variation HTML and loop */?>
                      <td class="col2">
                        <select class="wpsc_select_variation" name="variation[<?php echo wpsc_vargrp_id(); ?>]" id="<?php echo wpsc_vargrp_form_id(); ?>">
                    <?php while (wpsc_have_variations()) : wpsc_the_variation(); ?>
                          <option value="<?php echo wpsc_the_variation_id(); ?>" <?php echo wpsc_the_variation_out_of_stock(); ?>><?php echo wpsc_the_variation_name(); ?></option>
                    <?php endwhile; ?>
                        </select>
                      </td>
                    </tr> 
							<?php endwhile; ?>
                  </table>
                </div><!--close wpsc_variation_forms-->
              </fieldset>
						<?php endif ?>
						<?php /** the variation group HTML and loop ends here */?>
							<fieldset>
                <legend><?php _e('Quantity', 'wpsc'); ?></legend>
								<div class="wpsc_quantity_update">
                  <input type="text" id="wpsc_quantity_update_<?php echo wpsc_the_product_id(); ?>" name="wpsc_quantity_update" size="2" value="1" />
                  <input type="hidden" name="key" value="<?php echo wpsc_the_cart_item_key(); ?>"/>
                  <input type="hidden" name="wpsc_update_quantity" value="true" />
                </div><!--close wpsc_quantity_update-->
              </fieldset>

							<div class="wpsc_product_price">
									<?php if(wpsc_product_on_special()) : ?>
										<p class="pricedisplay product_<?php echo wpsc_the_product_id(); ?>"><?php _e('Old Price', 'wpsc'); ?>: <span class="oldprice" id="old_product_price_<?php echo wpsc_the_product_id(); ?>"><?php echo wpsc_product_normal_price(); ?></span></p>
									<?php endif; ?>
                  
									<p class="pricedisplay product_<?php echo wpsc_the_product_id(); ?>"><?php _e('Price', 'wpsc'); ?>: <span id='product_price_<?php echo wpsc_the_product_id(); ?>' class="currentprice pricedisplay"><?php echo wpsc_the_product_price(); ?></span></p>
									<?php if(wpsc_product_on_special()) : ?>
										<p class="pricedisplay product_<?php echo wpsc_the_product_id(); ?>"><?php _e('You save', 'wpsc'); ?>: <span class="yousave" id="yousave_<?php echo wpsc_the_product_id(); ?>"><?php echo wpsc_currency_display(wpsc_you_save('type=amount'), array('html' => false)); ?>! (<?php echo wpsc_you_save(); ?>%)</span></p>
									<?php endif; ?>
									
									<?php if(wpsc_show_pnp()) : ?>
										<p class="pricedisplay"><?php _e('Shipping', 'wpsc'); ?>:<span class="pp_price"><?php echo wpsc_product_postage_and_packaging(); ?></span></p>
									<?php endif; ?>
                  
							</div><!--close wpsc_product_price-->
							
							<input type="hidden" value="add_to_cart" name="wpsc_ajax_action"/>
							<input type="hidden" value="<?php echo wpsc_the_product_id(); ?>" name="product_id"/>
					
							<!-- END OF QUANTITY OPTION -->
              
							<?php if((get_option('hide_addtocart_button') == 0) &&  (get_option('addtocart_or_buynow') !='1')) : ?>
								<?php if(wpsc_product_has_stock()) : ?>
									<div class="wpsc_buy_button_container">
										<div class="wpsc_loading_animation">
											<img title="Loading" alt="Loading" src="<?php echo wpsc_loading_animation_url(); ?>" />
											<?php _e('Updating cart...', 'wpsc'); ?>
										</div>
                
										<input type="submit" value="<?php _e('Add To Cart', 'wpsc'); ?>" name="Buy" class="wpsc_buy_button" id="product_<?php echo wpsc_the_product_id(); ?>_submit_button"/>
									</div><!--close wpsc_buy_button_container-->
								<?php endif ; ?>
							<?php endif ; ?>

						</form><!--close product_form-->

						<?php if((get_option('hide_addtocart_button') == 0) && (get_option('addtocart_or_buynow')=='1')) : ?>
							<?php echo wpsc_buy_now_button(wpsc_the_product_id()); ?>
						<?php endif ; ?>
						
						<?php echo wpsc_product_rater(); ?>
					<?php // */ ?>
				</div><!--close productcol-->
			<?php if(wpsc_product_on_special()) : ?><span class="sale"><?php _e('Sale', 'wpsc'); ?></span><?php endif; ?>
		</div><!--close default_product_display-->

		<?php endwhile; ?>
		<?php /** end the product loop here */?>
		</div>
    
		<?php if(wpsc_product_count() == 0):?>
			<h3><?php  _e('There are no products in this group.', 'wpsc'); ?></h3>
		<?php endif ; ?>
    
	  <?php do_action( 'wpsc_theme_footer' ); ?> 	

		<?php if(wpsc_has_pages_bottom()) : ?>
			<div class="wpsc_page_numbers_bottom">
				<?php wpsc_pagination(); ?>
			</div><!--close wpsc_page_numbers_bottom-->
		<?php endif; ?>
    
	<?php endif; ?>
</div><!--close default_products_page_container-->
