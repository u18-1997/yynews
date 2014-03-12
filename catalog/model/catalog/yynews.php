<?php
class ModelCatalogYynews extends Model {

		public function getYynewss($data = array()) {
		if ($data) {
			$sql = "SELECT * FROM " . DB_PREFIX . "yynews i LEFT JOIN " . DB_PREFIX . "yynews_description id ON (i.yynews_id = id.yynews_id) WHERE id.language_id = '" . (int)$this->config->get('config_language_id') . "' ";
                        $sort_data = array(
				'id.title',
				'i.newsdate'
			);		
		
			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY i.top DESC, " . $data['sort'];	
			} else {
				$sql .= " ORDER BY i.top DESC, i.newsdate";	
			}
			
			if (isset($data['order']) && ($data['order'] == 'ASC')) {
				$sql .= " ASC";
			} else {
				$sql .= " DESC";
			}
			if (isset($data['start']) || isset($data['limit'])) {
				if ($data['start'] < 0) {
					$data['start'] = 0;
				}		

				if ($data['limit'] < 1) {
					$data['limit'] = 20;
				}	
                                
				$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
                                
			}	
			$query = $this->db->query($sql);
			return $query->rows;
		} else {
			$yynews_data = $this->cache->get('yynews.' . (int)$this->config->get('config_language_id'));
		
			if (!$yynews_data) {
                                $sql="( SELECT * FROM " . DB_PREFIX . "yynews i LEFT JOIN " . DB_PREFIX . "yynews_description id ON (i.yynews_id = id.yynews_id) WHERE id.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY i.top DESC ,i.newsdate ) ";
				$query = $this->db->query($sql);
	
				$yynews_data = $query->rows;
			
				$this->cache->set('yynews.' . (int)$this->config->get('config_language_id'), $yynews_data);
			}		
	
			return $yynews_data;			
		}
	}
	
	public function getYynewsDescriptions($yynews_id) {
		$yynews_description_data = array();
		
		$query = $this->db->query("SELECT title,description FROM " . DB_PREFIX ."yynews i LEFT JOIN ".DB_PREFIX ."yynews_description id  ON (i.yynews_id = id.yynews_id) WHERE i.yynews_id = '" . (int)$yynews_id . "' and id.language_id = " . (int)$this->config->get('config_language_id'));
		return $query->rows;
	}
	


		
	public function getTotalYynewss() {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "yynews where  status=1");
		
		return $query->row['total'];
	}	
	

}
?>