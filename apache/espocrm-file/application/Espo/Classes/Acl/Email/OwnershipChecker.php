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

namespace Espo\Classes\Acl\Email;

use Espo\Entities\User;
use Espo\Entities\Email;

use Espo\ORM\Entity;

use Espo\Core\Acl\DefaultOwnershipChecker;
use Espo\Core\Acl\OwnershipOwnChecker;
use Espo\Core\Acl\OwnershipTeamChecker;

/**
 * @implements OwnershipOwnChecker<Email>
 * @implements OwnershipTeamChecker<Email>
 */
class OwnershipChecker implements OwnershipOwnChecker, OwnershipTeamChecker
{
    private $defaultOwnershipChecker;

    public function __construct(DefaultOwnershipChecker $defaultOwnershipChecker)
    {
        $this->defaultOwnershipChecker = $defaultOwnershipChecker;
    }

    public function checkOwn(User $user, Entity $entity): bool
    {
        /** @var Email $entity */

        if ($user->getId() === $entity->get('assignedUserId')) {
            return true;
        }

        if ($user->getId() === $entity->get('createdById')) {
            return true;
        }

        if ($entity->hasLinkMultipleId('assignedUsers', $user->getId())) {
            return true;
        }

        return false;
    }

    public function checkTeam(User $user, Entity $entity): bool
    {
        return $this->defaultOwnershipChecker->checkTeam($user, $entity);
    }
}
