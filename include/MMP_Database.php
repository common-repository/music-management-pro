<?php

// CONNECT TO DATABASE
class MMP_Database
{

    /**
     * Get Results From Table
     *
     * @param $table string No prefix wordpress
     * @param $arg array page|per_page|relation|operator|where|order|order_by
     * @param $where array relation|operator|condition
     */
    public static function Get($table, $arg = array(), $where = array())
    {

        global $wpdb;
        global $mmp_query;

        $table = $wpdb->prefix . $table;
        $per_page = isset($arg['per_page']) ? $arg['per_page'] : 10;
        $page = isset($arg['page']) ? $arg['page'] : 1;

        // SQL
        $sql = "SELECT * FROM " . $table;
        $sql_count = 'SELECT COUNT(*) AS count FROM ' . $table;

        // CHECK WHERE BEFORE QUERY
        if (!empty($where)) {
            $sql .= self::Where($where);
            $sql_count .= self::Where($where);
        }

        // ORDER BY
        if (isset($arg['order_by'])) {
            $sql .= ' ORDER BY ' . $arg['order_by'];
        }

        // ORDER
        if (isset($arg['order'])) {
            $sql .= ' ' . $arg['order'];
        }

        $count = $wpdb->get_results($sql_count)[0]->count;
        $pages = ceil($count / $per_page);
        $mmp_query['pages'] = $pages;
        $mmp_query['page'] = $page;

        $start = ($page - 1) * $per_page;
        $sql = $sql . " LIMIT $start,$per_page";

        return $wpdb->get_results($sql);

    }

    /**
     * Insert data to table
     *
     * @param $table string No prefix wordpress
     * @param $data array Column in table. example: (column=>value)
     * @param null $format
     *
     * @return int Return insert id
     */
    public static function Insert($table, $data, $format = null)
    {

        global $wpdb;

        $wpdb->insert(
            $wpdb->prefix . $table,
            $data,
            $format
        );

        $this_insert = $wpdb->insert_id;

        return $this_insert;

    }

    /**
     * Update row from table
     *
     * @param $table string No prefix wordpress
     * @param $data array Column in table. example: (column=>value)
     * @param $where array The condition of executing the command. example: (column=>value)
     * @param null $format
     */
    public static function Update($table, $data, $where, $format = null)
    {

        global $wpdb;

        $wpdb->update(
            $wpdb->prefix . $table,
            $data,
            $where,
            $format
        );

    }

    /**
     * Remove row from table
     *
     * @param $table string No prefix wordpress
     * @param $where array The condition of executing the command. example: (column=>value)
     * @param null $where_format
     *
     * @return bool|false|int Return result remove
     */
    public static function Delete($table, $where, $where_format = null)
    {

        global $wpdb;

        $result = $wpdb->delete(
            $wpdb->prefix . $table,
            $where,
            $where_format
        );

        return $result;

    }

    /**
     * Create pagination
     *
     * @param bool $array Return array or html
     *
     * @return mixed|array
     */
    public static function Pagination($array = false)
    {

        global $mmp_query;

        if (isset($mmp_query['pages']) && isset($mmp_query['page'])) {

            $pages = $mmp_query['pages'];
            $page = $mmp_query['page'];
            $output = '';

            for ($a = $page; $a <= $pages; $a++) {

                $output .= '<a class="' . ($page === $a ? 'active' : null) . '" data-page="' . $a . '">' . $a . '</a>';

            }

            return
                '<div class="mmp_pagination ' . esc_attr($_REQUEST['page']) . '">
					<div class="mmp_pagination_current" data-current="' . $page . '" data-min="' . $page . '" data-max="' . $pages . '">
					    <i class="icon-chevron-left prev"></i>
                        ' . $output . '
                        <i class="icon-chevron-right next"></i>
                    </div>
				</div>';

        }

        return false;

    }

    /**
     * Create Table
     *
     *          Column:
     *              [
     *                  'name'           => Column Name
     *                  'type'           => Column Type - int(11) | varchar(150) | tinyint(4) | longtext
     *                  'not_null'       => true
     *                  'auto_increment' => true
     *                  'default'        => Text Default
     *              ]
     *
     * @param string $table
     * @param array $column
     * @return bool
     */
    public static function Table($table, $column = [])
    {

        global $wpdb;

        $sql = 'CREATE TABLE IF NOT EXISTS ' . $wpdb->prefix . $table;

        $arg_generate = [];
        $arg_key = [];
        foreach ($column as $key => $value) {

            $c = '';

            if (isset($value['name'])) $c .= $value['name'];
            if (isset($value['key'])) $value['key'] ? $arg_key[] = $value['name'] : null;
            if (isset($value['type'])) $c .= ' ' . $value['type'];
            if (isset($value['not_null'])) $value['not_null'] ? $c .= ' NOT NULL' : $c .= ' NULL';
            if (isset($value['auto_increment'])) $value['auto_increment'] ? $c .= ' AUTO_INCREMENT' : null;
            if (isset($value['default'])) $c .= ' DEFAULT ' . $value['default'];

            $arg_generate[] = $c;

        }

        if (!empty($arg_generate)) {
            $sql .= ' (' . join(', ', $arg_generate) . (!empty($arg_key) ? ', PRIMARY KEY (' . join(',', $arg_key) . ')' : null) . ')';
        }

        $sql .= ' ENGINE = MYISAM';

        return $wpdb->query($sql);

    }

    /**
     * @param $where
     *
     * @return bool|string Where sql
     */
    private static function Where($where)
    {

        if (empty($where)) {
            return false;
        }

        $where_ = array();
        $relation = isset($where['relation']) ? $where['relation'] : ' AND ';
        $operator = isset($where['operator']) ? $where['operator'] : ' = ';

        foreach ($where['condition'] as $key => $value) {
            $where_[] = $key . ' ' . $operator . ' \'' . $value . '\'';
        }

        return ' WHERE ' . join($relation, $where_);

    }

}