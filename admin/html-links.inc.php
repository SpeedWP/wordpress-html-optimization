<?php
namespace O10n;

/**
 * HTML link optimization admin template
 *
 * @package    optimization
 * @subpackage optimization/admin
 * @author     Optimization.Team <info@optimization.team>
 */
if (!defined('ABSPATH') || !defined('O10N_ADMIN')) {
    exit;
}

// print form header
$this->form_start(__('HTML Link Optimization', 'o10n'), 'html');

?>

<table class="form-table">
    <tr valign="top">
        <th scope="row"><span style="color:brown"><span style="color:mediumblue">&lt;</span>a<span style="color:red"> href</span><span style="color:mediumblue">&gt;</span></span> filter</th>
        <td>

            <label><input type="checkbox" name="o10n[html.linkfilter.enabled]" data-json-ns="1" value="1"<?php $checked('html.linkfilter.enabled', true); ?> /> Enabled</label>
            <p class="description">Filter <code>&lt;a href=...&gt;</code> links for optimization.</p>

            <div class="info_yellow" data-ns="html.linkfilter"<?php $visible('html.linkfilter'); ?>>When enabled, you can use the WordPress filter <code>o10n_link_filter</code> to apply custom optimization to links. (<a href="javascript:void(0);" onclick="jQuery('#wp_link_filter').fadeToggle();">show example</a>)
                <pre id="wp_link_filter" style="display:none;" class="clickselect" title="<?php print esc_attr('Click to select', 'optimization'); ?>" style="cursor:copy;padding: 10px;margin: 0 1px;margin-top:5px;font-size: 13px;">/* Link optimization filter */
add_filter('o10n_link_filter', function($link, $href, $set_attrs, $set_class, $rename_attrs, $delete_attrs) {
    
    // apply custom optimization to &lt;a href...&gt; tag or href link

    // add attributes
    $set_attrs['data-custom-attribute'] = 1;

    // add classes
    $set_class[] = 'classX';
    $set_class[] = 'classY';

    // delete attribute
    $delete_attrs[] = 'data-to-delete';

    // rename attribute
    $rename_attrs['src-attr'] = 'data-src-attr';

    // return modified link tag, href and attributes
    return array($link, $href, $set_attrs, $set_class, $rename_attrs, $delete_attrs);

    // alternatively, return the modified tag as a string
    // return $link;
}, 10, 6);</pre>
            </div>

            <div class="suboption" data-ns="html.linkfilter"<?php $visible('html.linkfilter'); ?>>
                <label><input type="checkbox" value="1" name="o10n[html.linkfilter.filter.enabled]" data-json-ns="1"<?php $checked('html.linkfilter.filter.enabled'); ?> /> Enable filter</label>
                <span data-ns="html.linkfilter.filter"<?php $visible('html.linkfilter.filter'); ?>>
                    <select name="o10n[html.linkfilter.filter.type]" data-ns-change="html.linkfilter.filter" data-json-default="<?php print esc_attr(json_encode('include')); ?>">
                        <option value="include"<?php $selected('html.linkfilter.filter.type', 'include'); ?>>Include by default</option>
                        <option value="exclude"<?php $selected('html.linkfilter.filter.type', 'exclude'); ?>>Exclude by default</option>
                    </select>
                </span>
                <p class="description">The filter enables to include or exclude links or to customize the optimization per individual link.</p>
            </div>
        </td>
    </tr>
    <tr valign="top" data-ns="html.linkfilter.filter"<?php $visible('html.linkfilter.filter'); ?>>
        <th scope="row">&nbsp;</th>
        <td style="padding-top:0px;">
            <h5 class="h">&nbsp;Link Filter</h5>
            <div id="html-linkfilter-filter-config"><div class="loading-json-editor"><?php print __('Loading JSON editor...', 'o10n'); ?></div></div>
            <input type="hidden" class="json" name="o10n[html.linkfilter.filter.config]" data-json-type="json-array" data-json-editor-height="auto" data-json-editor-init="1" value="<?php print esc_attr($json('html.linkfilter.filter.config')); ?>" />
            <p class="description">Enter a JSON array with objects. (<a href="javascript:void(0);" onclick="jQuery('#linkfilter_filter_example').fadeToggle();">show example</a>)</p>
            <div class="info_yellow" id="linkfilter_filter_example" style="display:none;"><strong>Example:</strong> <pre class="clickselect" title="<?php print esc_attr('Click to select', 'optimization'); ?>" style="cursor:copy;padding: 10px;margin: 0 1px;margin-top:5px;font-size: 13px;">[
  {
    "match": "/wp-content\\/uploads\\/.*800x600\\.jpg/",
    "regex": true,
    "attributes": [
      {
        "param": "data-photo-viewer",
        "value": "800x600,1"
      },
      {
        "rename": "href",
        "param": "data-href"
      },
      {
        "delete": "param-to-delete"
      }
    ],
    "class": [
      "photo-viewer",
      "big-img"
    ],
    "rel_noopener": "noreferrer",
    "cdn": {
      "url": "https://img.mydomain.com",
      "mask": "/wp-content/uploads/"
    }
  }
]</pre></div>

    

        </td>
    </tr>
    <tr valign="top" data-ns="html.linkfilter"<?php $visible('html.linkfilter'); ?>>
        <th scope="row">&nbsp;</th>
        <td style="padding-top:0px;">
            
            <!--
                <p class="poweredby" data-ns="html.linkfilter.minify"<?php $visible('html.linkfilter.minify'); ?>>Powered by <a href="https://github.com/pear/Net_URL2/" target="_blank">Net_URL2</a><span class="star">
                    <a class="github-button" data-manual="1" href="https://github.com/pear/Net_URL2" data-icon="octicon-star" data-show-count="true" aria-label="Star pear/Net_URL2 on GitHub">Star</a></span></p>
                <label><input type="checkbox" name="o10n[html.linkfilter.minify.enabled]" data-json-ns="1" value="1"<?php $checked('html.linkfilter.minify.enabled', true); ?> /> Minify URLs</label>
                <p class="description">Minify link URLs to relative paths using <a href="https://github.com/pear/Net_URL2" target="_blank" rel="noopener">Net_URL2</a>.</p>
            
            <div class="suboption"-->

            <label><input type="checkbox" name="o10n[html.linkfilter.rel_noopener.enabled]" data-json-ns="1" value="1"<?php $checked('html.linkfilter.rel_noopener.enabled', true); ?> /> Add <code>rel="noopener"</code></label> <span data-ns="html.linkfilter.rel_noopener"<?php $visible('html.linkfilter.rel_noopener'); ?>>
                <select name="o10n[html.linkfilter.rel_noopener.type]" data-ns-change="html.linkfilter.rel_noopener.type" data-json-default="<?php print esc_attr(json_encode('noopener')); ?>">
                    <option value="noopener"<?php $selected('html.linkfilter.rel_noopener.type', 'noopener'); ?>>rel="noopener"</option>
                    <option value="noreferrer"<?php $selected('html.linkfilter.rel_noopener.type', 'noreferrer'); ?>>rel="noreferrer"</option>
                </select>
            </span>

            <p class="description">Add noopener or noreferrer to external links. (<a href="https://developers.google.com/web/tools/lighthouse/audits/noopener" target="_blank" rel="noopener">more info</a>)</p>
        

            <div class="suboption">
                <label><input type="checkbox" name="o10n[html.linkfilter.observer.enabled]" data-json-ns="1" value="1"<?php $checked('html.linkfilter.observer.enabled', true); ?> /> Capture script injected links</label>
                <p class="description">Capture script injected links using <a href="https://developer.mozilla.org/nl/docs/Web/API/MutationObserver" target="_blank" rel="noopener">MutationObserver</a>. This feature enables to optimize AJAX loaded links.</p>
            </div>
            <div class="suboption" data-ns="html.linkfilter.observer"<?php $visible('html.linkfilter.observer'); ?>>
                <label><input type="checkbox" value="1" name="o10n[html.linkfilter.observer.filter.enabled]" data-json-ns="1"<?php $checked('html.linkfilter.observer.enabled'); ?> /> Enable capture filter</label>
                <span data-ns="html.linkfilter.observer.filter"<?php $visible('html.linkfilter.observer.filter'); ?>>
                    <select name="o10n[html.linkfilter.observer.filter.type]" data-ns-change="html.linkfilter.observer.filter" data-json-default="<?php print esc_attr(json_encode('include')); ?>">
                        <option value="include"<?php $selected('html.linkfilter.observer.filter.type', 'include'); ?>>Include by default</option>
                        <option value="exclude"<?php $selected('html.linkfilter.observer.filter.type', 'exclude'); ?>>Exclude by default</option>
                    </select>
                </span>
                <p class="description">The filter enables to include or exclude links to capture or to customize the optimization per individual link.</p>
            </div>
        </td>
    </tr>
    <tr valign="top" data-ns="html.linkfilter.observer.filter"<?php $visible('o10n[html.linkfilter.observer.filter'); ?>>
        <th scope="row">&nbsp;</th>
        <td style="padding-top:0px;">
            <h5 class="h">&nbsp;Link Capture Filter</h5>
            <div id="html-linkfilter-observer-filter-config"><div class="loading-json-editor"><?php print __('Loading JSON editor...', 'o10n'); ?></div></div>
            <input type="hidden" class="json" name="o10n[html.linkfilter.observer.filter.config]" data-json-type="json-array" data-json-editor-height="auto" data-json-editor-init="1" value="<?php print esc_attr($json('html.linkfilter.observer.filter.config')); ?>" />
            <p class="description">Enter a JSON array with objects. (<a href="javascript:void(0);" onclick="jQuery('#linkfilter_observer_filter_example').fadeToggle();">show example</a>)</p>
            <div class="info_yellow" id="linkfilter_observer_filter_example" style="display:none;"><strong>Example:</strong> <pre class="clickselect" title="<?php print esc_attr('Click to select', 'optimization'); ?>" style="cursor:copy;padding: 10px;margin: 0 1px;margin-top:5px;font-size: 13px;">[
  {
    "match": "/wp-content\\/uploads\\/.*800x600\\.jpg/",
    "regex": true,
    "attributes": [
      {
        "param": "data-photo-viewer",
        "value": "800x600,1"
      }
    ],
    "class": [
      "photo-viewer",
      "big-img"
    ],
    "rel_noopener": "noreferrer",
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
            <div data-ns="html.linkfilter"<?php $visible('html.linkfilter');  ?>>
            <label><input type="checkbox" name="o10n[html.linkfilter.cdn.enabled]" value="1" data-json-ns="1"<?php $checked('html.linkfilter.cdn.enabled'); ?> /> Enabled</label>
            <p class="description">When enabled, local links are loaded via a Content Delivery Network (CDN).</p>
            </div>

            <div data-ns-hide="html.linkfilter"<?php $invisible('html.linkfilter');  ?>>
                This feature requires the link filter to be enabled.
            </div>
        </td>
    </tr>
    <tr valign="top" data-ns="html.linkfilter.cdn"<?php $visible('html.linkfilter.cdn');  ?>>
        <th scope="row">&nbsp;</th>
        <td style="padding-top:0px;">
            <h5 class="h">&nbsp;CDN URL</h5>
            <input type="url" name="o10n[html.linkfilter.cdn.url]" value="<?php $value('html.linkfilter.cdn.url'); ?>" style="width:500px;max-width:100%;" placeholder="https://cdn.yourdomain.com/" />
            <p class="description">Enter a CDN URL for local links, e.g. <code>https://cdn.domain.com/</code></p>
        </td>
    </tr>
</table>
<hr />
<?php
    submit_button(__('Save'), 'primary large', 'is_submit', false);

// print form header
$this->form_end();
