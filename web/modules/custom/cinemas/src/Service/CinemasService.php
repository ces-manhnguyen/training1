<?php

namespace Drupal\cinemas\Service;

use GuzzleHttp\ClientInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\State\StateInterface;

class CinemasService {

  /**
   * The HTTP client to fetch the feed data with.
   *
   * @var \GuzzleHttp\ClientInterface
   */
  protected $httpClient;

  /**
   *
   * @var \Drupal\cinemas\Service\SettingsService
   */
  protected $settingsService;

  /**
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $config;

  /**
   *
   * @var \Drupal\Core\State\StateInterface
   */
  protected $state;

  /**
   * Constructor for CinemasService.
   *
   * @param \GuzzleHttp\ClientInterface $http_client
   *   A Guzzle client object.
   * 
   * @param \Drupal\cinemas\Service\SettingsService $settings_service
   *   A Settings service object.
   * 
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config
   *   A config object.
   * 
   */
  public function __construct(ClientInterface $http_client, SettingsService $settings_service, ConfigFactoryInterface $config, StateInterface $state) {
    $this->httpClient = $http_client;
    $this->settingsService = $settings_service;
    $this->config = $config;
    $this->state = $state;
  }

  public function fetchCinemas($movie_id) {
    $url = $this->config->get('cinemas.settings')->get('cinemas.url');
    $auth = 'Bearer ' . $this->getToken();
    $request = $this->httpClient->request('GET', $url . '/theatres/' . $movie_id, [
      'headers' => [
        'Authorization' => $auth,
      ],
    ]);
    $response = json_decode($request->getBody());
    return $response;
  }

  public function fetchCinema($cinema_id) {
    $url = $this->config->get('cinemas.settings')->get('cinemas.url');
    $auth = 'Bearer ' . $this->getToken();
    $request = $this->httpClient->request('GET', $url . '/theatres/view/' . $cinema_id, [
      'headers' => [
        'Authorization' => $auth,
      ],
    ]);
    $response = json_decode($request->getBody());
    return $response;
  }

  public function getToken() {
    $url = $this->config->get('cinemas.settings')->get('cinemas.url');
    $api_key = $this->config->get('cinemas.settings')->get('cinemas.api_key');
    $api_secret = $this->config->get('cinemas.settings')->get('cinemas.api_secret');
    $key_select = $this->config->get('cinemas.settings')->get('cinemas.key_select');

    $expire_time = $this->state->get('token_expire_time');
    if (time() - $expire_time >= 0) {
      $this->settingsService->authenticate($url, $api_key, $api_secret, $key_select);
    }
    return $this->settingsService->getKey($key_select);
  }
}
