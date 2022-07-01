<?php

namespace Drupal\cinemas\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\cinemas\Service\CinemasService;
use Drupal\leaflet\LeafletService;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\OpenModalDialogCommand;

/**
 * Defines ModalController class.
 */
class ModalController extends ControllerBase {

  /**
   * The cinemas service object.
   *
   * @var \Drupal\cinemas\Service\CinemasService
   */
  protected $cinemasService;

  /**
   * The leaflet service object.
   *
   * @var \Drupal\leaflet\LeafletService
   */
  protected $leafletService;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('cinemas_service'),
      $container->get('leaflet.service')
    );
  }

  /**
   * Constructs a CinemasController object.
   *
   * @param \Drupal\cinemas\Service\CinemasService $cinemas_service
   *   A cinemas service instance.
   * 
   * @param \Drupal\leaflet\LeafletService $leaflet_service
   *   A leaflet service instance.
   */
  public function __construct(CinemasService $cinemas_service, LeafletService $leaflet_service) {
    $this->cinemasService = $cinemas_service;
    $this->leafletService = $leaflet_service;
  }

  public function modal($id) {
    $cinema = (array) $this->cinemasService->fetchCinema($id);
    $cinema['map'] = $this->createMap($cinema['location']);
    
    $options = [
      'dialogClass' => 'popup-dialog-class',
      'width' => '1360',
    ];
    $content = [
      '#theme' => 'cinema_modal',
      '#cinema' => $cinema,
    ];
    
    $response = new AjaxResponse();
    $response->addCommand(new OpenModalDialogCommand($cinema['title'], $content, $options));

    return $response;
  }

  public function createMap($location) {   
    $features = [
      [
        'type' => 'point',
        'lat' => $location->latitude,
        'lon' => $location->longitude,
      ],
    ];

    $settings['leaflet_map'] = 'OSM Mapnik';
    $map = $this->leafletService->leafletMapGetInfo($settings['leaflet_map']);
    $map['settings']['zoom'] = 16;
    
    $result = $this->leafletService->leafletRenderMap($map, $features, $height = '480px');

    return $result;
  }
}
