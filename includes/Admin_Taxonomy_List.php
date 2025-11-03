<?php
/**
 * Class WordPress_Helper\Admin_Taxonomy_List
 *
 * @author  Marco Di Bella
 * @package wordpress-helper
 * @version 1.1.3
 */

namespace WordPress_Helper;



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

        public function manage_columns( $default ) {
            // do nothing
            return $default;
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
         * Filters the action links displayed for each term in the taxonomy list table.
         *
         * @see https://wordpress.stackexchange.com/questions/78211/remove-quick-edit-for-custom-post-type
         * @see https://developer.wordpress.org/reference/hooks/taxonomy_row_actions/
         *
         * @param array   $actions  An array of action links to be displayed
         * @param WP_Term $term     A term object.
         *
         * @return array The modified list of action links
         */

        public function manage_row_actions( $actions, $tag ) {
            return $actions;
        }



        /**
         * Trigger the sorting if the last query was made in the backend and it was related to our post type.
         *
         * @param WP_Query $query A data object of the last query made
         */

        public function pre_get_posts( $query ) {
            if ( is_admin() and $query->is_main_query() ) {
                $this->manage_sorting( $query );
            }
        }



        /**
         * Returns the primary column
         *
         * @param string $default Column name default for the specific list table, e.g. 'name'.
         * @param string $screen  Screen ID for specific list table, e.g. 'plugins'.
         */

        public function list_table_primary_column( $default, $screen ) {
            return $default;
        }



        /**
         * Constructor: Adds the hooks of this admin post list.
         */

        function __construct() {
            if ( ! empty( $this->taxonomy ) ) {
                add_filter( "manage_edit-{$this->get_taxonomy()}_columns", [$this, 'manage_columns'], 10, 1 );
                add_action( "manage_{$this->get_taxonomy()}_custom_column", [$this, 'manage_custom_column'], 10, 3 );    // 9999?
                add_filter( "manage_edit-{$this->get_taxonomy()}_sortable_columns", [$this, 'manage_sortable_columns'], 10, 1 );
                add_filter( "{$this->get_taxonomy()}_row_actions", [$this, 'manage_row_actions'], 10, 2 );
                add_action( "pre_get_posts", [$this, 'pre_get_posts'] );
                add_filter( 'list_table_primary_column', [$this, 'list_table_primary_column'], 10, 2 );
            }
        }
    }

}
