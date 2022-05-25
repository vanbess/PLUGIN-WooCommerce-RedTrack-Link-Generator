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

        <div id="campaign-tabs">

            <ul>
                <li><a href="#ext-campaign"><?php _e("Use existing campaign", "woocommerce"); ?></a></li>
                <li><a href="#new-campaign"><?php _e("Create new campaign", "woocommerce"); ?></a></li>
            </ul>

            <!-- ********************* -->
            <!-- use existing campaign -->
            <!-- ********************* -->
            <div id="ext-campaign">

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

            <!-- ******************* -->
            <!-- create new campaign -->
            <!-- ******************* -->
            <div id="new-campaign">

                <p><b><i><?php _e("Use the inputs below to create a new RedTrack campaign. Generated links can be saved to All RedTrack Links menu.", "woocommerce"); ?></i></b></p>

                <!-- campaign name -->
                <p>
                    <label for="campaign-name"><b><i><?php _e("Campaign name:", "woocommerce"); ?></b></i></label>
                </p>
                <p>
                    <input type="text" name="campaign-name" id="campaign-name" style="width: 400px;" placeholder="<?php _e("the name for this campaign", "woocommerce"); ?>" value="">
                </p>

                <!-- campaign source -->
                <p>
                    <label for="campaign-src"><b><i><?php _e("Select campaign source:", "woocommerce"); ?></b></i></label>
                </p>
                <p>
                    <select id="campaign-src" name="campaign-src" style="width: 400px;" data-nonce="<?php echo wp_create_nonce('fetch sources'); ?>">
                        <option value="" id="campaign-src-default"><?php _e("Select source", "woocommerce"); ?></option>
                    </select>
                </p>

                <!-- campaign domain -->
                <p>
                    <label for="campaign-domain"><b><i><?php _e("Select campaign domain:", "woocommerce"); ?></b></i></label>
                </p>
                <p>
                    <select id="campaign-domain" name="campaign-domain" style="width: 400px;" data-nonce="<?php echo wp_create_nonce('fetch domains'); ?>">
                        <option value="" id="domain-default"><?php _e("Select domain", "woocommerce"); ?></option>
                    </select>
                </p>

                <!-- offer type -->
                <p>
                    <label for="offer-type"><b><i><?php _e("Select offer type:", "woocommerce"); ?></b></i></label>
                </p>
                <p>
                    <select id="offer-type" name="offer-type" style="width: 400px;" data-nonce="<?php echo wp_create_nonce('fetch offers'); ?>">
                        <option value="" id="offer-default"><?php _e("Select offer", "woocommerce"); ?></option>
                    </select>
                </p>

                <!-- cost type (CPC etc) -->
                <p>
                    <label for="cost-type"><b><i><?php _e("Select cost type:", "woocommerce"); ?></b></i></label>
                </p>
                <p>
                    <select id="cost-type" name="cost-type" style="width: 400px;">
                        <option value=""><?php _e("Select cost type", "woocommerce"); ?></option>
                        <option value="cpc">CPC</option>
                        <option value="cpa">CPA</option>
                        <option value="cpm">CPM</option>
                        <option value="popcpm">POPCPM</option>
                        <option value="revshare">REVSHARE</option>
                        <option value="donottrack">DONOTTRACK</option>
                    </select>
                </p>

                <!-- create campaign -->
                <p>
                    <button id="create-campaign" class="button button-primary button-large" data-nonce="<?php echo wp_create_nonce('create campaign') ?>"><?php _e("Create Campaign", "woocommerce"); ?></button>
                </p>

                <!-- trackback link -->
                <p>
                    <label for="trackback-link-2"><b><i><?php _e("Trackback link:", "woocommerce"); ?></b></i></label>
                </p>
                <p>
                    <input type="text" name="trackback-link-2" id="trackback-link-2" placeholder="<?php _e("trackback link for new campaign will appear here", "woocommerce"); ?>" value="" style="width: 90%;" readonly>
                </p>

                <!-- user url -->
                <p>
                    <label for="url-link-2"><b><i><?php _e("What is your campaign URL link (without trailing slash)?:", "woocommerce"); ?></b></i></label>
                </p>
                <p>
                    <input type="text" name="url-link-2" id="url-link-2" placeholder="<?php _e("e.g. https://yourlink.com/product/product-name", "woocommerce"); ?>" value="" style="width: 400px;">
                </p>

                <!-- generate link -->
                <p>
                    <button id="generate-link-2" class="button button-primary button-large"><?php _e("Generate Link", "woocommerce"); ?></button>
                </p>

                <!-- genned link -->
                <p>
                    <label for="generated-link-2"><b><i><?php _e("Generated link will appear below:", "woocommerce"); ?></b></i></label>
                </p>
                <p>
                    <input type="text" name="generated-link-2" id="generated-link-2" placeholder="<?php _e("", "woocommerce"); ?>" value="" style="width: 90%;">
                </p>

                <!-- save -->
                <p>
                    <button id="save-generated-link-2" class="button button-primary button-large"><?php _e("Save Generated RedTrack Link", "woocommerce"); ?></button>
                </p>


            </div>
        </div>

    </div>

    <link rel="stylesheet" href="<?php echo REDTRACK_URL . 'assets/jqui/jqui.css' ?>">

    <?php
    wp_enqueue_script('jquery-ui-tabs');
    ?>

    <script>
        jQuery(document).ready(function($) {

            // tabs
            $("#campaign-tabs").tabs();

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

            // *************************************
            // load existing sources - new campaign
            // *************************************
            $('#campaign-src-default').text('<?php _e('Fetching sources...', 'woocommerce') ?>');

            var sources_nonce = $('#campaign-src').data('nonce');

            var data = {
                '_ajax_nonce': sources_nonce,
                'action': 'rtrack_sources_fetch',
            }

            $.post(ajaxurl, data, function(response) {
                $('#campaign-src-default').text('<?php _e('Select source', 'woocommerce') ?>');
                $('#campaign-src').append(response);
            });

            // *************************************
            // load existing domains - new campaign
            // *************************************
            $('#domain-default').text('<?php _e('Fetching domains...', 'woocommerce') ?>');

            var domains_nonce = $('#campaign-domain').data('nonce');

            var data = {
                '_ajax_nonce': domains_nonce,
                'action': 'rtrack_domains_fetch',
            }

            $.post(ajaxurl, data, function(response) {
                $('#domain-default').text('<?php _e('Select domain', 'woocommerce') ?>');
                $('#campaign-domain').append(response);
            });

            // ************************************
            // load existing offers - new campaign
            // ************************************
            $('#offer-default').text('<?php _e('Fetching offers...', 'woocommerce') ?>');

            var offers_nonce = $('#offer-type').data('nonce');

            var data = {
                '_ajax_nonce': offers_nonce,
                'action': 'rtrack_offers_fetch',
            }

            $.post(ajaxurl, data, function(response) {
                $('#offer-default').text('<?php _e('Select offer', 'woocommerce') ?>');
                $('#offer-type').append(response);
            });

            // *****************
            // add new campaign
            // *****************
            $('#create-campaign').click(function(e) {
                e.preventDefault();

                // setup vars
                var nonce = $(this).data('nonce'),
                    camp_name = $('#campaign-name').val(),
                    camp_source = $('#campaign-src').val(),
                    camp_domain = $('#campaign-domain').val(),
                    camp_offer_type = $('#offer-type').val(),
                    camp_cost_type = $('#cost-type').val();

                // setup error checks
                if (!camp_name) {
                    alert('<?php _e("Campaign name is required.", "woocommerce"); ?>');
                    return;
                }

                if (!camp_source) {
                    alert('<?php _e("Please select a campaign source.", "woocommerce"); ?>');
                    return;
                }

                if (!camp_domain) {
                    alert('<?php _e("Please select a campaign domain.", "woocommerce"); ?>');
                    return;
                }

                if (!camp_offer_type) {
                    alert('<?php _e("Please select campaign offer type.", "woocommerce"); ?>');
                    return;
                }

                if (!camp_cost_type) {
                    alert('<?php _e("Please select campaign cost type.", "woocommerce"); ?>');
                    return;
                }

                // setup ajax data
                var data = {
                    '_ajax_nonce': nonce,
                    'action': 'rtrack_create_campaign',
                    'camp_name': camp_name,
                    'camp_source': camp_source,
                    'camp_domain': camp_domain,
                    'camp_offer_type': camp_offer_type,
                    'camp_cost_type': camp_cost_type,
                }

                // send ajax
                $.post(ajaxurl, data, function(response) {
                    console.log(response);
                });

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
 * AJAX to create new campaign [DEFERRED FOR NOW]
 */
add_action('wp_ajax_nopriv_rtrack_create_campaign', 'rtrack_create_campaign');
add_action('wp_ajax_rtrack_create_campaign', 'rtrack_create_campaign');

function rtrack_create_campaign()
{

    check_ajax_referer('create campaign');

    // retrieve posted vals
    $camp_name       = $_POST['camp_name'];
    $camp_source     = $_POST['camp_source'];
    $camp_domain     = $_POST['camp_domain'];
    $camp_offer_type = $_POST['camp_offer_type'];
    $camp_cost_type  = $_POST['camp_cost_type'];

    $data = '{
        "title": "'.$camp_name.'",
        "'.$camp_cost_type.'": 0,
        "source_id": "'.$camp_source.'",
        "domain_id": "'.$camp_domain.'",
        "postbacks": [{
          "url": "",
          "payment_percent": 0
        }],
        "streams": [{
          "offers": [
            {
              "id": "'.$camp_offer_type.'",
              "weight": 100
            }
          ],
          "landings": [{
              "id": "",
              "weight": 0
            }
          ],
          "cost": 0,
          "weight": 0,
          "filters": {
            "country": {
              "iso": [],
              "active": false
            },
            "city": {
              "titles": [],
              "active": false
            },
            "browser": {
              "titles": []
            },
            "os": {
              "titles": [],
              "active": false
            },
            "device_type": {
              "items": [],
              "active": false
            },
            "bots": {
              "ids": [],
              "active": false,
              "exclude": false
            },
            "subs": {
              "items": {
                "sub1": "",
                "sub2": "",
                "sub3": "",
                "sub4": "",
                "sub5": "",
                "sub6": ""
              },
              "active": false
            }
          }
        }],
        "notes": [
          ""
        ],
        "tags": []
      }';

      wp_send_json($data);

      wp_die();

      

    // // setup post data array
    // $camp_data = [
    //     'title'         => $camp_name,
    //     $camp_cost_type => 0.00,
    //     'source_id'     => $camp_source,
    //     'domain_id'     => $camp_domain,
    //     'postbacks'     => [
    //         'url' => '',
    //         'payment_percent' => ''
    //     ],
    //     'streams' => [
    //         'offers' => [
    //             'id'     => $camp_offer_type,
    //             'weight' => 100
    //         ],
    //         'landings' => [
    //             'id'     => '',
    //             'weight' =>  ''
    //         ],
    //         'cost'   => 1,
    //         'weight' => 1,
    //         'filters' => [
    //             'country' => [
    //                 'iso'    => [],
    //                 'active' => false
    //             ],
    //             'city' => [
    //                 'titles' => [],
    //                 'active' => false
    //             ],
    //             'browser' => [
    //                 'titles' => []
    //             ],
    //             'os' => [
    //                 'titles' => [],
    //                 'active' => false
    //             ],
    //             'device_type' => [
    //                 'items'  => [],
    //                 'active' => false
    //             ],
    //             'bots' => [
    //                 'ids'     => [],
    //                 'active'  => false,
    //                 'exclude' =>  false
    //             ],
    //             'subs' => [
    //                 'items' => [
    //                     'sub1' => '',
    //                     'sub2' => '',
    //                     'sub3' => '',
    //                     'sub4' => '',
    //                     'sub5' => '',
    //                     'sub6' => ''
    //                 ],
    //                 'active' => false
    //             ]
    //         ]
    //     ],
    //     'notes' => [
    //         ''
    //     ],
    //     'tags' => []

    // ];

    // retrieve API key
    $rt_api_key = get_option('redtrack-api-key');

    // set up sources fetch link
    $campaign_post_link = "http://api.redtrack.io/campaigns?api_key=$rt_api_key";

    // fetch sources data
    $response = wp_remote_post($campaign_post_link, [
        'method' => 'POST',
        'body'   => $data
    ]);

    if (is_wp_error($response)) :
        wp_send_json($response->get_error_message());
    else :
        print_r($response);
    endif;

    wp_die();
}

/**
 * AJAX to fetch sources for new campaign
 */
add_action('wp_ajax_nopriv_rtrack_sources_fetch', 'rtrack_sources_fetch');
add_action('wp_ajax_rtrack_sources_fetch', 'rtrack_sources_fetch');

function rtrack_sources_fetch()
{

    check_ajax_referer('fetch sources');

    // retrieve API key
    $rt_api_key = get_option('redtrack-api-key');

    // set up sources fetch link
    $sources_fetch_link = "http://api.redtrack.io/sources?api_key=$rt_api_key";

    // fetch sources data
    $fetched = wp_remote_get($sources_fetch_link);

    // decode fetched data
    $sources_data = json_decode($fetched['body']);

    // build options and return
    foreach ($sources_data as $sobj) : ?>
        <option value="<?php echo $sobj->id; ?>"><?php echo $sobj->title; ?></option>
    <?php endforeach;

    wp_die();
}

/**
 * AJAX to fetch domains for new campaign
 */
add_action('wp_ajax_nopriv_rtrack_domains_fetch', 'rtrack_domains_fetch');
add_action('wp_ajax_rtrack_domains_fetch', 'rtrack_domains_fetch');

function rtrack_domains_fetch()
{

    check_ajax_referer('fetch domains');

    // retrieve API key
    $rt_api_key = get_option('redtrack-api-key');

    // set up domains fetch link
    $domain_fetch_link = "http://api.redtrack.io/domains?api_key=$rt_api_key";

    // fetch domains data
    $fetched = wp_remote_get($domain_fetch_link);

    // decode fetched data
    $domain_data = json_decode($fetched['body']);

    // build options and return
    foreach ($domain_data as $dobj) : ?>
        <option value="<?php echo $dobj->id; ?>"><?php echo $dobj->url; ?></option>
    <?php endforeach;

    wp_die();
}


/**
 * AJAX to fetch offers for new campaign
 */
add_action('wp_ajax_nopriv_rtrack_offers_fetch', 'rtrack_offers_fetch');
add_action('wp_ajax_rtrack_offers_fetch', 'rtrack_offers_fetch');

function rtrack_offers_fetch()
{

    check_ajax_referer('fetch offers');

    // retrieve API key
    $rt_api_key = get_option('redtrack-api-key');

    // set up offers fetch link
    $offers_fetch_link = "http://api.redtrack.io/offers?api_key=$rt_api_key";

    // fetch offers data
    $fetched = wp_remote_get($offers_fetch_link);

    // decode fetched data
    $offer_data = json_decode($fetched['body']);

    // build options and return
    foreach ($offer_data as $oobj) : ?>
        <option value="<?php echo $oobj->id; ?>"><?php echo $oobj->title; ?></option>
        <?php endforeach;

    wp_die();
}

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
