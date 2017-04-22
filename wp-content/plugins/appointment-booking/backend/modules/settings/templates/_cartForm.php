<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<form method="post" action="<?php echo esc_url( add_query_arg( 'tab', 'cart' ) ) ?>" class="ab-settings-form">
    <div class="form-group">
        <label for="bookly_cart_enabled"><?php _e( 'Cart', 'bookly' ) ?></label>
        <p class="help-block"><?php _e( 'If cart is enabled then your clients will be able to book several appointments at once. Please note that WooCommerce integration must be disabled.', 'bookly' ) ?></p>
        <?php \Bookly\Lib\Utils\Common::optionToggle( 'bookly_cart_enabled' ) ?>
    </div>
    <div class="form-group">
        <label for="bookly_cart_show_columns"><?php _e( 'Columns', 'bookly' ) ?></label><br/>
        <div class="ab-flags" id="bookly_cart_show_columns">
            <?php foreach ( (array) get_option( 'bookly_cart_show_columns' ) as $column => $attr ) : ?>
                <div class="bookly-flexbox"<?php if ( $column == 'deposit' && ! \Bookly\Lib\Utils\Common::isPluginActive( 'bookly-addon-deposit-payments/main.php' ) ): ?> style="display:none"<?php endif ?>>
                    <div class="bookly-flex-cell">
                        <i class="bookly-js-handle bookly-margin-right-sm bookly-icon bookly-icon-draghandle bookly-cursor-move" title="<?php esc_attr_e( 'Reorder', 'bookly' ) ?>"></i>
                    </div>
                    <div class="bookly-flex-cell" style="width: 100%">
                        <div class="checkbox">
                            <label>
                                <input type="hidden" name="bookly_cart_show_columns[<?php echo $column ?>][show]" value="0">
                                <input type="checkbox"
                                       name="bookly_cart_show_columns[<?php echo $column ?>][show]"
                                       value="1" <?php checked($attr['show'], true) ?>>
                                <?php echo $cart_columns[$column] ?>
                            </label>
                        </div>
                    </div>
                </div>
            <?php endforeach ?>
        </div>
    </div>
    <div class="panel-footer">
        <?php \Bookly\Lib\Utils\Common::submitButton() ?>
        <?php \Bookly\Lib\Utils\Common::resetButton() ?>
    </div>
</form>