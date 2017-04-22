<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<div class="well bookly-board">
    <div class="h4"><?php _e( 'License verification required', 'bookly' ) ?></div>
    <div>
        <p><?php _e( 'Thank you for choosing Bookly as your booking solution.', 'bookly' ) ?></p>
        <p><?php _e( 'Please verify your license by providing a valid purchase code. Upon providing the purchase code you will get access to software updates, including feature improvements and important security fixes.', 'bookly' ) ?></p>
        <p><?php _e( 'If you do not provide a valid purchase code within 14 days, access to your bookings will be disabled.', 'bookly' ) ?></p>
    </div>
    <div class="btn-group-vertical align-left" role="group">
        <button type="button" class="btn btn-link"><span class="text-success"><i class="glyphicon glyphicon-star"></i> <?php _e( 'I have already made the purchase', 'bookly' ) ?></span></button>
        <button type="button" class="btn btn-link"><i class="glyphicon glyphicon-thumbs-up"></i> <?php _e( 'I want to make a purchase now', 'bookly' ) ?></button>
        <button type="button" class="btn btn-link"><span class="text-warning"><i class="glyphicon glyphicon glyphicon-time"></i> <?php _e( 'I will provide license info later', 'bookly' ) ?></span></button>
    </div>
</div>