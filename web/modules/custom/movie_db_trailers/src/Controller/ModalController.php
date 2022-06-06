<?php

/**
 * @file
 * ModalController class.
 */

namespace Drupal\movie_db_trailers\Controller;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\OpenModalDialogCommand;
use Drupal\Core\Controller\ControllerBase;
use Drupal\media\MediaInterface;

class ModalController extends ControllerBase {

  public function modal(MediaInterface $media) {
    $options = [
      'dialogClass' => 'popup-dialog-class',
      'width' => '800',
    ];
    $content = [
      '#type' => 'view',
      '#name' => 'trailer',
      '#display_id' => 'block_1',
      '#arguments' => [
        $media->mid->value,
      ],
    ];
    $response = new AjaxResponse();
    $response->addCommand(new OpenModalDialogCommand($media->name->value, $content, $options));

    return $response;
  }
}
