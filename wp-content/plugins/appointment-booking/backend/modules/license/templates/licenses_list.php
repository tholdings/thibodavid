<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<div class="well bookly-board">
    <div class="h4"><?php _e( 'License verification required', 'bookly' ) ?></div>
    <div>
        <p><?php printf( __( 'Cannot find your purchase code? See this <a href="%s" target="_blank">page</a>.', 'bookly' ), 'https://help.market.envato.com/hc/en-us/articles/202822600-Where-can-I-find-my-Purchase-Code' ) ?></p>
    </div>
    <div class="btn-group-vertical align-left" role="group">
        <button type="button" class="btn btn-link"><span class="text-success"><i class="glyphicon glyphicon-star"></i> <?php _e( 'I have already made the purchase', 'bookly' ) ?></span></button>
        <button type="button" class="btn btn-link"><i class="glyphicon glyphicon-thumbs-up"></i> <?php _e( 'I want to make a purchase now', 'bookly' ) ?></button>
        <button type="button" class="btn btn-link"><span class="text-warning"><i class="glyphicon glyphicon glyphicon-time"></i> <?php _e( 'I will provide license info later', 'bookly' ) ?></span></button>
    </div>
</div>