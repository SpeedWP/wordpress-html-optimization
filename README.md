[![Build Status](https://travis-ci.org/o10n-x/wordpress-html-optimization.svg?branch=master)](https://travis-ci.org/o10n-x/wordpress-html-optimization) ![Version](https://img.shields.io/github/release/o10n-x/wordpress-html-optimization.svg)

# WordPress HTML Optimization

Advanced HTML optimization toolkit. Minify, code optimization, search & replace, strip comments and more.

* [Documentation](https://github.com/o10n-x/wordpress-html-optimization/tree/master/docs)
* [Description](https://github.com/o10n-x/wordpress-html-optimization#description)
* [Version history (Changelog)](https://github.com/o10n-x/wordpress-html-optimization/releases)
## Installation

![Github Updater](https://github.com/afragen/github-updater/raw/develop/assets/GitHub_Updater_logo_small.png)

This plugin can be installed and updated using [Github Updater](https://github.com/afragen/github-updater) ([installation instructions](https://github.com/afragen/github-updater/wiki/Installation))

<details/>
  <summary>Installation instructions</summary>

### Step 1: Install Github Updater and first optimization plugin

Installing and updating the plugins is possible using Github Updater. It is easy to install one of the plugins. You simply need to download the Github Updater plugin ([zip file](https://github.com/afragen/github-updater/archive/develop.zip)), install it from the WordPress plugin admin panel and copy the Github URL of the plugin into the Github Updater installer.

![image](https://user-images.githubusercontent.com/8843669/39889846-46158cc2-5499-11e8-824d-720020f758db.png)

### Step 2: Install other optimization plugins with a single click

A recent update of all plugins contains a easy single click install button.

![image](https://user-images.githubusercontent.com/8843669/39661507-cc1eac5e-5052-11e8-8fba-33c0cc959b07.png)
</details>

## Description

This plugin is a toolkit for advanced HTML optimization.

The plugin provides in a complete solution for HTML optimization including HTML minification, selective removal of comments and search & replace.

#### Multiple minifiers + custom minifier

The plugin provides the option to minify HTML code using multiple HTML minifiers including [Minify's HTML.php](https://github.com/mrclay/minify/) (PHP), [HtmlMin](https://github.com/voku/HtmlMin) (based on PHP 7 [symfony/css-selector](https://github.com/symfony/css-selector)) and the option to use a custom minifier using a WordPress filter that enables to use any solution, including a Amazon Lambda or Google Cloud function with Node.js based HTML optimization software. 

![HTML Optimization](https://github.com/o10n-x/wordpress-html-optimization/blob/master/docs/images/html-code-optimization.png)

#### HtmlMin configuration

HtmlMin minifier provides many options for tuning the quality of the code minification.

![HtmlMin config](https://github.com/o10n-x/wordpress-html-optimization/blob/master/docs/images/htmlmin-php7.png)

The plugin provides in a complete solution for CSS code optimization, CSS delivery optimization (async CSS loading) and Critical CSS management.

### Link Optimization

The plugin provides advanced HTML link (`<a href`) optimization that includes a MutationObserver innovation to capture and optimize script injected links. This solution enables to add `rel=noopener` to links to achieve a perfect Google Lighthouse score. 

A JSON config filter enables to add, rename or remove attribute and classes, modify `href` and to apply a CDN with CDN mask.

![Link Optimization](https://github.com/o10n-x/wordpress-html-optimization/blob/master/docs/images/link-optimization.png)

### Image Optimization

The plugin provides advanced HTML image (`<img`) optimization.

A JSON config filter enables to add, rename or remove attributes and classes, modify `src` and `srcset` and to apply a CDN with CDN mask.

![Image Optimization](https://github.com/o10n-x/wordpress-html-optimization/blob/master/docs/images/image-optimization.png)

Additional features can be requested on the [Github forum](https://github.com/o10n-x/wordpress-html-optimization/issues).

## WordPress WPO Collection

This plugin is part of a Website Performance Optimization collection that include [CSS](https://github.com/o10n-x/wordpress-css-optimization), [Javascript](https://github.com/o10n-x/wordpress-javascript-optimization), [Web Font](https://github.com/o10n-x/wordpress-font-optimization), [HTTP/2](https://github.com/o10n-x/wordpress-http2-optimization), [Progressive Web App (Service Worker)](https://github.com/o10n-x/wordpress-pwa-optimization) and [Security Header](https://github.com/o10n-x/wordpress-security-header-optimization) optimization. 

The WPO optimization plugins provide in all essential tools that enable to achieve perfect [Google Lighthouse Test](https://developers.google.com/web/tools/lighthouse/) scores and to validate a website as [Google PWA](https://developers.google.com/web/progressive-web-apps/), an important ranking factor for Google's [Speed Update](https://searchengineland.com/google-speed-update-page-speed-will-become-ranking-factor-mobile-search-289904) (July 2018).

![Google Lighthouse Perfect Performance Scores](https://github.com/o10n-x/wordpress-css-optimization/blob/master/docs/images/google-lighthouse-pwa-validation.jpg)

The WPO optimization plugins are designed to work together with single plugin performance. The plugins provide the latest optimization technologies and many unique innovations.

### JSON shema configuration

The WPO optimization plugins are based on JSON schema based configuration that enables full control of the optimization using JSON. This provides several great advantages for website performance optimization.

Read more about [JSON schemas](https://github.com/o10n-x/wordpress-o10n-core/tree/master/schemas).

## Google PageSpeed vs Google Lighthouse Scores

While a Google PageSpeed 100 score is still of value, websites with a high Google PageSpeed score may score very bad in Google's new [Lighthouse performance test](https://developers.google.com/web/tools/lighthouse/). 

The following scores are for the same site. It shows that a perfect Google PageSpeed score does not correlate to a high Google Lighthouse performance score.

![Perfect Google PageSpeed 100 Score](https://github.com/o10n-x/wordpress-css-optimization/blob/master/docs/images/google-pagespeed-100.png) ![Google Lighthouse Critical Performance Score](https://github.com/o10n-x/wordpress-css-optimization/blob/master/docs/images/lighthouse-performance-15.png)

### Google PageSpeed score is outdated

For the open web to have a chance of survival in a mobile era it needs to compete with and win from native mobile apps. Google is dependent on the open web for it's advertising revenue. Google therefor seeks a way to secure the open web and the main objective is to rapidly enhance the quality of the open web to meet the standards of native mobile apps.

For SEO it is therefor simple: websites will need to meet the standards set by the [Google Lighthouse Test](https://developers.google.com/web/tools/lighthouse/) (or Google's future new tests). A website with perfect scores will be preferred in search over low performance websites. The officially announced [Google Speed Update](https://searchengineland.com/google-speed-update-page-speed-will-become-ranking-factor-mobile-search-289904) (July 2018) shows that Google is going as far as it can to drive people to enhance the quality to ultra high levels, to meet the quality of, and hopefully beat native mobile apps.

A perfect Google Lighthouse Score includes validation of a website as a [Progressive Web App (PWA)](https://developers.google.com/web/progressive-web-apps/).

Google offers another new website performance test that is much tougher than the Google PageSpeed score. It is based on a AI neural network and it can be accessed on https://testmysite.thinkwithgoogle.com