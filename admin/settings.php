<?php

/**
 * ADD REDTRACK ADMIN MENU
 */

add_action('admin_menu', function () {

    // main
    add_menu_page(
        __('RedTrack API Settings', 'woocommerce'),
        __('RedTrack', 'woocommerce'),
        'manage_options',
        'redtrack-settings',
        'sbwc_redtrack_settings',
        REDTRACK_URL . 'assets/img/redtrack-logo.png',
        20
    );

    // CPT
    add_submenu_page(
        'redtrack-settings',
        __('RedTrack API Settings', 'woocommerce'),
        __('API Settings', 'woocommerce'),
        'manage_options',
        'redtrack-links',
        'sbwc_redtrack_settings',
        1
    );

    // Link generator
    add_submenu_page(
        'redtrack-settings',
        __('RedTrack Link Generator', 'woocommerce'),
        __('Link Generator', 'woocommerce'),
        'manage_options',
        'redtrack-link-generator',
        'sbwc_redtrack_link_generator',
        1
    );
});

/**
 * RedTrack API Settings
 */
function sbwc_redtrack_settings()
{

    global $title; ?>

    <div id="redtrack-settings">

        <h2><?php _e($title, 'woocommerce'); ?></h2>

        <p><b><i><?php _e("Add your RedTrack API settings below to connect to RedTrack", "woocommerce"); ?></i></b></p>

        <?php
        // save settings
        if (isset($_POST['redtrack-api-key'])) :

            update_option('redtrack-api-key', $_POST['redtrack-api-key']); ?>

            <div class="notice notice-success is-dismissible" style="margin-left: 0;">
                <p><?php _e('RedTrack settings successfully updated.', 'woocommerce'); ?></p>
            </div>

        <?php
        endif;
        ?>

        <form action="" method="post">

            <!-- redtrack api key -->
            <p>
                <label for="redtrack-api-key"><b><i><?php _e("RedTrack API Key:", "woocommerce"); ?></i></b></label>
            </p>
            <p>
                <input type="text" name="redtrack-api-key" id="redtrack-api-key" style="width: 380px;" value="<?php echo get_option('redtrack-api-key'); ?>" required>
            </p>

            <!-- save settings -->
            <p>
                <button type="submit" class="button button-primary button-large"><?php _e("Save RedTrack API Settings", "woocommerce"); ?></button>
            </p>

        </form>

    </div>

<?php }

/**
 * RedTrack Link Generator
 */
function sbwc_redtrack_link_generator()
{
    global $title; ?>

    <div id="redtrack-link-generator">

        <h2><?php _e($title, "woocommerce"); ?></h2>

        <!-- ********************* -->
        <!-- use existing campaign -->
        <!-- ********************* -->

        <p><b><i><?php _e("Use the inputs below to select options for generating your RedTrack link. Generated links can be saved to All RedTrack Links menu.", "woocommerce"); ?></i></b></p>

        <!-- existing campaigns select -->
        <p>
            <label for="select-existing"><b><i><?php _e("Select campaign:", "woocommerce"); ?></i></b></label>
        </p>

        <p>
            <select name="select-existing" id="select-existing" style="width: 400px;" data-nonce="<?php echo wp_create_nonce('fetch redtrack campaigns'); ?>">
                <option value="" id="select-existing-base-option"><?php _e("Select existing campaign", "woocommerce"); ?></option>
            </select>
        </p>

        <!-- trackback link -->
        <p>
            <label for="trackback-link"><b><i><?php _e("Trackback link for selected:", "woocommerce"); ?></i></b></label>
        </p>

        <p>
            <input type="text" name="trackback-link" id="trackback-link" readonly style="width: 90%" placeholder="<?php _e("trackback link for selected campaign will appear here", "woocommerce"); ?>">
        </p>

        <!-- url link -->
        <p>
            <label for="url-link"><b><i><?php _e("What is your campaign URL link (without trailing slash)?", "woocommerce"); ?></i></b></label>
        </p>

        <p>
            <input type="text" name="url-link" id="url-link" style="width: 400px;" placeholder="<?php _e("e.g. https://yourlink.com/product/product-name", "woocommerce"); ?>">
        </p>

        <!-- generate link -->
        <p>
            <button id="generate-link" class="button button-primary button-large"><?php _e("Generate Link", "woocommerce"); ?></button>
        </p>

        <!-- generated link -->
        <p>
            <label for="generated-link"><b><i><?php _e("Generated link will appear below:", "woocommerce"); ?></i></b></label>
        </p>

        <p>
            <input type="text" name="generated-link" id="generated-link" style="width: 90%;">
        </p>

        <!-- save generated link -->
        <p>
            <button id="save-redtrack-link" class="button button-primary button-large"><?php _e("Save Generated RedTrack Link", "woocommerce"); ?></button>
        </p>

    </div>

    <link rel="stylesheet" href="<?php echo REDTRACK_URL . 'assets/jqui/jqui.css' ?>">

    <?php
    wp_enqueue_script('jquery-ui-tabs');
    ?>

    <script>
        jQuery(document).ready(function($) {

            // ************************
            // load existing campaigns
            // ************************
            $('#select-existing-base-option').text('<?php _e('Fetching campaigns...', 'woocommerce') ?>');

            var nonce = $('#select-existing').data('nonce');

            var data = {
                '_ajax_nonce': nonce,
                'action': 'fetch_redtrack_campaigns',
                'fetch_type': 'dropdown'
            }

            $.post(ajaxurl, data, function(response) {
                $('#select-existing-base-option').text('<?php _e('Select existing campaign', 'woocommerce') ?>');
                $('#select-existing').append(response);
            });

            // **************************************
            // load links on #select-existing change
            // **************************************
            $('#select-existing').change(function(e) {
                e.preventDefault();

                var camp_id = $(this).val();

                $('#trackback-link').val('<?php _e("fetching...", "woocommerce"); ?>');

                var data = {
                    '_ajax_nonce': nonce,
                    'action': 'fetch_redtrack_campaigns',
                    'campaign_id': camp_id,
                    'fetch_type': 'links'
                }

                $.post(ajaxurl, data, function(response) {
                    $('#trackback-link').val(response);
                });

            });

            // *****************************
            // generate final link existing
            // *****************************
            $('#generate-link').click(function(e) {
                e.preventDefault();

                // grab trackback link and url link provided by user
                var tb_link = $('#trackback-link').val();
                var url_link = $('#url-link').val();

                // if no trackback link, display error and bail
                if (!tb_link) {
                    alert('<?php _e("Trackback link not present. Please select an existing campaign first.", "woocommerce"); ?>');
                    return;
                }

                // if no user url link, display error and bail
                if (!url_link) {
                    alert('<?php _e("Please provide your campaign URL link first.", "woocommerce"); ?>')
                    return;
                }

                // grab domain origin from trackback link
                var domain = new URL(tb_link);
                var to_replace = domain.origin + '/';

                // replace trackback link and generate/insert user url based link
                var genned_link = tb_link.replace(to_replace, url_link + '/?cmpid=');
                $('#generated-link').val(genned_link);

            });

            // *****************************
            // save generated link existing
            // *****************************
            $('#save-redtrack-link').click(function(e) {
                e.preventDefault();

                $(this).text('<?php _e("Processing...", "woocommerce"); ?>');

                // grab generated link
                var genned_link = $('#generated-link').val();

                // if generated link not found, bail
                if (!genned_link) {
                    alert('<?php _e("Please generate your link before attempting to save.", "woocommerce"); ?>');
                    return;
                }

                // save generated link to CPT
                var data = {
                    '_ajax_nonce': nonce,
                    'action': 'fetch_redtrack_campaigns',
                    'genned_link': genned_link,
                    'save_link': true
                }

                $.post(ajaxurl, data, function(response) {
                    alert(response);
                    location.reload();
                });

            });

        });
    </script>

    <?php }

/**
 * AJAX to fetch existing campaigns
 */
add_action('wp_ajax_nopriv_fetch_redtrack_campaigns', 'fetch_redtrack_campaigns');
add_action('wp_ajax_fetch_redtrack_campaigns', 'fetch_redtrack_campaigns');

function fetch_redtrack_campaigns()
{

    check_ajax_referer('fetch redtrack campaigns');

    // retrieve API key
    $rt_api_key = get_option('redtrack-api-key');

    // set up campaign fetch link
    $campaign_fetch_link = "http://api.redtrack.io/campaigns?api_key=$rt_api_key";

    // fetch campaign data
    $fetched = wp_remote_get($campaign_fetch_link);

    // decode fetched data
    $campaign_data = json_decode($fetched['body']);

    // **********************************
    // fetch and return dropdown options
    // **********************************
    if ($_POST['fetch_type'] === 'dropdown') :

        // trimmed data array for dropdown options
        $campaign_data_trimmed = [];

        // push data to $campaign_data_trimmed
        foreach ($campaign_data as $cobj) :
            $campaign_data_trimmed[$cobj->id] = $cobj->title;
        endforeach;

        // generate and return options html
        foreach ($campaign_data_trimmed as $cid => $cname) : ?>
            <option value="<?php echo $cid; ?>"><?php echo $cname; ?></option>
    <?php endforeach;

    endif;

    // ****************************************
    // fetch and return link for chosen option
    // ****************************************
    if ($_POST['fetch_type'] === 'links') :

        // retrieve campaign id
        $camp_id = $_POST['campaign_id'];

        $link = '';

        foreach ($campaign_data as $cobj) :
            if ($cobj->id == $camp_id) :
                $link = $cobj->trackback_url;
            endif;
        endforeach;

        if ($link === '') :
            wp_send_json(__('Trackback link not found. Please select another campaign to try again.', 'woocommerce'));
        else :
            wp_send_json($link);
        endif;

    endif;

    // ********************
    // save generated link
    // ********************
    if ($_POST['save_link']) :

        $genned_link = $_POST['genned_link'];

        $link_saved = wp_insert_post([
            'post_type'    => 'redtrack-link',
            'post_status'  => 'publish',
            'post_author'  => '',
            'post_content' => '',
            'post_title'   => $genned_link
        ]);

        if (is_wp_error($link_saved)) :
            wp_send_json($link_saved->get_error_message());
        else :
            wp_send_json(__('Link saved successfully and can be found under RedTrack -> All RedTrack Links.', 'woocommerce'));
        endif;

    endif;

    wp_die();
}

/**
 * Admin menu icon and admin pages styles
 */
add_action('admin_head', function () { ?>

    <style>
        li#toplevel_page_redtrack-settings img {
            width: 22px;
            height: 22px;
            position: relative;
            bottom: 3px;
        }

        div#redtrack-settings>h2,
        div#redtrack-link-generator>h2 {
            background: white;
            padding: 15px 20px;
            box-shadow: 0px 0px 4px lightgrey;
            margin-top: 0;
            margin-left: -19px;
        }
    </style>

<?php });
