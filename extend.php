<?php

namespace Wylzn\GroupList;

use Flarum\Api\Serializer\ForumSerializer;
use Flarum\Extend;
use Flarum\Settings\SettingsRepositoryInterface;

return [
    (new Extend\Frontend('forum'))
        ->js(__DIR__ . '/js/dist/forum.js')
        ->css(__DIR__ . '/resources/less/forum.less')
        ->route('/team', 'wylzn-group-list'),
    (new Extend\Frontend('admin'))
        ->js(__DIR__ . '/js/dist/admin.js')
        ->css(__DIR__ . '/resources/less/admin.less'),
    new Extend\Locales(__DIR__ . '/resources/locale'),
    (new Extend\Routes('api'))
        ->get('/wylzn-group-list', 'wylzn-group-list.index', Controllers\GroupListController::class)
        ->post('/wylzn-group-list-items', 'wylzn-group-list.create', Controllers\ItemStoreController::class)
        ->patch('/wylzn-group-list-items/{id:[0-9]+}', 'wylzn-group-list.update', Controllers\ItemUpdateController::class)
        ->delete('/wylzn-group-list-items/{id:[0-9]+}', 'wylzn-group-list.delete', Controllers\ItemDeleteController::class),
    (new Extend\ApiSerializer(ForumSerializer::class))
        ->attributes(function (ForumSerializer $serializer): array {
            /**
             * @var $settings SettingsRepositoryInterface
             */
            $settings = resolve(SettingsRepositoryInterface::class);

            return [
                'wylzn-group-list.showSideNavLink' => $settings->get('wylzn-group-list.showSideNavLink') !== '0' && $serializer->getActor()->hasPermission('wylzn-group-list.see'),
                'wylzn-group-list.showAvatarBadges' => $settings->get('wylzn-group-list.showAvatarBadges') === '1',
                'wylzn-group-list.showOnlineStatus' => $settings->get('wylzn-group-list.showOnlineStatus') === '1',
            ];
        }),
];
