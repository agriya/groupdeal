<?php
App::import('Helper', 'Javascript');
class RestJsonHelper extends JavascriptHelper
{
    function serialize($data, $name = null, $options = array()) 
    {
        //return json_encode($data);
        $json = $this->object($data);
        $jsonClean = $this->_jsonCleanup($json);
        return $jsonClean;
    }
    protected function _jsonCleanup($json) 
    {
        $json = $this->_indent($json);
        return $json;
    }
    /**
     * Indents a flat JSON string to make it more human-readable
     * http://recurser.com/articles/2008/03/11/format-json-with-php/comment-page-1/
     *
     * @param string $json The original JSON string to process
     * @return string Indented version of the original JSON string
     */
    public static function _indent($json) 
    {
        $result = '';
        $pos = 0;
        $strLen = strlen($json);
        $indentStr = '  ';
        $newLine = "\n";
        for ($i = 0; $i <= $strLen; $i++) {
            // Grab the next character in the string
            $char = substr($json, $i, 1);
            // If this character is the end of an element,
            // output a new line and indent the next line
            if ($char == '}' || $char == ']') {
                $result.= $newLine;
                $pos--;
                for ($j = 0; $j < $pos; $j++) {
                    $result.= $indentStr;
                }
            }
            // Add the character to the result string
            $result.= $char;
            // If the last character was the beginning of an element,
            // output a new line and indent the next line
            if ($char == ',' || $char == '{' || $char == '[') {
                $result.= $newLine;
                if ($char == '{' || $char == '[') {
                    $pos++;
                }
                for ($j = 0; $j < $pos; $j++) {
                    $result.= $indentStr;
                }
            }
        }
        return $result;
    }
}
?>