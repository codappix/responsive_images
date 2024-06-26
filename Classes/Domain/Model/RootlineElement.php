<?php

declare(strict_types=1);

namespace Codappix\ResponsiveImages\Domain\Model;

/*
 * Copyright (C) 2024 Justus Moroni <justus.moroni@codappix.com>
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

use Exception;

final class RootlineElement implements RootlineElementInterface
{
    private ?int $colPos = null;

    private ?RootlineElementInterface $parent = null;

    public function __construct(
        private readonly Scaling $scaling,
        private readonly array $data
    ) {
        if (isset($data['colPos'])) {
            $this->colPos = (int) $data['colPos'];
        }
    }

    public function getParent(): ?RootlineElementInterface
    {
        return $this->parent;
    }

    public function setParent(RootlineElementInterface $rootlineElement): void
    {
        $this->parent = $rootlineElement;
    }

    public function getScaling(): Scaling
    {
        return $this->scaling;
    }

    public function getFinalSize(array $multiplier): array
    {
        if ($this->getScaling()->getSizes()) {
            if (empty($multiplier)) {
                return $this->getScaling()->getSizes();
            }

            return $this->multiplyArray($this->getScaling()->getSizes(), $multiplier);
        }

        if (is_null($this->getParent())) {
            return $this->multiplyArray($this->getScaling()->getMultiplier(), $multiplier);
        }

        return $this->getParent()->getFinalSize(
            $this->multiplyArray($this->getScaling()->getMultiplier(), $multiplier)
        );
    }

    public function getData(?string $dataIdentifier = null): mixed
    {
        if ($dataIdentifier === null) {
            return $this->data;
        }

        if (isset($this->data[$dataIdentifier]) === false) {
            throw new Exception('No data found for key ' . $dataIdentifier . ' in $this->data.');
        }

        return $this->data[$dataIdentifier];
    }

    public function getColPos(): ?int
    {
        return $this->colPos;
    }

    private function multiplyArray(array $factor1, array $factor2): array
    {
        if (empty($factor1)) {
            return $factor2;
        }
        if (empty($factor2)) {
            return $factor1;
        }

        foreach ($factor1 as $sizeName => &$size) {
            if (isset($factor2[$sizeName]) === false) {
                continue;
            }

            $factor1[$sizeName] *= $factor2[$sizeName];
        }

        return $factor1;
    }
}
