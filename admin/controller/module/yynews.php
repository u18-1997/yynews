<?php
class ControllerModuleYynews extends Controller { 
	private $error = array();

	public function index() {
		$this->load->language('module/yynews');

		$this->document->setTitle($this->language->get('heading_title'));
		

                 $this->load->model('setting/setting');
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('yynews', $this->request->post);		
					
			$this->session->data['success'] = $this->language->get('text_success');
						
			$this->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
		}            
                
			 

		$this->getModule();
	}

	public function insert() {
		$this->load->language('module/yynews');

		$this->document->setTitle($this->language->get('text_news_manager'));
		
		$this->load->model('catalog/yynews');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_yynews->addYynews($this->request->post);
			
			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			
			$this->redirect($this->url->link('module/yynews/getList', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function update() {
		$this->load->language('module/yynews');

		$this->document->setTitle($this->language->get('text_news_manager'));
		
		$this->load->model('catalog/yynews');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_yynews->editYynews($this->request->get['yynews_id'], $this->request->post);
			
			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			
			$this->redirect($this->url->link('module/yynews/getList', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}
 
	public function delete() {
		$this->load->language('module/yynews');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('catalog/yynews');
		
		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $yynews_id) {
				$this->model_catalog_yynews->deleteYynews($yynews_id);
			}
			
			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			
			$this->redirect($this->url->link('module/yynews/getList', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getList();
	}

	public function getList() {
               $this->load->language('module/yynews');
                $this->load->model('catalog/yynews');
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'i.newsdate';
		}
		
		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'DESC';
		}
		
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		
		$url = '';
			
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
                
                $this->document->setTitle($this->language->get('heading_title'));

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
                );

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('module/yynews', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => ' :: '
   		);
							
		$this->data['insert'] = $this->url->link('module/yynews/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['delete'] = $this->url->link('module/yynews/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');	

		$this->data['yynewss'] = array();

		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
		);
		
		$yynews_total = $this->model_catalog_yynews->getTotalYynewss();
	
		$results = $this->model_catalog_yynews->getYynewss($data);
 
    	foreach ($results as $result) {
			$action = array();
						
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('module/yynews/update', 'token=' . $this->session->data['token'] . '&yynews_id=' . $result['yynews_id'] . $url, 'SSL')
			);
						
			$this->data['yynewss'][] = array(
				'yynews_id' => $result['yynews_id'],
                                'status'    =>$result['status'],
                                'top'       => $result['top'],
				'title'          => $result['title'],
                                'newsdate'          => $result['newsdate'],
				'selected'       => isset($this->request->post['selected']) && in_array($result['yynews_id'], $this->request->post['selected']),
				'action'         => $action
			);
		}	
	
		$this->data['heading_title'] = $this->language->get('text_news_manager');

		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_title'] = $this->language->get('column_title');
                $this->data['column_newsdate'] = $this->language->get('column_newsdate');
		$this->data['column_action'] = $this->language->get('column_action');		
		
		$this->data['button_insert'] = $this->language->get('button_insert');
		$this->data['button_delete'] = $this->language->get('button_delete');
 
 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
		
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		$this->data['sort_title'] = $this->url->link('module/yynews/getList', 'token=' . $this->session->data['token'] . '&sort=id.title' . $url, 'SSL');
		$this->data['sort_newsdate'] = $this->url->link('module/yynews/getList', 'token=' . $this->session->data['token'] . '&sort=i.newsdate' . $url, 'SSL');
		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
												
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $yynews_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('module/yynews/getlist', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');
			
		$this->data['pagination'] = $pagination->render();

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->template = 'module/yynews_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
	}

	public function getForm() {
                $this->load->language('module/yynews');
                $this->load->model('catalog/yynews');
		$this->data['heading_title'] = $this->language->get('text_news_manager');

		$this->data['text_default'] = $this->language->get('text_default');
		$this->data['text_enabled'] = $this->language->get('text_enabled');
                $this->data['text_disabled'] = $this->language->get('text_disabled');
		
		$this->data['entry_title'] = $this->language->get('entry_title');
		$this->data['entry_description'] = $this->language->get('entry_description');
		$this->data['entry_keyword'] = $this->language->get('entry_keyword');
		$this->data['entry_newsdate'] = $this->language->get('entry_newsdate');
                $this->data['entry_top'] = $this->language->get('entry_top');
		$this->data['entry_status'] = $this->language->get('entry_status');
		//这是新加的内容
                $this->data['entry_titleimage'] = $this->language->get('entry_titleimage');
                $this->data['text_image_manager'] = $this->language->get('text_image_manager');
                $this->data['entry_summary'] = $this->language->get('entry_summary');
		$this->data['text_browse'] = $this->language->get('text_browse');//选择图像
		$this->data['text_clear'] = $this->language->get('text_clear');	//清除图像	                
                
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
    	
		$this->data['tab_general'] = $this->language->get('tab_general');
                $this->data['tab_data'] = $this->language->get('tab_data');

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

 		if (isset($this->error['title'])) {
			$this->data['error_title'] = $this->error['title'];
		} else {
			$this->data['error_title'] = array();
		}
 		if (isset($this->error['summary'])) {
			$this->data['error_summary'] = $this->error['error_summary'];
		} else {
			$this->data['error_summary'] = array();
		}
	 	if (isset($this->error['description'])) {
			$this->data['error_description'] = $this->error['description'];
		} else {
			$this->data['error_description'] = array();
		}
		
		$url = '';
			
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),     		
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
                );
                
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('module/yynews', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => ' :: '
   		);
                
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_news_manager'),
			'href'      => $this->url->link('module/yynews/getList', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => ' :: '
   		);                
							
		if (!isset($this->request->get['yynews_id'])) {
			$this->data['action'] = $this->url->link('module/yynews/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$this->data['action'] = $this->url->link('module/yynews/update', 'token=' . $this->session->data['token'] . '&yynews_id=' . $this->request->get['yynews_id'] . $url, 'SSL');
		}
		
		$this->data['cancel'] = $this->url->link('module/yynews/getList', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->request->get['yynews_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$yynews_info = $this->model_catalog_yynews->getYynews($this->request->get['yynews_id']);
		}
		
		$this->data['token'] = $this->session->data['token'];
		
		$this->load->model('localisation/language');
		
		$this->data['languages'] = $this->model_localisation_language->getLanguages();
		
		if (isset($this->request->post['yynews_description'])) {
			$this->data['yynews_description'] = $this->request->post['yynews_description'];
		} elseif (isset($this->request->get['yynews_id'])) {
			$this->data['yynews_description'] = $this->model_catalog_yynews->getYynewsDescriptions($this->request->get['yynews_id']);
		} else {
			$this->data['yynews_description'] = array();
		}


		if (isset($this->request->post['keyword'])) {
			$this->data['keyword'] = $this->request->post['keyword'];
		} elseif (!empty($yynews_info)) {
			$this->data['keyword'] = $yynews_info['keyword'];
		} else {
			$this->data['keyword'] = '';
		}
		
		if (isset($this->request->post['top'])) {
			$this->data['top'] = $this->request->post['top'];
		} elseif (!empty($yynews_info)) {
			$this->data['top'] = $yynews_info['top'];
		} else {
			$this->data['top'] = 0;
		}
		
		if (isset($this->request->post['status'])) {
			$this->data['status'] = $this->request->post['status'];
		} elseif (!empty($yynews_info)) {
			$this->data['status'] = $yynews_info['status'];
		} else {
			$this->data['status'] = 1;
		}
                
		if (isset($this->request->post['status'])) {
			$this->data['status'] = $this->request->post['status'];
		} elseif (!empty($yynews_info)) {
			$this->data['status'] = $yynews_info['status'];
		} else {
			$this->data['status'] = 1;
		}
                
		if (isset($this->request->post['newsdate'])) {
			$this->data['newsdate'] = $this->request->post['newsdate'];
		} elseif (!empty($yynews_info)) {
			$this->data['newsdate'] = $yynews_info['newsdate'];
		} else {
			$this->data['newsdate'] = '';
		}                
				
		if (isset($this->request->post['image'])) {
			$this->data['image'] = $this->request->post['image'];
		} elseif (!empty($yynews_info)) {
			$this->data['image'] = $yynews_info['titleimage'];
		} else {
			$this->data['image'] = '';
		}
		
		$this->load->model('tool/image');

		if (isset($this->request->post['image']) && file_exists(DIR_IMAGE . $this->request->post['image'])) {
			$this->data['thumb'] = $this->model_tool_image->resize($this->request->post['image'], 100, 100);
		} elseif (!empty($yynews_info) && $yynews_info['titleimage'] && file_exists(DIR_IMAGE . $yynews_info['titleimage'])) {
			$this->data['thumb'] = $this->model_tool_image->resize($yynews_info['titleimage'], 100, 100);
		} else {
			$this->data['thumb'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
		}
		
		$this->data['no_image'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);

				
		$this->template = 'module/yynews_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
	}

	public function validateForm() {
               
		if (!$this->user->hasPermission('modify', 'module/yynews')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		foreach ($this->request->post['yynews_description'] as $language_id => $value) {
			if ((utf8_strlen($value['title']) < 3) || (utf8_strlen($value['title']) > 64)) {
				$this->error['title'][$language_id] = $this->language->get('error_title');
			}
		
		}
		
		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}
			
		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
        
        public function getModule() 
        {
           //     $this->load->model('setting/setting');
                
                $this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_content_top'] = $this->language->get('text_content_top');
		$this->data['text_content_bottom'] = $this->language->get('text_content_bottom');		
		$this->data['text_column_left'] = $this->language->get('text_column_left');
		$this->data['text_column_right'] = $this->language->get('text_column_right');
		$this->data['entry_layout'] = $this->language->get('entry_layout');
		$this->data['entry_position'] = $this->language->get('entry_position');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
                
		
                
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_add_module'] = $this->language->get('button_add_module');
		$this->data['button_remove'] = $this->language->get('button_remove'); 
                
		$this->data['text_news_manager'] = $this->language->get('text_news_manager');//新添加
                
                $this->data['text_column_maxrows'] = $this->language->get('text_column_maxrows');
                $this->data['text_column_displayimg'] = $this->language->get('text_column_displayimg'); //新添加
                
 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
//   		$this->data['breadcrumbs'][] = array(
//       		'text'      => $this->language->get('heading_title'),
//			'href'      => $this->url->link('module/yynews', 'token=' . $this->session->data['token'], 'SSL'),
//      		'separator' => ' :: '
//   		);
		
               
                $this->data['news_manager'] =  $this->url->link('module/yynews/getList', 'token=' . $this->session->data['token'], 'SSL');
                
		$this->data['action'] = $this->url->link('module/yynews', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
				
		$this->data['modules'] = array();
		
		if (isset($this->request->post['yynews_module'])) {
			$this->data['modules'] = $this->request->post['yynews_module'];
		} elseif ($this->config->get('yynews_module')) { 
			$this->data['modules'] = $this->config->get('yynews_module');
		}		
		
		$this->load->model('design/layout');
		
		$this->data['layouts'] = $this->model_design_layout->getLayouts();

		$this->template = 'module/yynews.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);          
                $this->response->setOutput($this->render());
        }
        
	public function validate() {
		if (!$this->user->hasPermission('modify', 'module/yynews')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}        

	public function validateDelete() {
		if (!$this->user->hasPermission('modify', 'module/yynews')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
}
?>