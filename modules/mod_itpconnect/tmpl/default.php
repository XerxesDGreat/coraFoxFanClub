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

defined('_JEXEC') or die('Restricted access'); ?>

<?php if($type == 'logout') { ?>
<form action="index.php" method="post" name="login" id="form-login">
<?php if ($params->get('greeting')) { ?>
    <div>
    <?php if ($params->get('name'))  {
        echo JText::sprintf( 'HINAME', $user->get('name') );
    } else {
        echo JText::sprintf( 'HINAME', $user->get('username') );
    }?>
    </div>
<?php } ?>
<?php if($itpConnectParams->get("facebookOn", 0)){?>
    <?php if($me AND $params->get("fbShowImage")){?>
    <img style="margin: 10px 0 10px 0;" src="http://graph.facebook.com/<?php echo $me["id"];?>/picture?type=<?php echo $params->get("fbImageSize","square")?>" alt="<?php echo $me["name"];?>" />
    <?php }?>
<?php }?>
    <div align="center">
        <input type="submit" name="Submit" class="button" value="<?php echo JText::_( 'BUTTON_LOGOUT'); ?>" />
        <img id="itpconnect-ajax-loader" style="display:none;margin:5px 0 5px 0;" src="<?php echo JURI::root();?>administrator/components/com_itpconnect/assets/images/ajax-loader.gif" />
    </div>

    <input type="hidden" name="option" value="com_user" />
    <input type="hidden" name="task" value="logout" />
    <input type="hidden" name="return" value="<?php echo $return; ?>" />
</form>
<?php } else { ?>
<form action="<?php echo JRoute::_( 'index.php', true, $params->get('usesecure')); ?>" method="post" name="login" id="form-login" >
    <?php echo $params->get('pretext'); ?>
    <fieldset class="input">
    <p id="form-login-username">
        <label for="modlgn_username"><?php echo JText::_('Username') ?></label><br />
        <input id="modlgn_username" type="text" name="username" class="inputbox" alt="username" size="18" tabindex="1" accesskey="u" />
    </p>
    <p id="form-login-password">
        <label for="modlgn_passwd"><?php echo JText::_('Password') ?></label><br />
        <input id="modlgn_passwd" type="password" name="passwd" class="inputbox" size="18" alt="password" tabindex="2" accesskey="p" />
    </p>
    <?php if(JPluginHelper::isEnabled('system', 'remember')) : ?>
    <p id="form-login-remember">
        <label for="modlgn_remember"><?php echo JText::_('Remember me') ?></label>
        <input id="modlgn_remember" type="checkbox" name="remember" class="inputbox" value="yes" alt="Remember Me" tabindex="3" accesskey="r" />
    </p>
    <?php endif; ?>
    <input type="submit" name="Submit" class="button" value="<?php echo JText::_('LOGIN') ?>" tabindex="4" accesskey="l" />
    <img id="itpconnect-ajax-loader" style="display:none;margin:5px 0 5px 0;" src="<?php echo JURI::root();?>administrator/components/com_itpconnect/assets/images/ajax-loader.gif" />
    </fieldset>
    <ul>
        <li>
            <a href="<?php echo JRoute::_( 'index.php?option=com_user&view=reset' ); ?>">
            <?php echo JText::_('FORGOT_YOUR_PASSWORD'); ?></a>
        </li>
        <li>
            <a href="<?php echo JRoute::_( 'index.php?option=com_user&view=remind' ); ?>">
            <?php echo JText::_('FORGOT_YOUR_USERNAME'); ?></a>
        </li>
        <?php
        $usersConfig = &JComponentHelper::getParams( 'com_users' );
        if ($usersConfig->get('allowUserRegistration')) : ?>
        <li>
            <a href="<?php echo JRoute::_( 'index.php?option=com_user&view=register' ); ?>">
                <?php echo JText::_('REGISTER'); ?></a>
        </li>
        <?php endif; ?>
    </ul>
    <?php echo $params->get('posttext'); ?>

    <input type="hidden" name="option" value="com_user" />
    <input type="hidden" name="task" value="login" />
    <input type="hidden" name="return" value="<?php echo $return; ?>" />
    <?php echo JHTML::_( 'form.token' ); ?>
</form>
<?php } ?>
<?php if($itpConnectParams->get("facebookOn", 0)){?>
    <?php if(!$me){?>
    <fb:login-button size="large" perms="<?php echo $params->def('fbPerms', 'email');?>"><?php echo JText::_("MOD_ITPCONNECT_FB_BUTTON");?></fb:login-button>
    <?php } ?>
<?php }?>