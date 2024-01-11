<?php
/**
 * @file
 * Contains \Drupal\invitelist\Plugin\Block\INVITEBlock
 */

namespace Drupal\invitelist\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;


/**
 * Provides a 'INVITE' List Block
 *
 * @Block(
 *   id = "INVITE_block",
 *   admin_label = @Translation("INVITE Block"),
 *   category = @Translation("Blocks")
 * )
 */
class INVITEBlock extends BlockBase
{

  /**
   * {@inheritdoc}
   */
  public function build()
  {
    return \Drupal::formBuilder()->getForm('Drupal\invitelist\Form\INVITEForm');
  }

  /**
   * {@inheritdoc}
   */
  public function blockAccess(AccountInterface $account)
  {
    /** @var \Drupal\node\Entity\Node $node */
    $node = \Drupal::routeMatch()->getParameter('node');
    $nid = $node->nid->value;
    /** @var \Drupal\invitelist\EnablerService $enabler */
    $enabler = \Drupal::service('invitelist.enabler');
    if (is_numeric($nid)) {
      if ($enabler->isEnabled($node)) {
        return AccessResult::allowedIfHasPermission($account, 'view invitelist');
      }
    }
    return AccessResult::forbidden();
  }

}

