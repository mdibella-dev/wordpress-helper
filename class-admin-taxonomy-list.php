<?php
/**
 * Class Admin_Taxonomy_List
 *
 * @author  Marco Di Bella
 * @package wordpress-helper
 *
 * @version 1.1.1
 */

namespace wordpress_helper;



/** Prevent direct access */

defined( 'ABSPATH' ) or exit;



if ( ! class_exists( __NAMESPACE__ . '\Admin_Taxonomy_List' ) ) {

    /**
     * A class for the implementation of taxonomy tables.
     */

    class Admin_Taxonomy_List {

        /**
         * The taxonomy.
         *
         * @var string
         */

        protected $taxonomy = '';


        /**
         * Gets the post type.
         *
         * @return string The post type slug
         */

        protected function get_taxonomy() {
            return $this->taxonomy;
        }



        /**
         * Determines the columns of the admin taxonomy list.
         *
         * @see https://developer.wordpress.org/reference/hooks/manage_screen-id_columns/
         *
         * @param array $default The column header labels keyed by column ID
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
         * @see https://developer.wordpress.org/reference/hooks/manage_this-screen-taxonomy_custom_column/
         *
         * @param string $output      Custom column output. Default empty
         * @param string $column_name Designation of the column to be output
         * @param int    $term_id     The term ID
         */

        public function manage_custom_column( $output, $column_name, $term_id ) {
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
        if ( is_admin() and $query->is_main_query() /*and ( $this->get_taxonomy() === $query->get( 'post_type' ) )*/ ) {
                $this->manage_sorting( $query );
            }
        }



        /**
         * Constructor: Adds the hooks of this admin post list.
         */

        function __construct() {
            if ( ! empty( $this->taxonomy ) ) {
                add_filter( "manage_edit-{$this->get_taxonomy()}_columns", [$this, 'manage_columns'], 10, 1 );
                add_action( "manage_{$this->get_taxonomy()}_custom_column", [$this, 'manage_custom_column'], 10, 3 );    // 9999?
                add_filter( "manage_edit-{$this->get_taxonomy()}_sortable_columns", [$this, 'manage_sortable_columns'], 10, 1 );
                add_action( "pre_get_posts", [$this, 'pre_get_posts'] );
            }
        }
    }

}
