<?php

/**
 * @file
 * Contains \Drupal\invitelist\Form\INVITEForm
 */

namespace Drupal\invitelist\Form;

use Drupal\Core\Database\Database;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Messenger\MessengerInterface;

/**
 * Provides a INVITE Email form.
 */
class INVITEForm extends FormBase
{

  /**
   * {@inheritdoc}
   */
  public function getFormId()
  {
    return 'invitelist_email_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state)
  {
    $node = \Drupal::routeMatch()->getParameter('node');
    $nid = $node->nid->value;
    $form['email'] = array(
      '#title' => t('Email address'),
      '#type' => 'textfield',
      '#size' => 25,
      '#description' => t("We'll send updates to the email address you provide."),
      '#required' => TRUE,
    );
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => t('ACCEPT INVITE'),
    ];
    $form['nid'] = [
      '#type' => 'hidden',
      '#value' => $nid,
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state)
  {
    $value = $form_state->getValue('email');
    if ($value == !\Drupal::service('email.validator')->isValid($value)) {
      $form_state->setErrorByName('email', $this->t('The email address %mail is not valid.', ['%mail' => $value]));
      return;
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    $user = \Drupal\user\Entity\User::load(\Drupal::currentUser()->id());
    $query = \Drupal::database()->insert('invitelist');
    $query->fields([
      'mail',
      'nid',
      'uid',
      'created',
    ]);
    $query->values(
      [
        $form_state->getValue('email'),
        $form_state->getValue('nid'),
        $user->id(),
        time(),
      ]
    );
    $query->execute();
    $this->messenger()->addMessage(t(('Thank you for your INVITE, you are on the list for the event.')));
  }
}
