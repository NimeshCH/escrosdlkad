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

namespace Espo\Core\ORM;

use Espo\Core\Utils\Config;
use Espo\ORM\Entity;

class Helper
{
    private const FORMAT_LAST_FIRST = 'lastFirst';
    private const FORMAT_LAST_FIRST_MIDDLE = 'lastFirstMiddle';
    private const FORMAT_FIRST_MIDDLE_LAST = 'firstMiddleLast';

    public function __construct(private Config $config)
    {}

    public function formatPersonName(Entity $entity, string $field): ?string
    {
        $format = $this->config->get('personNameFormat');

        $first = $entity->get('first' . ucfirst($field));
        $last = $entity->get('last' . ucfirst($field));
        $middle = $entity->get('middle' . ucfirst($field));

        switch ($format) {
            case self::FORMAT_LAST_FIRST:
                if (!$first && !$last) {
                    return null;
                }

                if (!$first) {
                    return $last;
                }

                if (!$last) {
                    return $first;
                }

                return $last . ' ' . $first;

            case self::FORMAT_LAST_FIRST_MIDDLE:
                if (!$first && !$last && !$middle) {
                    return null;
                }

                $arr = [];

                if ($last) {
                    $arr[] = $last;
                }

                if ($first) {
                    $arr[] = $first;
                }

                if ($middle) {
                    $arr[] = $middle;
                }

                return implode(' ', $arr);

            case self::FORMAT_FIRST_MIDDLE_LAST:
                if (!$first && !$last && !$middle) {
                    return null;
                }

                $arr = [];

                if ($first) {
                    $arr[] = $first;
                }

                if ($middle) {
                    $arr[] = $middle;
                }

                if ($last) {
                    $arr[] = $last;
                }

                return implode(' ', $arr);
        }

        if (!$first && !$last) {
            return null;
        }

        if (!$first) {
            return $last;
        }

        if (!$last) {
            return $first;
        }

        return $first . ' ' . $last;
    }
}
