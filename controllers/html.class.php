<?php
namespace O10n;

/**
 * HTML Optimization Controller
 *
 * @package    optimization
 * @subpackage optimization/controllers
 * @author     PageSpeed.pro <info@pagespeed.pro>
 */
if (!defined('ABSPATH')) {
    exit;
}

class Html extends Controller implements Controller_Interface
{
    private $preserve_comments = false; // preserve comments
    private $replace = false; // replace in HTML

     // add rel="noopener"
    private $rel_noopener = false;
    private $rel_noopener_type = 'noopener';

    private $link_filter = false;
    private $link_filter_type = 'include';
    private $link_cdn = false;

    private $minifier; // minifier
    
    /**
     * Load controller
     *
     * @param  Core       $Core Core controller instance.
     * @return Controller Controller instance.
     */
    public static function &load(Core $Core)
    {
        // instantiate controller
        return parent::construct($Core, array(
            'env',
            'url',
            'regex',
            'client',
            'output',
            'options'
        ));
    }

    /**
     * Setup controller
     */
    protected function setup()
    {
        // disabled
        if (!$this->env->is_optimization()) {
            return;
        }

        /*
         * Preserve comments
         */
        if ($this->options->bool('html.remove_comments')) {
            $this->preserve_comments = $this->options->get('html.remove_comments.preserve');
            if (!is_array($this->preserve_comments)) {
                $this->preserve_comments = false;
            }
        }

        // HTML Search & Replace
        $this->replace = $this->options->get('html.replace');
        if (isset($this->replace) && is_array($this->replace) && !empty($this->replace)) {
        
            // add filter for HTML output
            add_filter('o10n_html_pre', array( $this, 'search_replace' ), $this->first_priority, 1);
        } else {
            $this->replace = false;
        }

        /**
         * Optimize links
         */
        if ($this->options->bool('html.linkfilter.enabled')) {
            
            // CDN
            if ($this->options->bool('html.linkfilter.cdn.enabled')) {
                $this->link_cdn = $this->options->get('html.linkfilter.cdn.url');
            }

            // rel noopener
            $this->rel_noopener = $this->options->bool('html.linkfilter.rel_noopener.enabled');
            if ($this->rel_noopener) {
                $this->rel_noopener_type = $this->options->get('html.linkfilter.rel_noopener.type');
            }

            // verify cache policy
            if ($this->options->bool('html.linkfilter.filter.enabled')) {
                $this->link_filter = $this->options->get('html.linkfilter.filter.config', array());
                $this->link_filter_type = $this->options->get('html.linkfilter.filter.type', 'include');
            }

            // add filter for HTML output
            add_filter('o10n_html', array( $this, 'filter_links' ), 800, 1);

            /**
             * Capture script injected links
             */
            if ($this->options->bool('html.linkfilter.observer.enabled')) {

                // debug mode
                $js_ext = (defined('O10N_DEBUG') && O10N_DEBUG) ? '.debug.js' : '.js';

                // base client
                $link_filter_client = $this->core->modules('html')->dir_path() . 'public/js/html-link-capture' . $js_ext;
                
                // client config
                $config = array();
                if ($this->link_cdn) {
                    $config = $this->client->set_config('html', 'cdn', $this->link_cdn, $config);
                }
                if ($this->rel_noopener) {
                    $config = $this->client->set_config('html', 'rel_noopener', $this->rel_noopener_type, $config);
                }
                if ($this->options->bool('html.linkfilter.observer.filter.enabled')) {
                    $config = $this->client->set_config('html', 'filter', $this->options->get('html.linkfilter.observer.filter.config', array()), $config);
                    $config = $this->client->set_config('html', 'filter_type', $this->options->get('html.linkfilter.observer.filter.type', 'include'), $config);
                }

                $client_config = $this->client->parse_config($config);

                // link client as file
                // @todo
                /*if ($this->options->bool('client.link')) {
                    $client_html .= '<script data-o10n=\''.str_replace('\'', '&#39;', $client_config).'\' src="'.$fileurl.'"></script>';
                } else {*/

                $footer_client = '<script data-o10n=\''.str_replace('\'', '&#39;', $client_config).'\'>' . file_get_contents($link_filter_client) . '</script>';
                
                $this->client->at('footer', $footer_client);
            }
        }

        /**
         * Minify HTML
         */
        if ($this->options->bool('html.minify.enabled')) {

            // minifier
            $this->minifier = $this->options->get('html.minify.minifier', 'htmlphp');

            // add filter for HTML output
            add_filter('o10n_html', array( $this, 'process_html' ), 1000, 1);
        }
    }

    /**
     * Optimize links
     *
     * @return string
     */
    final public function filter_links($HTML)
    {
        return preg_replace_callback(
            '|<a\s[^>]*href[^>]*>|si',
            array($this,'filter_link_tag'),
            $HTML
        );
    }

    /**
     * Filter individual link tag
     *
     * @return string
     */
    final public function filter_link_tag($matches)
    {
        $link = $matches[0];

        // extract href
        $href = $this->regex->attr('href', $link);

        // attributes to add
        $set_attrs = apply_filters('o10n_link_filter_set_attributes', array());
        if (!is_array($set_attrs)) {
            $set_attrs = array();
        }

        // classes to add
        $set_class = apply_filters('o10n_link_filter_set_class', array());
        if (is_string($set_class)) {
            $set_class = array($set_class);
        }
        if (!is_array($set_class)) {
            $set_class = array();
        }

        // apply WordPress filter
        $filtered = apply_filters('o10n_link_filter', $link, $href, $set_attrs, $set_class);

        // skip filter
        if ($filtered === false) {
            return $link;
        }

        // process filter result
        if (is_string($filtered) && $filtered !== $link) {
            $link = $filtered;
        } elseif (is_array($filtered) && isset($filtered[0])) {

            // tag replace
            if ($filtered[0] !== $link) {
                $link = $filtered[0];
            }

            // add attributes
            if (isset($filtered[2]) && is_array($filtered[2])) {
                $set_attrs = $filtered[2];
            }

            // href replace
            if (isset($filtered[1]) && $filtered[1] !== $href) {

                // overwrite
                $href = $set_attrs['href'] = $filtered[1];
            }

            // add classes
            if (isset($filtered[3]) && is_array($filtered[3])) {
                $set_attrs = $filtered[3];
            }
        }

        // default optimization settings
        $rel_noopener = $this->rel_noopener;
        $rel_noopener_type = $this->rel_noopener_type;
        $cdn = $this->link_cdn;

        if ($this->link_filter) {
            $filterMatch = $this->match_link_filter($link, $this->link_filter, $this->link_filter_type);

            // do not filter link
            if (!$filterMatch) {
                return $link;
            }

            if (is_array($filterMatch)) {

                // rel="noopener"
                if (isset($filterMatch['rel_noopener'])) {
                    $rel_noopener = ($filterMatch['rel_noopener']) ? true : false;
                    if ($filterMatch['rel_noopener'] && is_string($filterMatch['rel_noopener'])) {
                        $rel_noopener_type = $filterMatch['rel_noopener'];
                    }
                }

                // classes
                if (isset($filterMatch['class'])) {
                    if (is_string($filterMatch['class'])) {
                        $filterMatch['class'] = array($filterMatch['class']);
                    }
                    if (is_array($filterMatch['class'])) {
                        $set_class = array_merge($set_class, $filterMatch['class']);
                    }
                }

                // attributes
                if (isset($filterMatch['attributes']) && is_array($filterMatch['attributes'])) {
                    foreach ($filterMatch['attributes'] as $attr) {
                        if (isset($attr['param']) && isset($attr['value'])) {
                            $set_attrs[$attr['param']] = $attr['value'];
                        }
                    }
                }

                // CDN
                if (isset($filterMatch['cdn'])) {
                    if (!$filterMatch['cdn']) {
                        $cdn = false;
                    } elseif (is_array($filterMatch['cdn']) && isset($filterMatch['cdn']['url'])) {
                        $cdn = array($filterMatch['cdn']['url']);
                        if (isset($filterMatch['cdn']['mask'])) {
                            $cdn[] = $filterMatch['cdn']['mask'];
                        }
                    }
                }
            }
        }

        // add rel="noopener"
        $target = $this->regex->attr('target', $link);
        if ($rel_noopener && $href && $target && strtolower(trim($target)) === '_blank') {
            $set_attrs['rel'] = ($rel_noopener_type) ? $rel_noopener_type : 'noopener';
        }

        // apply CDN
        if ($cdn && $href) {
            if (substr($href, 0, 1) === '/' || substr($href, 0, 1) === '.' || substr($href, 0, 4) === 'http') {
                // valid http link
            } elseif (preg_match('|^[a-z]+:|i')) {
                // javascript: etc.
                $cdn = false;
            }
            if ($cdn) {
                if (!is_array($cdn)) {
                    $cdn = array($cdn);
                }
                $cdn_href = $this->url->cdn($href, $cdn);
                if ($cdn_href !== $href) {
                    $set_attrs['href'] = $cdn_href;
                }
            }
        }

        // set classes
        if (!empty($set_class)) {
            $exist = $this->regex->attr('class', $link);
            if ($exist) {
                $set_class = array_merge($set_class, explode(' ', $exist));

                // remove
                $link = $this->regex->attr('class', $link, -1);
            }

            $set_class = array_unique($set_class);
            $set_attrs['class'] = trim($class . ' ' . implode(' ', $set_class));
        }

        // add attributes to link
        if (!empty($set_attrs)) {
            $attrs = '';
            foreach ($set_attrs as $param => $value) {

                // verify if param exists
                $exists = $this->regex->attr($param, $link);
                if ($exists !== false) {
                    if ($exists !== $value) {
                        $link = $this->regex->attr($param, $link, $value);
                    }
                } else {
                    $attrs .= ' ' . $param . '="' . $value .'"';
                }
            }

            if ($attrs !== '') {
                $link = substr_replace($link, $attrs, strrpos($link, '>'), 0);
            }
        }

        return $link;
    }

    /**
     * Minify the markeup given in the constructor
     *
     * @return string
     */
    final public function process_html($HTML)
    {
        // verify if empty
        $HTML = trim($HTML);
        if ($HTML === '') {
            return $HTML; // no data to compress
        }

        // minimum bytes required to activate optimization
        if ($bytes = $this->options->get('html.minimum_bytes')) {
            if (strlen($HTML) < $bytes) {
                return $HTML;
            }
        }

        // remove HTML comments
        if ($this->options->bool('html.remove_comments')) {
            $HTML = preg_replace_callback(
                '/<!--([\\s\\S]*?)-->/',
                array($this, 'remove_comments'),
                $HTML
            );
        }

        // minification
        if ($this->options->bool('html.minify')) {
            switch ($this->minifier) {
                case "voku-htmlmin":
                    if (!class_exists('\voku\helper\HtmlMin')) {
                        require_once $this->core->modules('html')->dir_path() . 'lib/vendor/autoload.php';
                    }

                    $htmlMin = new \voku\helper\HtmlMin();

                    $options = array(
                        'doOptimizeViaHtmlDomParser',
                        'doRemoveComments',
                        'doSumUpWhitespace',
                        'doRemoveWhitespaceAroundTags',
                        'doOptimizeAttributes',
                        'doRemoveHttpPrefixFromAttributes',
                        'doRemoveDefaultAttributes',
                        'doRemoveDeprecatedAnchorName',
                        'doRemoveDeprecatedScriptCharsetAttribute',
                        'doRemoveDeprecatedTypeFromScriptTag',
                        'doRemoveDeprecatedTypeFromStylesheetLink',
                        'doRemoveEmptyAttributes',
                        'doRemoveValueFromEmptyInput',
                        'doSortCssClassNames',
                        'doSortHtmlAttributes',
                        'doRemoveSpacesBetweenTags',
                        'doRemoveOmittedQuotes',
                        'doRemoveOmittedHtmlTags'
                    );
                    foreach ($options as $option_name) {
                        $htmlMin->{$option_name}($this->options->bool('html.minify.voku-htmlmin.' . $option_name));
                    }

                    // minify
                    try {
                        $minified = $htmlMin->minify($HTML);
                    } catch (\Exception $err) {
                        throw new Exception('HtmlMin minifier failed: ' . $err->getMessage(), 'js');
                    }

                    if (!$minified && $minified !== '') {
                        throw new Exception('HtmlMin minifier failed: unknown error', 'js');
                    }

                break;
                case "custom":

                    // minify
                    try {
                        $minified = apply_filters('o10n_html_custom_minify', $HTML);
                    } catch (\Exception $err) {
                        throw new Exception('Custom HTML minifier failed: ' . $err->getMessage(), 'js');
                    }

                    if (!$minified && $minified !== '') {
                        throw new Exception('Custom HTML minifier failed: unknown error', 'js');
                    }

                break;
                case "htmlphp":
                default:

                    // load library
                    if (!class_exists('O10n\HTMLMinify')) {
                        require_once $this->core->modules('html')->dir_path() . 'lib/HTML.php';
                    }
                    $htmlmin = new HTMLMinify();

                    // try minification
                    try {
                        $minified = $htmlmin->minify($HTML);
                    } catch (Exception $err) {
                        $minified = false;
                    }

                break;
            }
            

            if ($minified) {
                return $minified;
            } else {
                return $HTML;
            }
        }

        return $HTML;
    }

    /**
     * HTML Search & Replace
     */
    final public function search_replace($HTML)
    {
        if ($this->replace) {
            foreach ($this->replace as $object) {
                if (!isset($object['search']) || trim($object['search']) === '') {
                    continue;
                }

                if (isset($object['regex']) && $object['regex']) {
                    $this->output->add_search_replace($object['search'], $object['replace'], true);
                } else {
                    $this->output->add_search_replace($object['search'], $object['replace']);
                }
            }
        }

        return $HTML;
    }
    
    /**
     * Remove comments from HTML
     *
     * @param  array  $match The preg_replace_callback match result.
     * @return string The modified string.
     */
    final private function remove_comments($match)
    {
        if (!empty($this->preserve_comments)) {
            foreach ($this->preserve_comments as $str) {
                if (strpos($match[1], $str) !== false) {
                    return $match[0];
                }
            }
        }

        return (0 === strpos($match[1], '[') || false !== strpos($match[1], '<!['))
            ? $match[0]
            : '';
    }

    /**
     * Match link filter
     *
     * @param array $filter Policy config
     */
    final private function match_link_filter($link, $filter, $filter_type = 'include')
    {
        $match = ($filter_type === 'include') ? true : false;

        if (!is_array($filter)) {
            return $match;
        }

        foreach ($filter as $condition) {

            // url match
            if (is_string($condition)) {
                $condition = array(
                    'match' => $condition
                );
            }

            if (!is_array($condition)) {
                continue;
            }

            // always process exclude filter
            if (isset($condition['exclude']) && $condition['exclude']) {
            } elseif ($filter_type === 'include' && is_array($match)) {
                continue;
            }

            if (isset($condition['regex']) && $condition['regex']) {
                try {
                    if (preg_match($condition['match'], $link)) {

                        // exclude link
                        if (isset($condition['exclude']) && $condition['exclude']) {
                            return false;
                        } else {
                            $match = $condition;
                        }
                    }
                } catch (\Exception $err) {
                }
            } else {
                if (strpos($link, $condition['match']) !== false) {

                    // exclude link
                    if (isset($condition['exclude']) && $condition['exclude']) {
                        return false;
                    } else {
                        $match = $condition;
                    }
                }
            }
        }

        return $match;
    }
}
