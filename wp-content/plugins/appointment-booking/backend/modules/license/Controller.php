<?php
namespace Bookly\Backend\Modules\License;

use Bookly\Lib;

/**
 * Class Controller
 * @package Bookly\Backend\Modules\Debug
 */
class Controller extends Lib\Base\Controller
{

    public function index()
    {
        $this->enqueueStyles( array(
            'backend'  => array( 'bootstrap/css/bootstrap-theme.min.css', ),
        ) );

        $this->render( 'all' );
    }

    /**
     * Override parent method to add 'wp_ajax_bookly_' prefix
     * so current 'execute*' methods look nicer.
     *
     * @param string $prefix
     */
    protected function registerWpActions( $prefix = '' )
    {
        parent::registerWpActions( 'wp_ajax_bookly_' );
    }

}