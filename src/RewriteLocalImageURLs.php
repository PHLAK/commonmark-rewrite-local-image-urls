<?php

declare(strict_types=1);

namespace PHLAK\CommonMarkExtensions;

use League\CommonMark\Environment\EnvironmentBuilderInterface;
use League\CommonMark\Event\DocumentParsedEvent;
use League\CommonMark\Extension\CommonMark\Node\Inline\Image;
use League\CommonMark\Extension\ExtensionInterface;

class RewriteLocalImageURLs implements ExtensionInterface
{
    public function __construct(
        private string $baseUrl
    ) {}

    public function register(EnvironmentBuilderInterface $environment): void
    {
        $environment->addEventListener(DocumentParsedEvent::class, $this->onDocumentParsed(...));
    }

    public function onDocumentParsed(DocumentParsedEvent $event): void
    {
        $walker = $event->getDocument()->walker();

        while ($event = $walker->next()) {
            if (! $event->isEntering()) {
                continue;
            }

            $node = $event->getNode();

            if (! $node instanceof Image) {
                continue;
            }

            if (parse_url($node->getUrl(), PHP_URL_HOST) !== null) {
                continue;
            }

            $node->setUrl(sprintf('%s/%s', rtrim($this->baseUrl, '/'), ltrim($node->getUrl(), '/')));
        }
    }
}
