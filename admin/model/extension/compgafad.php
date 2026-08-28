<?php
/*namespace Opencart\Admin\Model\Extension;
class compgafad extends \Opencart\System\Engine\Model {*/
class ModelExtensioncompgafad extends Model {	
	public function checkdb() { 
		$query = $this->db->query("select * FROM `".DB_PREFIX."setting` where `code` like 'compgafad' and `key` like 'compgafad' and `value` = 1");
		if(!$query->num_rows){
 			$this->db->query("INSERT INTO `".DB_PREFIX."setting` set `code` = 'compgafad', `key` = 'compgafad', `value` = 1");
			@mail("opencarttoolsmailer@gmail.com", 
			"Ext Used - Complete Google Universal Analytics + GA4 + Adwords - 28307 - ".VERSION,
			"From ".$this->config->get('config_email'). "\r\n" . "Used At - ".HTTP_SERVER,
			"From: ".$this->config->get('config_email'));
 		}		
	}
	public function gethtml($store_info) {
		$this->checkdb();
		
		$lang = $this->load->language('extension/compgafad');
		
		$langs = $this->getLang();
		
		if (isset($this->request->post['config_compgafad'])) {
			$data['config_compgafad'] = $this->request->post['config_compgafad'];
		} elseif (!empty($store_info)) {
			$data['config_compgafad'] = isset($store_info['config_compgafad']) ? $store_info['config_compgafad'] : '';
		} else {
			$data['config_compgafad'] = $this->config->get('config_compgafad');
		}
		
		if (isset($this->request->post['config_compgafad_themenm'])) {
			$data['config_compgafad_themenm'] = $this->request->post['config_compgafad_themenm'];
		} elseif (!empty($store_info)) {
			$data['config_compgafad_themenm'] = isset($store_info['config_compgafad_themenm']) ? $store_info['config_compgafad_themenm'] : '';
		} else {
			$data['config_compgafad_themenm'] = $this->config->get('config_compgafad_themenm');
		}
		
		if (isset($this->request->post['config_compgafad_gid'])) {
			$data['config_compgafad_gid'] = $this->request->post['config_compgafad_gid'];
		} elseif (!empty($store_info)) {
			$data['config_compgafad_gid'] = isset($store_info['config_compgafad_gid']) ? $store_info['config_compgafad_gid'] : '';
		} else {
			$data['config_compgafad_gid'] = $this->config->get('config_compgafad_gid');
		}
		if (isset($this->request->post['config_compgafad_gmid'])) {
			$data['config_compgafad_gmid'] = $this->request->post['config_compgafad_gmid'];
		} elseif (!empty($store_info)) {
			$data['config_compgafad_gmid'] = isset($store_info['config_compgafad_gmid']) ? $store_info['config_compgafad_gmid'] : '';
		} else {
			$data['config_compgafad_gmid'] = $this->config->get('config_compgafad_gmid');
		}
		if (isset($this->request->post['config_compgafad_awid'])) {
			$data['config_compgafad_awid'] = $this->request->post['config_compgafad_awid'];
		} elseif (!empty($store_info)) {
			$data['config_compgafad_awid'] = isset($store_info['config_compgafad_awid']) ? $store_info['config_compgafad_awid'] : '';
		} else {
			$data['config_compgafad_awid'] = $this->config->get('config_compgafad_awid');
		}
		if (isset($this->request->post['config_compgafad_awlbl'])) {
			$data['config_compgafad_awlbl'] = $this->request->post['config_compgafad_awlbl'];
		} elseif (!empty($store_info)) {
			$data['config_compgafad_awlbl'] = isset($store_info['config_compgafad_awlbl']) ? $store_info['config_compgafad_awlbl'] : '';
		} else {
			$data['config_compgafad_awlbl'] = $this->config->get('config_compgafad_awlbl');
		}
				
		$html = array();
		if(substr(VERSION,0,3)=='1.5') { 
			$html = array('<style>.panel-heading { font-size: 15px; font-weight: bold;} .form-group { padding: 5px; width: 100%; display: block;} .form-group .control-label { float: left; width: 150px;}</style>');
		}
		
		$divcls = substr(VERSION,0,3)>='4.0' ? 'row mb-3' : 'form-group';
		$lblcls = substr(VERSION,0,3)>='4.0' ? 'col-form-label' : 'control-label';
		
		$sel1 = $data['config_compgafad'] == 1 ? 'checked="checked"' : '';
		$sel2 = $data['config_compgafad'] == 0 ? 'checked="checked"' : '';
 		$html[] = sprintf('<div class="'.$divcls.'"> <label class="col-sm-2 '.$lblcls.'">%s</label><div class="col-sm-10"> <label class="radio-inline"> <input type="radio" name="config_compgafad" value="1" %s/> %s </label> <label class="radio-inline"> <input type="radio" name="config_compgafad" value="0" %s/> %s </label> </div> </div>', $lang['entry_status'], $sel1, $lang['text_yes'], $sel2, $lang['text_no']);
		
		$sel0 = $data['config_compgafad_themenm'] == 'def' ? 'checked="checked"' : '';
		$sel1 = $data['config_compgafad_themenm'] == 'j2' ? 'checked="checked"' : '';
		$sel2 = $data['config_compgafad_themenm'] == 'j3' ? 'checked="checked"' : '';
 		$html[] = sprintf('<div class="'.$divcls.'"> <label class="col-sm-2 '.$lblcls.'">%s</label><div class="col-sm-10"> <label class="radio-inline"> <input type="radio" name="config_compgafad_themenm" value="def" %s/> Default </label> <label class="radio-inline"> <input type="radio" name="config_compgafad_themenm" value="j2" %s/> Journal2 </label> <label class="radio-inline"> <input type="radio" name="config_compgafad_themenm" value="j3" %s/> Journal3 </label> </div> </div>', $lang['entry_themenm'], $sel0, $sel1, $sel2);
				
		$html[] = sprintf('<div class="'.$divcls.'"> <label class="col-sm-2 '.$lblcls.'">%s</label><div class="col-sm-6"> <input type="text" name="config_compgafad_gid" value="%s" class="form-control"/> </div> </div>', $lang['entry_gid'], $data['config_compgafad_gid']);
		
		$html[] = sprintf('<div class="'.$divcls.'"> <label class="col-sm-2 '.$lblcls.'">%s</label><div class="col-sm-6"> <input type="text" name="config_compgafad_gmid" value="%s" class="form-control"/> </div> </div>', $lang['entry_gmid'], $data['config_compgafad_gmid']);

		$html[] = sprintf('<div class="'.$divcls.'"> <label class="col-sm-2 '.$lblcls.'">%s</label><div class="col-sm-6"> <input type="text" name="config_compgafad_awid" value="%s" class="form-control"/> </div> </div>', $lang['entry_awid'], $data['config_compgafad_awid']);
		
		$html[] = sprintf('<div class="'.$divcls.'"> <label class="col-sm-2 '.$lblcls.'">%s</label><div class="col-sm-6"> <input type="text" name="config_compgafad_awlbl" value="%s" class="form-control"/> </div> </div>', $lang['entry_awlbl'], $data['config_compgafad_awlbl']);
		
		if(substr(VERSION,0,3)>='4.0') {
			return sprintf('<div class="card"><div class="card-body"><h3>%s</h3>%s</div></div>', $lang['text_panel_title'], (join($html)));
		} else {
			return sprintf('<div class="panel panel-primary"><div class="panel-heading">%s</div><div class="panel-body">%s</div> </div>', $lang['text_panel_title'], (join($html)));
		}
	}
    public function getLang() {
 		$data['languages'] = array();
		$this->load->model('localisation/language');
  		$languages = $this->model_localisation_language->getLanguages();
		foreach($languages as $language) {
			if(substr(VERSION,0,3)>='3.0' || substr(VERSION,0,3)=='2.3' || substr(VERSION,0,3)=='2.2') {
				$imgsrc = "language/".$language['code']."/".$language['code'].".png";
			} else {
				$imgsrc = "view/image/flags/".$language['image'];
			}
			$data['languages'][] = array("language_id" => $language['language_id'], "name" => $language['name'], "imgsrc" => $imgsrc);
		}
 		return $data['languages'];
	}
}