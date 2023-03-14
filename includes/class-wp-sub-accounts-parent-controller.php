<?php


class Wp_Sub_Accounts_Parent_Controller {

    /**
     * The single instance of the class.
     */
    protected static $_instance = null;

    public $model;

    public function __construct(  ) {
        $this->model = wp_sub_accounts_parent_model();
    }

    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }


}

function wp_sub_accounts_parent_controller() {
    return Wp_Sub_Accounts_Parent_Controller::instance();
}