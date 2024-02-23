<?php
/************************************************************************
 * This file is part of NupiCRM.
 *
 * NupiCRM – Open Source CRM application.
 * Copyright (C) 2014-2024 Yurii Kuznietsov, Taras Machyshyn, Oleksii Avramenko
 * Website: https://www.NupiCRM.com
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program. If not, see <https://www.gnu.org/licenses/>.
 *
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU Affero General Public License version 3.
 *
 * In accordance with Section 7(b) of the GNU Affero General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "NupiCRM" word.
 ************************************************************************/

namespace Espo\Core\Utils\DateTime;

use Espo\Core\Utils\DateTime;
use Espo\Core\InjectableFactory;
use Espo\ORM\EntityManager;
use Espo\Core\Utils\Config;
use Espo\Entities\User;
use Espo\Entities\Preferences;

class DateTimeFactory
{
    public function __construct(
        private InjectableFactory $injectableFactory,
        private EntityManager $entityManager,
        private Config $config
    ) {}

    public function createWithUserTimeZone(User $user): DateTime
    {
        $preferences = $this->entityManager->getEntity(Preferences::ENTITY_TYPE, $user->getId());

        $timeZone = $this->config->get('timeZone') ?? 'UTC';

        if ($preferences) {
            $timeZone = $preferences->get('timeZone') ? $preferences->get('timeZone') : $timeZone;
        }

        return $this->createWithTimeZone($timeZone);
    }

    public function createWithTimeZone(string $timeZone): DateTime
    {
        return $this->injectableFactory->createWith(DateTime::class, [
            'timeZone' => $timeZone,
            'dateFormat' => $this->config->get('dateFormat'),
            'timeFormat' => $this->config->get('timeFormat'),
            'language' => $this->config->get('language'),
        ]);
    }
}
