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

namespace Espo\Classes\FieldValidators;

use Espo\ORM\Entity;

use stdClass;

class JsonArrayType
{
    public function rawCheckArray(stdClass $data, string $field): bool
    {
        if (isset($data->$field) && !is_array($data->$field)) {
            return false;
        }

        return true;
    }

    protected function isNotEmpty(Entity $entity, string $field): bool
    {
        if (!$entity->has($field) || $entity->get($field) === null) {
            return false;
        }

        $list = $entity->get($field);

        if (!is_array($list)) {
            return false;
        }

        if (count($list)) {
            return true;
        }

        return false;
    }
}
