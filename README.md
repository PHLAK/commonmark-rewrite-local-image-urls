<p align="center">
    <h1>CommonMark Rewrite Local Image URLs Extension</h1>
</p>

<p align="center">
    <a href="https://github.com/users/PHLAK/sponsorship"><img src="https://img.shields.io/badge/Become_a-Sponsor-cc4195.svg?style=for-the-badge" alt="Become a Sponsor"></a>
    <a href="https://paypal.me/ChrisKankiewicz"><img src="https://img.shields.io/badge/Make_a-Donation-006bb6.svg?style=for-the-badge" alt="One-time Donation"></a>
    <br>
    <a href="https://packagist.org/packages/phlak/commonmark-rewrite-local-image-urls"><img src="https://img.shields.io/packagist/v/phlak/commonmark-rewrite-local-image-urls.svg?style=flat-square" alt="Latest Stable Version"></a>
    <a href="https://packagist.org/packages/phlak/commonmark-rewrite-local-image-urls"><img src="https://img.shields.io/packagist/dt/phlak/commonmark-rewrite-local-image-urls.svg?style=flat-square" alt="Total Downloads"></a>
    <a href="https://github.com/PHLAK/commonmark-rewrite-local-image-urls/blob/master/LICENSE"><img src="https://img.shields.io/github/license/PHLAK/commonmark-rewrite-local-image-urls?style=flat-square" alt="License"></a>
    <a href="https://github.com/PHLAK/commonmark-rewrite-local-image-urls/actions"><img src="https://img.shields.io/github/actions/workflow/status/PHLAK/commonmark-rewrite-local-image-urls/ci-suite.yaml?style=flat-square&label=tests" alt="Tests Status"></a>
</p>

---

CommonMark extension for rewriting local, relative image URLs to absolute URLs.

Requirements
------------

  - [PHP](https://www.php.net)
    - [league/commonmark](https://commonmark.thephpleague.com)

Installation
------------

Install the extension with Composer.

    composer require phlak/commonmark-rewrite-local-image-urls

Then add the extension to your CommonMark environment.

```php
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\MarkdownConverter;
use PHLAK\CommonMarkExtensions\RewriteLocalImageURLs;

$environment = new Environment;
$environment->addExtension(new CommonMarkCoreExtension);
$environment->addExtension(new RewriteLocalImageURLs('https://example.com'));

$converter = new MarkdownConverter($environment);
```

Usage
-----

When converting Markdown, any local, relative image URL will be rewritten to an
absolute URL using the configured base URL.

```php
$converter->convert('![alt text](image.png)');
// Returns: <p><img src="https://example.com/image.png" alt="alt text" /></p>

$converter->convert('![alt text](/image.png)');
// Returns: <p><img src="https://example.com/image.png" alt="alt text" /></p>

$converter->convert('![alt text](assets/images/photo.jpg)');
// Returns: <p><img src="https://example.com/assets/images/photo.jpg" alt="alt text" /></p>
```

External image URLs are left unchanged.

```php
$converter->convert('![alt text](https://external.com/image.png)');
// Returns: <p><img src="https://external.com/image.png" alt="alt text" /></p>

$converter->convert('![alt text](//external.com/image.png)');
// Returns: <p><img src="//external.com/image.png" alt="alt text" /></p>
```

Changelog
---------

A list of changes can be found on the [GitHub Releases](https://github.com/PHLAK/commonmark-rewrite-local-image-urls/releases) page.

Troubleshooting
---------------

For general help and support join our [GitHub Discussion](https://github.com/PHLAK/commonmark-rewrite-local-image-urls/discussions) or reach out on [Bluesky](https://bsky.app/profile/phlak.dev).

Please report bugs to the [GitHub Issue Tracker](https://github.com/PHLAK/commonmark-rewrite-local-image-urls/issues).

Copyright
---------

This project is licensed under the [MIT License](https://github.com/PHLAK/commonmark-rewrite-local-image-urls/blob/master/LICENSE).
