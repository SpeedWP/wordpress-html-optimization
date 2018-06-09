<?php
namespace O10n;

/**
 * HTML image optimization admin template
 *
 * @package    optimization
 * @subpackage optimization/admin
 * @author     Optimization.Team <info@optimization.team>
 */
if (!defined('ABSPATH') || !defined('O10N_ADMIN')) {
    exit;
}

// print form header
$this->form_start(__('HTML Image Optimization', 'o10n'), 'html');

?>

<table class="form-table">
    <tr valign="top">
        <th scope="row"><span style="color:brown"><span style="color:mediumblue">&lt;</span>img<span style="color:red"> src</span><span style="color:mediumblue">&gt;</span></span> filter</th>
        <td>

            <label><input type="checkbox" name="o10n[html.imagefilter.enabled]" data-json-ns="1" value="1"<?php $checked('html.imagefilter.enabled', true); ?> /> Enabled</label>
            <p class="description">Filter <code>&lt;img ...&gt;</code> elements for optimization.</p>

            <div class="info_yellow" data-ns="html.imagefilter"<?php $visible('html.imagefilter'); ?>>When enabled, you can use the WordPress filter <code>o10n_image_filter</code> to apply custom optimization to image elements. (<a href="javascript:void(0);" onclick="jQuery('#wp_link_filter').fadeToggle();">show example</a>)
                <pre id="wp_link_filter" style="display:none;" class="clickselect" title="<?php print esc_attr('Click to select', 'optimization'); ?>" style="cursor:copy;padding: 10px;margin: 0 1px;margin-top:5px;font-size: 13px;">/* Image optimization filter */
add_filter('o10n_image_filter', function($img, $src, $srcset, $set_attrs, $set_class, $rename_attrs, $delete_attrs) {
    
    // apply custom optimization to &lt;img ...&gt; tag or src URL

    // add attributes
    $set_attrs['data-custom-attribute'] = 1;

    // add classes
    $set_class[] = 'classX';
    $set_class[] = 'classY';

    // delete attribute
    $delete_attrs[] = 'data-to-delete';

    // rename attribute
    $rename_attrs['src-attr'] = 'data-src-attr';

    // return modified img tag, src and attributes
    return array($img, $src, $srcset, $set_attrs, $set_class, $rename_attrs, $delete_attrs);

    // alternatively, return the modified tag as a string
    // return $img;
}, 10, 7);</pre>
            </div>

            <div class="suboption" data-ns="html.imagefilter"<?php $visible('html.imagefilter'); ?>>
                
                    <select name="o10n[html.imagefilter.filter.type]" data-ns-change="html.imagefilter.filter" data-json-default="<?php print esc_attr(json_encode('include')); ?>">
                        <option value="include"<?php $selected('html.imagefilter.filter.type', 'include'); ?>>Include by default</option>
                        <option value="exclude"<?php $selected('html.imagefilter.filter.type', 'exclude'); ?>>Exclude by default</option>
                    </select>
            </div>
        </td>
    </tr>
    <tr valign="top" data-ns="html.imagefilter"<?php $visible('html.imagefilter'); ?>>
        <th scope="row">&nbsp;</th>
        <td style="padding-top:0px;">
            <h5 class="h">&nbsp;Image Filter</h5>
            <div id="html-imagefilter-filter-config"><div class="loading-json-editor"><?php print __('Loading JSON editor...', 'o10n'); ?></div></div>
            <input type="hidden" class="json" name="o10n[html.imagefilter.filter.config]" data-json-type="json-array" data-json-editor-height="auto" data-json-editor-init="1" value="<?php print esc_attr($json('html.imagefilter.filter.config')); ?>" />
            <p class="description">Enter a JSON array with objects. (<a href="javascript:void(0);" onclick="jQuery('#linkfilter_filter_example').fadeToggle();">show example</a>)</p>
            <div class="info_yellow" id="linkfilter_filter_example" style="display:none;"><strong>Example:</strong> <pre class="clickselect" title="<?php print esc_attr('Click to select', 'optimization'); ?>" style="cursor:copy;padding: 10px;margin: 0 1px;margin-top:5px;font-size: 13px;">[
  {
    "match": "/wp-content\\/uploads\\/.*800x600\\.jpg/",
    "regex": true,
    "attributes": [
      {
        "param": "width",
        "value": "100%"
      }
    ],
    "class": [
      "photo-viewer",
      "big-img"
    ],
    "cdn": {
      "url": "https://img.mydomain.com",
      "mask": "/wp-content/uploads/"
    }
  }
]</pre></div>

    

        </td>
    </tr>
    <tr valign="top">
        <th scope="row">CDN</th>
        <td>
            <div data-ns="html.imagefilter"<?php $visible('html.imagefilter');  ?>>
            <label><input type="checkbox" name="o10n[html.imagefilter.cdn.enabled]" value="1" data-json-ns="1"<?php $checked('html.imagefilter.cdn.enabled'); ?> /> Enabled</label>
            <p class="description">When enabled, local image URLs are loaded via a Content Delivery Network (CDN).</p>
            </div>

            <div data-ns-hide="html.imagefilter"<?php $invisible('html.imagefilter');  ?>>
                This feature requires the image filter to be enabled.
            </div>
        </td>
    </tr>
    <tr valign="top" data-ns="html.imagefilter.cdn"<?php $visible('html.imagefilter.cdn');  ?>>
        <th scope="row">&nbsp;</th>
        <td style="padding-top:0px;">
            <h5 class="h">&nbsp;CDN URL</h5>
            <input type="url" name="o10n[html.imagefilter.cdn.url]" value="<?php $value('html.imagefilter.cdn.url'); ?>" style="width:500px;max-width:100%;" placeholder="https://cdn.yourdomain.com/" />
            <p class="description">Enter a CDN URL for local images, e.g. <code>https://cdn-img.domain.com/</code></p>
        </td>
    </tr>
</table>
<hr />
<?php
    submit_button(__('Save'), 'primary large', 'is_submit', false);

// print form header
$this->form_end();
