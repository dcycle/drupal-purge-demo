<?php

namespace Drupal\my_custom_module\Controller;

use Drupal\Core\Cache\Cache;
use Drupal\Core\Cache\CacheableJsonResponse;
use Drupal\Core\Cache\CacheableMetadata;
use Drupal\Core\Session\AccountInterface;
use Symfony\Component\HttpFoundation\Request;

class MyController {

  // ...
  public function get(AccountInterface $user, Request $request) {
    $data['#cache'] = [
      'max-age' => Cache::PERMANENT,
      'contexts' => [
        'url',
      ],
      'tags' => [
        'some-custom-application-tag',
        'user:' . $user->id(),
      ],
    ];
    $response = new CacheableJsonResponse([
      'name' => $user->getAccountName(),
      'generated' => date('Y-m-d H:i:s'),
    ]);
    $response->addCacheableDependency(CacheableMetadata::createFromRenderArray($data));
    return $response;
  }

}
