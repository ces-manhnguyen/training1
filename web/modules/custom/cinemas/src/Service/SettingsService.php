<?php

namespace Drupal\cinemas\Service;

use GuzzleHttp\ClientInterface;
use Drupal\Key\KeyRepositoryInterface;
use Drupal\Core\State\StateInterface;
use GuzzleHttp\Exception\RequestException;

class SettingsService {

  /**
   * The HTTP client to fetch the feed data with.
   *
   * @var \GuzzleHttp\ClientInterface
   */
  protected $httpClient;

  /**
   *
   * @var \Drupal\Key\KeyRepositoryInterface
   */
  protected $keyRepository;

  /**
   *
   * @var \Drupal\Core\State\StateInterface
   */
  protected $state;

  /**
   * Constructor for SettingsService.
   *
   * @param \GuzzleHttp\ClientInterface $http_client
   *   A Guzzle client object.
   * 
   * @param \Drupal\Key\KeyRepositoryInterface $key_repository
   *   A Key repository object.
   * 
   */
  public function __construct(ClientInterface $http_client, KeyRepositoryInterface $key_repository, StateInterface $state) {
    $this->httpClient = $http_client;
    $this->keyRepository = $key_repository;
    $this->state = $state;
  }

  public function authenticate($url, $api_key, $api_secret, $key_select) {
    $auth = 'Basic ' . base64_encode($api_key . ':' . $api_secret);
    try {
      $request = $this->httpClient->request('POST', $url . '/authenticate', [
        'headers' => [
          'Authorization' => $auth,
        ],
      ]);
      $response = json_decode($request->getBody());
      $key = $this->keyRepository->getKey($key_select);
      $key->setKeyValue($response->access_token);
      $key->save();
      $this->state->set('token_expire_time', time() + $response->expires_in);
    } catch (RequestException $e) {
      return $e->getMessage();
    }
  }

  public function getKey($key) {
    $key = $this->keyRepository->getKey($key)->getKeyValue();
    return $key;
  }

  public function getKeys() {
    $keys = $this->keyRepository->getKeys();
    $key_list = array_map(function ($key) {
      return $key->label();
    }, $keys);
    return $key_list;
  }
}
