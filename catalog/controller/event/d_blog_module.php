<?php
class ControllerEventDBlogModule extends Controller {
	public function view_common_header_before(&$route, &$data, &$output) {

		$bm_status = $this->config->get('d_blog_module_status');
		if($bm_status){
			$this->load->language('event/d_blog_module');
			$this->load->model('d_blog_module/category');
			$config = $this->config->get('d_blog_module_setting');
    		$bm_category_id = (isset($config['category']['main_category_id'])) ? $config['category']['main_category_id'] : 0;
			$bm_children_data = array();
			$children = $this->model_d_blog_module_category->getCategories($bm_category_id);
			foreach($children as $child){
				$bm_children_data[] = array(
					'name'  => $child['title'],
					'href'  => $this->url->link('d_blog_module/category', 'category_id=' . $child['category_id'])
				);
			}


			$data['text_blog'] = $this->language->get('text_blog');
			$data['blog'] = $this->url->link('d_blog_module/category', '', 'SSL');
			$data['categories'][] = array(
				'name'     => $this->language->get('text_blog'),
				'children' => $bm_children_data,
				'column'   => 1,
				'href'     => $this->url->link('d_blog_module/category', '', 'SSL')
			);
		}
	}
}