<?php
/**
 * @file
 * Contains \Drupal\invitelist\Form\INVITESettingsForm
 */

namespace Drupal\invitelist\Form;

use Drupal\Core\Form\ConfigFormBase;
use Symfony\Component\HttpFoundation\Request;
use Drupal\Core\Form\FormStateInterface;

/**
 * Defines a form to configure INVITE List module settings
 */
class INVITESettingsForm extends ConfigFormBase
{
  /**
   * {@inheritdoc}
   */
  public function getFormID()
  {
    return 'invitelist_admin_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames()
  {
    return ['invitelist.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, Request $request = NULL)
  {
    $types = node_type_get_names();
    $config = $this->config('invitelist.settings');
    $form['invitelist_types'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('The content types to enable INVITE collection for'),
      '#default_value' => $config->get('allowed_types'),
      '#options' => $types,
      '#description' => $this->t('On the specified node types, an INVITE option will be available and can be enabled while tht node is being edited.'),
    ];
    $form['array_filter'] = ['#type' => 'value', '#value' => TRUE];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    $allowed_types = array_filter($form_state->getValue('invitelist_types'));
    sort($allowed_types);
    $this->config('invitelist.settings')
      ->set('allowed_types', $allowed_types)
      ->save();
    parent::submitForm($form, $form_state);
  }

}



