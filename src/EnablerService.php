<?php

/**
 * @file
 * Contains \Drupal\invitelist\EnablerService.
 */

namespace Drupal\invitelist;

use Drupal\Core\Database\Database;
use Drupal\node\Entity\Node;

/**
 * Defines a service for managing INVITE list enabled for nodes.
 */
class EnablerService
{
  /**
   * Constructor.
   */
  public function __construct()
  {
  }

  /**
   * Sets a individual node to be INVITE enabled.
   *
   * @param \Drupal\node\Entity\Node $node
   */
  public function setEnabled(Node $node)
  {
    if (!$this->isEnabled($node)) {
      $insert = Database::getConnection()->insert('invitelist_enabled');
      $insert->fields(array('nid'), array($node->id()));
      $insert->execute();
    }
  }

  /**
   * Checks if an individual node is Invite enabled.
   *
   * @param \Drupal\node\Entity\Node $node
   *
   * @return bool
   *  Whether the node is enabled for the Invite functionality.
   */
  public function isEnabled(Node $node)
  {
    if ($node->isNew()) {
      return FALSE;
    }
    $select = Database::getConnection()->select('invitelist_enabled', 're');
    $select->fields('re', array('nid'));
    $select->condition('nid', $node->id());
    $results = $select->execute();
    return !empty($results->fetchCol());
  }

  /**
   * Deletes enabled settings for an individual node.
   *
   * @param \Drupal\node\Entity\Node $node
   */
  public function delEnabled(Node $node)
  {
    $delete = Database::getConnection()->delete('invitelist_enabled');
    $delete->condition('nid', $node->id());
    $delete->execute();
  }
}
