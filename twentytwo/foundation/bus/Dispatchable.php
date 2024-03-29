<?php
namespace twentytwo\foundation\bus;

use Illuminate\Contracts\Bus\Dispatcher;

trait Dispatchable
{
  /**
   * Dispatch the job with the given arguments.
   *
   * @return \twentytwo\foundation\bus\PendingDispatch
   */
  public static function dispatch()
  {
    return new PendingDispatch(new static(...func_get_args()));
  }
  /**
   * Dispatch a command to its appropriate handler in the current process.
   *
   * @return mixed
   */
  public static function dispatchNow()
  {
    return app(Dispatcher::class)->dispatchNow(new static(...func_get_args()));
  }
  /**
   * Set the jobs that should run if this job is successful.
   *
   * @param  array  $chain
   * @return \twentytwo\foundation\bus\PendingChain
   */
  public static function withChain($chain)
  {
    return new PendingChain(static::class, $chain);
  }
}
