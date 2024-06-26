<?php

declare(strict_types=1);

namespace Codappix\ResponsiveImages\Domain\Factory;

/*
 * Copyright (C) 2024 Daniel Gohlke <daniel.gohlke@codappix.com>
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301, USA.
 */

use Codappix\ResponsiveImages\Configuration\ConfigurationManager;
use Codappix\ResponsiveImages\Domain\Model\Breakpoint;

final class BreakpointFactory
{
    public function __construct(
        private readonly ConfigurationManager $configurationManager
    ) {
    }

    /**
     * @return Breakpoint[]
     */
    public function getByConfigurationPath(array|string $configurationPath): array
    {
        $breakpoints = [];

        $breakpointsByPath = $this->configurationManager->getByPath($configurationPath);

        if (is_iterable($breakpointsByPath)) {
            foreach ($breakpointsByPath as $breakpointIdentifier => $breakpointData) {
                $breakpoints[$breakpointIdentifier] = new Breakpoint($breakpointIdentifier, $breakpointData);
            }
        }

        return $breakpoints;
    }
}
