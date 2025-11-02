<?php
/**
 * Class Shortcode
 *
 * @author  Marco Di Bella
 * @package wordpress-helper
 *
 * @version 1.1.1
 */

namespace wordpress_helper;



/** Prevent direct access */

defined( 'ABSPATH' ) or exit;



if ( ! class_exists( __NAMESPACE__ . '\Shortcode' ) ) {

    /**
     * An abstract class for the implementation of shortcodes.
     *
     * @version 1.0.1
     */

    abstract class Shortcode {

        /**
         * The shortcode tag.
         *
         * @var string
         */

        protected $tag = '';



        /**
         * The shortcode attributes (parameters).
         *
         * @var array
         */

        protected $atts = [];



        /**
         * The shortcode content (the code between the opening and closing shortcode clamp).
         *
         * @var string
         */

        protected $content = '';



        /**
         * Constructor: Adds the shortcode to the WordPress ecosystem.
         */

        function __construct() {
            if ( ! empty( $this->get_tag() ) ) {
                add_shortcode( $this->get_tag(), [$this, 'callback'] );
            }
        }



        /**
         * Gets the tag.
         *
         * @return string The shortcode tag
         */

        protected function get_tag() {
            return $this->tag;
        }



        /**
         * Gets the content.
         *
         * @return string The content
         */

        protected function get_content() {
            return $this->content;
        }



        /**
         * Gets the The default attributes of this shortcode.
         *
         * Note: Should be overloaded!
         *
         * @return array The default attributes
         */

        protected function get_default_atts() {
            // This is a placeholder for shortcodes that have no default attributes.
            // Should be overloaded, if necessary.
            return [];
        }



        /**
         * Sets the content.
         *
         * @param string $content The content
         */

        protected function set_content( $content ) {
            $this->content = $content;
        }



        /**
         * Merges the custom defined shortcode attributes with the default shortcode attributes.
         *
         * @param array $atts The array with shortcode attributes
         */

        protected function set_atts( $atts ) {
            if ( ( true == is_array( $atts ) ) and ( 0 != count( $atts ) ) ) {
                $this->atts = array_merge( $this->get_default_atts(), $atts );
            } else {
                $this->atts = $this->get_default_atts();
            }
        }



        /**
         * Prepares the shortcode (the shortcode logic).
         *
         * Note: Should be overloaded!
         *
         * @return bool true|false The outcome of the preparation process
         */

        public function prepare() {
            // This is a placeholder for shortcodes that have no processing logic and are for output only.
            // Should be overloaded, if necessary.
            return true;
        }



        /**
         * Renders the shortcode (the shortcode output).
         *
         * Note: Must be overloaded!
         */

        abstract public function render();



        /**
         * Processes all shortcode calls.
         */

        public function callback( $atts, $content = '' ) {
            $output = '';

            // Set up shortcode parameters
            $this->set_atts( $atts );
            $this->set_content( $content );

            // Prepare the shortcode
            if ( false !== $this->prepare() ) {

                // Render the shortcode
                ob_start();
                $this->render();
                $output = ob_get_contents();
                ob_end_clean();
            }

            return $output;
        }
    }

}
