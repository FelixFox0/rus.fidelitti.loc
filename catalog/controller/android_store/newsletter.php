<?php
class ControllerAndroidStoreNewsletter extends Controller {
	public function index() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('android_store/newsletter', '', true);

			$this->response->redirect($this->url->link('android_store/login', '', true));
		}

		$this->load->language('account/newsletter');

		$this->document->setTitle($this->language->get('heading_title'));

		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			$this->load->model('account/customer');

			$this->model_account_customer->editNewsletter($this->request->post['newsletter']);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('android_store/account', '', true));
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('android_store/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_account'),
			'href' => $this->url->link('android_store/account', '', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_newsletter'),
			'href' => $this->url->link('android_store/newsletter', '', true)
		);

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');

		$data['entry_newsletter'] = $this->language->get('entry_newsletter');

		$data['button_continue'] = $this->language->get('button_continue');
		$data['button_back'] = $this->language->get('button_back');

		$data['action'] = $this->url->link('android_store/newsletter', '', true);

		$data['newsletter'] = $this->customer->getNewsletter();

		$data['back'] = $this->url->link('android_store/account', '', true);

		$data['column_left'] = false;
		$data['column_right'] = false;
		$data['content_top'] = $this->load->controller('android_store/content_top');
		$data['content_bottom'] = $this->load->controller('android_store/content_bottom');
		$data['footer'] = $this->load->controller('android_store/footer');
		$data['header'] = $this->load->controller('android_store/header');

		$this->response->setOutput($this->load->view('android_store/newsletter', $data));
	}
}