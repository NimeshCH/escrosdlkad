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

namespace Espo\Core\Select\AccessControl\Filters;

use Espo\Core\Select\AccessControl\Filter;
use Espo\Core\Select\Helpers\FieldHelper;
use Espo\Entities\User;
use Espo\ORM\Defs;
use Espo\ORM\Query\SelectBuilder as QueryBuilder;

class OnlyOwn implements Filter
{
    public function __construct(
        private User $user,
        private FieldHelper $fieldHelper,
        private string $entityType,
        private Defs $defs
    ) {}

    public function apply(QueryBuilder $queryBuilder): void
    {
        if ($this->fieldHelper->hasAssignedUsersField()) {
            $relationDefs = $this->defs
                ->getEntity($this->entityType)
                ->getRelation('assignedUsers');

            $middleEntityType = ucfirst($relationDefs->getRelationshipName());
            $key1 = $relationDefs->getMidKey();
            $key2 = $relationDefs->getForeignMidKey();

            $subQuery = QueryBuilder::create()
                ->select('id')
                ->from($this->entityType)
                ->leftJoin($middleEntityType, 'assignedUsersMiddle', [
                    "assignedUsersMiddle.{$key1}:" => 'id',
                    'assignedUsersMiddle.deleted' => false,
                ])
                ->where(["assignedUsersMiddle.{$key2}" => $this->user->getId()])
                ->build();

            $queryBuilder->where(['id=s' => $subQuery]);

            return;
        }

        if ($this->fieldHelper->hasAssignedUserField()) {
            $queryBuilder->where(['assignedUserId' => $this->user->getId()]);

            return;
        }

        if ($this->fieldHelper->hasCreatedByField()) {
            $queryBuilder->where(['createdById' => $this->user->getId()]);
        }
    }
}