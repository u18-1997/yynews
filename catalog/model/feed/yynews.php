<?php
class ModelFeedYynews extends Model {

		public function getYynewss($data = array()) {
		if ($data) {
			$sql1 = "SELECT * FROM " . DB_PREFIX . "yynews i LEFT JOIN " . DB_PREFIX . "yynews_description id ON (i.yynews_id = id.yynews_id) WHERE id.language_id = '" . (int)$this->config->get('config_language_id') . "' and top=1 and status=1";
                        $sql2 = "SELECT * FROM " . DB_PREFIX . "yynews i LEFT JOIN " . DB_PREFIX . "yynews_description id ON (i.yynews_id = id.yynews_id) WHERE id.language_id = '" . (int)$this->config->get('config_language_id') . "' and top=0 and status=1";
			$sql="";
                        $sort_data = array(
				'id.title',
				'i.newsdate'
			);		
		
			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];	
			} else {
				$sql .= " ORDER BY id.newsdate";	
			}
			
			if (isset($data['order']) && ($data['order'] == 'ASC')) {
				$sql .= " ASC";
			} else {
				$sql .= " DESC";
			}
                        
                        $sql1='('.$sql1.$sql.')';
                        
			if (isset($data['start']) || isset($data['limit'])) {
				if ($data['start'] < 0) {
					$data['start'] = 0;
				}		

				if ($data['limit'] < 1) {
					$data['limit'] = 20;
				}	
                                
				$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
                                
			}	
			$sql2='('.$sql2.$sql.')';
                        $sql=$sql1.' union all '.$sql2;
			$query = $this->db->query($sql);
			
			return $query->rows;
		} else {
			$yynews_data = $this->cache->get('yynews.' . (int)$this->config->get('config_language_id'));
		
			if (!$yynews_data) {
                                $sql="( SELECT * FROM " . DB_PREFIX . "yynews i LEFT JOIN " . DB_PREFIX . "yynews_description id ON (i.yynews_id = id.yynews_id) WHERE id.language_id = '" . (int)$this->config->get('config_language_id') . " and top=1 and status=1' ORDER BY i.newsdate ) "
                                    ." union all  "
                                    ."( SELECT * FROM " . DB_PREFIX . "yynews i LEFT JOIN " . DB_PREFIX . "yynews_description id ON (i.yynews_id = id.yynews_id) WHERE id.language_id = '" . (int)$this->config->get('config_language_id') . " and top=0 and status=1' ORDER BY i.newsdate ) ";
				$query = $this->db->query($sql);
	
				$yynews_data = $query->rows;
			
				$this->cache->set('yynews.' . (int)$this->config->get('config_language_id'), $yynews_data);
			}	
	
			return $yynews_data;			
		}
	}
	
	public function getYynewsDescriptions($yynews_id) {
		$yynews_description_data = array();
		
		$query = $this->db->query("SELECT title,description FROM " . DB_PREFIX . " yynews i LEFT JOIN yynews_description id  ON (i.yynews_id = id.yynews_id) WHERE i.yynews_id = '" . (int)$yynews_id . "' and id.language_id = " . (int)$this->config->get('config_language_id'));

//		foreach ($query->rows as $result) {
//			$yynews_description_data[$result['language_id']] = array(
//				'title'       => $result['title'],
//				'description' => $result['description']
//			);
//		}
		
		return $query->rows;
	}
	


		
	public function getTotalYynewss() {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "yynews where top=0 and status=1");
		
		return $query->row['total'];
	}	
	

}
?>