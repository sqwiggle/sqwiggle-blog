<?php

/**
 * The main class for core blocks
 *
 */
class ABH_Classes_BlockController {

    /** @var object of the model class */
    protected $model;

    /** @var boolean */
    public $flush = true;

    /** @var object of the view class */
    protected $view;

    /** @var name of the  class */
    private $name;

    public function __construct() {
        /** check the admin condition */
        if (!is_admin())
            return;

        /* get the name of the current class */
        $this->name = get_class($this);

        /* create the model and view instances */
        $this->model = ABH_Classes_ObjController::getModel($this->name);
    }

    /**
     * load sequence of classes
     *
     * @return void
     */
    public function init() {

        $this->view = ABH_Classes_ObjController::getController('ABH_Classes_DisplayController');

        if ($this->flush)
            $this->hookHead();

        /* check if there is a hook defined in the block class */
        ABH_Classes_ObjController::getController('ABH_Classes_HookController')
                ->setBlockHooks($this);

        if ($this->flush)
            $this->output();
    }

    protected function output() {
        /* view is called from theme directory with the class name by default */
        if ($class = ABH_Classes_ObjController::getClassPath($this->name))
            $this->view->output($class['name'], $this);
    }

    /**
     * This function is called from Ajax class as a wp_ajax_action
     *
     */
    protected function action() {
        // check to see if the submitted nonce matches with the
        // generated nonce we created
        if (class_exists('wp_verify_nonce'))
            if (!wp_verify_nonce(ABH_Classes_Tools::getValue(_ABH_NONCE_ID_), _ABH_NONCE_ID_))
                die('Invalid request!');
    }

    /**
     * This function will load the media in the header for each class
     *
     * @return void
     */
    protected function hookHead() {
        if ($class = ABH_Classes_ObjController::getClassPath($this->name)) {
            ABH_Classes_ObjController::getController('ABH_Classes_DisplayController')
                    ->loadMedia($class['name']);
        }
    }

    /** @todo _ GASESTE O CALE SA INCARC CSS PENTRU BLOCURI */
}

?>