<?php
class ControllerExtensionModuleBestSeller extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/module/bestseller');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/module');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			if (!isset($this->request->get['module_id'])) {
				$this->model_setting_module->addModule('bestseller', $this->request->post);
			} else {
				$this->model_setting_module->editModule($this->request->get['module_id'], $this->request->post);
			}

			$this->cache->delete('product');

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module&language=' . $this->config->get('config_language')));
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = '';
		}

		if (isset($this->error['width'])) {
			$data['error_width'] = $this->error['width'];
		} else {
			$data['error_width'] = '';
		}

		if (isset($this->error['height'])) {
			$data['error_height'] = $this->error['height'];
		} else {
			$data['error_height'] = '';
		}
		
		if (isset($this->error['order_period_value'])) {
			$data['error_order_period_value'] = $this->error['order_period_value'];
		} else {
			$data['error_order_period_value'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'] . '&language=' . $this->config->get('config_language'))
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module&language=' . $this->config->get('config_language'))
		);

		if (!isset($this->request->get['module_id'])) {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/module/bestseller', 'user_token=' . $this->session->data['user_token'] . '&language=' . $this->config->get('config_language'))
			);
		} else {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/module/bestseller', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'] . '&language=' . $this->config->get('config_language'))
			);
		}

		if (!isset($this->request->get['module_id'])) {
			$data['action'] = $this->url->link('extension/module/bestseller', 'user_token=' . $this->session->data['user_token'] . '&language=' . $this->config->get('config_language'));
		} else {
			$data['action'] = $this->url->link('extension/module/bestseller', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'] . '&language=' . $this->config->get('config_language'));
		}

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module'. '&language=' . $this->config->get('config_language'));

		if (isset($this->request->get['module_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$module_info = $this->model_setting_module->getModule($this->request->get['module_id']);
		}

		if (isset($this->request->post['name'])) {
			$data['name'] = $this->request->post['name'];
		} elseif (!empty($module_info)) {
			$data['name'] = $module_info['name'];
		} else {
			$data['name'] = '';
		}

		if (isset($this->request->post['limit'])) {
			$data['limit'] = $this->request->post['limit'];
		} elseif (!empty($module_info)) {
			$data['limit'] = $module_info['limit'];
		} else {
			$data['limit'] = 5;
		}

		if (isset($this->request->post['width'])) {
			$data['width'] = $this->request->post['width'];
		} elseif (!empty($module_info)) {
			$data['width'] = $module_info['width'];
		} else {
			$data['width'] = 200;
		}

		if (isset($this->request->post['height'])) {
			$data['height'] = $this->request->post['height'];
		} elseif (!empty($module_info)) {
			$data['height'] = $module_info['height'];
		} else {
			$data['height'] = 200;
		}
		
		if (isset($this->request->post['database_transaction'])) {
			$data['database_transaction'] = $this->request->post['database_transaction'];
		} elseif (!empty($module_info)) {
			$data['database_transaction'] = $module_info['database_transaction'];
		} else {
			$data['database_transaction'] = 'delete';
		}
		
		if (isset($this->request->post['type'])) {
			$data['type'] = $this->request->post['type'];
		} elseif (!empty($module_info)) {
			$data['type'] = $module_info['type'];
		} else {
			$data['type'] = '';
		}
		
		if (isset($this->request->post['type_order'])) {
			$data['type_order'] = $this->request->post['type_order'];
		} elseif (!empty($module_info)) {
			$data['type_order'] = $module_info['type_order'];
		} else {
			$data['type_order'] = '';
		}
		
		if (isset($this->request->post['group'])) {
			$data['group'] = $this->request->post['group'];
		} elseif (!empty($module_info)) {
			$data['group'] = $module_info['group'];
		} else {
			$data['group'] = '';
		}
		
		if (isset($this->request->post['rating'])) {
			$data['rating'] = $this->request->post['rating'];
		} elseif (!empty($module_info)) {
			$data['rating'] = $module_info['rating'];
		} else {
			$data['rating'] = '';
		}
		
		if (isset($this->request->post['order_period_notify'])) {
			$data['order_period_notify'] = $this->request->post['order_period_notify'];
		} elseif (!empty($module_info)) {
			$data['order_period_notify'] = $module_info['order_period_notify'];
		} else {
			$data['order_period_notify'] = '';
		}
		
		if (isset($this->request->post['order_period_value'])) {
			$data['order_period_value'] = $this->request->post['order_period_value'];
		} elseif (!empty($module_info)) {
			$data['order_period_value'] = $module_info['order_period_value'];
		} else {
			$data['order_period_value'] = '';
		}

		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($module_info)) {
			$data['status'] = $module_info['status'];
		} else {
			$data['status'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/bestseller', $data));
	}
	
	public function install() {
		// If OC has been upgraded, verify that the module has the new event registered.
		$this->load->model('setting/event');

		$bestseller_event = $this->model_setting_event->getEventByCode("extension_module_bestseller_checkout");

		if (empty($bestseller_event)) {
			// Event is missing, add it
			$this->model_setting_event->addEvent('extension_module_bestseller_checkout', 'catalog/model/checkout/order/addOrder/before', 'extension/module/bestseller/getBestSellerByOrders');
		}
	}
	
	public function uninstall() {
		$this->load->model('setting/event');
		
		$this->model_setting_event->deleteEventByCode('extension_module_bestseller_checkout');
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/bestseller')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 64)) {
			$this->error['name'] = $this->language->get('error_name');
		}

		if (!$this->request->post['width']) {
			$this->error['width'] = $this->language->get('error_width');
		}

		if (!$this->request->post['height']) {
			$this->error['height'] = $this->language->get('error_height');
		}
		
		if (!empty($this->request->post['group'])) {
			if ($this->request->post['group'] == 'day' && ((int)$this->request->post['order_period_value'] < 1 || (int)$this->request->post['order_period_value'] > 365)) {
				$this->error['order_period_value'] = $this->language->get('error_order_period_value_day');
			} elseif ($this->request->post['group'] == 'week' && ((int)$this->request->post['order_period_value'] < 1 || (int)$this->request->post['order_period_value'] > 52)) {
				$this->error['order_period_value'] = $this->language->get('error_order_period_value_week');
			} elseif ($this->request->post['group'] == 'month' && ((int)$this->request->post['order_period_value'] < 1 || (int)$this->request->post['order_period_value'] > 12)) {
				$this->error['order_period_value'] = $this->language->get('error_order_period_value_month');
			} elseif ($this->request->post['group'] == 'month' && ((int)$this->request->post['order_period_value'] < 1 || (int)$this->request->post['order_period_value'] > 1)) {
				$this->error['order_period_value'] = $this->language->get('error_order_period_value_year');
			}
		}

		return !$this->error;
	}
}
