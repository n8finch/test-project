<?php
namespace Application\AtlasOrm\Gateway;

use Application\AtlasOrm\DataSource\Link\LinkMapper;
use Application\Domain\Entity\Text;
use Application\Domain\Entity\User;
use Application\Domain\Gateway\TextReadOnly as TextReadOnlyGateway;
use Atlas\Orm\Atlas;
use DateTime;
use DateTimeZone;

class TextReadOnly implements TextReadOnlyGateway
{
    private $atlas;

    public function __construct(Atlas $atlas)
    {
        $this->atlas = $atlas;
    }

    public function getText()
    {
        $links = [];

        $linkRecordSet = $this->atlas
            ->select(LinkMapper::class)
            ->orderBy([
                'created DESC',
            ])
            ->with([
                'submitter',
            ])
            ->fetchRecordSet();

        $text = new Text(
            $linkRecord->id,
            $linkRecord->title,
            $linkRecord->url,
            $linkRecord->text,
            new User(
                $linkRecord->submitter->id,
                $linkRecord->submitter->email,
                $linkRecord->submitter->password,
                $linkRecord->submitter->first_name,
                $linkRecord->submitter->last_name,
                new DateTime($linkRecord->submitter->created, new DateTimeZone('UTC')),
                new DateTime($linkRecord->submitter->updated, new DateTimeZone('UTC'))
            ),
            new DateTime($linkRecord->created, new DateTimeZone('UTC')),
            new DateTime($linkRecord->updated, new DateTimeZone('UTC'))
        }

        return $text;
    }
}
