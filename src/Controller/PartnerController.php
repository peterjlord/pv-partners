<?php

namespace Drupal\pvm_partners\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Url;
use Drupal\pvm_partners\Entity\PartnerInterface;

/**
 * Class PartnerController.
 *
 *  Returns responses for Partner routes.
 *
 * @package Drupal\pvm_partners\Controller
 */
class PartnerController extends ControllerBase implements ContainerInjectionInterface {

  /**
   * Displays a Partner  revision.
   *
   * @param int $partner_revision
   *   The Partner  revision ID.
   *
   * @return array
   *   An array suitable for drupal_render().
   */
  public function revisionShow($partner_revision) {
    $partner = $this->entityManager()->getStorage('partner')->loadRevision($partner_revision);
    $view_builder = $this->entityManager()->getViewBuilder('partner');

    return $view_builder->view($partner);
  }

  /**
   * Page title callback for a Partner  revision.
   *
   * @param int $partner_revision
   *   The Partner  revision ID.
   *
   * @return string
   *   The page title.
   */
  public function revisionPageTitle($partner_revision) {
    $partner = $this->entityManager()->getStorage('partner')->loadRevision($partner_revision);
    return $this->t('Revision of %title from %date', array('%title' => $partner->label(), '%date' => format_date($partner->getRevisionCreationTime())));
  }

  /**
   * Generates an overview table of older revisions of a Partner .
   *
   * @param \Drupal\pvm_partners\Entity\PartnerInterface $partner
   *   A Partner  object.
   *
   * @return array
   *   An array as expected by drupal_render().
   */
  public function revisionOverview(PartnerInterface $partner) {
    $account = $this->currentUser();
    $langcode = $partner->language()->getId();
    $langname = $partner->language()->getName();
    $languages = $partner->getTranslationLanguages();
    $has_translations = (count($languages) > 1);
    $partner_storage = $this->entityManager()->getStorage('partner');

    $build['#title'] = $has_translations ? $this->t('@langname revisions for %title', ['@langname' => $langname, '%title' => $partner->label()]) : $this->t('Revisions for %title', ['%title' => $partner->label()]);
    $header = array($this->t('Revision'), $this->t('Operations'));

    $revert_permission = (($account->hasPermission("revert all partner revisions") || $account->hasPermission('administer partner entities')));
    $delete_permission = (($account->hasPermission("delete all partner revisions") || $account->hasPermission('administer partner entities')));

    $rows = array();

    $vids = $partner_storage->revisionIds($partner);

    $latest_revision = TRUE;

    foreach (array_reverse($vids) as $vid) {
      /** @var \Drupal\pvm_partners\PartnerInterface $revision */
      $revision = $partner_storage->loadRevision($vid);
      // Only show revisions that are affected by the language that is being
      // displayed.
      if ($revision->hasTranslation($langcode) && $revision->getTranslation($langcode)->isRevisionTranslationAffected()) {
        $username = [
          '#theme' => 'username',
          '#account' => $revision->getRevisionUser(),
        ];

        // Use revision link to link to revisions that are not active.
        $date = \Drupal::service('date.formatter')->format($revision->revision_timestamp->value, 'short');
        if ($vid != $partner->getRevisionId()) {
          $link = $this->l($date, new Url('entity.partner.revision', ['partner' => $partner->id(), 'partner_revision' => $vid]));
        }
        else {
          $link = $partner->link($date);
        }

        $row = [];
        $column = [
          'data' => [
            '#type' => 'inline_template',
            '#template' => '{% trans %}{{ date }} by {{ username }}{% endtrans %}{% if message %}<p class="revision-log">{{ message }}</p>{% endif %}',
            '#context' => [
              'date' => $link,
              'username' => \Drupal::service('renderer')->renderPlain($username),
              'message' => ['#markup' => $revision->revision_log_message->value, '#allowed_tags' => Xss::getHtmlTagList()],
            ],
          ],
        ];
        $row[] = $column;

        if ($latest_revision) {
          $row[] = [
            'data' => [
              '#prefix' => '<em>',
              '#markup' => $this->t('Current revision'),
              '#suffix' => '</em>',
            ],
          ];
          foreach ($row as &$current) {
            $current['class'] = ['revision-current'];
          }
          $latest_revision = FALSE;
        }
        else {
          $links = [];
          if ($revert_permission) {
            $links['revert'] = [
              'title' => $this->t('Revert'),
              'url' => $has_translations ?
              Url::fromRoute('entity.partner.translation_revert', ['partner' => $partner->id(), 'partner_revision' => $vid, 'langcode' => $langcode]) :
              Url::fromRoute('entity.partner.revision_revert', ['partner' => $partner->id(), 'partner_revision' => $vid]),
            ];
          }

          if ($delete_permission) {
            $links['delete'] = [
              'title' => $this->t('Delete'),
              'url' => Url::fromRoute('entity.partner.revision_delete', ['partner' => $partner->id(), 'partner_revision' => $vid]),
            ];
          }

          $row[] = [
            'data' => [
              '#type' => 'operations',
              '#links' => $links,
            ],
          ];
        }

        $rows[] = $row;
      }
    }

    $build['partner_revisions_table'] = array(
      '#theme' => 'table',
      '#rows' => $rows,
      '#header' => $header,
    );

    return $build;
  }

}
