<?php

/**
 * Class ControllerModuleGeoip
 * @property GeoIP $geoip
 */
class ControllerModuleGeoip extends Controller {

    private $main_domain;

    public function __construct($registry) {
        parent::__construct($registry);
        $this->main_domain = rtrim(str_replace(array('http://', 'https://'), '', HTTP_SERVER), '/');
    }

    public function index() {

        $this->load->model('extension/extension');
        $extensions = $this->model_extension_extension->getExtensions('module');

        $installed = false;

        foreach ($extensions as $extension) {
            if ($extension['code'] == 'geoip') {
                $installed = true;
                break;
            }
        }

        if (!$installed) {
            return '';
        }

        $this->saveInSession();

		$this->language->load('module/geoip');

        $data['text_zone'] = $this->language->get('text_zone');
        $data['text_search_zone'] = $this->language->get('text_search_zone');
        $data['text_search_placeholder'] = $this->language->get('text_search_placeholder');
        $data['text_yes'] = $this->language->get('text_yes');
        $data['text_no'] = $this->language->get('text_no');

        $geoip_settings = $this->config->get('geoip_setting');
        $data['from_ajax'] = !empty($geoip_settings['from_ajax']);
        $data['http_server'] = $this->geoip->getCurrentHttpServer();

        $data['block_id'] = 'geoip-' . time() . mt_rand(0, 1000);

        $short_city_name = $this->geoip->getShortCityName();
        $city_name = $this->geoip->getCityName();
        $zone_name = $this->geoip->getZoneName();
        $country_name = $this->geoip->getCountryName();
        $popup_city_name = $this->geoip->getPopupCityName();

        if (empty($geoip_settings['from_ajax'])) {

            if ($popup_city_name) {
                $zone = $popup_city_name;
            }
            elseif ($short_city_name) {
                $zone = $short_city_name;
            }
            elseif ($city_name) {
                $zone = $city_name;
            }
            elseif ($zone_name) {
                $zone = $zone_name;
            }
            elseif ($country_name) {
                $zone = $country_name;
            }
            else {
                $zone = $this->language->get('text_unknown');
            }
        }
        else {
            $zone = '';
        }

        $data['zone'] = $zone;

        if (!empty($this->session->data['geoip_confirm']) || !empty($this->request->cookie['geoip_confirm'])) {
            $confirm_region = false;
        }
        else {
            $confirm_region = !empty($geoip_settings['popup_active']) && ($short_city_name || $zone_name);
            $time = !empty($geoip_settings['popup_cookie_time']) ? time() + $geoip_settings['popup_cookie_time'] : null;

            $this->session->data['geoip_confirm'] = 1;
            setcookie('geoip_confirm', 1, $time, '/', $this->geoip->getCookieDomain());
        }

        $data['confirm_region'] = $confirm_region;
        $data['confirm_redirect'] = $confirm_region ? $this->getRedirectUrl() : false;

        $your_city = htmlspecialchars($short_city_name ? $short_city_name : $zone_name, ENT_QUOTES, 'UTF-8');
        $data['text_confirm_region'] = sprintf($this->language->get('text_confirm_region'), $your_city);

        if (VERSION < '2.2') {
            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/geoip.tpl')) {
                return $this->load->view($this->config->get('config_template') . '/template/module/geoip.tpl', $data);
            } else {
                return $this->load->view('default/template/module/geoip.tpl', $data);
            }
        } else {
            return $this->load->view('module/geoip', $data);
        }
    }

    public function search() {

        $json = array();
        $search = !empty($this->request->get['term']) ? trim($this->request->get['term']) : '';

        if ($search) {
            $json = $this->geoip->search($search);
        }
        else {
            foreach ($this->model_module_geoip->getCities() as $city) {
                $json[] = array('label' => $city['name'], 'value' => $city['name'], 'fias_id' => $city['fias_id']);
            }
        }

        $this->response->setOutput(json_encode($json));
    }

    public function save() {

        $fias_id = isset($this->request->get['fias_id']) ? $this->request->get['fias_id'] : 0;

        if ($fias_id && $this->geoip->save($fias_id)) {
            $this->forceSaveInSession();
            $this->geoip->setCurrency(true);
            $success = 1;
        }
        else {
            $success = 0;
        }

        $this->response->setOutput(json_encode(array('success' => $success)));
    }

    public function getList() {

        $this->language->load('module/geoip');

        $data['text_search_placeholder'] = $this->language->get('text_search_placeholder');
        $data['text_choose_region'] = $this->language->get('text_choose_region');

        $this->load->model('module/geoip');

        $cities_rows = $this->model_module_geoip->getCities();

        $cities = array();

        foreach ($cities_rows as $row) {
            $cities[$row['fias_id']] = $row['name'];
        }

        $count_columns = 3;
        $data['columns'] = $cities ? array_chunk($cities, ceil(count($cities) / $count_columns), true) : array();

		if (VERSION < '2.2') {
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/geoip/cities_list.tpl')) {
				$response = $this->load->view($this->config->get('config_template') . '/template/module/geoip/cities_list.tpl', $data);
			} else {
				$response = $this->load->view('default/template/module/geoip/cities_list.tpl', $data);
			}
		}
		else {
			$response = $this->load->view('module/geoip/cities_list', $data);
		}

        $this->response->setOutput($response);
    }

    public function getCity() {

        $short_city_name = $this->geoip->getShortCityName();
        $city_name = $this->geoip->getCityName();
        $zone_name = $this->geoip->getZoneName();
        $country_name = $this->geoip->getCountryName();
        $popup_city_name = $this->geoip->getPopupCityName();

        if ($popup_city_name) {
            $zone = $popup_city_name;
        }
        elseif ($short_city_name) {
            $zone = $short_city_name;
        }
        elseif ($city_name) {
            $zone = $city_name;
        }
        elseif ($zone_name) {
            $zone = $zone_name;
        }
        elseif ($country_name) {
            $zone = $country_name;
        }
        else {
            $zone = $this->language->get('text_unknown');
        }

        $this->response->setOutput(json_encode(array('zone' => $zone)));
    }

    public function getRules() {

        $geoip = $this->registry->get('geoip');

        $this->response->setOutput(json_encode(array('rules' => $geoip->getRules())));
    }

    /**
     * Записывает адреса доставки и оплаты в сессию,
     * только если эти значения не были установлены ранее.
     * Не перезаписывает уже установленных значений.
     */
    private function saveInSession() {

        $zone_id = $this->geoip->getZoneId();
        $country_id = $this->geoip->getCountryId();
        $city_name = $this->geoip->getCityName();
        $postcode = $this->geoip->getPostcode();

        $data = array(
            'country_id' => $country_id,
            'zone_id' => $zone_id,
            'postcode' => $postcode,
            'city' => $city_name
        );

        foreach ($data as $key => $value) {

            if (empty($this->session->data['payment_address'][$key])) {
                $this->session->data['payment_address'][$key] = $value;
            }

            if (empty($this->session->data['shipping_address'][$key])) {
                $this->session->data['shipping_address'][$key] = $value;
            }

            if (empty($this->session->data['simple']['payment_address'][$key])) {
                $this->session->data['simple']['payment_address'][$key] = $value;
            }

            if (empty($this->session->data['simple']['shipping_address'][$key])) {
                $this->session->data['simple']['shipping_address'][$key] = $value;
            }

            if (empty($this->session->data['shipping_' . $key])) {
                $this->session->data['shipping_' . $key] = $value;
            }

            if (empty($this->session->data['payment_' . $key])) {
                $this->session->data['payment_' . $key] = $value;
            }
        }
    }

    /**
     * Записывает адреса доставки и оплаты в сессию.
     * Используется, когда пользователь меняет регион вручную.
     */
    private function forceSaveInSession() {

        $zone_id = $this->geoip->getZoneId();
        $country_id = $this->geoip->getCountryId();
        $city_name = $this->geoip->getCityName();
        $postcode = $this->geoip->getPostcode();

        $this->session->data['payment_address']['country_id'] = $this->session->data['shipping_address']['country_id'] = $country_id;
        $this->session->data['payment_address']['zone_id'] = $this->session->data['shipping_address']['zone_id'] = $zone_id;
        $this->session->data['payment_address']['city'] = $this->session->data['shipping_address']['city'] = $city_name;
        $this->session->data['payment_address']['postcode'] = $this->session->data['shipping_address']['postcode'] = $postcode;

        // Для совместимости с Simple, вообще в OC2 такие поля не используются
        $this->session->data['shipping_zone_id'] = $this->session->data['payment_zone_id'] = $zone_id;
        $this->session->data['shipping_country_id'] = $this->session->data['payment_country_id'] = $country_id;
        $this->session->data['shipping_postcode'] = $postcode;
        $this->session->data['shipping_city'] = $city_name;

        $this->session->data['simple']['payment_address']['country_id'] = $this->session->data['simple']['shipping_address']['country_id'] = $country_id;
        $this->session->data['simple']['payment_address']['zone_id'] = $this->session->data['simple']['shipping_address']['zone_id'] = $zone_id;
        $this->session->data['simple']['payment_address']['city'] = $this->session->data['simple']['shipping_address']['city'] = $city_name;
        $this->session->data['simple']['payment_address']['postcode'] = $this->session->data['simple']['shipping_address']['postcode'] = $postcode;
    }

    private function getRedirectUrl() {

        // Редирект уже был
        if (!empty($this->session->data['geoip']['redirected'])) {
            return false;
        }

        $redirect_url = false;
        $geo = $this->geoip->getFullInfo();

        foreach ($this->geoip->getRedirects() as $redirect) {

            // Для города имеет приоритет над остальными
            if ($redirect['fias_id'] == $geo['fias_id']) {
                $redirect_url = $redirect['url'];
                break;
            }

            // Для региона может быть переписан
            if ($redirect['fias_id'] == $geo['fias_zone_id']) {
                $redirect_url = $redirect['url'];
            }

            // Для страны устанавливаем, если не было определено ранее
            else {
                if (!$redirect_url && $redirect['fias_id'] == $geo['fias_country_id']) {
                    $redirect_url = $redirect['url'];
                }
            }
        }

        // Редирект по-умолчанию на основной домен
        if (!$redirect_url) {
            $redirect_url = $this->getProtocol() . $this->main_domain . '/';
        }

        if ($redirect_url) {
            $request_uri = ltrim($this->request->server['REQUEST_URI'], '/');
            $current_host = preg_replace('#^(www\.)?#', '', $this->geoip->getCurrentHttpServer());
            $current_url = $current_host . '/' . $request_uri;

            $new_request_uri = $request_uri;
            $paths = $this->getRedirectPaths();

            foreach ($paths as $path) {
                if (stripos($request_uri, $path . '/') === 0) {
                    $new_request_uri = preg_replace('#^' . preg_quote($path) . '/#iu', '', $request_uri);
                    break;
                }
            }

            if (preg_replace('#^http(s)?://(www\.)?#', '', $redirect_url) . $new_request_uri != $current_url) {
                return str_replace(array('&amp;', "\n", "\r"), array('&', '', ''), $redirect_url . $new_request_uri);
            }
        }

        return false;
    }

    protected function getProtocol() {
        if ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || stripos($_SERVER['SERVER_PROTOCOL'], 'https') === true || $_SERVER['SERVER_PORT'] == 443 ) {
            return 'https://';
        }
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' || !empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on') {
            return 'https://';
        }
        else {
            return 'http://';
        }
    }

    protected function getRedirectPaths() {

        $paths = array();
        $regex = '#' . preg_quote($this->main_domain) . '/(.+)/$#';

        foreach ($this->geoip->getRedirects() as $redirect) {
            if (preg_match($regex, $redirect['url'], $matches)) {
                $paths[] = $matches[1];
            }
        }

        return $paths;
    }

}