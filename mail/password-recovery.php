<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\BaseMessage instance of newly created mail message */
/* @todo understand and fix phpcs so it stops messing with the formatting of the mail directory */

?>
    <span class="preheader">Use this link to reset your password. The link is only valid for 24 hours.</span>
    <table class="email-wrapper" width="100%" cellpadding="0" cellspacing="0">
      <tr>
        <td align="center">
          <table class="email-content" width="100%" cellpadding="0" cellspacing="0">
            <tr>
              <td class="email-masthead">
                <img src="<?=$message->embed($logo);?>" alt="<?=\Yii::$app->id?>" align="center" hspace="30"><br><br>
                <?=Html::a('CMS', Url::home(true) . '/cms', ['class' => 'email-masthead_name'])?>
              </td>
            </tr>
            <!-- Email Body -->
            <tr>
              <td class="email-body" width="100%" cellpadding="0" cellspacing="0">
                <table class="email-body_inner" align="center" width="570" cellpadding="0" cellspacing="0">
                  <!-- Body content -->
                  <tr>
                    <td class="content-cell">
                      <h1>Hi,</h1>
                      <p>You recently requested to reset your password for the CMS. Use the button below to reset it.
                          <strong>This password reset is only valid for the next 24 hours.</strong></p>
                      <!-- Action -->
                      <table class="body-action" align="center" width="100%" cellpadding="0" cellspacing="0">
                        <tr>
                          <td align="center">
                            <!-- Border based button
                       https://litmus.com/blog/a-guide-to-bulletproof-buttons-in-email-design -->
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                              <tr>
                                <td align="center">
                                  <table border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                      <td>
<?=Html::a(
    'Reset your password',
    Url::to(['user/change-password', 'token' => $token, 'email' => $email], true),
    ['class' => 'button button--green', 'target' => '_blank']
)?>
                                      </td>
                                    </tr>
                                  </table>
                                </td>
                              </tr>
                            </table>
                          </td>
                        </tr>
                      </table>
                      <p>If you did not request a password reset, please ignore this email or 5
                          <a href="mailto:<?=\Yii::$app->params['supportEmail']?>">contact support</a> if you have questions.</p>
                      <p>Thanks,
                        <br>Skyline Technology Solutions</p>
                      <!-- Sub copy -->
                      <table class="body-sub">
                        <tr>
                          <td>
                            <p class="sub">If you’re having trouble with the button above, copy and paste the URL below into your web browser.</p>
                            <p class="sub"><?=Url::to(['user/change-password', 'token' => $token, 'email' => $email], true)?></p>
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
            <tr>
              <td>
                <table class="email-footer" align="center" width="570" cellpadding="0" cellspacing="0">
                  <tr>
                    <td class="content-cell" align="center">
                      <p class="sub align-center">&copy; <?=date('Y')?> Skyline Technology Solutions. All rights reserved.</p>
                      <p class="sub align-center">
                        Skyline Technology Solutions
                        <br>6956-F Aviation Boulevard
                        <br>len Burnie, MD 21061
                        <br>(410) 553-2600
                      </p>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
