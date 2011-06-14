<?php 

/**************************************************************************
*  Copyright notice
*
*  Copyright 2011 Logic Works GmbH
*
*  Licensed under the Apache License, Version 2.0 (the "License");
*  you may not use this file except in compliance with the License.
*  You may obtain a copy of the License at
*
*  http://www.apache.org/licenses/LICENSE-2.0
*  
*  Unless required by applicable law or agreed to in writing, software
*  distributed under the License is distributed on an "AS IS" BASIS,
*  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
*  See the License for the specific language governing permissions and
*  limitations under the License.
*  
***************************************************************************/

class lw_globalbox extends lw_plugin
{
	function __construct()
	{
		parent::__construct();
		$reg = lw_registry::getInstance();
		$this->repository = $reg->getEntry("repository");
	}
	
	function buildPageOutput()
	{
		if ($this->params['hide'] == 1) return false;
		$oid		 = $this->params['oid'];
		$parser      = new lw_fe_parser();
		include_once($this->config['path']['agents']."versioning/agent_versioning_dh.class.php");
		$this->dh	 = new agent_versioning_dh();		
		$set 		 = $this->dh->getCObjectById($oid);
		$data        = $this->repository->loadEAV($oid, 'globalbox');
		
        foreach($data as $key => $value) {
            $key = str_replace('lw_', '', $key);
            $set['data'][$key] = $value;
        }		
		
        $parser->setOID($oid);
        $parser->setOddEven($this->oddeven);
	    $parser->setTemplate($set['template']);
		$parser->setData($set['data']);
		$out = $parser->parse();
		$out = str_replace('<!-- lw:function edit(,'.$oid.') -->','',$out);
		return $out;
	}
}
