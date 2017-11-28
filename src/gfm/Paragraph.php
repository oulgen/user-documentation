<?hh // strict
/*
 *  Copyright (c) 2004-present, Facebook, Inc.
 *  All rights reserved.
 *
 *  This source code is licensed under the BSD-style license found in the
 *  LICENSE file in the root directory of this source tree. An additional grant
 *  of patent rights can be found in the PATENTS file in the same directory.
 *
 */

namespace Facebook\GFM;

use namespace HH\Lib\{C, Vec};

final class Paragraph extends LeafBlock {
  public function __construct(
    private vec<string> $lines,
  ) {
  }

  public static function consume(vec<string> $lines): (Paragraph, vec<string>) {
    $matched = vec[C\firstx($lines)];

    for ($idx = 1; $idx < C\count($lines); ++$idx) {
      if ($lines[$idx] === '') {
        break;
      }
      $matches_other_block = C\any(
        Block::PRIORITIZED_BLOCK_TYPES,
        (classname<Block> $block) ==>
          $block !== self::class &&
          $block !== SetextHeading::class &&
          $block::consume(Vec\drop($lines, $idx)) !== null
      );
      if ($matches_other_block) {
        break;
      }
      $matched[] = $lines[$idx];
    }

    return tuple(
      new self($matched),
      Vec\drop($lines, C\count($matched)),
    );
  }
}