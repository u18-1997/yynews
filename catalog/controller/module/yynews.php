<?php
class Controllermoduleyynews extends Controller { 
	private $error = array();

	public function index() {
		$this->load->language('feed/yynews');
		$this->load->model('feed/yynews');
		$this->getList();
	}

        public function  browse()
        {
		$this->load->language('feed/yynews');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('feed/yynews');    
                $this->getDetail();
        }
        private function getDetail()
        {
                $this->data['heading_title'] = $this->language->get('heading_title');
  		$this->data['breadcrumbs'] = array();
                $this->data['breadcrumbs'][] = array(
                        'text'      => $this->language->get('text_home'),
                                'href'      => $this->url->link('common/home'),
                        'separator' => false
                );
                if (isset($this->request->get['yynews_id']))
                {   $yynews_id=$this->request->get['yynews_id'];}
                else 
                {   $yynews_id=0;}
                $description_info = $this->model_feed_yynews->getYynewsDescriptions($yynews_id);
                if ($description_info)
		 {
                        $this->data['breadcrumbs'][] = array(
        		'text'      => $this->data['heading_title'],
				'href'      => $this->url->link('module/yynews/browse', 'yynews_id=' .  $yynews_id),      		
        		'separator' => $this->language->get('text_separator')
                                                                );               
                $this->data['title']=$description_info[0]['title'];        
                $this->data['description']=html_entity_decode($description_info[0]['description'], ENT_QUOTES, 'UTF-8');
                $this->template = 'default/template/module/yynews_form.tpl';
		$this->children = array(
			'common/column_left',
			'common/content_top',
			'common/content_bottom',
			'common/footer',
			'common/header'
		);
				
		$this->response->setOutput($this->render());     
		 } 
                else
                {
                    $this->data['breadcrumbs'][] = array(
        		'text'      => $this->language->get('text_error'),
			'href'      => $this->url->link('module/yynews/browse', 'yynews_id=' .  $yynews_id),      	
        		'separator' => $this->language->get('text_separator')
                                                           );
                    $this->document->setTitle($this->language->get('text_error'));
                    $this->data['heading_title'] = $this->language->get('text_error');
                    $this->data['text_error'] = $this->language->get('text_error');
                    $this->data['button_continue'] = $this->language->get('button_continue');
                    $this->data['continue'] = $this->url->link('common/home');
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/error/not_found.tpl';
			} else {
				$this->template = 'default/template/error/not_found.tpl';
			}
			$this->children = array(
				'common/column_left',
				'common/column_right',
				'common/content_top',
				'common/content_bottom',
				'common/footer',
				'common/header'
                                                );
	  		$this->response->setOutput($this->render());
                }
                                  
        }
        private function getList() {
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


		$this->data['yynewss'] = array();
                $yynews_module=$this->config->get('yynews_module');
                $limit= $yynews_module[0]['max_rows'];
                if (!($limit))
                    $limit=5;
                $this->data['display_titleimage']=$yynews_module[0]['title_image'];
		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $limit,
			'limit' => $limit
		);
		
		$yynews_total = $this->model_feed_yynews->getTotalYynewss();
	
		$results = $this->model_feed_yynews->getYynewss($data);
 
                if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) 
                {
                   $tempTitleImage= $this->config->get('config_ssl') . 'image/';
                } else 
                {
                   $tempTitleImage= $this->config->get('config_url') . 'image/';
                }	
                foreach ($results as $result) {
			$action = array();
						
			$action[] = array(
				'text' => $this->language->get('text_browse'),
				'href' => $this->url->link('module/yynews/browse', '&yynews_id=' . $result['yynews_id'] . $url, 'SSL')
			);
					
                        
			$this->data['yynewss'][] = array(
				'yynews_id' => $result['yynews_id'],
                                'status'    =>$result['status'],
                                'top'       => $result['top'],
				'title'     => $result['title'],
                                'newsdate'  => $result['newsdate'],
                                'summary'  => $result['summary'],
                                'titleimage'=> $result['titleimage']?$tempTitleImage.$result['titleimage']:"",
				'action'    => $action
			);
		}	
	
		$this->data['heading_title'] = $this->language->get('heading_title');

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
		

		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		$this->data['sort_title'] = $this->url->link('feed/yynews','&sort=id.title' . $url, 'SSL');
		$this->data['sort_newsdate'] = $this->url->link('feed/yynews','&sort=i.newsdate' . $url, 'SSL');
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
		$pagination->limit = $limit;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('common/home', $url . '&page={page}', 'SSL');
		$this->data['pagination'] = $pagination->render();
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;
		$this->template = 'default/template/module/yynews_list.tpl';
		$this->response->setOutput($this->render());
	}

	
}
?>