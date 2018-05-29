<?php
App::import('Helper', 'Xml');
class RestXmlHelper extends AppHelper
{
    public $helpers = array(
        'Xml'
    );
    function serialize($data, $options = array()) 
    {
        $xml = $this->_serialize(array(
            'response' => $data
        ));
        $xml = $this->_xmlCleanup($xml);
        return $xml;
    }
    protected function _xmlCleanup($xml) 
    {
        // Indentation
        $doc = new DOMDocument('1.0');
        $doc->preserveWhiteSpace = false;
        if (!$doc->loadXML($xml)) {
            //prd($xml);
            
        }
        $doc->formatOutput = true;
        return $doc->saveXML();
    }
    function _serialize($data, $name = null) 
    {
        $type = $this->_getType($data);
        if ($type == 'string') {
            $type = null;
        }
        if ($type == 'boolean') {
            $data = ($data ? 'true' : 'false');
        }
        if ($type == 'datetime') {
            if (preg_match('/^\d{4}\-\d{2}\-\d{2}T\d{2}\:\d{2}\:\d{2}Z$/', $data)) {
                $data = gmdate('Y-m-d\TH:i:s\Z', strtotime($data));
            } else {
                $data = date('Y-m-d\TH:i:s\Z', strtotime($data));
            }
        }
        if ($type == 'array') {
            $map = Set::map($data);
            $type = $this->_getType($map);
            if ($type == 'array') {
                $data = array();
                if (isset($map[0]->_name_)) {
                    $name = Inflector::underscore(Inflector::pluralize($map[0]->_name_));
                }
                for ($i = 0; $i < count($map); $i++) {
                    if (!empty($map[$i])) {
                        $data[] = $this->_serialize($map[$i], Inflector::singularize($name));
                    }
                }
                $data = implode('', $data);
            } else {
                $type = 'object';
                $name = Inflector::singularize($name);
                $data = $map;
            }
        }
        if ($type == 'object') {
            $map = array();
            if (isset($data->_name_)) {
                $name = Inflector::underscore($data->_name_);
                unset($data->_name_);
            }
            foreach($data as $key => $value) {
                $map[] = $this->_serialize($value, $key);
            }
            if (get_class($data) !== 'stdClass') {
                $name = get_class($data);
            }
            $type = null;
            $data = implode($map);
        }
        if (empty($name)) {
            $name = 'var';
        }
        //$tag = str_replace('_', '-', Inflector::underscore($name));
        $tag = str_replace('-', '_', Inflector::underscore($name));
        // Start tag
        $result = '<' . $tag;
        // Type attribute
        if (!is_null($type)) {
            $result.= ' type="' . $type . '"';
        }
        $result.= '>' . $data . '</' . $tag . '>';
        return $result;
    }
    function _getType(&$value) 
    {
        $type = gettype($value);
        if ($type == 'string') {
            if ((preg_match('/^\d{4}\-\d{2}\-\d{2}T\d{2}\:\d{2}\:\d{2}Z$/', $value) || preg_match('/^\d{4}\-\d{2}\-\d{2} \d{2}\:\d{2}\:\d{2}$/', $value) || preg_match('/^\d{4}\-\d{2}\-\d{2}$/', $value)) && strtotime($value)) {
                $type = 'datetime';
            }
        }
        if ($type == 'string' && is_numeric($value)) {
            if (floatval($value) == intval($value)) {
                $type = 'integer';
            } else {
                $type = 'double';
            }
        }
        return $type;
    }
}
?>