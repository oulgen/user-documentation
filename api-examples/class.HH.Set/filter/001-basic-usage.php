<?hh // partial

namespace Hack\UserDocumentation\API\Examples\Set\Filter;

$colors = Set {'red', 'green', 'blue', 'yellow'};

// Create a Set of colors that contain the letter 'l'
$l_colors = $colors->filter($color ==> strpos($color, 'l') !== false);
var_dump($l_colors);
