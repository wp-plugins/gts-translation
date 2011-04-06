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

global $gts_plugin, $wp_version;

$gts_localization_default_theme = "twentyten";
if( preg_match( '/^2\./', $wp_version ) ) {
    $gts_localization_default_theme = 'kubrick';
}

function gts_localization_page_get_mofile_version( $mofile ) {

    if( $mofile ) {
        $version = basename( dirname( dirname( $mofile ) ) );
        $version = $version == '0.0' ? 'trunk' : $version;

        global $wp_version;
        if( $version != $wp_version ) {
            $version = "<span style=\"color: #E66F00\">$version</span>";
        }

        return $version;
    }

    return "<span style=\"color: red\">UNAVAILABLE</span>";
}

function gts_localization_page_get_mofile_lastmodified( $mofile ) {

    if( $mofile ) {
        return date( 'Y-m-d G:i:s', filemtime( $mofile ) );
    }

    return '&nbsp;';
}

?>

<h2>Localization Settings</h2>

<?php if( !$gts_plugin->is_plugin_theme_directory_writable() ) { ?>
<div class="updated">
    Your plugin directory isn't <a href="http://codex.wordpress.org/Changing_File_Permissions">writable</a>, so localization
    files cannot be downloaded!<br/>
    <code><?php echo is_dir(GTS_I18N_DIR) ? GTS_I18N_DIR : GTS_PLUGIN_DIR; ?></code>
</div>
<?php } ?>

<p>The GTS Plugin automatically downloads files to translate the content produced by WordPress.
 These localization files are taken from the official international Subversion repository at
<a href="http://svn.automattic.com/wordpress-i18n/">http://svn.automattic.com/wordpress-i18n/</a>.</p>

<p>Sometimes, there isn't an official localization release for an English release, and other times it
lags behind.  The plugin always tries to make a best effort to match an earlier or later version to your
WordPress version.</p>

<p>The plugin checks once per day for updated localization files.</p>

<div style="text-align: center">
    <table class="widefat fixed">
        <thead>
        <tr>
            <th scope="col" class="manage-column">Language</th>
            <th scope="col" class="manage-column">Locale</th>
            <th scope="col" class="manage-column">Localized WP Version</th>
            <th scope="col" class="manage-column">Last Modified</th>
            <th scope="col" class="manage-column">Localized <?php echo $gts_localization_default_theme == 'kubrick' ? 'Kubrick' : 'TwentyTen' ?> Version</th>
            <th scope="col" class="manage-column">Last Modified</th>
        </tr>
        </thead>
        <tbody style="text-align: left">
        <?php foreach( $gts_plugin->config->target_languages as $language ) {

            $mofile = $gts_plugin->get_best_mofile( $language );
            $theme_mofile = $gts_plugin->get_best_mofile( $language, $gts_localization_default_theme );

            ?>
        <tr class="active<?php if ($i++ % 2 == 1) echo ' second' ?>">
            <td><?php echo com_gts_Language::get_by_code( $language )->englishName; ?></td>
            <td><?php echo com_gts_Language::get_by_code( $language )->wordpressLocaleName; ?></td>
            <td><?php echo gts_localization_page_get_mofile_version( $mofile ); ?></td>
            <td><?php echo gts_localization_page_get_mofile_lastmodified( $mofile ); ?></td>
            <td><?php echo gts_localization_page_get_mofile_version( $theme_mofile ) ?></td>
            <td><?php echo gts_localization_page_get_mofile_lastmodified( $theme_mofile ); ?></td>
        </tr>
        <?php } ?>
        </tbody>
    </table>
</div>