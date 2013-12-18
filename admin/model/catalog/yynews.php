<?php
class ModelCatalogYynews extends Model {
	public function addYynews($data) {

                $this->db->query("INSERT INTO " . DB_PREFIX . "yynews SET  top = '" . (isset($data['top']) ? (int)$data['top'] : 0) 
                                                ."',newsdate='".$this->db->escape($data['newsdate'])
                                                ."',titleimage = '" . $this->db->escape(html_entity_decode($data['image'], ENT_QUOTES, 'UTF-8'))
                                                . "', status = '" . (int)$data['status'] . "'");

		$yynews_id = $this->db->getLastId(); 
		foreach ($data['yynews_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "yynews_description SET yynews_id = '" . (int)$yynews_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($value['title']) . "',summary='".$this->db->escape($value['summary'])."', description = '" . $this->db->escape($value['description']) . "'");
		}
				
		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'yynews_id=" . (int)$yynews_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}
		
		$this->cache->delete('yynews');
	}
	
	public function editYynews($yynews_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "yynews SET  top = '" . (isset($data['top']) ? (int)$data['top'] : 0) 
                                                ."',newsdate='".$this->db->escape($data['newsdate'])
                                                ."',titleimage = '" . $this->db->escape(html_entity_decode($data['image'], ENT_QUOTES, 'UTF-8'))
                                                . "', status = '" . (int)$data['status'] . "'"
                                                . "  WHERE yynews_id = '" . (int)$yynews_id . "'");
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "yynews_description WHERE yynews_id = '" . (int)$yynews_id . "'");
					
		foreach ($data['yynews_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "yynews_description SET yynews_id = '" . (int)$yynews_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($value['title']) . "',summary='".$this->db->escape($value['summary'])."', description = '" . $this->db->escape($value['description']) . "'");
		}
			
				
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'yynews_id=" . (int)$yynews_id. "'");
		
		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'yynews_id=" . (int)$yynews_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}
		
		$this->cache->delete('yynews');
	}
	
	public function deleteYynews($yynews_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "yynews WHERE yynews_id = '" . (int)$yynews_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "yynews_description WHERE yynews_id = '" . (int)$yynews_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'yynews_id=" . (int)$yynews_id . "'");

		$this->cache->delete('yynews');
	}	

	public function getYynews($yynews_id) {
		$query = $this->db->query("SELECT DISTINCT *, (SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'yynews_id=" . (int)$yynews_id . "') AS keyword FROM " . DB_PREFIX . "yynews WHERE yynews_id = '" . (int)$yynews_id . "'");
		
		return $query->row;
	}
		
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
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "yynews i LEFT JOIN ".DB_PREFIX."yynews_description id  ON (i.yynews_id = id.yynews_id) WHERE i.yynews_id = '" . (int)$yynews_id . "'");

		foreach ($query->rows as $result) {
			$yynews_description_data[$result['language_id']] = array(
				'title'       => $result['title'],
                                'summary'    => $result['summary'],
                                'titleimage'    => $result['titleimage'],
                                'newsdate'     => $result['newsdate'],
				'description' => $result['description']
			);
		}
		
		return $yynews_description_data;
	}
	


		
	public function getTotalYynewss() {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "yynews where top=0");
		
		return $query->row['total'];
	}	
	
	public function checkNews() {
		$create_news = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "yynews` (`yynews_id` int(11) NOT NULL AUTO_INCREMENT, `top` int(1) NOT NULL DEFAULT '0',  `sort_order` int(3) NOT NULL DEFAULT '0',  `status` tinyint(1) NOT NULL DEFAULT '1',  `newsdate` datetime NOT NULL,  `titleimage` varchar(255) COLLATE utf8_bin DEFAULT NULL,  PRIMARY KEY (`yynews_id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;";
		$this->db->query($create_news);
		$create_news_descriptions = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX ."yynews_description` (`yynews_id` int(11) NOT NULL,`language_id` int(11) NOT NULL,  `title` varchar(64) COLLATE utf8_bin NOT NULL DEFAULT '',  `description` text COLLATE utf8_bin NOT NULL,  `summary` varchar(255) COLLATE utf8_bin DEFAULT NULL,  PRIMARY KEY (`yynews_id`,`language_id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;" ;
		$this->db->query($create_news_descriptions);
	}
}
?>