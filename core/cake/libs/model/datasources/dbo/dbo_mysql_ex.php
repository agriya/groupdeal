<?php
// http://openpaste.org/en/5941/
require_once (LIBS . 'model' . DS . 'datasources' . DS . 'dbo' . DS . 'dbo_mysql.php');
class DboMysqlEx extends DboMysql
{
    var $description = "MySQL DBO Driver - Extended";
    function logQuery($sql)
    {
        $ret = parent::logQuery($sql);
        //$this->log($this->_queriesLog[count($this->_queriesLog)-1], 'sql');
        return $ret;
    }
    function showLog($sorted = false)
    {
        if ($sorted) {
            $log = sortByKey($this->_queriesLog, 'took', 'desc', SORT_NUMERIC);
        } else {
            $log = $this->_queriesLog;
        }
        if ($this->_queriesCnt > 1) {
            $text = 'queries';
        } else {
            $text = 'query';
        }
        if (php_sapi_name() != 'cli') {
            require_once VENDORS . DS . 'geshi' . DS . 'geshi.php';
            $geshi = new GeSHi('', 'mysql');
            $geshi->set_header_type(GESHI_HEADER_NONE);
            /*
            Note: qot crashes with real queries.
            // qot schema preparation
            // qot --input-file=qot.sql --input-query="SELECT * FROM videos WHERE title = 'foo'" --info --propose=merged-index
            // http://ritmark.com/
            $tables_arr = $this->query('SHOW tables');
            if ($fp = fopen(APP . DS . 'tmp' . DS . 'qot.sql', 'w')) {
                fputs($fp, 'CREATE SCHEMA qot;
                USE qot;
                ');
                foreach($tables_arr as $table_arr) {
                    list($table) = array_values($table_arr['TABLE_NAMES']);
                    $table_schema_arr = $this->query('SHOW CREATE TABLE ' . $table);
                    $table_schema = $table_schema_arr[0][0]['Create Table'] . ';';
                    // Fix for qot: otherwise crashes
                    $table_schema = str_replace(array(
                        'bigint(',
                        'datetime',
                        'date'
                    ) , array(
                        'int(',
                        'varchar(255)',
                        'varchar(255)'
                    ) , $table_schema);
                    fputs($fp, $table_schema . "\r\n\r\n");
                }
                fclose($fp);
            }
            */
            print ("<table class=\"cake-sql-log\" id=\"cakeSqlLog_" . preg_replace('/[^A-Za-z0-9_]/', '_', uniqid(time() , true)) . "\" summary=\"Cake SQL Log\" cellspacing=\"0\" border = \"0\">\n<caption>{$this->_queriesCnt} {$text} took {$this->_queriesTime} ms</caption>\n");
            print ("<thead>\n<tr><th>Nr</th><th>Query</th><th>Error</th><th>Affected</th><th>Num. rows</th><th>Took (ms)</th></tr>\n</thead>\n<tbody>\n");
            foreach($log as $k => $i) {
                $geshi->set_source($i['query']);
                $query_formatted = $geshi->parse_code();
                $class = '';
                $explain_tbl = '';
                if (strpos($i['query'], 'SELECT') === 0) {
                    // http://www.mysqlperformanceblog.com/2006/07/24/extended-explain/
                    $explain_results_arr = $this->query('EXPLAIN EXTENDED ' . $i['query']);
                    $explain_tbl.= '<table class="list">';
                    $explain_tbl.= '<tr><th>Id</th><th>Select Type</th><th>Table</th><th>Type</th><th>Possible Keys</th><th>Key</th><th>Key Len</th><th>Ref</th><th>Rows</th><th>Extra</th></tr>';
                    foreach($explain_results_arr as $explain_result_arr) {
                        $explain_result_arr = $explain_result_arr[0];
                        if (strpos($explain_result_arr['Extra'], 'Using index') === false) {
                            $class = ' class="notice"';
                        }
                        /*
                        foreach($explain_result_arr as $key => $val) {
                        $explain_tbl .= '<th>'.Inflector::humanize($key).'</th>';
                        }*/
                        $explain_tbl.= '<tr>';
                        foreach($explain_result_arr as $key => $val) {
                            $explain_tbl.= '<td>' . $val . '</td>';
                        }
                        $explain_tbl.= '</tr>' . "\n";
                    }
                    $explain_tbl.= '</table>' . "\n";
                    $warnings_results_arr = $this->query('SHOW warnings');
                    $explain_tbl.= '<table class="list">';
                    $explain_tbl.= '<tr><th>Level</th><th>Code</th><th>Message</th></tr>';
                    foreach($warnings_results_arr as $warnings_result_arr) {
                        $warnings_result_arr = $warnings_result_arr[0];
                        $explain_tbl.= '<tr>';
                        $explain_tbl.= '<td>' . $warnings_result_arr['Level'] . '</td>';
                        $explain_tbl.= '<td>' . $warnings_result_arr['Code'] . '</td>';
                        $geshi->set_source($warnings_result_arr['Message']);
                        $message_query_formatted = $geshi->parse_code();
                        $explain_tbl.= '<td>' . $message_query_formatted . '</td>';
                        /*
                        foreach($warnings_result_arr as $key => $val) {
                        $explain_tbl.= '<td>' . $val . '</td>';
                        }*/
                        $explain_tbl.= '</tr>' . "\n";
                    }
                    $explain_tbl.= '</table>' . "\n";
                    /*
                    Note: qot crashes with real queries.
                    // qot info
                    exec('"'.CAKE_CORE_INCLUDE_PATH . DS . 'cake' . DS . 'console' . DS . 'qot" "' . $i['query'] . '"', $result_arr);
                    $explain_tbl.= implode("\n", $result_arr) . "\n";
                    */
                }
                print ("<tr$class><td>" . ($k+1) . "</td><td>" . $query_formatted . "</td><td>{$i['error']}</td><td style = \"text-align: right\">{$i['affected']}</td><td style = \"text-align: right\">{$i['numRows']}</td><td style = \"text-align: right\">{$i['took']}</td></tr>\n");
                if (strpos($i['query'], 'SELECT') === 0) {
                    print ('<tr><th>EXPLAIN</th><td colspan="5">' . $explain_tbl . '</td></tr>' . "\n");
                }
            }
            print ("</tbody></table>\n");
        } else {
            foreach($log as $k => $i) {
                print (($k+1) . ". {$i['query']} {$i['error']}\n");
            }
        }
    }
}
?>
