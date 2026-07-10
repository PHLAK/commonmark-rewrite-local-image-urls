<?php

declare(strict_types=1);

namespace Tests;

use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\MarkdownConverter;
use PHLAK\CommonMarkExtensions\RewriteLocalImageURLs;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase as FrameworkTestCase;

#[CoversClass(RewriteLocalImageURLs::class)]
class RewriteLocalImageURLsTest extends FrameworkTestCase
{
    private MarkdownConverter $converter;

    protected function setUp(): void
    {
        parent::setUp();

        $environment = new Environment;
        $environment->addExtension(new CommonMarkCoreExtension);
        $environment->addExtension(new RewriteLocalImageURLs('https://example.com'));

        $this->converter = new MarkdownConverter($environment);
    }

    #[Test]
    public function it_rewrites_local_image_paths_to_an_absolute_url_using_the_base_url(): void
    {
        $relativePathWithoutLeadingSlash = $this->converter->convert('![alt text](image.png)');
        $relativePathWithLeadingSlash = $this->converter->convert('![alt text](/image.png)');
        $nestedRelativePath = $this->converter->convert('![alt text](assets/images/photo.jpg)');

        $this->assertSame("<p><img src=\"https://example.com/image.png\" alt=\"alt text\" /></p>\n", (string) $relativePathWithoutLeadingSlash);
        $this->assertSame("<p><img src=\"https://example.com/image.png\" alt=\"alt text\" /></p>\n", (string) $relativePathWithLeadingSlash);
        $this->assertSame("<p><img src=\"https://example.com/assets/images/photo.jpg\" alt=\"alt text\" /></p>\n", (string) $nestedRelativePath);
    }

    #[Test]
    public function it_does_not_rewrite_external_image_urls(): void
    {
        $renderedContent = $this->converter->convert('![alt text](https://external.com/image.png)');

        $this->assertSame("<p><img src=\"https://external.com/image.png\" alt=\"alt text\" /></p>\n", (string) $renderedContent);
    }

    #[Test]
    public function it_does_not_rewrite_protocol_relative_image_urls(): void
    {
        $renderedContent = $this->converter->convert('![alt text](//external.com/image.png)');

        $this->assertSame("<p><img src=\"//external.com/image.png\" alt=\"alt text\" /></p>\n", (string) $renderedContent);
    }

    #[Test]
    public function it_rewrites_multiple_local_image_urls_in_a_single_document(): void
    {
        $markdown = <<<MARKDOWN
        ![First](first.png)
        ![Second](/second.png)
        ![External](https://external.com/third.png)
        MARKDOWN;

        $renderedContent = $this->converter->convert($markdown);

        $this->assertSame(<<<HTML
        <p><img src="https://example.com/first.png" alt="First" />
        <img src="https://example.com/second.png" alt="Second" />
        <img src="https://external.com/third.png" alt="External" /></p>

        HTML, (string) $renderedContent);
    }
}
