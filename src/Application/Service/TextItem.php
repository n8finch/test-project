<?php
namespace Application\Service;

use Application\Domain\Gateway\LinkReadOnly;

class TextItem
{
    private $linkGateway;

    public function __construct(LinkReadOnly $linkGateway)
    {
        $this->linkGateway = $linkGateway;
    }

    public function __invoke()
    {
        $links = $this->linkGateway->getRecentLinks();

        $payload = [
            'success' => true,
            'links' => [],
        ];

        foreach ($links as $link) {
            $payload['links'][] = [
                'id' => $link->id(),
                'title' => $link->title(),
                'text' => $link->text(),
                'firstName' => $link->submitter()->firstName(),
                'lastName' => $link->submitter()->lastName(),
                'created' => $link->created(),
            ];
        }

        return $payload;
    }
}