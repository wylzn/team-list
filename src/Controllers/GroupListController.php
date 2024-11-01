<?php

namespace Wylzn\GroupList\Controllers;

use Wylzn\GroupList\GroupListItem;
use Wylzn\GroupList\Serializers\GroupListItemSerializer;
use Flarum\Api\Controller\AbstractListController;
use Flarum\Http\RequestUtil;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class GroupListController extends AbstractListController
{
    public $serializer = GroupListItemSerializer::class;

    public $include = [
        'group',
        'members.groups',
    ];

    protected function data(ServerRequestInterface $request, Document $document)
    {
        RequestUtil::getActor($request)->assertCan('wylzn-group-list.see');

        $items = GroupListItem::query()->orderBy('order')->get();

        $items->load([
            'group',
            'members',
        ]);

        return $items;
    }
}
