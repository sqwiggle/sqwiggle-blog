<?php

class ABH_Models_Frontend {

    public $author;
    public $details;
    public $position;
    public $single = true;

    /**
     * Get the html author box
     * @global object $wp_query
     * @return string
     */
    public function getAuthorBox() {
        global $wp_query;

        if (!isset($this->author))
            return;

        $content = '';

        if (isset($this->author) && isset($this->author->ID)) {
            if (!isset($this->author->user_description))
                $this->author->user_description = '';

            if ($this->details['abh_theme'] == 'default')
                $this->details['abh_theme'] = ABH_Classes_Tools::getOption('abh_theme');

            $content .= '
                         <div class="abh_box abh_box_' . $this->position . ' abh_box_' . $this->details['abh_theme'] . '">
                                <ul class="abh_tabs">
                                 <li class="abh_about abh_active"><a href="#abh_about">' . __('About', _ABH_PLUGIN_NAME_) . '</a></li>
                                 <li class="abh_posts"><a href="#abh_posts">' . __('Latest Posts', _ABH_PLUGIN_NAME_) . '</a></li>
                                </ul>
                                <div class="abh_tab_content">' .
                    $this->showAuthorDescription() .
                    $this->showAuthorPosts() . '
                                </div>
                         </div>';

            return $this->clearTags($content);
        }
        return '';
    }

    /**
     * Get the image for the author
     * @return string
     */
    public function getProfileImage() {
        if (isset($this->details['abh_gravatar']) && $this->details['abh_gravatar'] <> '' && file_exists(_ABH_GRAVATAR_DIR_ . $this->details['abh_gravatar'])) {
            return '<img src="' . _ABH_GRAVATAR_URL_ . $this->details['abh_gravatar'] . '" class="photo" width="80" />';
        } else {
            return get_avatar($this->author->ID, 80);
        }
    }

    /**
     * Get the author Title and Description
     * @return string
     */
    private function showAuthorDescription() {
        $content = '
                <section class="' . (($this->single) ? 'vcard' : '') . ' abh_about_tab abh_tab" style="display:block">
                    <div class="abh_image">
                      ' . (($this->author->user_url) ? '<a href="' . $this->author->user_url . '" class="url" target="_blank" title="' . $this->author->display_name . '">' . $this->getProfileImage() . '</a>' : '<a href="' . get_author_posts_url($this->author->ID) . '" class="url" title="' . $this->author->display_name . '">' . $this->getProfileImage() . '</a>') . '</a>' . '
                    </div>
                    <div class="abh_social"> ' . $this->getSocial() . '</div>
                    <div class="abh_text">
                        <h3 class="fn name">' . (($this->author->user_url) ? '<a href="' . $this->author->user_url . '" class="url" target="_blank">' . $this->author->display_name . '</a>' : '<a href="' . get_author_posts_url($this->author->ID) . '" class="url">' . $this->author->display_name . '</a>' ) . '</h3>
                        <div class="abh_job">' . (($this->details['abh_title'] <> '' && $this->details['abh_company'] <> '') ? '<span class="title">' . $this->details['abh_title'] . '</span> ' . __('at', _ABH_PLUGIN_NAME_) . ' <span class="org">' . (($this->details['abh_company_url'] <> '') ? sprintf('<a href="%s" target="_blank">%s</a>', $this->details['abh_company_url'], $this->details['abh_company']) : $this->details['abh_company']) . '</span>' : '') . '</div>
                        <div class="description note abh_description">' . ((isset($this->details['abh_extra_description']) && $this->details['abh_extra_description'] <> '') ? nl2br($this->details['abh_extra_description']) : nl2br($this->author->user_description)) . '</div>
                    </div>
               </section>';
        return $content;
    }

    /**
     * Get the html author latest posts
     * @return string
     */
    private function showAuthorPosts() {
        $content = '
                <section class="abh_posts_tab abh_tab" >
                    <div class="abh_image">
                      ' . (($this->author->user_url) ? '<a href="' . $this->author->user_url . '" class="url" target="_blank" title="' . $this->author->display_name . '">' . $this->getProfileImage() . '</a>' : '<a href="' . get_author_posts_url($this->author->ID) . '" class="url" title="' . $this->author->display_name . '">' . $this->getProfileImage() . '</a>') . '</a>' . '
                    </div>
                    <div class="abh_social"> ' . $this->getSocial() . '</div>
                    <div class="abh_text">
                        <h4>' . sprintf(__('Latest posts by %s', _ABH_PLUGIN_NAME_), $this->author->display_name) . ' <span class="abh_allposts">(<a href="' . get_author_posts_url($this->author->ID) . '">' . __('see all', _ABH_PLUGIN_NAME_) . '</a>)</span></h4>
                        <div class="abh_description note">' . $this->getLatestPosts() . '</div>
                    </div>
               </section>';
        return $content;
    }

    /**
     * Get the social icon for the author
     * @return string
     */
    private function getSocial() {
        $content = '';
        $count = 0;
        $nofollow = (!$this->details['abh_nofollow_social'] == 0) ? 'rel="nofollow"' : '';

        if (isset($this->details['abh_facebook']) && $this->details['abh_facebook'] <> '') {
            $count++;
            $content .= '<a href="' . ((strpos($this->details['abh_facebook'], 'http') === false) ? 'http://facebook.com/' : '') . $this->details['abh_facebook'] . '" title="' . __('Facebook', _ABH_PLUGIN_NAME_) . '" class="abh_facebook" target="_blank" ' . $nofollow . '></a>';
        }
        if (isset($this->details['abh_twitter']) && $this->details['abh_twitter'] <> '') {
            $count++;
            $content .= '<a href="' . ((strpos($this->details['abh_twitter'], 'http') === false) ? 'http://twitter.com/' : '') . $this->details['abh_twitter'] . '" title="' . __('Twitter', _ABH_PLUGIN_NAME_) . '" class="abh_twitter" target="_blank" ' . $nofollow . '></a>';
        }
        if (isset($this->details['abh_google']) && $this->details['abh_google'] <> '') {
            $count++;
            if ($this->single)
                $content .= '<a href="' . ((strpos($this->details['abh_google'], 'http') === false) ? 'http://plus.google.com/' : '') . $this->details['abh_google'] . '?rel=author" title="' . __('Google Plus', _ABH_PLUGIN_NAME_) . '" class="abh_google" rel="author" target="_blank" ' . $nofollow . '></a>';
            else
                $content .= '<a href="' . ((strpos($this->details['abh_google'], 'http') === false) ? 'http://plus.google.com/' : '') . $this->details['abh_google'] . '" title="' . __('Google Plus', _ABH_PLUGIN_NAME_) . '" class="abh_google" target="_blank" ' . $nofollow . '></a>';
        }
        if (isset($this->details['abh_linkedin']) && $this->details['abh_linkedin'] <> '') {
            $count++;
            $content .= '<a href="' . ((strpos($this->details['abh_linkedin'], 'http') === false) ? 'http://www.linkedin.com/in/' : '') . $this->details['abh_linkedin'] . '" title="' . __('LinkedIn', _ABH_PLUGIN_NAME_) . '" class="abh_linkedin" target="_blank" ' . $nofollow . '></a>';
        }
        if (isset($this->details['abh_instagram']) && $this->details['abh_instagram'] <> '') {
            $count++;
            $content .= '<a href="' . ((strpos($this->details['abh_instagram'], 'http') === false) ? 'http://instagram.com/' : '') . $this->details['abh_instagram'] . '" title="' . __('Instagram', _ABH_PLUGIN_NAME_) . '" class="abh_instagram" target="_blank" ' . $nofollow . '></a>';
        }
        if (isset($this->details['abh_flickr']) && $this->details['abh_flickr'] <> '') {
            $count++;
            $content .= '<a href="' . ((strpos($this->details['abh_flickr'], 'http') === false) ? 'http://www.flickr.com/photos/' : '') . $this->details['abh_flickr'] . '" title="' . __('Flickr', _ABH_PLUGIN_NAME_) . '" class="abh_flickr" target="_blank" ' . $nofollow . '></a>';
        }
        if (isset($this->details['abh_pinterest']) && $this->details['abh_pinterest'] <> '') {
            $count++;
            $content .= '<a href="' . ((strpos($this->details['abh_pinterest'], 'http') === false) ? 'http://pinterest.com/' : '') . $this->details['abh_pinterest'] . '" title="' . __('Pinterest', _ABH_PLUGIN_NAME_) . '" class="abh_pinterest" target="_blank" ' . $nofollow . '></a>';
        }
        if (isset($this->details['abh_tumblr']) && $this->details['abh_tumblr'] <> '') {
            $count++;
            $content .= '<a href="' . ((strpos($this->details['abh_tumblr'], 'http') === false) ? 'http://' . $this->details['abh_tumblr'] . '.tumblr.com/' : $this->details['abh_tumblr']) . '" title="' . __('Tumblr', _ABH_PLUGIN_NAME_) . '" class="abh_tumblr" target="_blank" ' . $nofollow . '></a>';
        }
        if (isset($this->details['abh_youtube']) && $this->details['abh_youtube'] <> '') {
            $count++;
            $content .= '<a href="' . ((strpos($this->details['abh_youtube'], 'http') === false) ? 'http://www.youtube.com/user/' : '') . $this->details['abh_youtube'] . '" title="' . __('YouTube', _ABH_PLUGIN_NAME_) . '" class="abh_youtube" target="_blank" ' . $nofollow . '></a>';
        }
        if (isset($this->details['abh_vimeo']) && $this->details['abh_vimeo'] <> '') {
            $count++;
            $content .= '<a href="' . ((strpos($this->details['abh_vimeo'], 'http') === false) ? 'http://vimeo.com/' : '') . $this->details['abh_vimeo'] . '" title="' . __('Vimeo', _ABH_PLUGIN_NAME_) . '" class="abh_vimeo" target="_blank" ' . $nofollow . '></a>';
        }

        if (isset($this->details['abh_klout']) && $this->details['abh_klout'] <> '') {
            $count++;
            if ($score = $this->getKloutScore())
                $content .= '<a href="' . ((strpos($this->details['abh_klout'], 'http') === false) ? 'http://klout.com/#/' : '') . $this->details['abh_klout'] . '" title="' . __('Klout', _ABH_PLUGIN_NAME_) . '" class="abh_klout_score" target="_blank" ' . $nofollow . '>' . $score . '</a>';
            else
                $content .= '<a href="' . ((strpos($this->details['abh_klout'], 'http') === false) ? 'http://klout.com/#/' : '') . $this->details['abh_klout'] . '" title="' . __('Klout', _ABH_PLUGIN_NAME_) . '" class="abh_klout" target="_blank" ' . $nofollow . '></a>';
        }

        if ($count == 5 || $count == 6) {
            $content = '<div style="width:85px; margin: 0 0 0 auto;">' . $content . '</div>';
        } elseif ($count == 7 || $count == 8) {
            $content = '<div style="width:120px; margin: 0 0 0 auto;">' . $content . '</div>';
        } elseif ($count == 9 || $count == 10) {
            $content = '<div style="width:140px; margin: 0 0 0 auto;">' . $content . '</div>';
        } elseif ($count == 11 || $count == 12) {
            $content = '<div style="width:160px; margin: 0 0 0 auto;">' . $content . '</div>';
        }

        if ($count > 0 && isset($this->details['abh_socialtext']) && $this->details['abh_socialtext'] <> '')
            $content = '<div style="clear: both; font-size:12px; font-weight:normal; width: 85px; margin: 0 0 2px auto; line-height: 20px;">' . $this->details['abh_socialtext'] . '</div>' . $content;

        return $content;
    }

    /**
     * Get the Klout Score for the author
     * @return boolean
     */
    private function getKloutScore() {
        $data = null;

        if (isset($this->details['abh_klout']) && $this->details['abh_klout'] <> '') {
            if (strpos($this->details['abh_klout'], 'http') !== false) {
                $this->details['abh_klout'] = preg_replace('/http:\/\/klout.com\/#\//', '', $this->details['abh_klout']);
                if (strpos($this->details['abh_klout'], 'http') !== false)
                    return false;
            }

            if (is_file(_ABH_GRAVATAR_DIR_ . $this->details['abh_klout']) && @filemtime(_ABH_GRAVATAR_DIR_ . $this->details['abh_klout']) > (time() - (3600 * 24))) {
                $data = json_decode(@file_get_contents(_ABH_GRAVATAR_DIR_ . $this->details['abh_klout']));
            } else {

                //First, we need to retreive the user's Klout ID
                $userID = "http://api.klout.com/v2/identity.json/twitter?screenName=" . $this->details['abh_klout'] . "&key=7a8z53zg55bk2gkuuznm98xe";
                $user = json_decode((@file_get_contents($userID)));
                $klout = $user->id;

                //Then, retreive the Klout score of the user, using the user's Klout ID and API key V2
                $url_kscore = "http://api.klout.com/v2/user.json/" . $klout . "/score?key=7a8z53zg55bk2gkuuznm98xe";
                $data = (@file_get_contents($url_kscore));

                @file_put_contents(_ABH_GRAVATAR_DIR_ . $this->details['abh_klout'], $data);
                $data = json_decode($data);
            }

            //If everything works well, then display Klout score
            if ($data) {
                return number_format($data->score, 0);
            }
        }
        return false;
    }

    /**
     * Get the List Of Posts for the author
     * @return string
     */
    private function getLatestPosts() {
        $content = '<ul>';
        $latest_posts = new WP_Query(array('posts_per_page' => ABH_Classes_Tools::getOption('anh_crt_posts'), 'author' => $this->author->ID));


        while ($latest_posts->have_posts()) : $latest_posts->the_post();
            if (get_the_title() <> '')
                $content .= '
				<li>
					<a href="' . get_permalink() . '">' . get_the_title() . '</a>' .
                        (((int) get_the_time('U') > 0) ? '<span> - ' . @date_i18n(get_option('date_format'), (int) get_the_time('U')) . '</span>' : '') . '
				</li>';
        endwhile;
        wp_reset_postdata();
        $content .= '</ul>';

        return $content;
    }

    /**
     * Clear the new lines from the author box
     * @param type $content
     * @return string
     */
    private function clearTags($content) {
        return preg_replace_callback('~\<[^>]+\>.*\</[^>]+\>~ms', array($this, 'stripNewLines'), $content);
    }

    /**
     * Clear the new lines
     * @param type $match
     * @return type
     */
    public function stripNewLines($match) {
        return str_replace(array("\r", "\n", "  "), '', $match[0]);
    }

    /**
     * Get the meta with Social and Profile
     * @return string
     */
    public function showMeta() {
        if (!isset($this->author))
            return;

        $meta = "\n<!-- StarBox - the Author Box for Humans " . ABH_VERSION . ", visit: http://wordpress.org/plugins/starbox/ -->\n";

        if (ABH_Classes_Tools::getOption('abh_showopengraph') == 1 && is_author()) {
            //Show the OpenGraph
            $meta .= $this->showOpenGraph();
        }

        if (isset($this->details['abh_google']) && $this->details['abh_google'] <> '')
            $meta .= $this->showGoogleAuthorMeta(); //show google author meta
        if (isset($this->details['abh_facebook']) && $this->details['abh_facebook'] <> '')
            $meta .= $this->showFacebookAuthorMeta(); //show facebook author meta

        $meta .= "<!-- /StarBox - the Author Box for Humans -->\n\n";

        return $meta;
    }

    /**
     * Get the Open Graph for the current author
     * @return string
     */
    public function showOpenGraph() {
        $og = '';
        $og .= sprintf('<meta property="og:url" content="%s" />', get_author_posts_url($this->author->ID)) . "\n";
        $og .= sprintf('<meta property="og:type" content="%s" />', 'profile') . "\n";
        $og .= sprintf('<meta property="profile:first_name" content="%s" />', get_the_author_meta('first_name', $this->author->ID)) . "\n";
        $og .= sprintf('<meta property="profile:last_name" content="%s" />', get_the_author_meta('last_name', $this->author->ID)) . "\n";

        return $og;
    }

    /**
     * Get the Google author Meta
     * @return string
     */
    public function showGoogleAuthorMeta() {
        return '<link rel="author" href="' . ((strpos($this->details['abh_google'], 'http') === false) ? 'http://plus.google.com/' : '') . $this->details['abh_google'] . '" />' . "\n";
    }

    /**
     * Get the Facebook author Meta
     * @return string
     */
    public function showFacebookAuthorMeta() {
        return '<meta property="article:author" content="' . ((strpos($this->details['abh_facebook'], 'http') === false) ? 'http://facebook.com/' : '') . $this->details['abh_facebook'] . '" />' . "\n";
    }

}

?>