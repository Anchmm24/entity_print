<?php

namespace Drupal\entity_print\Renderer;

interface RendererInterface {

  /**
   * Generates the HTML for an array of entities.
   *
   * @param array $entities
   *   An array of entities to generate the HTML for.
   * @param bool $use_default_css
   *   TRUE if we should inject our default CSS otherwise FALSE.
   * @param bool $optimize_css
   *   TRUE if we should compress the CSS otherwise FALSE.
   * @return mixed
   */
  public function generateHtml(array $entities, $use_default_css, $optimize_css);

  /**
   * Get the filename for the entity we're printing *without* the extension.
   *
   * @param \Drupal\Core\Entity\EntityInterface[] $entities
   *   The entities for which to generate the filename from.
   * @return string
   *   The generate file name for this entity.
   */
  public function getFilename(array $entities);

}
