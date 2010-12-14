<?php

/*
 * Copyright (c) 2010, Localization Technologies (LT) LLC
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *
 *     * Redistributions of source code must retain the above copyright
 *       notice, this list of conditions and the following disclaimer.
 *
 *     * Redistributions in binary form must reproduce the above copyright
 *       notice, this list of conditions and the following disclaimer in the
 *       documentation and/or other materials provided with the distribution.
 *
 *     * Neither the name of Localization Technologies (LT) LLC nor the
 *       names of its contributors may be used to endorse or promote products
 *       derived from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
 * ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL LOCALIZATION TECHNOLOGIES (LT) LLC BE LIABLE FOR ANY
 * DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
 * ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 */

class GTS_LanguageSelectWidget extends WP_Widget {

    protected static $TITLES = array (
        'en' => 'Also available in',
        'de' => 'Auch verfügbar in',
        'es' => 'También disponible en',
        'fr' => 'En outre disponible dans',
        'it' => 'Inoltre  disponibile  in',
    );

    protected static $POWERED_BY = array (
        'en' => 'Powered by',
        'de' => 'Übersetzt durch',
        'es' => 'Traducido por',
        'fr' => 'Traduit par',
        'it' => 'Tradotto da',
    );

    protected static $SELECT_LANGUAGE = array (
        'en' => 'Select Language',
        'de' => 'Wählen Sprache',
        'es' => 'Elige Idioma',
        'fr' => 'Choisir Langue',
        'it' => 'Scegliere Lingua',
    );


    function __construct() {
        parent::__construct(false, 'Gts Language Selector', array (
            'description' => __('Adds a drop-down menu to change the language of the page.')
        ));
    }

    function widget($args, $instance) {

        extract($args);

        global $gts_plugin;

        $available_langs = array ($gts_plugin->config->source_language);
        if( is_array( $gts_plugin->config->target_languages ) ) {
            $available_langs = array_merge($available_langs, $gts_plugin->config->target_languages);
        }

        if(!($curr_lang = $gts_plugin->language)) {
            $curr_lang = $gts_plugin->config->source_language;
        }

        $title = apply_filters('widget_title', GTS_LanguageSelectWidget::$TITLES[$curr_lang]);

        $languages_with_links = array();
        foreach($available_langs as $code) {
            $lang = com_gts_Language::get_by_code($code);
            $is_current = $lang->code == $curr_lang;
            $link = $this->get_current_url_for_language( $lang );

            if ( !$is_current ) {
                $languages_with_links[ $lang->code ] = $link;
            }
        }

        echo $before_widget;

        $this->output_widget_html($curr_lang, $title, $languages_with_links, $before_title, $after_title);

        echo $after_widget;
    }


    function output_widget_html($curr_lang, $title, $languages_with_links, $before_title = "", $after_title = "") {
        ?>

        <div class="gtsLanguageSelector">
            <!-- GTS Plugin Version <?php echo GTS_PLUGIN_VERSION ?> -->

        <?php if($title) {
            echo $before_title . $title . ":" . $after_title;
        } ?>

            <script type="text/javascript">
                var com_gts_languageLookup = {
                    <?php
                    foreach ( $languages_with_links as $lang => $link ) {
                        echo "$lang : '$link',\n";
                    }
                    ?>
                };

                function sendToTranlsatedPage(select) {
                    var code = select.options[select.selectedIndex].value;
                    if(com_gts_languageLookup[code] != null) {
                        window.location.href = com_gts_languageLookup[code];
                    }
                }
            </script>

            <select onchange="sendToTranlsatedPage(this)">
                <option><?php echo GTS_LanguageSelectWidget::$SELECT_LANGUAGE[$curr_lang]; ?>...</option>
            <?php
            foreach( $languages_with_links as $lang_code => $link ) {
                $lang = com_gts_Language::get_by_code( $lang_code );
                echo "<option value=\"$lang->code\">$lang->name</option>\n";
            }
            ?>
            </select>

            <p style="vertical-align: middle; margin-top:3px">
                <span><?php echo GTS_LanguageSelectWidget::$POWERED_BY[$curr_lang]; ?>
                    <a href="http://www.gts-translation.com/" target="_blank"><img src="<?php echo GTS_PLUGIN_URL ?>/wordpress/images/logo_trans_sm.png" alt="GTS Translation" title="GTS Translation"/></a>
                </span>
            </p>
        </div>
        <?php
    }


    /** @see WP_Widget::update */
    function update($new_instance, $old_instance) {
        return $new_instance;
    }




    function get_current_url_for_language( $lang ) {

        global $gts_plugin, $wp_rewrite;
        $is_source = $lang->code == $gts_plugin->config->source_language;

        // this little ditty makes sure we get appropriate hostname replacement...
        $home = trailingslashit( $gts_plugin->do_with_language( array( $this, 'callback_get_home' ), $is_source ? null : $lang->code ) );

        // this is the easy case...  no permalinks.  then we just have to toggle the language
        // parameter and we can call it a day.
        if( !$wp_rewrite->permalink_structure ) {

            $link = $this->get_homed_url( $home, null );
            if( $lang->code == $gts_plugin->config->source_language ) {
                $link = remove_query_arg( "language", $link );
            }
            else {
                $link = add_query_arg( "language", $lang->code, $link );
            }
        }
        else {

            // and if we have permalink support, there's a whole mess of special cases.  most
            // of them boil down to running the link through the plugin with the language overridden.

            $homed_url = $this->get_homed_url( $home, $lang->code );
            $interesting_part = substr( $homed_url, strlen( $home ) );

            if( $is_source ) {

                $interesting_part = preg_replace( '/^language\/[a-z]{2}\/?/', '', $interesting_part);

                if( is_tag() && get_query_var( 'tag' ) ) {
                    $link = $gts_plugin->do_without_language( array( $this, 'callback_get_tag_link' ) );
                }
                else if( is_category() && get_query_var( 'cat' ) ) {
                    $link = $gts_plugin->do_without_language( array( $this, 'callback_get_category_link' ) );
                }
                else if( is_tax() && get_query_var( 'taxonomy' ) && get_query_var( 'term' ) ) {
                    $link = $gts_plugin->do_without_language( array( $this, 'callback_get_taxonomy_link' ) );
                }
                else if( is_single() && ( get_query_var( 'p' ) || get_query_var( 'name' ) ) ) {
                    $link = $gts_plugin->do_without_language( array( $this, 'callback_get_post_link' ) );
                }
                else if( is_page() ) {
                    $link = $gts_plugin->do_without_language( array( $this, 'callback_get_page_link' ) );
                }
                else {
                    $link = $home . $interesting_part;
                }
            }
            else {

                if( is_tag() && get_query_var( 'tag' ) ) {
                    $link = $gts_plugin->do_with_language( array( $this, 'callback_get_tag_link' ), $lang->code);
                }
                else if ( is_category() && get_query_var( 'cat' ) && ( $gts_plugin->link_rewriter->original_category_id || $gts_plugin->link_rewriter->original_category_name ) ) {
                    $link = $gts_plugin->do_with_language( array( $this, 'callback_get_category_link' ), $lang->code);
                }
                else if( is_tax() && get_query_var( 'taxonomy' ) && get_query_var( 'term' ) ) {
                    $link = $gts_plugin->do_with_language( array( $this, 'callback_get_taxonomy_link' ), $lang->code);
                }
                else if( is_single() && ( get_query_var( 'p' ) || get_query_var( 'name' ) ) ) {
                    $link = $gts_plugin->do_with_language( array( $this, 'callback_get_post_link' ), $lang->code);
                }
                else if( is_page() ) {
                    $link = $gts_plugin->do_with_language( array( $this, 'callback_get_page_link' ), $lang->code);
                }
                else if(!preg_match( '/^(language\/)([a-z]{2})(\/.*)?$/', $interesting_part, $matches ) ) {
                    $link = $home . 'language/' . $lang->code . '/' . $interesting_part;
                }
                else {
                    $link = $home . $matches[1] . $lang->code . $matches[3];
                }
            }


            // if we have permalinks, then make sure this parameter isn't hanging around where
            // it may accidentally override the displayed page language.
            if ( ! preg_match( '/(\?|\&)(tag|cat)=/', $link ) ) {
                $link = remove_query_arg( 'language', $link );
            }
        }

        return $link;
    }

    function callback_get_post_link() {

        if( ! $post_id = get_query_var( 'p' ) && isset( $GLOBALS['post'] ) ) {
            $post_id = $GLOBALS['post']->ID;
        }

        // boot the post from our cache so that it gets reloaded with our current
        // filters.  then we delete it again to make sure that a translated version
        // doesn't end up in the cache for other display stuff.
        wp_cache_delete( $post_id, 'posts' );

        $link = get_permalink( $post_id );

        wp_cache_delete( $post_id, 'posts' );

        return $link;
    }

    function callback_get_tag_link() {

        global $gts_plugin;

        // when there are multiple tags passed in, we need to
        // use the query arg way of doing things.  remove the
        // language arg from the current location, and we're all good.
        $tags = $gts_plugin->link_rewriter->original_tag;
        if( count(explode( ',', $tags ) ) > 1) {
            if( $gts_plugin->language ) {
                return add_query_arg( 'language', $gts_plugin->language );
            }
            else {
                return remove_query_arg( 'language' );
            }
        }

        return get_tag_link( get_query_var( 'tag_id' ) );
    }

    function callback_get_category_link() {
        global $gts_plugin;

        // like with tags, we have to check to see whether there were
        // originally mulitple categories or not.  this will change
        // whether we pemarlink or query-arg it.
        $cat = $gts_plugin->link_rewriter->original_category_id;
        if( count(explode( ',', $cat ) ) > 1) {
            if( $gts_plugin->language ) {
                return add_query_arg( 'language', $gts_plugin->language );
            }
            else {
                return remove_query_arg( 'language' );
            }
        }


        $cat = get_term_by( 'slug', get_query_var( 'category_name' ), 'category' );
        return get_category_link( $cat ? $cat->term_id : 0 );
    }

    function callback_get_taxonomy_link() {
        $term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
        return get_term_link( $term, get_query_Var( 'taxonomy' ) );
    }

    function callback_get_page_link() {
        return get_page_link( get_page_by_path( get_query_var( GtsLinkRewriter::$PAGEPATH_PARAM ) )->ID );
    }


    function get_homed_url( $home, $lang ) {

        $home = untrailingslashit( $home );
        preg_match( '/^(.*?)([#?].*)?$/', $_SERVER['REQUEST_URI'] , $matches );

        $url_parts = array_filter( explode( '/', $matches[1] ) );

        $non_shared = array();
        while( count($url_parts) > 0 && !strrpos( $home, '/' . implode( '/', $url_parts ) ) ) {
            $non_shared[] = array_pop( $url_parts );
        }

        return trailingslashit($home) . implode('/', array_reverse( $non_shared ) ) . $matches[2] ;
    }


    function callback_get_home() {
        return get_option('home');
    }
}
