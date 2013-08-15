<?php 
class CreateXML {
	/**
	* 编码
	* @access	private
	* @var		array1
	*/
    //var $encoding;
	/**
	* @access	private
	* @var		array1
	*/
    var $_tree;
    var $encoding="GBK";
	/**
	* Constructor
	*/
	function CBCreateXML($encoding = 'GBK') {
	    $this->encoding = $encoding;
		$this->_tree = array();
		
	}
	
	/**
	* 建立根节点
	*/
	function createRoot($sname, $sversion) {
	    $tree = array();
	    $tree['tag'] = 'cb-xdoc';
	    $tree['attrs'] = array(
	       'sname' => $sname,
	       'sversion' => $sversion
	    );
	    /*
	    $tree['att.sname'] = $sname;
	    $tree['att.sversion'] = $sversion;
	    */
	    
	    $this->_tree = $tree;
	}
	
	/**
	* 建立普通节点
	*/
	function addNode($name, $type, $val) {	   
	    $node = array();
	    $node['tag'] = 'node';
	    $node['attrs'] = array(
	       'name' => $name,
	       'type' => $type
	    );
	    switch ($type) {
	       case 'list':
	       case 'map':
	           $node['node'] = array();
	           foreach($val as $k=>$v) {
	               $node['node'][] = $this->_recursionGetNode($k, $v);
	           }
	           break;
	       default:
	           $node['val'] = $val;
	    }
	           
	    
	    /*
	    $node['att.name'] = $name;
	    $node['att.type'] = $type;
	    if ($type = 'money') {
	        $node['att.currency'] = 'CNY';
	    }
	    $node['val'] = $val;
	    */
	    
	    $this->_tree['node'][] = $node;
	}
	
	function _recursionGetNode($name, $val) {
	    $node = array();
	    $node['tag'] = 'node';
	    $node['attrs'] = array('name' => $name);
	    
	    $type = gettype($val);
	    switch ($type) {
	       case "array":
	           $node['attrs']['type'] = 'map';
	           foreach($val as $k=>$v) {
	               $node['node'][] = $this->_recursionGetNode($k, $v);
	           }
	           break;
	       case "integer":
	           $node['attrs']['type'] = 'int';
	           $node['val'] = $val;
	           break;
	       default:
	           $node['attrs']['type'] = 'string';
	           $node['val'] = $val;
	    }
	    return $node;
	}
	
	function getTree() {
	    return $this->_tree;
	}
	
	function getString() {
	    $tree = $this->_tree;
	    $strxml  = "<?xml version=\"1.0\" encoding=\"{$this->encoding}\"?>\n";
	    $strxml .= "<{$tree['tag']} sname=\"{$tree['attrs']['sname']}\" sversion=\"{$tree['attrs']['sversion']}\">\n";
	    
	    foreach ($tree['node'] as $node) {
	        $strxml .=  $this->_2str($node);
	    }
	    $strxml .= "</{$tree['tag']}>\n";
	    
	    return $strxml;
	}
	
	function _2str($node, $sp="    ") {
	    $tag  = $node['tag'];
	    $name = $node['attrs']['name'];
	    $type = $node['attrs']['type'];
	    //$val  = $node['val'];
	    $val  = !empty($node['val']) ? $node['val'] : ''; //modify by flyC
	    
	    // 开始 <node>
	    if ($type == 'money') {
	        $str  = "$sp<$tag name=\"$name\" type=\"$type\" currency=\"{$node['att.currency']}\">\n";
	    } else {
	        $str  = "$sp<$tag name=\"$name\" type=\"$type\">\n";
	    } 
	    /**
	    if ($type == 'string') {
	        $str .= '<![CDATA[' . $val . ']]>';
	    } else {
	        $str .= $val;
	    }*/
	    // 值
	    switch($type) {
	       case 'NULL':
	           return '';
	       case 'string':
	           $str .= "$sp    <![CDATA[$val]]>\n";
	           break;
	       case 'list':
	       case 'map':
	           if (array_key_exists('node', $node)) {
	               foreach ($node['node'] as $n) {
	                   $str .=  $this->_2str($n, $sp."    ");
	               }
	           }
	           break;
	       default:
	           $str .= "$sp    $val\n";
	    }
	    // 关闭 </node>
	    $str .= "$sp</$tag>\n";
	    
	    return $str;
	}
}
?>