<?php

/**
 * @file
 * Contains partner.page.inc.
 *
 * Page callback for Partner entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Partner templates.
 *
 * Default template: partner.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_partner(array &$variables) {
  // Fetch Partner Entity Object.
  $partner = $variables['elements']['#partner'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
