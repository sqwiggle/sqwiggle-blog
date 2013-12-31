<div id="abh_settings" >
    <a name="abh_settings"></a>
    <div id="abh_settings_title" ><?php _e('Starbox Settings for this Author', _ABH_PLUGIN_NAME_); ?><a href="http://wordpress.org/support/view/plugin-reviews/starbox" target="_blank"><span class="abh_settings_rate" ><span></span><?php _e('Please support us on Wordpress', _ABH_PLUGIN_NAME_); ?></span></a></div>
    <div id="abh_settings_body">
        <div id="abh_settings_left" >
            <fieldset >
                <div class="abh_option_content">
                    <div class="abh_switch">
                        <input id="abh_use_on" type="radio" class="abh_switch-input" name="abh_use"  value="1" <?php echo (($view->author['abh_use'] == 1) ? "checked" : '') ?> />
                        <label for="abh_use_on" class="abh_switch-label abh_switch-label-off"><?php _e('Yes', _ABH_PLUGIN_NAME_); ?></label>
                        <input id="abh_use_off" type="radio" class="abh_switch-input" name="abh_use" value="0" <?php echo ((!$view->author['abh_use'] == 1) ? "checked" : '') ?> />
                        <label for="abh_use_off" class="abh_switch-label abh_switch-label-on"><?php _e('No', _ABH_PLUGIN_NAME_); ?></label>
                        <span class="abh_switch-selection"></span>
                    </div>
                    <span><?php _e('Show the StarBox for this author', _ABH_PLUGIN_NAME_); ?></span>
                </div>

            </fieldset>
            <fieldset>
                <legend><?php _e('Change the Profile Image', _ABH_PLUGIN_NAME_); ?></legend>
                <div class="abh_gravatar">
                    <p>
                        <?php _e('File types: JPG, JPEG, GIF and PNG. Ideal image size is: 80x80', _ABH_PLUGIN_NAME_); ?>
                    </p>
                    <p><span class="sq_settings_info"><?php echo ((defined('ABH_MESSAGE_FAVICON')) ? ABH_MESSAGE_FAVICON : '') ?></span></p>
                    <div>
                        <?php if (isset($view->author['abh_gravatar']) && $view->author['abh_gravatar'] <> '' && file_exists(_ABH_GRAVATAR_DIR_ . $view->author['abh_gravatar'])) { ?>
                            <img src="<?php echo _ABH_GRAVATAR_URL_ . $view->author['abh_gravatar'] . '?' . time() ?>" width="80" class="photo" />
                            <?php
                        } else {

                            echo get_avatar($view->user->ID, 80);
                        }
                        ?>
                        <div class="abh_upload">
                            <input type="file" name="abh_gravatar" />
                            <input type="submit"  id="abh_gravatar_update" name="abh_update" value="<?php _e('Upload', _ABH_PLUGIN_NAME_) ?>" />
                            <div class="abh_upload_reset"><label for="abh_resetgravatar"><?php _e('Reset the uploaded image', _ABH_PLUGIN_NAME_); ?></label><input name="abh_resetgravatar" type="checkbox" value="1" /></div>
                            <span class="abh_settings_info"><?php echo sprintf(__('You can also set your image on %shttps://en.gravatar.com/%s for your email address', _ABH_PLUGIN_NAME_), '<a href="https://en.gravatar.com/" target="_blank">', '</a>'); ?></span>
                        </div>
                    </div>
                </div>

            </fieldset>
            <fieldset>
                <legend><?php _e('Theme settings:', _ABH_PLUGIN_NAME_); ?></legend>
                <div class="abh_option_content">
                    <div class="abh_select">
                        <select name="abh_position">
                            <?php
                            if (isset($view->author['abh_position']))
                                $position = $view->author['abh_position'];
                            else
                                $position = 'default';
                            ?>
                            <option value="default" <?php echo (($position == 'default') ? 'selected="selected"' : '') ?>><?php _e('Default', _ABH_PLUGIN_NAME_); ?></option>
                            <option value="up" <?php echo (($position == 'up') ? 'selected="selected"' : '') ?>><?php _e('Up', _ABH_PLUGIN_NAME_); ?></option>
                            <option value="down" <?php echo (($position == 'down') ? 'selected="selected"' : '') ?>><?php _e('Down', _ABH_PLUGIN_NAME_); ?></option>
                        </select>
                    </div>
                    <span><?php _e('The Author Box position', _ABH_PLUGIN_NAME_); ?></span>
                </div>

                <div class="abh_option_content">

                    <div class="abh_select">
                        <select id="abh_theme_select" name="abh_theme">
                            <?php
                            if (isset($view->author['abh_theme']))
                                $theme = $view->author['abh_theme'];
                            else
                                $theme = 'default';

                            foreach ($view->themes as $name) {
                                echo '<option value="' . $name . '" ' . (($theme == $name) ? 'selected="selected"' : '') . ' >' . ucfirst($name) . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <span><?php _e('This Author\'s theme', _ABH_PLUGIN_NAME_); ?></span>

                </div>
                <div id="abh_box_preview_title"><?php _e('Preview mode (change the theme)', _ABH_PLUGIN_NAME_); ?></div>
                <div id="abh_box_preview"><?php
                    if ($theme == 'default')
                        $theme = ABH_Classes_Tools::getOption('abh_theme');
                    echo '<script type="text/javascript" src="' . _ABH_ALL_THEMES_URL_ . $theme . '/js/frontend.js?ver=' . ABH_VERSION . '"></script>';
                    echo '<link rel="stylesheet"  href="' . _ABH_ALL_THEMES_URL_ . $theme . '/css/frontend.css?ver=' . ABH_VERSION . '" type="text/css" media="all" />';

                    echo ABH_Classes_ObjController::getController('ABH_Controllers_Frontend')->showBox($view->user->ID);
                    ?></div>
            </fieldset>
            <fieldset>
                <legend><?php _e('Job settings:', _ABH_PLUGIN_NAME_); ?></legend>
                <div>
                    <p><span><?php _e('Job Title:', _ABH_PLUGIN_NAME_); ?></span> <input type="text" name="abh_title" value="<?php echo $view->author['abh_title']; ?>" size="30" /></p>
                    <p><span><?php _e('Company:', _ABH_PLUGIN_NAME_); ?></span> <input type="text" name="abh_company" value="<?php echo $view->author['abh_company']; ?>" size="30" /></p>
                    <p><span><?php _e('Company URL:', _ABH_PLUGIN_NAME_); ?></span> <input type="text" name="abh_company_url" value="<?php echo $view->author['abh_company_url']; ?>" size="30" /></p>
                    <p class="abh_description_author"></p>
                    <p class="abh_show_extra_description" <?php echo (($view->author['abh_extra_description'] == '') ? '' : 'style="display: none"'); ?>><?php _e('add custom author bio >>', _ABH_PLUGIN_NAME_); ?></p>
                    <p class="abh_extra_description" <?php echo (($view->author['abh_extra_description'] <> '') ? '' : 'style="display: none"'); ?>>
                        <span> </span><span style="font-size:12px; font-weight: normal; margin-left: 15px; font-style: italic;"><?php _e('By adding text here, you will replace the above description with this one', _ABH_PLUGIN_NAME_); ?></span>
                        <br style="clear:both;" />
                        <span><?php _e('Author BIO:', _ABH_PLUGIN_NAME_); ?></span> <textarea id="abh_extra_description" name="abh_extra_description"  ><?php echo $view->author['abh_extra_description']; ?></textarea>
                        <br style="clear:both;" />
                        <span> </span><a href="javascript:void(0);" onclick="jQuery('#abh_extra_description').val('')" style="font-size:12px; font-weight: normal; margin-left: 15px;"><?php _e('Clear the custom description and show the default description', _ABH_PLUGIN_NAME_); ?></a>
                    </p>
                </div>
            </fieldset>
            <fieldset >
                <legend><?php _e('Social settings:', _ABH_PLUGIN_NAME_); ?></legend>

                <div id="abh_option_subscribe" <?php if (ABH_Classes_Tools::getOption('abh_subscribe') == 1) echo 'style="display:none"'; ?>>
                    <div id="abh_subscribe"><?php _e('To unlock social fields please enter your email:', _ABH_PLUGIN_NAME_); ?></div>
                    <div id="abh_subscribe_social">
                        <span class="abh_social_settings abh_twitter"></span>
                        <span class="abh_social_settings abh_facebook"></span>
                        <span class="abh_social_settings abh_google"></span>
                        <span class="abh_social_settings abh_linkedin"></span>
                        <span class="abh_social_settings abh_klout"></span>
                        <span class="abh_social_settings abh_instagram"></span>
                        <span class="abh_social_settings abh_flickr"></span>
                        <span class="abh_social_settings abh_pinterest"></span>
                        <span class="abh_social_settings abh_tumblr"></span>
                        <span class="abh_social_settings abh_youtube"></span>
                        <span class="abh_social_settings abh_vimeo"></span>
                    </div>
                    <input type="email" value="<?php echo esc_attr($GLOBALS['profileuser']->user_email) ?>" size="30" name="abh_email" id="abh_subscribe_email" >
                    <input type="text" style="display: none;" value="<?php echo get_bloginfo('url') ?>" size="30" id="abh_subscribe_url" >

                    <input type="button" value="Subscribe" id="abh_subscribe_subscribe">
                    <div style="margin:3px; font-style: italic;" ><?php _e('You will only subscribe to StarBox News (No spam). <br />We do not connect your site to our server in any way. The plugin is stand-alone.', _ABH_PLUGIN_NAME_); ?></div>

                </div>
                <div id="abh_option_social" <?php if (ABH_Classes_Tools::getOption('abh_subscribe') == 0) echo 'style="display:none"'; ?>>
                    <p class="abh_social_text" style="height:30px; line-height: 30px;">
                        <span><?php _e('Social text (12 chars):', _ABH_PLUGIN_NAME_); ?></span>
                        <span ><input name="abh_socialtext" value="<?php echo $view->author['abh_socialtext']; ?>" size="30" maxlength="12" style="min-width: 100px; width: 100px;" /></span>
                        <span style="font-size:12px; font-weight: normal; font-style: italic; margin-left: 5px;"><?php _e('eq. "Follow me"', _ABH_PLUGIN_NAME_); ?></span>
                    </p>
                    <p><span class="abh_social_settings abh_twitter"></span><span><?php _e('Twitter:', _ABH_PLUGIN_NAME_); ?></span> <input type="text" name="abh_twitter" value="<?php echo $view->author['abh_twitter']; ?>" size="30" /></p>
                    <p><span class="abh_social_settings abh_facebook"></span><span><?php _e('Facebook:', _ABH_PLUGIN_NAME_); ?></span> <input type="text" name="abh_facebook" value="<?php echo $view->author['abh_facebook']; ?>" size="30" /></p>
                    <p><span class="abh_social_settings abh_google"></span><span><?php _e('Google +:', _ABH_PLUGIN_NAME_); ?></span> <input type="text" name="abh_google" value="<?php echo $view->author['abh_google']; ?>" size="30" /></p>
                    <p><span class="abh_social_settings abh_linkedin"></span><span><?php _e('LinkedIn:', _ABH_PLUGIN_NAME_); ?></span> <input type="text" name="abh_linkedin" value="<?php echo $view->author['abh_linkedin']; ?>" size="30" /></p>
                    <p><span class="abh_social_settings abh_klout"></span><span><?php _e('Klout:', _ABH_PLUGIN_NAME_); ?></span> <input type="text" name="abh_klout" value="<?php echo $view->author['abh_klout']; ?>" size="30" /></p>
                    <p><span class="abh_social_settings abh_instagram"></span><span><?php _e('Instagram:', _ABH_PLUGIN_NAME_); ?></span> <input type="text" name="abh_instagram" value="<?php echo $view->author['abh_instagram']; ?>" size="30" /></p>
                    <p><span class="abh_social_settings abh_flickr"></span><span><?php _e('Flickr:', _ABH_PLUGIN_NAME_); ?></span> <input type="text" name="abh_flickr" value="<?php echo $view->author['abh_flickr']; ?>" size="30" /></p>
                    <p><span class="abh_social_settings abh_pinterest"></span><span><?php _e('Pinterest:', _ABH_PLUGIN_NAME_); ?></span> <input type="text" name="abh_pinterest" value="<?php echo $view->author['abh_pinterest']; ?>" size="30" /></p>
                    <p><span class="abh_social_settings abh_tumblr"></span><span><?php _e('Tumblr:', _ABH_PLUGIN_NAME_); ?></span> <input type="text" name="abh_tumblr" value="<?php echo $view->author['abh_tumblr']; ?>" size="30" /></p>
                    <p><span class="abh_social_settings abh_youtube"></span><span><?php _e('YouTube:', _ABH_PLUGIN_NAME_); ?></span> <input type="text" name="abh_youtube" value="<?php echo $view->author['abh_youtube']; ?>" size="30" /></p>
                    <p><span class="abh_social_settings abh_vimeo"></span><span><?php _e('Vimeo:', _ABH_PLUGIN_NAME_); ?></span> <input type="text" name="abh_vimeo" value="<?php echo $view->author['abh_vimeo']; ?>" size="30" /></p>


                    <div class="abh_option_content">
                        <div class="abh_switch">
                            <input id="abh_nofollow_social_on" type="radio" class="abh_switch-input" name="abh_nofollow_social"  value="1" <?php echo ((!$view->author['abh_nofollow_social'] == 0) ? "checked" : '') ?> />
                            <label for="abh_nofollow_social_on" class="abh_switch-label abh_switch-label-off"><?php _e('Yes', _ABH_PLUGIN_NAME_); ?></label>
                            <input id="abh_nofollow_social_off" type="radio" class="abh_switch-input" name="abh_nofollow_social" value="0" <?php echo (($view->author['abh_nofollow_social'] == 0) ? "checked" : '') ?> />
                            <label for="abh_nofollow_social_off" class="abh_switch-label abh_switch-label-on"><?php _e('No', _ABH_PLUGIN_NAME_); ?></label>
                            <span class="abh_switch-selection"></span>
                        </div>
                        <span><?php _e('Add rel="nofollow" to Social links', _ABH_PLUGIN_NAME_); ?></span>
                    </div>
                </div>
            </fieldset>

            <div id="abh_settings_title" >&nbsp;</div>
        </div>


    </div>
</div>