<?php
/**
 * Class Admin_Post_List
 *
 * @author  Marco Di Bella
 * @package wordpress-helper
 *
 * @version 1.1.1
 */

namespace wordpress_helper;



/** Prevent direct access */

defined( 'ABSPATH' ) or exit;



if ( ! class_exists( __NAMESPACE__ . '\Admin_Post_List' ) ) {

    /**
     * A class for the implementation of post tables.
     */

    class Admin_Post_List {

        /**
         * The post type.
         *
         * @var string
         */

        protected $post_type = '';


        /**
         * Gets the post type.
         *
         * @return string The post type slug
         */

        protected function get_post_type() {
            return $this->post_type;
        }



        /**
         * Determines the columns of the admin post list.
         *
         * @param array $default The defaults for columns
         *
         * @return array An associative array describing the columns to use
         */

        public function manage_columns( $columns ) {
            // do nothing
            return $columns;
        }



        /**
         * Generates the column output.
         *
         * @param string $column_name Designation of the column to be output
         * @param int    $post_id     ID of the post (aka record) to be output
         */

        public function manage_custom_column( $column_name, $post_id ) {
            // do nothing
        }



        /**
         * Registers sortable columns (by assigning appropriate orderby parameters).
         *
         * @param array columns The columns
         *
         * @return array An associative array
         */

        public function manage_sortable_columns( $columns ) {
            // do nothing
            return $columns;
        }



        /**
         * Modifys the query string (by assigning appropriate parameters).
         *
         * @param WP_Query $query A data object of the last query made
         */

        public function manage_sorting( &$query ) {
            // do nothing
        }



        /**
         * Trigger the sorting if the last query was made in the backend and it was related to our post type.
         *
         * @param WP_Query $query A data object of the last query made
         */

        public function pre_get_posts( $query ) {
            if ( is_admin() and $query->is_main_query() and ( $this->get_post_type() === $query->get( 'post_type' ) ) ) {
                $this->manage_sorting( $query );
            }
        }



        /**
         * Constructor: Adds the hooks of this admin post list.
         */

        function __construct() {
            if ( ! empty( $this->post_type ) ) {
                add_filter( "manage_{$this->get_post_type()}_posts_columns", [$this, 'manage_columns'], 10, 1 );
                add_action( "manage_{$this->get_post_type()}_posts_custom_column", [$this, 'manage_custom_column'], 10, 2 );    // 9999?
                add_filter( "manage_edit-{$this->get_post_type()}_sortable_columns", [$this, 'manage_sortable_columns'], 10, 1 );
                add_action( "pre_get_posts", [$this, 'pre_get_posts'] );
            }
        }
    }

}
