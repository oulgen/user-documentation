<?hh // partial

namespace Hack\UserDocumentation\API\Examples\MCRouter\MCrouter\Replace;

function get_simple_mcrouter(): \MCRouter {
  $servers = Vector { \getenv('HHVM_TEST_MCROUTER') };
  $mc = \MCRouter::createSimple($servers);
  return $mc;
}

async function add_value(\MCRouter $mc, string $key,
                         string $value): Awaitable<void> {
  await $mc->add($key, $value);
}

async function replace_value(\MCRouter $mc, string $key,
                             string $value): Awaitable<void> {
  // can also pass optional int flags and int expiration time (in seconds)
  await $mc->replace($key, $value);
}

async function run(): Awaitable<void> {
  $mc = get_simple_mcrouter();
  $unique_key = \str_shuffle('ABCDEFGHIJKLMN');
  try {
    // We never added or set this key, so it can't be replaced.
    await replace_value($mc, $unique_key, "Bye");
    $val = await $mc->get($unique_key);
    \var_dump($val);
  } catch (\MCRouterException $ex) {
    \var_dump($ex->getMessage()); // We will get here
  }
  await add_value($mc, $unique_key, "Hi");
  $val = await $mc->get($unique_key);
  \var_dump($val);
  await replace_value($mc, $unique_key, "Bye");
  $val = await $mc->get($unique_key);
  \var_dump($val);
}

\HH\Asio\join(run());
