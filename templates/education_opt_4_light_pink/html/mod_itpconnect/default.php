<?php
/**
 * @package      ITPrism Modules
 * @subpackage   ITPConnect
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2010 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * ITPConnect is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 */

// no direct access
defined('_JEXEC') or die;
JHtml::_('behavior.keepalive');
?>
<?php if ($type == 'logout') { ?>
<form action="<?php echo JRoute::_('index.php', true, $params->get('usesecure')); ?>" method="post" id="login-form">
<?php if ($params->get('greeting')) { ?>
    <div class="login-greeting">
    <?php if($params->get('name') == 1) {
        echo JText::sprintf('MOD_LOGIN_HINAME', $user->get('name'));
    } else  {
        echo JText::sprintf('MOD_LOGIN_HINAME', $user->get('username'));
    } ?>
    </div>
<?php } ?>
<?php if($itpConnectParams->get("facebookOn", 0)){?>
    <?php if($me AND $params->get("fbShowImage")){?>
    <img style="margin: 10px 0 10px 0;" src="http://graph.facebook.com/<?php echo $me["id"];?>/picture?type=<?php echo $params->get("fbImageSize","square")?>" alt="<?php echo $me["name"];?>" />
    <?php }?>
<?php }?>
    <div class="logout-button">
        <input type="submit" name="Submit" class="button" value="<?php echo JText::_('JLOGOUT'); ?>" id="itpconnect-logout-btn" />
        <img id="itpconnect-ajax-loader" style="display:none;margin:5px 0 5px 0;" src="<?php echo JURI::root();?>media/com_itpconnect/images/ajax-loader.gif" />
        <input type="hidden" name="option" value="com_users" />
        <input type="hidden" name="task" value="user.logout" />
        <input type="hidden" name="return" value="<?php echo $return; ?>" />
        <?php echo JHtml::_('form.token'); ?>
    </div>
</form>
<?php } else { ?>
<form action="<?php echo JRoute::_('index.php', true, $params->get('usesecure')); ?>" method="post" id="login-form" >
    <?php if ($params->get('pretext')){ ?>
        <div class="pretext">
        <p><?php echo $params->get('pretext'); ?></p>
        </div>
    <?php } ?>
    <fieldset class="userdata">
    <p id="form-login-username">
        <label for="modlgn-username"><?php echo JText::_('MOD_LOGIN_VALUE_USERNAME') ?></label>
        <input id="modlgn-username" type="text" name="username" class="inputbox"  size="18" />
    </p>
    <p id="form-login-password">
        <label for="modlgn-passwd"><?php echo JText::_('JGLOBAL_PASSWORD') ?></label>
        <input id="modlgn-passwd" type="password" name="password" class="inputbox" size="18"  />
    </p>
    <?php if (JPluginHelper::isEnabled('system', 'remember')) : ?>
    <p id="form-login-remember">
        <label for="modlgn-remember"><?php echo JText::_('MOD_LOGIN_REMEMBER_ME') ?></label>
        <input id="modlgn-remember" type="checkbox" name="remember" class="inputbox" value="yes"/>
    </p>
    <?php endif; ?>
    <input type="submit" name="Submit" class="button" value="<?php echo JText::_('JLOGIN') ?>" />
    <img id="itpconnect-ajax-loader" style="display:none; margin:5px 0 5px 0;" src="<?php echo JURI::root();?>media/com_itpconnect/images/ajax-loader.gif" />
    <input type="hidden" name="option" value="com_users" />
    <input type="hidden" name="task" value="user.login" />
    <input type="hidden" name="return" value="<?php echo $return; ?>" />
    <?php echo JHtml::_('form.token'); ?>
    </fieldset>
    <ul>
        <li>
            <a href="<?php echo JRoute::_('index.php?option=com_users&view=reset'); ?>">
            <?php echo JText::_('MOD_LOGIN_FORGOT_YOUR_PASSWORD'); ?></a>
        </li>
        <li>
            <a href="<?php echo JRoute::_('index.php?option=com_users&view=remind'); ?>">
            <?php echo JText::_('MOD_LOGIN_FORGOT_YOUR_USERNAME'); ?></a>
        </li>
        <?php
        $usersConfig = JComponentHelper::getParams('com_users');
        if ($usersConfig->get('allowUserRegistration')) : ?>
        <li>
            <a href="<?php echo JRoute::_('index.php?option=com_users&view=registration'); ?>">
                <?php echo JText::_('MOD_LOGIN_REGISTER'); ?></a>
        </li>
        <?php endif; ?>
    </ul>
    <?php if ($params->get('posttext')){ ?>
        <div class="posttext">
        <p><?php echo $params->get('posttext'); ?></p>
        </div>
    <?php } ?>
</form>
<?php } ?>
<?php if($itpConnectParams->get("facebookOn", 0)){?>
    <?php if(!$me){?>
    <fb:login-button size="medium" perms="<?php echo $params->def('fbPerms', 'email');?>"><?php echo JText::_("MOD_ITPCONNECT_FB_BUTTON");?></fb:login-button>
    <?php } ?>
<?php }?>
