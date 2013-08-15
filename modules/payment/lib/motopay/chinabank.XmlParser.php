<?php
function print_a($name, $arr) {
    echo "$name: ";
    print_r($arr);
    echo "<br/>\n";
}

class SaxElement {
    var $parentNode;
    var $tagName;
    var $val;
    var $attrs;
    var $childs;
    function SaxElement($tagName) {
        $this->tagName = $tagName;
        $this->attrs = array();
        $this->childs = array();
    }
    function setParentNode(& $parentNode) {
        $this->parentNode = & $parentNode;
    }
    function setValue($val) {
        $this->val = $val;
    }
    function addAttr($key, $val) {
        $this->attrs[$key] = $val;
    }
    function setAttrs($attrs) {
        $this->attrs = $attrs;
    }
    function addChild(& $element) {
        array_push($this->childs, &$element);
    }
    function getParent() {
        return $this->parent;
    }
    function getChilds() {
        return $this->childs;
    }
    function isRoot() {
        return $this->parent == NULL;
    }
    /**
     * 得到类XML的结构
     */
    function getArray() {
        $arr = array();
        $arr['tag'] = $this->tagName;
        $arr['attrs'] = $this->attrs;
        if ($this->val != NULL) {
            $arr['value'] = $this->val;
        } elseif ( sizeof($this->childs) ) {
            $arr['childs'] = array();
            foreach($this->childs as $childElement) {
                $arr['childs'][] = $childElement->getArray();
            }
        }
        return $arr;
    }
    /**
     * 得到简化的结构
     */
    function getArray2() {
        $rs = array();
        $rs['sname'] = $this->attrs['sname'];
        $rs['result'] = $this->attrs['result'];
        foreach($this->childs as $child) {
            $rt = $child->_getArray2();
            $rs[ $rt['key'] ] = $rt['value'];
        }
        return $rs;
    }
    function _getArray2() {
        $rt = array();
        $name = $this->attrs['name'];
        $type = $this->attrs['type'];
        switch ($type) {
            case 'list':
            case 'map':
                $var = array();
                foreach($this->childs as $i => $child) {
                    $_rt = $child->_getArray2();
                    $k = $_rt['key'];
                    $k = ($k==null ? $i : $k);
                    $var[$k] = $_rt['value'];
                }
                break;
            default:
                $var = $this->val;
        }
        $rt['key'] = $name;
        $rt['value'] = $var;
        return $rt;
    }
}

$rootDom;
$stack = array();

function startElement($parser, $name, $attrs) {
    global $rootDom, $stack;    

    if ( sizeof($stack)==0 ) {
        // 当栈为空时认为是XML的根节点
        $rootDom = new SaxElement($name);
        $rootDom->setAttrs($attrs);
        array_push($stack, &$rootDom);
    } else {
        $element = new SaxElement($name);
        $element->setAttrs($attrs);
        $parent = &$stack[sizeof($stack)-1];
        $element->setParentNode($parent);
        $parent->addChild($element);
        array_push($stack, &$element);
    }
}
function endElement($parser, $name) {
    global $stack;
    array_pop($stack);
}
function characterData($parser, $data) {
    if ( trim($data)!="" ) {
        global $stack;
        $element = &$stack[sizeof($stack)-1];
        $element->setValue($data);
    }
}

function xml2array($xml) {
    $xml_parser = xml_parser_create();
    xml_set_element_handler($xml_parser, "startElement", "endElement");
    xml_set_character_data_handler($xml_parser, "characterData");
    
    xml_parser_set_option($xml_parser, XML_OPTION_CASE_FOLDING, 0);
    xml_parser_set_option($xml_parser, XML_OPTION_SKIP_WHITE, 1);

    if (!xml_parse($xml_parser, $xml)) {
        die(sprintf("XML error: %s at line %d",
                    xml_error_string(xml_get_error_code($xml_parser)),
                    xml_get_current_line_number($xml_parser)));
    }
    xml_parser_free($xml_parser);
    
    global $rootDom;
    return $rootDom->getArray2();
}

/*
 * 测试
$xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
<root sname=\"ConsumeService\" result=\"0\">
	<node name=\"time\" type=\"date\">2008-01-22</node>
	<node name=\"merchantid\" type=\"string\">1001</node>
	<node name=\"ch\" type=\"list\">
	    <node type=\"int\">22</node>
	    <node type=\"int\">33</node>
	    <node type=\"string\">44</node>
	</node>
	<node name=\"mapping\" type=\"map\">
	    <node name=\"aaa\" type=\"string\">22</node>
	    <node name=\"bbb\" type=\"int\">33</node>
	    <node name=\"ccc\" type=\"int\">44</node>
	</node>
</root>";

$arr = xml2array($xml);
print_a('return', $arr);


$xml = "<?xml version=\"1.0\" encoding=\"GBK\"?>
<cb-xdoc sname=\"ConsumeService\" result=\"E12042000\"><node name=\"_error\" type=\"string\"><![CDATA[银行不允许此交易]]></node><node name=\"error\" type=\"string\"><![CDATA[E12042000]]></node></cb-xdoc>";
$arr = xml2array($xml);
print_a('return', $arr);
*/
?>