<?php 
/*
 * @company: 	Symbiotic Infotech Pvt. Ltd.
 * @copyright: 	� Symbiotic Infotech Pvt. Ltd. 2011
 *				All rights reserved.Any redistribution or reproduction of part
 * 				or all of the contents in any form is prohibited. You may not,
 * 				except with express written permission, distribute or
 * 				commercially exploit or personally use the content.
 * 				Nor may you transmit it or store it in any other media or
 * 				other form of electronic or physical retrieval system.
 *
 * @filename:	arraytoxml.class.inc
 * @filetype:	PHP
 * @filedesc:	this class can convert a xml document into assoc array
 *
 */
 
class XMLtoArray {
	function __construct($xml) {
		// check for file
		if (file_exists ( $xml ))
			$xml = file_get_contents ( $xml );
		
		// check for string, open in dom
		if (is_string ( $xml ) ) {
			$xml = domxml_open_mem ( $xml );
			//$this->root_element = $xml->document_element ();
		}
		
		// check for dom-creation, 
		if (is_object ( $xml ) && $xml->node_type () == XML_DOCUMENT_NODE) {
			$this->root_element = $xml->document_element ();
			//$this->xml_string = $xml->dump_mem(true);
			return TRUE;
		}
		
		if (is_object ( $xml ) && $xml->node_type () == XML_ELEMENT_NODE) {
			$this->root_element = $xml;
			return TRUE;
		}
		
		return FALSE;
	}
	
	/**
	 * recursive function to walk through dom and create array
	 */
	function _recNode2Array($domnode) {
		if ($domnode->node_type () == XML_ELEMENT_NODE) {
			
			$childs = $domnode->child_nodes ();
			foreach ( $childs as $child ) {
				if ($child->node_type () == XML_ELEMENT_NODE) {
					$subnode = false;
					$prefix = ($child->prefix ()) ? $child->prefix () . ':' : '';
					
					// try to check for multisubnodes
					foreach ( $childs as $testnode )
						if (is_object ( $testnode ))
							if ($child->node_name () === $testnode->node_name () && $child != $testnode)
								$subnode = true;
					
					if (is_array ( $result [$prefix . $child->node_name ()] ))
						$subnode = true;
					
					if ($subnode == true)
						$result [$prefix . $child->node_name ()] [] = $this->_recNode2Array ( $child );
					else
						$result [$prefix . $child->node_name ()] = $this->_recNode2Array ( $child );
				}
			}
			
			if (! is_array ( $result )) {
				// correct encoding from utf-8 to locale
				// NEEDS to be updated to correct in both ways!
				//$result ['#text'] = html_entity_decode ( htmlentities ( $domnode->get_content (), ENT_COMPAT, 'UTF-8' ), ENT_COMPAT, 'ISO-8859-15' );
				$result = html_entity_decode ( htmlentities ( $domnode->get_content (), ENT_COMPAT, 'UTF-8' ), ENT_COMPAT, 'ISO-8859-15' );
			}
			
			if ($domnode->has_attributes ())
				foreach ( $domnode->attributes () as $attrib ) {
					$prefix = ($attrib->prefix ()) ? $attrib->prefix () . ':' : '';
					$result ["@" . $prefix . $attrib->name ()] = $attrib->value ();
				}
			
			return $result;
		}
	}
	
	/**
	 * caller func to get an array out of dom
	 */
	function getResult() {
		if ($resultDomNode = $this->root_element) {
			$array_result [$resultDomNode->tagname ()] = $this->_recNode2Array ( $resultDomNode );
			return $array_result;
		} else
			return false;
	}
	
	function getEncoding() {
		preg_match ( "~\<\?xml.*encoding=[\"\'](.*)[\"\'].*\?\>~i", $this->xml_string, $matches );
		return ($matches [1]) ? $matches [1] : "";
	}
	
	function getNamespaces() {
		preg_match_all ( "~[[:space:]]xmlns:([[:alnum:]]*)=[\"\'](.*?)[\"\']~i", $this->xml_string, $matches, PREG_SET_ORDER );
		foreach ( $matches as $match )
			$result [$match [1]] = $match [2];
		return $result;
	}
}
?>